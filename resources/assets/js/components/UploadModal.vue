<template>
    <div>
        <form class="form-horizontal">
            <div class="form-group">
                <label for="productSize" class="col-sm-2 control-label">Размер</label>
                <div class="col-sm-10">
                    <div class="input-group col-sm-4">
                        <input type="text" class="form-control" id="productSize" placeholder="21" v-model="size" />
                        <div class="input-group-addon">см.</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <vue-clip :options="options" :on-complete="onComplete">
                        <template slot="clip-uploader-action">
                            <div v-show="size">
                                <div class="dz-message">
                                    <span class="btn btn-primary">Выбрать файл и загрузить...</span>
                                </div>
                            </div>
                        </template>
                        <template slot="clip-uploader-body" slot-scope="props">
                            <div class="padding-top">
                                <div class="progress" v-if="props.files.length">
                                    <div class="progress-bar progress-bar-striped active" style="width: 100%"></div>
                                </div>
                            </div>
                        </template>
                    </vue-clip>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['product', 'uploadCallback', 'closeCallback'],
        data() {
            return {
                options: {
                    url: `/api/shop/${this.product.owner_id}/models/${this.product.id}`,
                    paramName: 'model'
                },
                size: null
            }
        },
        methods: {
            onComplete(file, status, xhr) {
                this.product.size = this.size;
                axios.post(`/api/shop/${this.product.owner_id}/info/${this.product.id}`, {
                    product: this.product
                }).then(({ data }) => {
                    this.uploadCallback(data);
                    this.closeCallback();
                });
            }
        }
    }
</script>
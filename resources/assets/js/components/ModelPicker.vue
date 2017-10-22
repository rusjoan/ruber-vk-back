<template>
    <div>
        <template v-if="model">
            <a :href="deeplink" class="btn btn-xs btn-default" target="_blank">
                <span class="glyphicon glyphicon-eye-open"></span> 3D-модель
            </a>
            <a :href="model" class="btn btn-xs btn-primary">
                <span class="glyphicon glyphicon-download"></span>
            </a>
            <button @click="deleteModel" class="btn btn-xs btn-danger">
                <span class="glyphicon glyphicon-remove"></span>
            </button>
        </template>
        <template v-else>
            <button class="btn btn-default" @click="uploadModal"><span class="glyphicon glyphicon-plus-sign"></span></button>
        </template>

        <sweet-modal title="Добавить 3D-модель к товару" ref="modal">
            <upload-modal :product="product" :uploadCallback="uploadCallback" :closeCallback="closeUploadModal"></upload-modal>
        </sweet-modal>
    </div>
</template>

<script>
    import { SweetModal } from 'sweet-modal-vue';
    import UploadModal from './UploadModal.vue';

    export default {
        props: ['product', 'models', 'uploadCallback', 'deleteCallback'],
        data() {
            return {
                options: {
                    url: `/api/shop/${this.product.owner_id}/models/${this.product.id}`,
                    paramName: 'model'
                }
            }
        },
        methods: {
            deleteModel() {
                axios.delete(`/api/shop/${this.product.owner_id}/models/${this.product.id}`);
                this.deleteCallback();
            },
            uploadModal() {
                this.$refs.modal.open();
            },
            closeUploadModal() {
                this.$refs.modal.close();
            }
        },
        computed: {
            model() {
                const model = _.find(this.models, m => m.product_id == this.product.id);
                return model ? model.model : null;
            },
            deeplink() {
                const model = _.find(this.models, m => m.product_id == this.product.id);
                return model ? `/product/${model.id}` : null;
            }
        },
        components: {
            SweetModal,
            UploadModal
        }
    }
</script>
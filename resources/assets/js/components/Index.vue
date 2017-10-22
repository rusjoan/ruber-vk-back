<template>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default hide">
                <div class="panel-heading">AR-галерея товаров</div>

                <div class="panel-body">
                    <p>Текущая группа: {{ groupId }}</p>
                    <!--<button class="btn btn-default" @click="toGroup()">Перейти в настройки группы</button>-->
                </div>
            </div>

            <h3>Товары группы</h3>

            <p v-if="!products.length">
                Не видите товары? <button class="btn btn-primary" @click="marketAccess">Получить доступ к товарам</button>
            </p>

            <table class="table table-condensed products" v-else>
                <thead>
                    <tr>
                        <th></th>
                        <th>Название</th>
                        <th>Стоимость</th>
                        <th>Модель</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(product, key) in products">
                        <td><img :src="product.thumb_photo" width="48" /></td>
                        <td><a :href="productUrl(product)" target="_blank">{{ productTitle(product) }}</a></td>
                        <td>{{ product.price.text }}</td>
                        <td><ModelPicker
                                :models="models"
                                :product="product"
                                :uploadCallback="uploadCallback"
                                :deleteCallback="deleteCallback"
                        /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="scss">
    table.products {
        tr td {
            vertical-align: middle;
        }
    }
</style>

<script>
    import ModelPicker from "./ModelPicker.vue";

    const api = {
        getProducts(group_id) {
            return new Promise((r, e) => {
                try {
                    VK.api('market.get', {
                        owner_id: `-${group_id}`,
                        extended: 1
                    }, (response) => {
                        if ('response' in response && 'items' in response.response) {
                            r(response.response.items);
                        } else {
                            e(response.error)
                        }
                    })
                } catch (er) {
                    e('api.getProducts failed');
                }
            });
        },
        getModels(group_id) {
            return axios.get(`/api/shop/-${group_id}/products`);
        },
        editProductDescription(product, modelUrl) {
            return new Promise((r, e) => {
                try {
                    VK.api('market.edit', {
                        owner_id: product.owner_id,
                        item_id: product.id,
                        name: product.title,
                        description: `${product.description}\n\n3D-модель: ${modelUrl}`,
                        category_id: product.category.id,
                        price: parseInt(product.price.amount) / 100,
                        main_photo_id: product.photos[0].id,
                    }, ({response}) => {
                        r(response);
                    })
                } catch (er) {
                    console.debug(er);
                    e('api.editProductDescription failed');
                }
            });
        }
    };

    export default {
        data() {
            return {
                products: [],
                models: []
            }
        },

        methods: {
            toGroup() {
                this.$router.push(`/shop/${this.groupId}`);
            },

            fetchProducts() {
                return api.getProducts(this.groupId)
                    .then(items => this.products = items)
                    .then(() => {
                        api.getModels(this.groupId)
                            .then(({ data }) => this.models = data.items);
                    })
            },

            assingModel(product, model) {
                const p = this.products.find(p => p.id = product.id);
                if (p) {
                    p.model = model;
                }
            },

            uploadCallback(model) {
                const product = _.find(this.products, p => p.id == model.product_id);
                api.editProductDescription(product, `https://stutab.ru/product/${model.id}`).then(() => {
                    this.fetchProducts();
                });
            },

            deleteCallback() {
                this.fetchProducts();
            },

            marketAccess() {
                VK.callMethod("showSettingsBox", 134217728);
            },

            productUrl(product) {
                return `https://vk.com/market${product.owner_id}?w=product${product.owner_id}_${product.id}`;
            },

            productTitle(product) {
                let m = _.find(this.models, m => m.product_id == product.id);
                if (m && m.size) {
                    return `${product.title} (${m.size} см.)`;
                }
                return product.title;
            }
        },

        computed: {
            groupId() {
                return loadParams ? loadParams.group_id : null;
            },
            token() {
                return loadParams ? loadParams.access_token : null;
            }
        },

        created() {
            console.debug('index created()');
            VK.init(() => {
                this.fetchProducts().catch(({ error_code }) => {
                    if (error_code !== 100) {
                        VK.callMethod("showSettingsBox", 134217728);
                    }
                })
            }, null, '5.68');

            VK.addCallback('onSettingsChanged', () => {
                this.fetchProducts();
            });
        },

        components: {
            ModelPicker
        }
    }
</script>
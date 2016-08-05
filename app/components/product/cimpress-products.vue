<template>
<div>

    <div v-if="error" class="uk-alert uk-alert-danger">{{ error }}</div>
    <div class="uk-flex uk-flex-middle uk-flex-space-between uk-form">
        <h3><span v-show="products">{{ count }} product<span v-show="total != 1">en</span> in Cimpress aanbod</span></h3>
        <input type="search" v-model="search" placeholder="Zoeken"/>
    </div>

    <table class="uk-table uk-table-hover" v-show="products">
        <thead>
        <tr>
            <th class="uk-width-1-6">SKU</th>
            <th class="uk-width-3-6">Productnaam</th>
            <th class="uk-width-2-6">
                <div class="uk-grid uk-grid-width-1-3">
                    <div>Minimaal</div>
                    <div>Maximaal</div>
                    <div>Prijs</div>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="product in products | filterBy search | count | orderBy filter.order filter.dir">
            <td>
                {{ product.Sku }}
            </td>
            <td>
                <a @click="productDetails(product)">{{ product.ProductName }}</a>
            </td>
            <td>
                <cimpress-product-prices :product="product"
                                         :product-prices="getProductPrices(product)"></cimpress-product-prices>
            </td>
        </tr>
        <tr v-show="!products.length">
            <td colspan="4" class="uk-text-center">
                <div class="uk-alert">Geen producten gevonden.</div>
            </td>
        </tr>
        </tbody>
    </table>
    <div v-else class="uk-margin uk-text-center"><i class="uk-icon-circle-o-notch uk-icon-spin"></i></div>

    <v-modal v-ref:productmodal :large="true">
        <div class="uk-modal-header">
            <h3>Product oppervlaktes</h3>
        </div>

        <cimpress-product :product="product"></cimpress-product>

        <div class="uk-modal-footer uk-text-right">
            <button type="button" class="uk-button uk-modal-close">Sluiten</button>
        </div>
    </v-modal>

</div>
</template>

<script>

    module.exports = {

        name: 'cimpress-products',

        props: ['config', 'isAdmin'],

        data: function () {
            return _.merge({
                sku: '',
                search: '',
                error: '',
                count: 0,
                product: {},
                products: false,
                product_prices: false,
                filter: {
                    order: 'ProductName',
                    dir: '1'
                }
            }, window.$data)
        },

        created: function () {
            this.resource = this.$resource('/api/cimpress_api/products');
        },

        ready: function () {
            this.load();
        },

        filters: {
            count: function (products) {
                this.count = _.size(products);
                return products;
            }
        },

        methods: {
            load: function () {
                this.$set('products', false);
                this.resource.query().then(function (res) {
                    if (res.data.products !== undefined) {
                        this.$set('product_prices', _.groupBy(res.data.product_prices, 'Sku'));
                        this.$set('products', res.data.products);
                    }
                }, function (res) {
                    this.$set('error', res.data.message || res.data);
                    this.$set('product_prices', []);
                    this.$set('products', []);
                });
            },
            getProductPrices: function (product) {
                return this.product_prices[product.Sku] || {};
            },
            productDetails: function (product) {
                this.product = product;
                this.$refs.productmodal.open();

            }

        },

        watch: {

            'filter': {
                handler: 'load',
                deep: true
            }
        },
        components: {
            'cimpress-product': require('../../components/product/cimpress-product.vue'),
            'cimpress-product-prices': require('../../components/product/cimpress-product-prices.vue')
        }

    };


</script>
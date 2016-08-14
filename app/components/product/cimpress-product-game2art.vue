<template>
    <div>

        <div v-if="game2art_product.id">
            <p>
                <em>{{ game2art_product.id }}</em> - <strong>{{ game2art_product.title }}</strong>
            </p>
            <button class="uk-button uk-button-small" @click="$refs.modal.open()">Ververs gegevens</button>

        </div>
        <div v-else>
            <button class="uk-button uk-button-small" @click="importProduct">Importeren in Game2art</button>
        </div>

        <v-modal v-ref:modal large>
            <div class="uk-modal-header">
                <h2>Importeren <em
                        v-show="game2art_product.id">{{ game2art_product.id }}</em> {{ game2art_product.title || product.ProductName }}
                </h2>
            </div>
            <div class="uk-form uk-form-stacked">
                <div class="uk-form-row">
                    <div class="uk-flex uk-flex-middle">
                        <div class="uk-flex-item-auto">
                            <label class="uk-form-label">Cimpress product</label>
                        </div>
                        <div class="uk-margin-left uk-margin-right">
                            <button type="button" class="uk-button uk-button-small"
                                    :title="Kopieer alle waarden" data-uk-tooltip="delay:200"
                                    @click="copyProduct"><i class="uk-icon-chevron-right"></i></button>
                        </div>
                        <div class="uk-flex-item-auto">
                            <label class="uk-form-label">Game2art product</label>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-flex uk-flex-middle">
                        <div class="uk-flex-item-auto">
                            <input class="uk-width-1-1 uk-form-blank" type="text" v-model="product.Sku" readonly>
                        </div>
                        <div class="uk-margin-left uk-margin-right">
                            <button type="button" class="uk-button uk-button-small"
                                    :title="Kopieer waarde" data-uk-tooltip="delay:200"
                                    @click="game2art_product.sku = product.Sku">
                                <i class="uk-icon-chevron-right"></i></button>
                        </div>
                        <div class="uk-flex-item-auto">
                            <input class="uk-width-1-1 uk-form-blank" type="text" v-model="game2art_product.sku" readonly>
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-flex uk-flex-middle">
                        <div class="uk-flex-item-auto">
                            <input class="uk-width-1-1 uk-form-blank" type="text" v-model="product.ProductName" readonly>
                        </div>
                        <div class="uk-margin-left uk-margin-right">
                            <button type="button" class="uk-button uk-button-small"
                                    :title="Kopieer waarde" data-uk-tooltip="delay:200"
                             @click="game2art_product.title = product.ProductName"><i class="uk-icon-chevron-right"></i></button>
                        </div>
                        <div class="uk-flex-item-auto">
                            <input class="uk-width-1-1" type="text" v-model="game2art_product.title">
                        </div>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="uk-flex uk-flex-middle">
                        <div class="uk-flex-item-auto">
                            <input class="uk-width-1-1 uk-form-blank" type="text" v-model="product.ProductName" readonly>
                        </div>
                        <div class="uk-margin-left uk-margin-right">
                            <button type="button" class="uk-button uk-button-small"
                                    :title="Kopieer waarde" data-uk-tooltip="delay:200"
                             @click="game2art_product.description = product.ProductName"><i class="uk-icon-chevron-right"></i></button>
                        </div>
                        <div class="uk-flex-item-auto">
                            <textarea rows="2" class="uk-width-1-1" v-model="game2art_product.description"></textarea>
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="uk-form-row">
                    <div class="uk-flex uk-flex-middle">
                        <div class="uk-flex-item-auto">
                            <ul class="uk-list uk-list-line">
                                <li v-for="price in $parent.getProductPrices(product)">
                                    <div class="uk-grid uk-grid-small uk-flex-middle">
                                        <div class="uk-width-1-5">
                                            {{ price.MinQuantity }}
                                        </div>
                                        <div class="uk-width-1-5">
                                            {{ price.MaxQuantity }}
                                        </div>
                                        <div class="uk-width-3-5 uk-text-right">
                                            {{ price.Currency }}
                                            <input type="number" v-model="price.WholesalePrice" step="0.01"
                                                   class="uk-form-width-small uk-form-blank uk-text-right" readonly number/>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="uk-margin-left uk-margin-right uk-form-stacked uk-text-center">
                            <div class="uk-form-row">
                                <label for="product_factor" class="uk-form-label">{{ 'Factor' | trans }}</label>
                                <input type="number" id="product_factor" v-model="config.margins.products.factor"
                                       step="0.01"class="uk-form-width-small uk-text-center" number/>
                            </div>
                            <div class="uk-form-row">
                                <label for="product_fee" class="uk-form-label">{{ 'Opslag' | trans }}</label>
                                <input type="number" id="product_fee" v-model="config.margins.products.fee" step="0.01"
                                       class="uk-form-width-small uk-text-center" number/>
                            </div>

                            <button type="button" class="uk-button uk-button-small uk-margin"
                                    :title="Kopieer prijzen" data-uk-tooltip="delay:200"
                                    @click="copyPrices"><i class="uk-icon-chevron-right"></i></button>
                        </div>
                        <div class="uk-flex-item-auto">
                            <ul class="uk-list uk-list-line">
                                <li v-for="price in game2art_product.prices">
                                    <div class="uk-grid uk-grid-small uk-flex-middle">
                                        <div class="uk-width-1-5">
                                            {{ price.min_quantity }}
                                        </div>
                                        <div class="uk-width-1-5">
                                            {{ price.max_quantity }}
                                        </div>
                                        <div class="uk-width-3-5 uk-text-right">
                                            {{ price.currency }}
                                            <input type="number" v-model="price.price" step="0.01"
                                                   class="uk-form-width-small uk-text-right" number/>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <hr/>

                <cimpress-surfaces v-ref:surfaces :product="product"></cimpress-surfaces>

                <p class="uk-text-center">
                    <button type="button" class="uk-button uk-button-small"
                            :title="Kopieer oppervlaktes" data-uk-tooltip="delay:200"
                            @click="copySurfaces"><i class="uk-icon-chevron-down"></i></button>
                </p>

                <div v-if="game2art_product.data.surfaces" class="uk-grid uk-grid-width-medium-1-2" data-uk-grid-margin>
                    <div>
                        <h3>Oppervlaktes</h3>
                        <dl v-for="spec in game2art_product.data.surfaces.ImageSpecification" class="uk-description-list-horizontal">
                            <dt>Naam</dt>
                            <dd>{{ spec.Name }}</dd>
                            <dt>Hoogte</dt>
                            <dd>{{ spec.WidthInMm }} mm</dd>
                            <dt>Breedte</dt>
                            <dd>{{ spec.HeightInMm }} mm</dd>
                        </dl>
                    </div>
                    <div>
                        <h3>Afbeeldingen</h3>
                        <dl v-for="spec in game2art_product.data.surfaces.Surfaces" class="uk-description-list-horizontal">
                            <dt>Naam</dt>
                            <dd>{{ spec.Name }}</dd>
                            <dt>Hoogte</dt>
                            <dd>{{ spec.WidthInMm }} mm</dd>
                            <dt>Breedte</dt>
                            <dd>{{ spec.HeightInMm }} mm</dd>
                        </dl>
                    </div>
                </div>


            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="uk-button uk-modal-close">Sluiten</button>
                <button type="button" class="uk-button uk-button-primary" @click="save">Opslaan</button>
            </div>
        </v-modal>

    </div>

</template>

<script>

    module.exports = {

        name: 'cimpress-product-game2art',

        props: ['product'],

        data: function () {
            return _.merge({
                config: {},
                default_product: {},
                game2art_products: {}
            }, window.$data);
        },

        created: function () {
            this.$options.components = this.$parent.$options.components;
            this.Product = this.$resource('api/game2art/product{/id}');
            if (!this.game2art_products[this.product.Sku]) {
                Vue.set(this.game2art_products, this.product.Sku, _.assign({}, this.default_product));
            }
            if (!_.isArray(this.game2art_products[this.product.Sku].prices)) {
                this.game2art_products[this.product.Sku].prices = [];
            }
            this.game2art_products[this.product.Sku].data.supplier = 'cimpress';
        },

        computed: {
            game2art_product: function () {
                return this.game2art_products[this.product.Sku];
            }
        },

        methods: {
            copyProduct: function () {
                this.game2art_product.sku = this.product.Sku;
                this.game2art_product.title = this.product.ProductName;
                this.game2art_product.description = this.product.ProductName;
                this.copyPrices();
                this.copySurfaces();
            },
            copyPrices: function () {
                this.game2art_product.prices = [];
                this.$parent.getProductPrices(this.product).forEach(function (cimpress_price) {
                    var price = Math.round(((cimpress_price.WholesalePrice * this.config.margins.products.factor)
                                    + this.config.margins.products.fee) * 100) / 100;
                    this.game2art_product.prices.push({
                        min_quantity: cimpress_price.MinQuantity,
                        max_quantity: cimpress_price.MaxQuantity,
                        currency: cimpress_price.Currency,
                        price: price
                    })
                }.bind(this));
            },
            copySurfaces: function () {
                if (this.$refs.surfaces.isLoaded()) {
                    Vue.set(this.game2art_product.data, 'surfaces', this.$refs.surfaces.getSurfaces());
                }
            },
            importProduct: function () {
                this.$refs.modal.modal.one('show.uk.modal', function () {
                    this.copyProduct();
                }.bind(this));
                this.$refs.modal.open();
            },
            save: function () {
                this.Product.save({id: this.game2art_product.id}, {product: this.game2art_product}).then(function (res) {

                    Vue.set(this.game2art_products, this.product.Sku, res.data.product);

                    this.$notify(this.$trans('Product %title% saved.', {title: this.game2art_product.title}));

                }, function (res) {
                    this.$notify(res.data, 'danger');
                });
            }
        }
    };


</script>
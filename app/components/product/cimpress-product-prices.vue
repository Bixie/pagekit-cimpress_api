<template>
    <ul class="uk-list uk-list-line">
        <li v-for="productPrice in productPrices">
            <div class="uk-grid uk-grid-width-1-3">
                <div>
                    {{ productPrice.MinQuantity }}
                </div>
                <div>
                    <a @click="addToCart(productPrice)">{{ productPrice.MaxQuantity }}</a>
                </div>
                <div>
                    {{ productPrice.WholesalePrice | currency productPrice.Currency }}
                </div>
            </div>
        </li>
    </ul>
</template>

<script>

    module.exports = {

        name: 'cimpress-product-prices',

        props: ['product', 'productPrices'],

        methods: {
            addToCart: function (productPrice) {
                $bixCart.addItem({
                    Sku: this.product.Sku,
                    Quantity: productPrice.MaxQuantity
                })
            }
        }
    };


</script>
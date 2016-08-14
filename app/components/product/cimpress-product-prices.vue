<template>
    <ul class="uk-list uk-list-line">
        <li v-for="productPrice in productPrices">
            <div class="uk-grid uk-grid-width-1-3">
                <div>
                    {{ productPrice.MinQuantity }}
                </div>
                <div>
                    {{ productPrice.MaxQuantity }}
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
    };


</script>

module.exports = {

    el: '#cimpress_api-dashboard',

    data: function () {
        return _.merge({
            error: '',
            livecheck: {},
            status: {}
        }, window.$data);
    },

    components: {
        'cart-widget': require('../../components/cart-widget.vue'),
        'cimpress-products': require('../../components/product/cimpress-products.vue')
    }

};

Vue.ready(module.exports);

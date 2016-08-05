module.exports = {

    el: '#cimpress_api-settings',

    data: function () {
        return _.merge({
            config: {},
            form: {}
        }, window.$data);
    },

    fields: require('../../settings/fields'),

    methods: {

        save: function () {
            this.$http.post('admin/cimpress_api/config', { config: this.config }).then(function () {
                this.$notify('Settings saved.');
            }, function (res) {
                this.$notify(res.data, 'danger');
            });
        }

    }

};

Vue.ready(module.exports);

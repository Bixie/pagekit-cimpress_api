module.exports = [

    {
        entry: {
            /*admin views*/
            "cimpress_api-dashboard": "./app/views/admin/dashboard.js",
            "cimpress_api-settings": "./app/views/admin/settings.js"
        },
        output: {
            filename: "./app/bundle/[name].js"
        },
        externals: {
            "lodash": "_",
            "jquery": "jQuery",
            "uikit": "UIkit",
            "vue": "Vue"
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: "vue"},
                {test: /\.html$/, loader: "vue-html"}
            ]
        }

    }

];

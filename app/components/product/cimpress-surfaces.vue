<template>
    <div>

        <div v-if="error" class="uk-alert uk-alert-danger">{{ error }}</div>

        <div v-show="surfaces" class="uk-grid uk-grid-width-medium-1-2" data-uk-grid-margin>
            <div>
                <h3>Oppervlaktes</h3>
                <dl v-for="spec in surfaces.ImageSpecification" class="uk-description-list-horizontal">
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
                <dl v-for="spec in surfaces.Surfaces" class="uk-description-list-horizontal">
                    <dt>Naam</dt>
                    <dd>{{ spec.Name }}</dd>
                    <dt>Hoogte</dt>
                    <dd>{{ spec.WidthInMm }} mm</dd>
                    <dt>Breedte</dt>
                    <dd>{{ spec.HeightInMm }} mm</dd>
                </dl>
            </div>
        </div>

        <div v-show="surfaces === false" class="uk-margin uk-text-center"><i class="uk-icon-circle-o-notch uk-icon-spin"></i></div>

    </div>
</template>

<script>

    module.exports = {

        name: 'cimpress-product',

        props: ['product'],

        data: function () {
            return {
                error: '',
                surfaces: false
            }
        },

        created: function () {
            this.$options.components = this.$parent.$parent.$options.components;
            this.resource = this.$resource('/api/cimpress_api/product{/id}');
            this.load();
        },

        methods: {
            load: function () {
                this.$set('surfaces', false);
                this.resource.query({id: this.product.Sku}).then(function (res) {
                    if (res.data.surfaces !== undefined) {
                        this.$set('surfaces', res.data.surfaces);
                    }
                }, function (res) {
                    this.$set('error', res.data.message || res.data);
                    this.$set('surfaces', []);
                });
            },
            isLoaded: function () {
                return this.surfaces !== false;
            },
            getSurfaces: function () {
                return this.surfaces;
            }

        }

    };


</script>
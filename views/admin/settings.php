<?php $view->script('cimpress_api-settings', 'bixie/cimpress_api:app/bundle/cimpress_api-settings.js', ['bixie-framework']) ?>

<div id="cimpress_api-settings" class="uk-form uk-form-horizontal" v-cloak>


	<div class="uk-margin uk-flex uk-flex-space-between uk-flex-wrap" data-uk-margin>
		<div data-uk-margin>

			<h2 class="uk-margin-remove">{{ 'Cimpress Api Settings' | trans }}</h2>

		</div>
		<div data-uk-margin>

			<button class="uk-button uk-button-primary" @click="save">{{ 'Save' | trans }}</button>

		</div>
	</div>

	<fields :config="$options.fields.settings" :model.sync="config" template="formrow"></fields>

</div>

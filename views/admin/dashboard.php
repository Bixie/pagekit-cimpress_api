<?php
$view->script('cimpress-admin-dashboard', 'bixie/cimpress_api:app/bundle/cimpress_api-dashboard.js', ['bixie-pkframework']);
?>

<div id="cimpress_api-dashboard" class="uk-noconflict" v-cloak>

    <div class="uk-grid uk-grid-width-medium-1-2" data-uk-grid-margin>
        <div class="uk-width-medium-1-3">
            <div class="uk-panel uk-panel-box uk-panel-box-secondary uk-margin">
                <h3 class="uk-panel-title">{{ 'Api Status' | trans }}</h3>

                <div v-if="error" class="uk-alert uk-alert-danger">{{ error }}</div>

                <dl v-if="livecheck.status" class="uk-description-list-horizontal">
                    <dt>{{ 'Status' | trans }}</dt>
                    <dd>{{ livecheck.status }}</dd>
                    <dt>{{ 'Uptime' | trans }}</dt>
                    <dd>{{ livecheck.upTime }}</dd>
                    <dt>{{ 'Build date' | trans }}</dt>
                    <dd>{{ livecheck.buildDate | date }}</dd>
                    <dt v-if="status.SettingName">{{ status.SettingName }}</dt>
                    <dd v-if="status.SettingValue">{{ status.SettingValue }}</dd>
                </dl>
            </div>

        </div>
        <div class="uk-width-medium-2-3">

            <cimpress-products :config="config" :is-admin="true"></cimpress-products>

        </div>
    </div>

</div>

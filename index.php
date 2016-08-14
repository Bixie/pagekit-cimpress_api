<?php

return [

	'name' => 'bixie/cimpress_api',

	'type' => 'extension',

	'main' => 'Bixie\\CimpressApi\\CimpressApiModule',

	'autoload' => [

		'Bixie\\CimpressApi\\' => 'src'

	],
	'routes' => [

		'/cimpress_api' => [
			'name' => '@cimpress_api',
			'controller' => [
				'Bixie\\CimpressApi\\Controller\\CimpressApiController'
			]
		],
		'/api/cimpress_api' => [
			'name' => '@cimpress_api/api',
			'controller' => [
				'Bixie\\CimpressApi\\Controller\\CimpressApiApiController'
			]
		]

	],

	'resources' => [

		'bixie/cimpress_api:' => ''

	],

	'config' => [
		'api_username' => '',
		'api_password' => '',
		'api_client_id' => '',
		'api_connection' => 'default',
		'debug' => '',
        'margins' => [
            'products' => [
                'factor' => 1,
                'fee' => 0
            ],
            'delivery' => [
                'factor' => 1,
                'fee' => 0
            ]
        ]
	],

	'menu' => [

		'cimpress_api' => [
			'label' => 'Cimpress Api',
			'icon' => 'packages/bixie/cimpress_api/icon.svg',
			'url' => '@cimpress_api',
			'access' => 'cimpress_api: use api',
			'active' => '@cimpress_api(/*)'
		],

		'cimpress_api: index' => [
			'label' => 'Api',
			'parent' => 'cimpress_api',
			'url' => '@cimpress_api',
			'access' => 'cimpress_api: use api',
			'active' => '@cimpress_api'
		],

		'cimpress_api: settings' => [
			'label' => 'Settings',
			'parent' => 'cimpress_api',
			'url' => '@cimpress_api/settings',
			'access' => 'cimpress_api: manage settings',
			'active' => '@cimpress_api/settings'
		]

	],

	'permissions' => [

		'cimpress_api: use api' => [
			'title' => 'Use API'
		],

		'cimpress_api: manage settings' => [
			'title' => 'Manage settings'
		]

	],

	'settings' => '@cimpress_api/settings',

];

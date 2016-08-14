
module.exports = {
    settings: {
        'api_username': {
            type: 'text',
            label: 'Username',
            attrs: {'class': 'uk-form-width-large'}
        },
        'api_password': {
            type: 'text',
            label: 'Password',
            attrs: {'class': 'uk-form-width-large'}
        },
        'api_client_id': {
            type: 'text',
            label: 'Client ID',
            attrs: {'class': 'uk-form-width-large'}
        },
        'api_connection': {
            type: 'select',
            label: 'Connection type',
            options: {
                'Default': 'default', /*trans*/
                'Fulfillers': 'fulfillers' /*trans*/
            },
            attrs: {'class': 'uk-form-width-medium'}
        },
        'debug': {
            type: 'checkbox',
            label: 'Debug mode',
            optionlabel: 'Set debug mode'
        }
    },
    margins: {
        'products.factor': {
            type: 'number',
            label: 'Products factor',
            attrs: {'class': 'uk-form-width-medium uk-text-right', 'step': '0.01'}
        },
        'products.fee': {
            type: 'number',
            label: 'Products options fee',
            attrs: {'class': 'uk-form-width-medium uk-text-right', 'step': '0.01'}
        },
        'delivery.factor': {
            type: 'number',
            label: 'Delivery options factor',
            attrs: {'class': 'uk-form-width-medium uk-text-right', 'step': '0.01'}
        },
        'delivery.fee': {
            type: 'number',
            label: 'Delivery options fee',
            attrs: {'class': 'uk-form-width-medium uk-text-right', 'step': '0.01'}
        }
    }


};
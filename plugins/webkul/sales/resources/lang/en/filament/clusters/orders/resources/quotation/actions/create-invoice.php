<?php

return [
    'title' => 'Create Invoice',

    'modal' => [
        'heading' => 'Create Invoice',
    ],

    'notification' => [
        'invoice-created' => [
            'title' => 'Invoice created',
            'body'  => 'Invoice has been created successfully.',
        ],

        'no-invoiceable-lines' => [
            'title' => 'No invoiceable lines',
            'body'  => 'There is no invoiceable line, please make sure that a quantity has been received.',
        ],
    ],

    'form' => [
        'fields' => [
            'create-invoice' => 'Create Invoice',
        ],
    ],
];

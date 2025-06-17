<?php

return [
    'title' => 'Order Template Products',

    'navigation' => [
        'title' => 'Order Template Products',
        'group' => 'Sales Orders',
    ],

    'global-search' => [
        'name'    => 'Name',
    ],

    'form' => [
        'fields' => [
            'sort'           => 'Sort',
            'order-template' => 'Order Template',
            'company'        => 'Company',
            'product'        => 'Product',
            'product-uom'    => 'Product UOM',
            'creator'        => 'Creator',
            'display-type'   => 'Display Type',
            'name'           => 'Name',
            'quantity'       => 'Quantity',
        ],
    ],

    'table' => [
        'columns' => [
            'sort'           => 'Sort',
            'order-template' => 'Order Template',
            'company'        => 'Company',
            'product'        => 'Product',
            'product-uom'    => 'Product UOM',
            'created-by'     => 'Created By',
            'display-type'   => 'Display Type',
            'name'           => 'Name',
            'quantity'       => 'Quantity',
            'created-at'     => 'Created At',
            'updated-at'     => 'Updated At',

        ],
        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Order Template Products updated',
                    'body'  => 'The order template products has been updated successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Order Template Products deleted',
                    'body'  => 'The order template products has been deleted successfully.',
                ],
            ],
        ],
        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Order Template Products deleted',
                    'body'  => 'The order template products has been deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'entries' => [
            'sort'           => 'Sort Order',
            'order-template' => 'Order Template',
            'company'        => 'Company',
            'product'        => 'Product',
            'product-uom'    => 'Product UOM',
            'display-type'   => 'Display Type',
            'name'           => 'Name',
            'quantity'       => 'Quantity',
        ],
    ],
];

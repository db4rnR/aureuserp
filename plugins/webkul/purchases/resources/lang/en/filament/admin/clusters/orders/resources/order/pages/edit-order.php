<?php

return [
    'notification' => [
        'title' => 'Order updated',
        'body'  => 'The order has been updated successfully.',
    ],

    'header-actions' => [
        'confirm' => [
            'label' => 'Confirm',
        ],

        'close' => [
            'label' => 'Close',
        ],

        'cancel' => [
            'label' => 'Cancel',
        ],

        'print' => [
            'label' => 'Print',
        ],

        'delete' => [
            'notification' => [
                'success' => [
                    'title' => 'Order deleted',
                    'body'  => 'The order has been deleted successfully.',
                ],

                'error' => [
                    'title' => 'Order could not be deleted',
                    'body'  => 'The order cannot be deleted because it is currently in use.',
                ],
            ],
        ],
    ],
];

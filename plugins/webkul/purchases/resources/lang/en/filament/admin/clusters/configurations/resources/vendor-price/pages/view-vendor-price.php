<?php

declare(strict_types=1);

return [
    'navigation' => [
        'title' => 'View Vendor Price List',
    ],

    'header-actions' => [
        'delete' => [
            'notification' => [
                'success' => [
                    'title' => 'Vendor Price deleted',
                    'body' => 'The vendor price has been deleted successfully.',
                ],

                'error' => [
                    'title' => 'Vendor Price could not be deleted',
                    'body' => 'The vendor price cannot be deleted because it is currently in use.',
                ],
            ],
        ],
    ],
];

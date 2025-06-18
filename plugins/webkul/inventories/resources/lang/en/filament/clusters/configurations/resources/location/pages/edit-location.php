<?php

declare(strict_types=1);

return [
    'notification' => [
        'title' => 'Location updated',
        'body' => 'The location has been updated successfully.',
    ],

    'header-actions' => [
        'print' => [
            'label' => 'Print',
        ],

        'delete' => [
            'notification' => [
                'title' => 'Location deleted',
                'body' => 'The location has been deleted successfully.',
            ],
        ],
    ],
];

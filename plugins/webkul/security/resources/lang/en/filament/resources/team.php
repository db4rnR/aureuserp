<?php

declare(strict_types=1);

return [
    'title' => 'Teams',

    'navigation' => [
        'title' => 'Teams',
        'group' => 'Settings',
    ],

    'form' => [
        'fields' => [
            'name' => 'Name',
        ],
    ],

    'table' => [
        'columns' => [
            'name' => 'Name',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Team updated',
                    'body' => 'The team has been updated successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Team deleted',
                    'body' => 'The team has been deleted successfully.',
                ],
            ],
        ],

        'empty-state-actions' => [
            'create' => [
                'notification' => [
                    'title' => 'Teams created',
                    'body' => 'The teams has been created successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'entries' => [
            'name' => 'Name',
        ],
    ],
];

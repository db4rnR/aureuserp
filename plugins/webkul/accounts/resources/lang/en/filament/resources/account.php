<?php

declare(strict_types=1);

return [
    'form' => [
        'sections' => [
            'fields' => [
                'code' => 'Code',
                'account-name' => 'Account Name',
                'accounting' => 'Accounting',
                'account-type' => 'Account Type',
                'default-taxes' => 'Default Taxes',
                'tags' => 'Tags',
                'journals' => 'Journals',
                'currency' => 'Currency',
                'deprecated' => 'Deprecated',
                'reconcile' => 'Reconcile',
                'non-trade' => 'Non Trade',
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'code' => 'Code',
            'account-name' => 'Account Name',
            'account-type' => 'Account Type',
            'currency' => 'Currency',
            'deprecated' => 'Deprecated',
            'reconcile' => 'Reconcile',
            'non-trade' => 'Non Trade',
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Account deleted',
                    'body' => 'The account has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Accounts deleted',
                    'body' => 'The accounts has been deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'sections' => [
            'entries' => [
                'code' => 'Code',
                'account-name' => 'Account Name',
                'accounting' => 'Accounting',
                'account-type' => 'Account Type',
                'default-taxes' => 'Default Taxes',
                'tags' => 'Tags',
                'journals' => 'Journals',
                'currency' => 'Currency',
                'deprecated' => 'Deprecated',
                'reconcile' => 'Reconcile',
                'non-trade' => 'Non Trade',
            ],
        ],
    ],
];

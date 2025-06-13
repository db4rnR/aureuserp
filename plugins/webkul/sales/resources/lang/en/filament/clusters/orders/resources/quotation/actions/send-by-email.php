<?php

return [
    'title' => 'Send By Email',

    'modal' => [
        'heading' => 'Send Quotation By Email',
    ],

    'form' => [
        'fields' => [
            'partners'    => 'Partners',
            'subject'     => 'Subject',
            'description' => 'Description',
            'attachment'  => 'Attachment',
        ],
    ],

    'actions' => [
        'notification' => [
            'title' => 'Quotation sent',
            'body'  => 'Quotation has been sent successfully.',
        ],
    ],
];

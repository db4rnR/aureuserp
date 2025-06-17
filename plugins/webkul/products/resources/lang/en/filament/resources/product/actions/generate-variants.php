<?php

return [
    'label'        => 'Generate Variants',
    'notification' => [
        'empty' => [
            'title' => 'No attributes found',
            'body'  => 'Please add attributes to generate variants.',
        ],

        'success' => [
            'title' => 'Variants generated successfully',
            'body'  => 'All product variants have been generated.',
        ],

        'error' => [
            'title' => 'Error generating variants',
            'body'  => 'An error occurred while generating product variants.',
        ],
    ],
];

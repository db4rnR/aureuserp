<?php

return [
    'navigation' => [
        'title' => 'Vendor Price Lists',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'vendor'                      => 'Vendor',
                    'vendor-product-name'         => 'Vendor Product Name',
                    'vendor-product-name-tooltip' => 'The vendor\'s product name will appear on the request for quotation. Leave it blank to use the internal product name.',
                    'vendor-product-code'         => 'Vendor Product Code',
                    'vendor-product-code-tooltip' => 'The vendor\'s product code will appear on the request for quotation. Leave it blank to use the internal code.',
                    'delay'                       => 'Delivery Lead Time (Days)',
                    'delay-tooltip'               => 'The lead time (in days) from purchase order confirmation to product receipt in the warehouse. Used by the scheduler for automatic purchase order planning.',
                ],
            ],

            'prices' => [
                'title'  => 'Prices',

                'fields' => [
                    'product'            => 'Product',
                    'quantity'           => 'Quantity',
                    'quantity-tooltip'   => 'The minimum quantity required to purchase from this vendor to qualify for the specified price. This is expressed in the vendor\'s Product Unit of Measure or, if not set, the product\'s default unit of measure.',
                    'unit-price'         => 'Unit Price',
                    'unit-price-tooltip' => 'The price per unit for this product from the vendor, expressed in the vendor’s Product Unit of Measure or, if not set, the product\'s default unit of measure.',
                    'currency'           => 'Currency',
                    'valid-from'         => 'Valid From',
                    'valid-to'           => 'Valid Until',
                    'discount'           => 'Discount (%)',
                    'company'            => 'Company',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'vendor'              => 'Vendor',
            'vendor-product-name' => 'Vendor Product Name',
            'vendor-product-code' => 'Vendor Product Code',
            'delay'               => 'Delivery Lead Time (Days)',
            'product'             => 'Product',
            'quantity'            => 'Quantity',
            'unit-price'          => 'Unit Price',
            'currency'            => 'Currency',
            'valid-from'          => 'Valid From',
            'valid-to'            => 'Valid Until',
            'discount'            => 'Discount (%)',
            'company'             => 'Company',
            'created-at'          => 'Created At',
            'updated-at'          => 'Updated At',
        ],

        'filters' => [
            'vendor'        => 'Filter by Vendor',
            'product'       => 'Filter by Product',
            'currency'      => 'Filter by Currency',
            'company'       => 'Filter by Company',
            'price-from'    => 'Minimum Price',
            'price-to'      => 'Maximum Price',
            'min-qty-from'  => 'Minimum Quantity From',
            'min-qty-to'    => 'Minimum Quantity To',
            'starts-from'   => 'Valid From Date',
            'ends-before'   => 'Valid To Date',
            'created-from'  => 'Created From',
            'created-until' => 'Created Until',
        ],

        'groups' => [
            'vendor'     => 'Vendor',
            'product'    => 'Product',
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'success' => [
                        'title' => 'Vendor Price deleted',
                        'body'  => 'The vendor price has been deleted successfully.',
                    ],

                    'error' => [
                        'title' => 'Vendor Price could not be deleted',
                        'body'  => 'The vendor price cannot be deleted because it is currently in use.',
                    ],
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'success' => [
                        'title' => 'Vendor Prices deleted',
                        'body'  => 'The vendor prices has been deleted successfully.',
                    ],

                    'error' => [
                        'title' => 'Vendor Prices could not be deleted',
                        'body'  => 'The vendor prices cannot be deleted because they are currently in use.',
                    ],
                ],
            ],
        ],
    ],

    'infolist' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'entries' => [
                    'vendor'                      => 'Vendor',
                    'vendor-product-name'         => 'Vendor Product Name',
                    'vendor-product-name-tooltip' => 'The vendor\'s product name will appear on the request for quotation. Leave it blank to use the internal product name.',
                    'vendor-product-code'         => 'Vendor Product Code',
                    'vendor-product-code-tooltip' => 'The vendor\'s product code will appear on the request for quotation. Leave it blank to use the internal code.',
                    'delay'                       => 'Delivery Lead Time (Days)',
                    'delay-tooltip'               => 'The lead time (in days) from purchase order confirmation to product receipt in the warehouse. Used by the scheduler for automatic purchase order planning.',
                ],
            ],

            'record-information' => [
                'title'  => 'Record Information',

                'entries' => [
                    'created-by'   => 'Created By',
                    'created-at'   => 'Created At',
                    'last-updated' => 'Last Updated',
                ],
            ],

            'prices' => [
                'title'  => 'Prices',

                'entries' => [
                    'product'            => 'Product',
                    'quantity'           => 'Quantity',
                    'quantity-tooltip'   => 'The minimum quantity required to purchase from this vendor to qualify for the specified price. This is expressed in the vendor\'s Product Unit of Measure or, if not set, the product\'s default unit of measure.',
                    'unit-price'         => 'Unit Price',
                    'unit-price-tooltip' => 'The price per unit for this product from the vendor, expressed in the vendor’s Product Unit of Measure or, if not set, the product\'s default unit of measure.',
                    'currency'           => 'Currency',
                    'valid-from'         => 'Valid From',
                    'valid-to'           => 'Valid Until',
                    'discount'           => 'Discount (%)',
                    'company'            => 'Company',
                ],
            ],
        ],
    ],
];

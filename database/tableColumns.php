<?php
$table_columns_mapping = [
    'UserLoginInformation' => [
        'first_name', 'last_name', 'password', 'email', 'created_at', 'updated_at'
    ],
    'products'=> [
        'product_name', 'description', 'image', 'created_by', 'created_at', 'updated_at'
    ],
    'suppliers'=> [
        'supplier_name', 'supplier_location', 'email', 'created_by', 'created_at', 'updated_at'
    ],
];

$table_update_columns_mapping = [
    'UserLoginInformation' => [
        'first_name', 'last_name', 'email', 'updated_at'
    ],
    'products'=> [
        'product_name', 'description', 'image', 'updated_at'
    ],
    'suppliers'=> [
        'supplier_name', 'supplier_location', 'email'
    ],
];
?>
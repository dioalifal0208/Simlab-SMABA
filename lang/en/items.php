<?php

return [
    // Page Titles
    'title' => 'Inventory',
    'add_item' => 'Add Item',
    'edit_item' => 'Edit Item',
    'item_details' => 'Item Details',

    // Table Headers
    'table' => [
        'name' => 'Name',
        'category' => 'Category',
        'stock' => 'Stock',
        'condition' => 'Condition',
        'location' => 'Location',
        'actions' => 'Actions',
        'code' => 'Code',
        'type' => 'Type',
        'unit' => 'Unit',
    ],

    // Form Labels
    'form' => [
        'name' => 'Item Name',
        'category' => 'Category',
        'type' => 'Type',
        'stock' => 'Stock',
        'unit' => 'Unit',
        'condition' => 'Condition',
        'location' => 'Location',
        'description' => 'Description',
        'image' => 'Image',
        'code' => 'Item Code',
    ],

    // Categories
    'categories' => [
        'alat' => 'Equipment',
        'bahan' => 'Materials',
    ],

    // Types
    'types' => [
        'habis_pakai' => 'Consumable',
        'tidak_habis_pakai' => 'Non-Consumable',
    ],

    // Conditions
    'conditions' => [
        'baik' => 'Good',
        'rusak_ringan' => 'Slightly Damaged',
        'rusak_berat' => 'Heavily Damaged',
    ],

    // Units
    'units' => [
        'pcs' => 'Pieces',
        'set' => 'Set',
        'box' => 'Box',
        'liter' => 'Liter',
        'ml' => 'Milliliter',
        'gram' => 'Gram',
        'kg' => 'Kilogram',
    ],

    // Actions
    'actions' => [
        'add' => 'Add Item',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'view' => 'View Details',
        'report_damage' => 'Report Damage',
        'request_stock' => 'Request Stock',
    ],

    // Messages
    'messages' => [
        'created' => 'Item created successfully',
        'updated' => 'Item updated successfully',
        'deleted' => 'Item deleted successfully',
        'not_found' => 'Item not found',
        'low_stock' => 'Low stock',
        'out_of_stock' => 'Out of stock',
    ],
];

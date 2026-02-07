<?php

return [
    // Page Titles
    'title' => 'Laboratory Inventory',
    'subtitle' => 'List of all equipment and materials available in the laboratory.',
    'add_item' => 'Add Item',
    'edit_item' => 'Edit Item',
    'item_details' => 'Item Details',
    'add_new_item' => 'Add New Inventory Item',

    // Table Headers
    'table' => [
        'name' => 'EQUIPMENT/MATERIAL NAME',
        'category' => 'CATEGORY',
        'stock' => 'STOCK',
        'condition' => 'CONDITION',
        'location' => 'LOCATION',
        'actions' => 'ACTIONS',
        'code' => 'CODE',
        'type' => 'TYPE',
        'unit' => 'UNIT',
        'lab' => 'LAB',
        'quantity' => 'QUANTITY',
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
        'import' => 'Import Item',
        'request_add' => 'Request Add Item',
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

    // Empty State
    'empty' => [
        'title' => 'No Items Found',
        'description' => 'Try changing the search filter or add a new item.',
        'action' => 'Add New Item',
    ],

    // Filters
    'filters' => [
        'search' => 'Search by name...',
        'type' => 'Type',
        'condition' => 'Condition',
        'all_labs' => 'All Labs',
    ],
];

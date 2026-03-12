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
        'name_label' => 'Equipment / Material Name',
        'category' => 'Category',
        'type' => 'Type',
        'stock' => 'Stock',
        'stock_label' => 'Quantity/Stock',
        'unit' => 'Unit',
        'unit_placeholder' => 'e.g., Pcs, Gram, Liter',
        'condition' => 'Condition',
        'location' => 'Storage Location',
        'description' => 'Description',
        'description_optional' => 'Description / Notes (Optional)',
        'image' => 'Image',
        'photos_label' => 'Item Photos (Multiple allowed)',
        'add_photo_label' => 'Add New Photos (Optional)',
        'current_gallery' => 'Current Gallery:',
        'code' => 'Item Code',
        'min_stock' => 'Minimum Stock',
        'optional' => '(Optional)',
        'min_stock_placeholder' => 'e.g., 10',
        'lab' => 'Laboratory',
        'lab_prefix' => 'Lab',
        // Document Support
        'doc_section_title' => 'Supporting Documents',
        'doc_hint_alat' => 'For measuring instruments, upload a Manual Book. For simple equipment, upload SOP or Work Instructions (WI).',
        'doc_hint_bahan' => 'For practicum materials/chemicals, upload MSDS (Material Safety Data Sheet) document.',
        'doc_type_label' => 'Document Type',
        'doc_type_placeholder' => 'Select Document Type',
        'doc_types' => [
            'manual_book' => 'Manual Book',
            'sop_ik' => 'SOP / Work Instructions',
            'msds' => 'MSDS (Safety Data Sheet)',
        ],
        'doc_file_label' => 'Upload Document',
        'doc_replace_label' => 'Replace Document',
        'doc_max_size' => 'Max. 5MB',
        'doc_current' => 'Existing document',
        'doc_view' => 'View',
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
        'alat' => 'Equipment (Non-Consumable)',
        'bahan' => 'Consumable Material',
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

    // Item Details
    'details' => [
        'title' => 'Item Details',
        'subtitle' => 'Complete information, history, and actions for this inventory item.',
        'back_to_list' => 'Back to Inventory',
        'photo_tip' => 'Click to enlarge',
        'single_photo' => 'Only one photo available.',
        'no_photo' => 'No photo available.',
        'specs_title' => 'Description & Specifications',
        'no_description' => 'No description available.',
        'inventory_code' => 'Inventory Code',
        'procurement_year' => 'Procurement Year',
        'min_stock' => 'Minimum Stock',
        'created_at' => 'Created',
        'updated_at' => 'Updated',
        'loan_history' => 'Loan History',
        'loan_history_soon' => 'Loan history feature coming soon.',
        'maintenance_history' => 'Maintenance History',
        'no_maintenance' => 'No maintenance records for this item.',
        'done_by' => 'Done by :name on :date',
        'usage_modules' => 'Used In Modules',
        'no_modules' => 'Not linked to any practicum module.',
        'created_by' => 'Created by',
        'scan_tip' => 'Scan to open item details.',
        'user_actions' => 'User Actions',
        'admin_actions' => 'Admin Actions',
        // Document Support
        'doc_title' => 'Supporting Documents',
        'doc_generic' => 'Document',
        'doc_format' => 'PDF Format',
        'doc_view' => 'View',
        'doc_download' => 'Download',
        'doc_empty' => 'No supporting documents uploaded for this item.',
        'doc_upload_cta' => 'Upload Document',
    ],
];

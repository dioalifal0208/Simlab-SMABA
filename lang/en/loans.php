<?php

return [
    // Page Titles
    'title' => 'Loans',
    'create_loan' => 'Create Loan',
    'edit_loan' => 'Edit Loan',
    'loan_details' => 'Loan Details',

    // Table Headers
    'table' => [
        'borrower' => 'Borrower',
        'item' => 'Item',
        'quantity' => 'Quantity',
        'purpose' => 'Purpose',
        'borrow_date' => 'Borrow Date',
        'return_date' => 'Return Date',
        'status' => 'Status',
        'actions' => 'Actions',
    ],

    // Form Labels
    'form' => [
        'borrower' => 'Borrower',
        'item' => 'Item',
        'quantity' => 'Quantity',
        'purpose' => 'Purpose',
        'borrow_date' => 'Borrow Date',
        'return_date' => 'Expected Return Date',
        'notes' => 'Notes',
    ],

    // Status
    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'returned' => 'Returned',
        'overdue' => 'Overdue',
    ],

    // Actions
    'actions' => [
        'create' => 'Create Loan',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'return' => 'Mark as Returned',
        'view' => 'View Details',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],

    // Messages
    'messages' => [
        'created' => 'Loan created successfully',
        'updated' => 'Loan updated successfully',
        'deleted' => 'Loan deleted successfully',
        'approved' => 'Loan approved successfully',
        'rejected' => 'Loan rejected successfully',
        'returned' => 'Loan marked as returned',
        'not_found' => 'Loan not found',
        'insufficient_stock' => 'Insufficient stock',
    ],
];

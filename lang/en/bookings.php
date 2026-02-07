<?php

return [
    // Page Titles
    'title' => 'Lab Booking',
    'create_booking' => 'Create Booking',
    'edit_booking' => 'Edit Booking',
    'booking_details' => 'Booking Details',

    // Table Headers
    'table' => [
        'lab' => 'Laboratory',
        'user' => 'User',
        'date' => 'Date',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'purpose' => 'Purpose',
        'status' => 'Status',
        'actions' => 'Actions',
    ],

    // Form Labels
    'form' => [
        'lab' => 'Laboratory',
        'date' => 'Date',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'purpose' => 'Purpose',
        'notes' => 'Notes',
        'participants' => 'Number of Participants',
    ],

    // Status
    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    // Actions
    'actions' => [
        'create' => 'Create Booking',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'complete' => 'Mark as Completed',
        'cancel' => 'Cancel',
        'view' => 'View Details',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'print_letter' => 'Print Letter',
    ],

    // Messages
    'messages' => [
        'created' => 'Booking created successfully',
        'updated' => 'Booking updated successfully',
        'deleted' => 'Booking deleted successfully',
        'approved' => 'Booking approved successfully',
        'rejected' => 'Booking rejected successfully',
        'completed' => 'Booking marked as completed',
        'cancelled' => 'Booking cancelled successfully',
        'not_found' => 'Booking not found',
        'time_conflict' => 'Time slot already booked',
    ],
];

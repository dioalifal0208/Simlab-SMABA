<?php

return [
    // Page Titles
    'title' => 'Lab Booking',
    'title_admin' => 'Manage Lab Booking',
    'title_user' => 'My Lab Booking History',
    'subtitle_admin' => 'View and process all lab usage schedule requests.',
    'subtitle_user' => 'Apply for a schedule and track your lab booking status.',
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
        'create_new' => 'Apply New Booking',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'complete' => 'Mark as Completed',
        'cancel' => 'Cancel',
        'view' => 'View Details',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'print_letter' => 'Print Letter',
    ],

    // Empty
    'empty' => [
        'title' => 'No Booking Data',
        'description' => 'No booking data matches your filter.',
        'action' => 'Apply New Booking',
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

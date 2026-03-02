<?php

return [
    // Welcome Section
    'welcome' => [
        'admin' => 'Welcome, Admin Lab SMABA!',
        'user' => 'Welcome, :name!',
        'subtitle' => 'Laboratory activity summary today.',
    ],

    // Metrics
    'metrics' => [
        'total_items' => 'TOTAL ITEMS',
        'total_users' => 'TOTAL USERS',
        'transactions_this_month' => 'TRANSACTIONS THIS MONTH',
        'items' => 'Items',
        'users' => 'Users',
        'transactions' => 'Transactions',
    ],

    // Quick Actions
    'quick_actions' => [
        'title' => 'Quick Actions',
        'add_item' => 'Add Item',
        'process_loan' => 'Process Loan',
        'view_reports' => 'View Reports',
    ],

    // Activity Cards
    'cards' => [
        'pending_loans' => 'Pending Loans',
        'needs_approval' => 'Needs approval',
        'pending_bookings' => 'Pending Bookings',
        'waiting_schedule' => 'Waiting schedule',
        'damage_reports' => 'Damage Reports',
        'needs_verification' => 'Needs verification',
        'this_week_schedule' => 'This Week\'s Schedule',
        'scheduled_practicum' => 'Scheduled practicum',
        'view_all' => 'View All',
        'no_data' => 'No data',
    ],

    // Recent Activity
    'recent_activity' => [
        'title' => 'Recent Activity',
        'description' => 'View all recent system activity, loans, bookings, and data changes.',
        'page' => 'Page',
        'of' => 'of',
        'no_activity' => 'No recent activity',
        'view_more' => 'View More',
        'previous' => 'Previous',
        'next' => 'Next',
    ],

    // Activity Types
    'activity' => [
        'loan_created' => 'requested equipment loan',
        'booking_created' => 'requested lab booking for ":purpose"',
        'item_added' => 'added a new item',
        'item_updated' => 'updated an item',
        'user_login' => 'logged into the system',
        'no_activity' => 'No recent activity yet.',
        'view_all' => 'View All Activity',
        'view' => 'View',
        'detail' => 'Detail',
        'system' => 'System',
        'actions' => [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login' => 'Login',
            'logout' => 'Logout',
            'failed_login' => 'Login Failed',
        ],
        'models' => [
            'Item' => 'Item',
            'Loan' => 'Loan',
            'Booking' => 'Booking',
            'User' => 'User',
            'Document' => 'Document',
            'DamageReport' => 'Damage Report',
            'PracticumModule' => 'Practicum Module',
            'Auth' => 'Authentication',
        ],
    ],
];

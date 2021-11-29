<?php

return [

    /*
     *--------------------------------------------------------------------------
     * MWC Dashboard Client
     *--------------------------------------------------------------------------
     *
     * The below information stores values related to the client side of the dashboard.
     * See https://github.com/gdcorp-partners/mwc-dashboard-client for more details
     */
    'client' => [
        'runtime' => [
            'url' => 'https://cdn4.mwc.secureserver.net/runtime.js',
        ],
        'vendors' => [
            'url' => 'https://cdn4.mwc.secureserver.net/vendors.js',
        ],
        'source' => [
            'url' => 'https://cdn4.mwc.secureserver.net/index.js',
        ],
        'menu' => [
            'url' => 'https://cdn4.mwc.secureserver.net/count.js',
        ],
    ],

    /*
     *--------------------------------------------------------------------------
     * MWC Dashboard Assets
     *--------------------------------------------------------------------------
     *
     * Locations for dashboard assets
     */
    'assets' => [
        'fonts' => [
            'url' => defined('MWC_DASHBOARD_PLUGIN_URL') ? MWC_DASHBOARD_PLUGIN_URL.'assets/css/dashboard-fonts.css' : '',
        ],
        'admin' => [
            'url' => defined('MWC_DASHBOARD_PLUGIN_URL') ? MWC_DASHBOARD_PLUGIN_URL.'assets/css/dashboard-admin.css' : '',
        ],
        'source' => [
            'url' => 'https://cdn.mwc.secureserver.net/index.js',
        ],
        'go_icon' => [
            'path' => defined('MWC_DASHBOARD_PLUGIN_DIR') ? MWC_DASHBOARD_PLUGIN_DIR.'assets/images/go-icon.svg' : '',
        ],
    ],
];

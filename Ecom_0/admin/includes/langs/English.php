<?php
function lang($key)
{
    static $translate = array(

        // 'key' => 'value'
        // navbar
        'HOME'             => "Home",
        'CATEGORIES'       => 'Categories',
        'ITEMS'            => 'Items',
        'MEMBERS'          => 'Members',
        'STATISTICS'       => 'Statistics',
        'LOGS'             => 'Logs',
        'PROFILE'          => 'Profile',
        'SETTINGS'         => 'Settings',
        'LOGOUT'           => 'Logout',

    );

    return $translate[$key];
}

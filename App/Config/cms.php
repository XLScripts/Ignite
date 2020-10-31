<?php namespace Ignition\Config;

/* -> CMS Configuration.
-- These settings are related to the Content-Management-System.
-- Edit these settings to suit your needs.
*/

return [
    /* -> The URI Slug to be used for the Backend Management Panel.
    ---- This will be used whenever accessing the admin panel as such:
    ---- http://localhost/backend/
    */ 
    'backend_route' => 'backend',

    // The theme to use for the website. This parameter is not stored in the database, and can be edited here.
    'theme' => 'default'
];
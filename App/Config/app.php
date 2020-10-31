<?php namespace Ignition\Config;

/* -> App Configuration.
-- Configure settings of the application.
-- These settings are mostly related to the Underlying Framework and not necesarily the CMS
*/

return [
    // Enable / Disable Debugging. Will print our errors, warnings and notices. Recommended to disable in Production.
    'debug' => true,

    // Application Base URL
    'base_url' => 'http://localhost/',

    // Information about the current application.
    'name'    => 'Ignition-CMS',
    'version' => '1.0.0'
];
<?php namespace Ignite\Config;

/* -> Security Configuration.
-- This configuration array controls various security related features of the CMS.
-- Default settings are recommended, and must be changed with caution.
*/

return [
    'auth' => [

        /* -> Control Backend Authentication Throttling.
            * Enable / Disable the throttling.
            * Specify a maximum number of attempts before suspending any incoming request.
            * Amount of minutes to suspend the user for.
        */
        'throttling' => [
            'enabled'        => true,  
            'maxAttempts'    => 5,     
            'suspensionTime' => 15,    
        ]
    ]
];
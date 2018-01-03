<?php

/**
 * Production configuration
 * Usage:
 * - Online website
 * - Production DB
 * - Standard production error pages (404, 500, etc.)
 */
return array(
    'yiiPath' => __DIR__ . '/../../../yii/yii.php',
    // Set YII_DEBUG and YII_TRACE_LEVEL flags
    'yiiDebug' => false,
    'yiiTraceLevel' => 0,
    // This is the specific Web application configuration for this mode.
    // Supplied config elements will be merged into the main config array.
    'configWeb' => array(
        'aliases' => array(
            'upload_dir' => '/volume2',
            'upload_tmp_dir' => '/volume2/_tmp',
            'consolesource' => '/uploads', //webroot not work in console app
            'consoletarget' => '/volume2'
        ),
        // Application components
        'components' => array(
            // Database
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=task_management',
                'username' => 'root',
                'password' => 'root',
                'enableParamLogging' => false,
                'enableProfiling' => false,
                'schemaCachingDuration' => 0,
            ),
            // Application Log
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    // Save log messages on file
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error,warning,info',
                        'logFile' => 'error.log',
                    ),
                    // Send errors via email to the system admin
                    array(
                        'class' => 'CEmailLogRoute',
                        'levels' => 'error, warning',
                        'emails' => array('cs.amateur@gmail.com'),
                        'sentFrom'=>'teamkenko@longdan.co.uk',
                        'categories' => '!exception.CHttpException.404'
                    ),
                ),
            ),
            'session' => array(
                'cookieParams' => array('domain' => 'teamkenko.longdan.com'),
            ),
            'user' => array(
                'identityCookie' => array(
                    'domain' => 'http://teamkenko.longdan.com',
                ),
            )
        ),
        'params' => array(
            'dropboxKey' => 'h8rou931jw2h6bn'
        ),
    ),
    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(
        // Application components
        'components' => array(
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=task_management',
                'username' => 'root',
                'password' => 'root!',
                'enableParamLogging' => false,
                'enableProfiling' => false,
                'schemaCachingDuration' => 0,
            ),
            // Application Log
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning, info', //trace
                        'maxFileSize' => 1000,
                        'logFile' => 'console.log'
                    ),
                    // Send errors via email to the system admin
                    array(
                        'class' => 'CEmailLogRoute',
                        'levels' => 'error',
                        'emails' => 'cs.amateur@gmail.com',
                        'categories' => '!exception.CHttpException.*'
                    ),
                )
            )
        ),
    ),
);

<?php

/**
 * Development configuration
 * Usage:
 * - Local website
 * - Local DB
 * - Show all details on each error
 * - Gii module enabled
 */
return array(
    // Set YII_DEBUG and YII_TRACE_LEVEL flags
    'yiiDebug' => true,
    'yiiTraceLevel' => 3,
    // This is the specific Web application configuration for this mode.
    // Supplied config elements will be merged into the main config array.
    'configWeb' => array(
        'aliases' => array(
            'upload_dir' => 'webroot.uploads',
            'upload_tmp_dir' => 'webroot.tmp',
            'consolesource' => realpath(__DIR__ . '/../../uploads'), //webroot not work in console app
            'consoletarget' => realpath('/volume1/newuploads')
        ),
        // Modules
        'modules' => array(
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => false,
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters' => array('127.0.0.1', '::1', 'localhost'),
                'generatorPaths' => array('bootstrap.gii'),
            ),
        ),
        // Application components
        'components' => array(
            // Asset manager
            'assetManager' => array(
                'forceCopy' => true,
                'linkAssets' => false, //publish symbolic links for easier developing
            ),
            // Database
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=task_management',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => 'root',
                'enableParamLogging' => true,
                'enableProfiling' => true,
                'schemaCachingDuration' => 0,
            ),
            // Application Log
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    // Save log messages on file
                    array(
                        'class' => 'CFileLogRoute',
                        'logFile' => 'app.log',
                        'levels' => 'error, warning,info',
                    ),
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error',
                        'logFile' => 'error.log',
                    ),
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning, info, trace',
                        'categories' => 'system.db.CDbCommand',
                        'logFile' => 'db.log',
                    ),
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'trace',
//                        'categories' => 'system.db.CDbCommand',
                        'logFile' => 'tracedb.log',
                    ),
                    // Show log messages on web pages
                    array(
                        'class' => 'CWebLogRoute',
//                        'categories'=>'application',
//                        'categories' => 'system.db.CDbCommand', //queries
                        'levels' => 'error, warning,info', //trace
//                        'showInFireBug' => true,
                    ),
                    array(
                        'class' => 'CProfileLogRoute',
                        'ignoreAjaxInFireBug' => false,
                        'showInFireBug' => false,
//                        'levels' => 'trace'
                    ),
                ),
            ),
            'bootstrap' => array(
                'forceCopyAssets' => false
            ),
            'session' => array(
                'cookieParams' => array('domain' => 'teamkenko.longdan.com'),
            ),
            'user' => array(
                'identityCookie' => array(
                    'domain' => 'teamkenko.longdan.com',
                ),
            ),
            'cache' => array(
                'keyPrefix' => 'absasadsad'
            )
        ),
        'params' => array(
            'dropboxKey' => 'h8rou931jw2h6bn',
            'domain' => 'http://teamkenko.longdan.com'
        ),
    ),
    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(
        'components' => array(
            'db' => 'inherit',
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
//                        'categories' => 'system.caching.*', //queries
                        'levels' => 'error, warning, info',
                        'logFile' => 'console.log',
                        'maxFileSize' => 1000
                    ),
                )
            )
        )
    ),
);

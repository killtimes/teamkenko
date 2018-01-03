    <?php

/**
 * Main configuration.
 * All properties can be overridden in mode_<mode>.php files
 */
return array(
    // Set yiiPath
    'yiiPath' => __DIR__ . '/../../../yii/yii.php',
    'yiicPath' => __DIR__ . '/../../../yii/yiic.php',
    'yiitPath' => __DIR__ . '/../../../yii/yiit.php',
    // Set YII_DEBUG and YII_TRACE_LEVEL flags
    'yiiDebug' => true,
    'yiiTraceLevel' => 0,
    // This is the main Web application configuration. Any writable
    // CWebApplication properties can be configured here.
    'configWeb' => array(
        'timeZone' => 'GMT',
        'basePath' => __DIR__ . '/..',
        'name' => 'Task Management',
        // Aliases
        'aliases' => array(
            'vendor' => __DIR__ . '/../vendor',
            'bootstrap' => realpath(__DIR__ . '/../extensions/yiistrap'),
            'yiiwheels' => realpath(__DIR__ . '/../extensions/yiiwheels'),
            'yiimaintenance' => realpath(__DIR__ . '/../extensions/yii-maintenance'),
            'jupload' => __DIR__ . '/../extensions/jquery-upload-v9112',
//            'xupload' => 'ext.xupload',
            'upload_dir' => 'webroot.uploads',
            'upload_tmp_dir' => 'webroot.tmp',
            'email' => __DIR__ . '/../vendor/cornernote/yii-email-module/email',
        ),
        'defaultController' => 'dashboard',
        // Preloading 'log' component
        'preload' => array('log', 'maintenance'),
        // Autoloading model and component classes
        'import' => array(
            'application.models.*',
            'application.components.*',
            'application.modules.user.models.*',
            'application.modules.user.components.*',
            'application.modules.rights.*',
            'application.modules.rights.components.*',
            'bootstrap.behaviors.*',
            'bootstrap.components.*',
            'bootstrap.form.*',
            'bootstrap.helpers.*',
            'bootstrap.widgets.*',
            'bootstrap.gii.bootstrap.*',
            'application.modules.user.UserModule',
        ),
        'modules' => array(
            'rights' => array(
                'systemUsers' => array('system'),
                'superuserName' => 'Admin', // Name of the role with super user privileges.
                'authenticatedName' => 'Authenticated', // Name of the authenticated user role.
                'userIdColumn' => 'id', // Name of the user id column in the database.
                'userNameColumn' => 'username', // Name of the user name column in the database.
                'enableBizRule' => false, // Whether to enable authorization item business rules.
                'enableBizRuleData' => false, // Whether to enable data for business rules.
                'displayDescription' => true, // Whether to use item description instead of name.
                'flashSuccessKey' => 'RightsSuccess', // Key to use for setting success flash messages.
                'flashErrorKey' => 'RightsError', // Key to use for setting error flash messages.
                'baseUrl' => '/rights', // Base URL for Rights. Change if module is nested.
                'layout' => 'webroot.themes.theme1.views.layouts.main', // Layout to use for displaying Rights.
                'appLayout' => 'webroot.themes.theme1.views.layouts.main', // Application layout.
//            'cssFile' => 'rights.css', // Style sheet file to use for Rights.
                'install' => false, // Whether to enable installer.
                'debug' => false,
            ),
            'user' => array(
                # encrypting method (php hash function)
                'hash' => 'md5',
                # send activation email
                'sendActivationMail' => false,
                # allow access for non-activated users
                'loginNotActiv' => false,
                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => false,
                # automatically login from registration
                'autoLogin' => true,
                # registration path
                'registrationUrl' => array('/user/registration'),
                # recovery password path
                'recoveryUrl' => array('/user/recovery'),
                # login form path
                'loginUrl' => array('/user/login'),
                # page after login
                'returnUrl' => array('/user/profile'),
                # page after logout
                'returnLogoutUrl' => array('/user/login'),
                'tableUsers' => 'User',
                'tableProfiles' => 'Profile',
                'tableProfileFields' => 'ProfilesField',
                'user_page_size' => 25
            ),
            'dashboard', 'supplier', 'shop', 'template', 'process','alert','report',
            'import' => array(
                'class' => 'ext.import.ImportModule',
                'onAfterImport' => array('ImportEvent', 'onAfterImport'),
                'onBeforeShowForm' => array('ImportEvent', 'onBeforeShowForm'),
            ),
            'email' => array(
                'class' => 'email.EmailModule',
                'connectionID' => 'db',
                'controllerFilters' => array(
                    'emailAccess' => array('email.components.EmailAccessFilter'),
                ),
                'adminUsers' => array('system'),
                'yiiStrapPath' => __DIR__ . '/../vendor/crisu83/yiistrap',
            ),
        ),
        // Application components
        'components' => array(
            // Asset manager
            'assetManager' => array(
                'forceCopy' => false,
                'linkAssets' => false, // publish symbolic links for easier developing
            ),
            'request' => array(
                'enableCsrfValidation' => true,
                'enableCookieValidation' => true
            ),
            'clientScript' => array(
                'class' => 'ClientScript',
                'packages' => array(
                    'jquery' => array(
                        'baseUrl' => 'themes/theme1/js/1_9_1',
                        'js' => array('jquery.min.js'),
                    )
                )
            ),
            'user' => array(
                'class' => 'RWebUser',
                // enable cookie-based authentication
                'allowAutoLogin' => true,
                'autoRenewCookie' => true,
                'identityCookie' => array(
                    'httpOnly' => true
                ),
                'loginUrl' => array('/user/login'),
                'behaviors' => array(
                    'ext.WebUserBehavior'
                ),
                'autoUpdateFlash' => false
            ),
            'authManager' => array(
                'class' => 'RDbAuthManager',
                'connectionID' => 'db',
                'itemTable' => 'AuthItem',
                'itemChildTable' => 'AuthItemChild',
                'assignmentTable' => 'AuthAssignment',
                'rightsTable' => 'Right',
            ),
            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'caseSensitive' => true,
                'rules' => array(
                    '<controller:\w+>/<id:\d+>' => '<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                    '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>'
                ),
            ),
            // Database
            'db' => array(
                'connectionString' => '', //override in config/mode_<mode>.php
                'username' => '', //override in config/mode_<mode>.php
                'password' => '', //override in config/mode_<mode>.php
                'charset' => 'utf8',
                'schemaCachingDuration' => 3600,
                'initSQLs' => array("set time_zone='+00:00';"),
            ),
            // Error handler
            'errorHandler' => array(
                // use 'site/error' action to display errors
                'errorAction' => '/dashboard/error',
            ),
            'bootstrap' => array(
                'class' => 'bootstrap.components.TbApi',
                'bootstrapPath' => 'webroot.themes.theme1.bootstrap.3_3_4',
                'forceCopyAssets' => true
//            'cdnUrl' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4',
            ),
            'yiiwheels' => array(
                'class' => 'yiiwheels.YiiWheels',
            ),
            'format' => array(
                'class' => 'yiiwheels.widgets.timeago.WhTimeAgoFormatter'
            ),
            'cache' => array(
                'class' => 'system.caching.CMemCache',
                'keyPrefix' => 'taskman',
                'hashKey' => true,
                'servers' => array(
                    array('host' => 'localhost', 'port' => 11211, 'weight' => 100),
                ),
                'useMemcached' => true
            ),
            'maintenance' => array(
                'class' => 'yiimaintenance.MaintenanceMode',
                'urls' => array('user/login', 'user/logout'),
                'message' => 'We are updating the application for you and will be back shortly.',
            ),
            'session' => array(
                'class' => 'application.components.DbHttpSession',
                'connectionID' => 'db',
                'sessionTableName' => 'Session',
                'userTableName' => 'User',
                'timeout' => 3600,
                'cookieParams' => array('httponly' => true),
            ),
            'localTime' => array(
                'class' => 'LocalTime',
                'localTimeZone' => 'Europe/London',
//                'localTimeZone' => 'Asia/Ho_Chi_Minh'
            ),
            'emailManager' => array(
                'class' => 'EmailManager',
                'fromEmail' => 'teamkenko@longdan.co.uk',
                'templateType' => 'php',
                'templatePath' => 'application.views.emails',
                'templateFields' => array('subject', 'heading', 'message'),
                'defaultLayout' => 'layout_default',
                'defaultTransport' => 'mail',
                'transports' => array(

                    // mail transport
                    'mail' => array(
                        // can be Swift_MailTransport or Swift_SmtpTransport
                        'class' => 'Swift_MailTransport',
                    ),

                    // smtp transport
                    'smtp' => array(
                        // if you use smtp you may need to define the host, port, security and setters
                        'class' => 'Swift_SmtpTransport',
                        'host' => 'localhost',
                        'port' => 25,
                        'security' => null,
                        'setters' => array(
                            'username' => 'your_username',
                            'password' => 'your_password',
                        ),
                    ),

                    // another smtp transport
                    'anotherSmtp' => array(
                        'class' => 'Swift_SmtpTransport',
                        'host' => 'localhost',
                        'port' => 25,
                        'security' => null,
                        'setters' => array(
                            'username' => 'another_username',
                            'password' => 'another_password',
                        ),
                    ),

                    // gmail smtp transport
                    'gmailSmtp' => array(
                        'class' => 'Swift_SmtpTransport',
                        'host' => 'smtp.gmail.com',
                        'port' => 465,
                        'security' => 'ssl',
                        'setters' => array(
                            'username' => 'anonjmous@gmail.com',
                            'password' => 'mobionmusic',
                        ),
                    ),
                ),
            ),
        ),
        // application-level parameters that can be accessed
        // using Yii::app()->params['paramName']
        'params' => array(
            // this is used in contact page
            'adminEmail' => 'teamkenko@longdan.co.uk',
            'dropboxKey' => 'h8rou931jw2h6bn',
            'domain' => 'http://longdan.teamkenko.com'
        ),
        'theme' => 'theme1'
    ),
    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(
        'basePath' => __DIR__ . '/..',
        'name' => 'Task Management',
        // Aliases
        'aliases' => 'inherit',
        // Preloading 'log' component
        'preload' => array('log', 'maintenance'),
        // Autoloading model and component classes
        'import' => 'inherit',
        'modules'=>'inherit',
        // Application componentshome
        'components' => array(
            'request' => array(
                'hostInfo' => 'https://teamkenko.longdan.com',
                'baseUrl' => '',
                'scriptUrl' => '',
            ),
            'localTime' => array(
                'class' => 'LocalTime',
                'localTimeZone' => 'Europe/London',
//                'localTimeZone' => 'Asia/Ho_Chi_Minh'
            ),
            'maintenance' => 'inherit',
            // Database
            'db' => 'inherit',
            // Application Log
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    // Save log messages on file
//                    array(
//                        'class' => 'CFileLogRoute',
//                        'levels' => 'error, warning, trace, info',
//                    ),
                ),
            ),
            'cache' => 'inherit',
            'user' => array(
                'class' => 'ConsoleUser',
                'primaryKey' => 1
            ),
            'emailManager'=>'inherit',
            'urlManager'=>'inherit'
        ),
        'commandMap' => array(
            'emailSpool' => 'email.commands.EmailSpoolCommand',
        ),
    ),
);

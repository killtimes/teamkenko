<?php 
// set environment
require_once(dirname(__FILE__) . '/../../extensions/yii-environment/Environment.php');
$env = new Environment('PRODUCTION'); //override mode

// set debug and trace level
defined('YII_DEBUG') or define('YII_DEBUG', $env->yiiDebug);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $env->yiiTraceLevel);

// run Yii app
require_once($env->yiiPath);
Yii::createConsoleApplication($env->configConsole)->run();

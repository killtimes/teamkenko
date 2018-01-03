<?php

// set environment
require_once(dirname(__FILE__) . '/extensions/yii-environment/Environment.php');
$env = new Environment('PRODUCTION'); //override mode
// set debug and trace level
defined('YII_DEBUG') or define('YII_DEBUG', $env->yiiDebug);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $env->yiiTraceLevel);

// change the following paths if necessary
$yiic = dirname(__FILE__) . '/../../yii/yiic.php';
$config = $env->configConsole;

require_once($yiic);

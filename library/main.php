<?php

/**
 * @package Quiz
 *
 * @author Selman Eser
 * @copyright 2014 Selman Eser
 * @license BSD 2-clause
 *
 * @version 1.0
 */

define('CORE', 1);

$start_time = microtime();

ob_start();

require $core['includes_dir'] . '/database.mysqli.php';
require $core['includes_dir'] . '/common.php';

$template = array();

clean_request();
db_initiate();

$modules = array('home', 'do', 'read', 'sort', 'break', 'print');

$core['current_module'] = 'home';
if (!empty($_REQUEST['module']) && in_array($_REQUEST['module'], $modules))
	$core['current_module'] = $_REQUEST['module'];

load_module($core['current_module']);

call_user_func($core['current_module'] . '_main');

ob_exit();
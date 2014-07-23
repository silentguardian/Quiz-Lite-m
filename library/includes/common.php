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

if (!defined('CORE'))
	exit();

function load_module($module)
{
	global $core;

	require_once $core['modules_dir'] . '/' . $module . '/' . $module . '.source.php';
	require_once $core['modules_dir'] . '/' . $module . '/' . $module . '.template.php';
}

function clean_request()
{
	unset($GLOBALS['HTTP_POST_VARS'], $GLOBALS['HTTP_POST_VARS']);
	unset($GLOBALS['HTTP_POST_FILES'], $GLOBALS['HTTP_POST_FILES']);

	if (isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS']))
		fatal_error('Invalid request!');

	foreach (array_merge(array_keys($_POST), array_keys($_GET), array_keys($_FILES)) as $key)
	{
		if (is_numeric($key))
			fatal_error('Invalid request!');
	}

	foreach ($_COOKIE as $key => $value)
	{
		if (is_numeric($key))
			unset($_COOKIE[$key]);
	}

	foreach (array('module', 'action') as $index)
	{
		if (isset($_GET[$index]))
			$_GET[$index] = (string) $_GET[$index];
	}

	$_REQUEST = $_POST + $_GET;
}

function build_url($parts = array(), $quick = true)
{
	global $core;

	$url = $core['site_url'];

	if (!is_array($parts))
		$parts = array($parts);
	if (empty($parts) || $parts == array('home'))
		return $url;

	if ($core['clean_url'] === true)
		$url .= implode('/', $parts);
	else
	{
		if ($quick)
		{
			foreach ($parts as $level => $part)
			{
				if ($level == 0)
					$url .= '?module=' . $part;
				elseif ($level == 1)
					$url .= '&amp;action=' . $part;
				elseif ($level == 2)
					$url .= '&amp;' . $parts[0] . '=' . $part;
				elseif ($level == 3)
					$url .= '&amp;' . $parts[1] . '=' . $part;
			}
		}
		else
		{
			$temp_parts = array();

			foreach ($parts as $key => $value)
				$temp_parts[] = $key . '=' . $value;

			$url .= '?' . implode('&amp;', $temp_parts);
		}
	}

	return $url;
}

function template_header()
{
	global $core, $template;

	echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>', $template['page_title'], '</title>
	<link href="', $core['site_url'], 'interface/css/bootstrap.min.css" rel="stylesheet">
	<link href="', $core['site_url'], 'interface/css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="', $core['site_url'], 'interface/css/style.css" rel="stylesheet">
</head>
<body>
	<div class="container">';
}

function template_footer()
{
	global $core;

	echo '
	</div>
	<script src="', $core['site_url'], 'interface/js/jquery.min.js"></script>
	<script src="', $core['site_url'], 'interface/js/bootstrap.min.js"></script>
</body>
</html>';
}

function redirect($location)
{
	header('Location: ' . str_replace(array(' ', '&amp;'), array('%20', '&'), $location));

	ob_end_flush();

	exit();
}

function fatal_error($error)
{
	global $core, $template;

	$template['error'] = $error;
	$core['current_module'] = 'error';

	load_module('error');

	call_user_func('error_main');

	ob_exit();
}

function ob_exit()
{
	global $core, $template;

	if (empty($template['page_title']))
		$template['page_title'] = $core['title_long'];
	else
		$template['page_title'] .= ' - ' . $core['title_long'];

	template_header();

	call_user_func('template_' . $core['current_template']);

	template_footer();

	ob_end_flush();

	db_exit();

	exit();
}
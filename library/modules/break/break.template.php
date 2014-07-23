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

function template_break_main()
{
	echo '
	<div class="jumbotron alert-warning" onclick="do_next_question();">
		<p class="text-center">Time for a short break!</p>
	</div>
	<script language="Javascript" type="text/javascript"><!-- // --><![CDATA[
		function do_next_question()
		{
			document.location.href = "', build_url('do'), '";
		}
	// ]]></script>';
}
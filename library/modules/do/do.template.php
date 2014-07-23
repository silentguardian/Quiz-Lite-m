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

function template_do_main()
{
	global $template;

	echo '
	<div class="alert alert-info">
		<div class="pull-right">
			<h3>
				<span class="label label-primary">', $template['question']['s'], '</span>
				<span class="label label-', ($template['question']['p'] > 6 ? 'danger' : ($template['question']['p'] > 4 ? 'warning' : 'success')), '">', $template['question']['p'], ' points</span>
			</h3>
		</div>
		<h3 onclick="do_break();">Question ', $template['current_question'], ' of ', $template['total_questions'], '</h3>
	</div>
	<div class="jumbotron">
		<p><strong>', $template['question']['q'], '</strong></p>
		<p class="options">';

	foreach (array('a', 'b', 'c', 'd') as $o)
	{
		echo '
			<span class="label label-default">', strtoupper($o), '</span> <span', ($template['question']['t'] == $o ? ' id="do_correct"' : ''),'>', $template['question'][$o], '</span><br />';
	}

	echo '
		</p>
	</div>
	<div class="progress">
		<div id="progress_bar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
			<span class="label label-warning" onclick="do_start_timer();">Start Timer</span>
		</div>
	</div>
	<script language="Javascript" type="text/javascript"><!-- // --><![CDATA[
		var time_left = 10, time_interval;
		function do_start_timer()
		{
			document.getElementById("progress_bar").innerHTML = "10 seconds";
			time_interval = setInterval("do_timer()", 1000);
		}
		function do_timer()
		{
			time_left = time_left - 1;

			if (time_left < 0)
			{
				clearInterval(time_interval);

				document.getElementById("progress_bar").innerHTML = "<span class=\"label label-success\" onclick=\"do_show_answer();\">Show Answer</span><span style=\"margin: 0 3em;\">Time is up!</span><span class=\"label label-primary\" onclick=\"do_next_question();\">Next Question</span>";
				document.getElementById("progress_bar").style.width = "100%";
			}
			else
			{
				document.getElementById("progress_bar").innerHTML = time_left + " seconds";
				document.getElementById("progress_bar").style.width = (time_left * 10) + "%";

				if (time_left < 4)
					document.getElementById("progress_bar").className = "progress-bar progress-bar-danger";
				else if (time_left < 7)
					document.getElementById("progress_bar").className = "progress-bar progress-bar-warning";
			}
		}
		function do_show_answer()
		{
			document.getElementById("do_correct").className = "label label-success";
		}
		function do_next_question()
		{
			document.location.href = "', build_url('do'), '";
		}
		function do_break()
		{
			document.location.href = "', build_url('break'), '";
		}
	// ]]></script>';
}

function template_do_done()
{
	echo '
	<div class="jumbotron alert-info">
		<p class="text-center">This is the end of the quiz!</p>
	</div>';
}
<?php
/**
 * get data from DB *only*
 * @return: load html template
 * date fix: Aug-08-2017
 */

require('config.php');

$settings['data_source'] = 'DB';
$site = new Site($settings);
// load config settings
$data['settings'] = $site->get_settings();

if ($site->check_request() === FALSE)
	$site->load('odds-invalid-request.tpl.php', $data);
else
{
	// declarations
	$data = array();
	$data['environment'] = $site->environment;
	$data['valid_request'] = TRUE;
	$data['notice']['invalid_request'] = "";	
	$data['sport'] = "sport=" . $_GET['sport'];
	$data['result'] = "";
	$data['date_time'] = date('M d, Y h:i:s A');

	// load our template
	$site->load('odds-live.tpl.php', $data);
	//$site->load('odds-not-available.tpl.php', $data);
}
?>

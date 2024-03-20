<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


// echo "hello";
// exit;

ini_set('max_execution_time', 120);
require('config.php');

$site = new Site($settings);

// don't touch. this is for manual inserting of sports in DB
//$site->save_all_sports();
//die('for sports reset');

// declarations
$data = array();
$data['valid_request'] = TRUE;
$data['notice']['error'] = "";
$data['sport'] = "";
$data['result'] = "";

// load config settings
$data['settings'] = $site->get_settings();


// check URL request
if ($site->check_request() === FALSE)
	$data['notice']['error'] = "Invalid URL Request: " . $site->get_url();
else
{
	switch ($site->data_source)
	{
		case "API":

			$max_time = 55; // 55 seconds
			$main_timer = 0;

			for ($i=0; $i<=100; $i++)
			{				
				$time_request = 8; // seconds interval per process
				$time_start = microtime(true);

					// call API
					$API = new MobileAPI($settings);
					$url = $API->restURL . $API->suffix;


					$the_result = $API->get_curl_result($url);

					// echo "<pre>";
					// print_r($the_result);
					// echo "</pre>";
					// exit;

						if ($API->get_curl_result($url) === FALSE) 
						{
							$data['notice']['error'] = "cURL Error: connection failed";
							break;
						}

					// convert API json data to array
					$sorted_live_data = $API->sortLiveData();

					if (!is_array($sorted_live_data))
					{
						echo date('M j, Y H:i:s') . " -> sorted live data error: ";
						var_dump($sorted_live_data);
						exit;
					}

					$all_sports_config = $site->all_sports_config();

						if ($sorted_live_data === FALSE)
						{
							$data['notice']['error'] = "cURL Error: data sorting failed";
							break;
						}

						if ($all_sports_config === FALSE || is_array($all_sports_config) === FALSE)
						{
							$data['notice']['error'] = "DB Error: getting sport failed";
							break;
						}

					$result = array_merge($all_sports_config, $sorted_live_data);
					
	
					if (!is_array($result)) {
						echo "<pre>";
						echo "result is not an array. ";
						echo "</pre>";
					}
					

					$site->update_live_data($result);

						// if ($site->update_live_data($result) === FALSE)
						// {
						// 	$data['notice']['error'] = "DB Error: updating records failed";
						// }

				// complete 55 seconds interval per API request
				$time_end = microtime(true);
				$running_time = $time_end - $time_start;
				$time_left = $time_request - $running_time;
				if ($time_left > 0)
				sleep($time_left);

				// for execution time
				$running_time = $time_left + $running_time;
				$main_timer += $running_time;

				//---
				/*
				$server_name = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : "api.mswodds.com/live/v1/index.php";
				$subject 	= "Subject: Cron LIVE Monitor - " . $server_name;	
				$headers   	= array();
				$headers[] 	= "MIME-Version: 1.0";
				$headers[] 	= "Content-type: text/plain; charset=iso-8859-1";
				$headers[] 	= "From: MSW Debug Mailer";
				$headers[] 	= $subject;
				mail("christian.realubit@megasportsworld.com", $subject, "processing... running time: " . $running_time . " exec time: " . $main_timer . date("d-m-Y: h:i:s") . "<br />", implode("\r\n", $headers));
				*/

				// exit script after 55 seconds	
				if ($main_timer > $max_time) break;
			
			} // ..end for

			break;


		case "DB":

			$result = $site->fetch_live_data();
			if ($result === FALSE)
				$data['notice']['error'] = "DB Error: fetching records failed";
			break;
	}
}


//debug_show($result);


// check for errors
if (!empty($data['notice']['error']))
{
	//echo '<pre>';
	//print_r($data);
	//echo '</pre>';
	
	$message = datetime_now() . " - " . $data['notice']['error'] . "\n";
	log_message($settings['log_file'], $message);
}
else
{
	if ($site->request_value == "all")
	{
		//debug_show($result);
		return $result;
	}
	else
	{
		//debug_show($result);
		//$show_sport = $site->json_sports_config();
		//echo '<pre>';
		//print_r($show_sport);
		//print_r($site->request_value);
		//echo '</pre>';
		//exit;

		$requested_sport = str_replace(' ', '', $site->request_value);
		//if (isset($show_sport[$site->request_value]['code']))
		if (isset($show_sport[$requested_sport]['code']))
		{
			//debug_show($result[$show_sport[$site->request_value]['code']]);
		}
		else
		{
			//echo " No result has found xxx: " . $site->request_value;
		}

	}
}




//load our template
//$site->load('odds.tpl.php', $data);
?>
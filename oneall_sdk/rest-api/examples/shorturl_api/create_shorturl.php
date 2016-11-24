<?php
/**
 * Copyright 2012 OneAll, LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 */

//HTTP Handler and Config
include '../../_include/init.php';

// Shorturl API \ Create a new Shorturl
// http://docs.oneall.com/api/resources/shorturls/create-shorturl/

//The URL to shorten
$url = 'http://www.oneall.com';

//Request Structure
$message_structure = array (
	'request' => array (
		'shorturl' => array (
			'original_url' => $url
		)
	)
);

//Encode structure
$message_structure_json = json_encode ($message_structure);

//Make Request
if ($oneall_curly->post (SITE_DOMAIN . "/shorturls.json", $message_structure_json))
{
	$result = $oneall_curly->get_result ();
	print_r (json_decode ($result->body));
}
//Error
else
{
	$result = $oneall_curly->get_result ();
	echo "Error: " . $result->http_info . "\n";
}

?>
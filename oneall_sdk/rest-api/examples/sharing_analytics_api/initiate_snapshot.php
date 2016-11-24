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

// Sharing Analytics API \ Initiate a new snapshot for shared content
// http://docs.oneall.com/api/resources/sharing-analytics/initiate-snapshot/

//The message to request a snapshot for
$sharing_message_token = 'a33ee832-4cb4-4fea-a6ca-9e93a8271380';

//Structure
$request_structure = array (
	'request' => array (
		'analytics' => array (
			'sharing' => array (
				'sharing_message_token' => $sharing_message_token
			)
		)
	)
);

//Encode structure
$request_structure_json = json_encode ($request_structure);

//Make Request
if ($oneall_curly->put (SITE_DOMAIN . "/sharing/analytics/snapshots.json", $request_structure_json))
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
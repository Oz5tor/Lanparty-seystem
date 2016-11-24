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

// User API \ Publish content to a user's social network account
// http://docs.oneall.com/api/resources/users/write-to-users-wall/

//Publish message for this user_token
$user_token = 'a96318d1-16a4-4461-8e7e-977d78665194';

//Publish message to this networks
$providers = array ('twitter', 'facebook');

//Message Structure
$message_structure = array (
	'request' => array (
		'message' => array (
			'parts' => array (
				'text' => array (
					'body' => 'http://www.oneall.com oneall simplifies the integration of social networks for Web 2.0 and SaaS companies http://www.oneall.com'
				),
				'picture' => array (
					'url' => 'http://oneallcdn.com/img/heading/slides/provider_grid.png'
				),
				'link' => array (
					'url' => 'http://www.oneall.com/',
					'name' => 'oneall.com',
					'caption' => 'Social Media Integration',
					'description' => 'Easily integrate social services like Facebook, Twitter, LinkedIn and Foursquare with your already-existing website.'
				)
			),
			'providers' => $providers
		)
	)
);

//Encode structure
$message_structure_json = json_encode ($message_structure);

//Make Request
if ($oneall_curly->post (SITE_DOMAIN . "/users/".$user_token."/publish.json", $message_structure_json))
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
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
require '_include/init.php';
require '_include/pretty_json.php';

//Check if we have received a connection_token
if ( ! empty ($_POST['connection_token']))
{
	//Get connection_token
	$connection_token = $_POST['connection_token'];

	//Make Request
	if ($oneall_curly->get (SITE_DOMAIN . "/connections/".$connection_token.".json"))
	{
		$result = $oneall_curly->get_result ();

		$json = $result->body;
		$json_decoded = json_decode($result->body);

		//The identity <identity_token> has been linked to the user <user_token>
		$data = $json_decoded->response->result->data;
		$user_token = $data->user->user_token;
		$identity_token = $data->user->identity->identity_token;

		?>
			<!DOCTYPE html>
				<html>
					<head>
						<meta charset="utf-8" />
						<title>Social Login Result</title>
					</head>
					<body>
						<h1>User</h1>
						<p>user_token: <strong><?php echo $user_token; ?></strong></p>
						<p>identity_token: <strong><?php echo $identity_token; ?></strong></p>
						<p><a href="display.php">Back</a></p>

						<h1>Raw Data</h1>
						<textarea rows="30" cols="200"><?php echo htmlspecialchars(pretty_json($result->body)); ?></textarea>
					</body>
				</html>
		<?php
	}
	//Error
	else
	{
		$result = $oneall_curly->get_result ();
		echo "Error: " . $result->http_info . "\n";
	}
}

?>
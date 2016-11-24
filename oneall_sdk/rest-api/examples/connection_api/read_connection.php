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

// Connection API \ Read connection details
// http://docs.oneall.com/api/resources/connections/read-connection-details/

//Get the details of this connection
$connection_token = 'e7c6693f-e6a1-426f-9580-1c0e5bf7d507';

//Make Request
if ($oneall_curly->get (SITE_DOMAIN . "/connections/".$connection_token.".json"))
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
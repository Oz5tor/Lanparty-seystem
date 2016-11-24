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

// User API \ Read a user's details
// http://docs.oneall.com/api/resources/users/read-user-details/

//Get the details of this userphp r
$user_token = '05895477-fa9c-4149-a8e4-309fd08a3e10';

//Make Request
if ($oneall_curly->get (SITE_DOMAIN . "/users/" . $user_token . ".json"))
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
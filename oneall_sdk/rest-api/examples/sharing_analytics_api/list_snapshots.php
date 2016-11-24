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

// Sharing Analytics API \ List all snapshots
// http://docs.oneall.com/api/resources/sharing-analytics/list-all-snapshots/

//The page to retrieve
$page = 1;

//Newest first
$order_direction = 'desc';

//Make Request
if ($oneall_curly->get (SITE_DOMAIN . "/sharing/analytics/snapshots.json?page=".$page."&order_direction=".$order_direction))
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
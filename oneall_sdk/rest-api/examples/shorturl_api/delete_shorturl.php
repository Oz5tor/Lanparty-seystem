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

// Shorturl API \ Delete a shorturl
// http://docs.oneall.com/api/resources/shorturls/delete-shorturl/

//The shorturl to delete
$shorturl_token = 'G0jbRphp';

if ($oneall_curly->delete (SITE_DOMAIN . "/shorturls/" . $shorturl_token . ".json?confirm_deletion=true"))
{
	$result = $oneall_curly->get_result ();
	print_r (json_decode ($result->body));
}
else
{
	$result = $oneall_curly->get_result ();
	echo "Error: " . $result->http_info . "\n";
}

?>
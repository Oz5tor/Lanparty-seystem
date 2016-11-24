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

// Social Sharing API \ Delete a message
// http://docs.oneall.com/api/resources/social-sharing/delete-message/

//The message to delete
$sharing_message_token = '029ad29b-2477-4718-8b68-b57f3211787a';

if ($oneall_curly->delete (SITE_DOMAIN .  "/sharing/messages/" . $sharing_message_token . ".json?confirm_deletion=true"))
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
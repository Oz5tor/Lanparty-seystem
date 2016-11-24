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

//This page displays examples for different Social Login configurations

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-EN">
		<head>
			<title>Social Login Example</title>
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			<meta name="robots" content="noindex, noarchive, follow" />
			<script type="text/javascript">
				var oajsp = (("https:" == document.location.protocol) ? "https" : "http");
				var oajsf = oajsp + '://<?php echo SITE_SUBDOMAIN; ?>.api.oneall.com/socialize/library.js?library=3';
				document.write(unescape('%3Cscript type="text/javascript" src="' + oajsf + '" %3E%3C/script%3E'));
			</script>
		</head>
	<body >

<script type="text/javascript">

	<!-- Compute the Callback used in the examples /-->
	var pathname = window.location.pathname;
	var dir = pathname.substring(0, pathname.lastIndexOf('/'));
	var path = window.location.protocol + '//' + window.location.host + dir + '/';
	var callback_uri = path + 'callback_handler.php';

</script>


<!-- -------------------------------------------------------------------------------------------------------- //-->

<h1>Modal Native</h1>
<div id="social_login_container_1" style="margin-bottom:40px">Show Modal</div>
<script type="text/javascript">
	oneall.api.plugins.social_login.build("social_login_container_1", {
		'providers' :  ['facebook', 'google', 'linkedin', 'twitter', 'yahoo', 'openid'],
  	'callback_uri': callback_uri,
  	'grid_size_x': 2,
  	'grid_size_y': 2,
  	'modal':true
 	});
</script>

<!-- -------------------------------------------------------------------------------------------------------- //-->

<h1>Inline Modal OnClick</h1>
<div id="modal_window" style="display:none">
	<div id="social_login_container_2"></div>
</div>
<div>
	<a href="#" onclick="show_modal();" id="modal_window_trigger">Show Social Login</a>
</div>

<script type="text/javascript">
 function show_modal()
 {
	 document.getElementById('modal_window').style.display='block';
	 document.getElementById('modal_window_trigger').style.display='none';

	 oneall.api.plugins.social_login.build("social_login_container_2", {
		  'providers' :  ['foursquare', 'facebook', 'google', 'linkedin', 'twitter', 'yahoo', 'openid', 'livejournal'],
		  'callback_uri': callback_uri,
		  'grid_size_x': 1,
		  'grid_size_y': 1
		 });
 }
 </script>

<!-- -------------------------------------------------------------------------------------------------------- //-->

<h1>Grid 3x3, Force Reauth</h1>
<div id="social_login_container_3"></div>
<script type="text/javascript">
oneall.api.plugins.social_login.build("social_login_container_3", {
  'providers' :  ['foursquare', 'windowslive','facebook', 'twitter','google','linkedin', 'yahoo', 'openid', 'wordpress', 'hyves', 'paypal', 'livejournal', 'vkontakte', 'steam'],
  'callback_uri': callback_uri,
  'grid_size_x': 3,
  'grid_size_y': 3,
  'css_theme_uri' : 'http://oneallcdn.com/css/api/socialize/themes/wordpress/small.css',
  'force_reauth':true
 });
</script>

<!-- -------------------------------------------------------------------------------------------------------- //-->

<h1>Same Window</h1>
<div id="social_login_container_4"></div>
<script type="text/javascript">
 oneall.api.plugins.social_login.build("social_login_container_4", {
	  'providers' :  ['foursquare', 'github', 'skyrock', 'windowslive','facebook', 'google', 'linkedin', 'twitter', 'yahoo', 'openid', 'livejournal'],
	  'callback_uri': callback_uri,
	  'same_window': true
	 });
</script>

<!-- -------------------------------------------------------------------------------------------------------- //-->

<h1>Grid Vertical</h1>
<div id="social_login_container_5"></div>
<script type="text/javascript">
 oneall.api.plugins.social_login.build("social_login_container_5", {
	  'providers' :  ['foursquare', 'mailru','windowslive','steam', 'stackexchange','facebook', 'google', 'linkedin', 'twitter', 'yahoo', 'openid', 'livejournal', 'wordpress'],
	  'callback_uri': callback_uri,
	  'grid_size_x': 1
	 });
</script>

<!-- -------------------------------------------------------------------------------------------------------- //-->

<h1>Grid Pagination Horizontal 1x1</h1>
<div id="social_login_container_6"></div>
<script type="text/javascript">
 oneall.api.plugins.social_login.build("social_login_container_6", {
	  'providers' :  ['foursquare', 'windowslive','facebook', 'google', 'linkedin', 'twitter', 'yahoo', 'openid', 'livejournal'],
	  'callback_uri': callback_uri,
	  'grid_size_x': 1,
	  'grid_size_y': 1
	 });
 </script>

<!-- -------------------------------------------------------------------------------------------------------- //-->

<h1>Grid Pagination Horizontal 3x1</h1>
<div id="social_login_container_7"></div>
<script type="text/javascript">
 oneall.api.plugins.social_login.build("social_login_container_7", {
	  'providers' :  ['foursquare', 'windowslive','blogger', 'paypal','vkontakte','hyves', 'facebook', 'google', 'linkedin', 'twitter', 'yahoo', 'openid', 'livejournal', 'wordpress'],
	  'callback_uri': callback_uri,
	  'grid_size_x': 3,
	  'grid_size_y': 1
	 });
 </script>

 <!-- -------------------------------------------------------------------------------------------------------- //-->

</body>
</html>
<?php
// ======================================================================
// adminnistration // hvilken admin side er man på
if(isset($_GET['administration']))
{
	$admin = mysqli_real_escape_string($db_conn,strip_tags($_GET['administration']));
    $html_headder_title = $admin;
}
else {
    $admin = '';
}
// ======================================================================
// page // hvilken side er det man står på
if(isset($_GET['page']))
{
	$page = mysqli_real_escape_string($db_conn,strip_tags($_GET['page']));
    $html_headder_title = $page;
}
else if($admin == '')
{
	$page = 'Forside';
    $html_headder_title = $page;
}
// ======================================================================
// subpage // hvilken subside er det man står på eller om man ikke er på en.
if(isset($_GET['subpage']))
{
	$subpage = mysqli_real_escape_string($db_conn,strip_tags($_GET['subpage']));
    $html_headder_title = $page.' -> '.$subpage;
}
else
{
	$subpage = '';
}
// ======================================================================
// action // hvad skal der ske
if(isset($_GET['action']))
{
	$action = mysqli_real_escape_string($db_conn,strip_tags($_GET['action']));
}
?>

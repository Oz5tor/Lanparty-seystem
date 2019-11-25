<?php
if($action != ''){
  if(isset($_GET['id']) && $_GET['id'] != ''){
    $URLPageID = $db_conn->real_escape_string($_GET['id']);
  }
  switch($action){
    case 'New':
      $newOrEdit = true;
      break;
    case 'Edit':
      $newOrEdit = true;
      break;
  }
} // $action end

if (isset($newOrEdit) && $newOrEdit != '') {
  include_once("Include/Admin/Competitions/CreateOrEditTournament.php");
} else {
?>
<a href="?page=Admin&subpage=Competitions&action=New#admin_menu" alt="Ny Konkurrence" type="button" class="text-center btn btn-info">Opret ny konkurrence</a>
<hr>
<?php 
}
?>


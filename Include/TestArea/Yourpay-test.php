<?php 
$action = "";
$action = $_GET["action"];

switch ($action) {
    case 'Godkendt':
        # code...
        echo "Betaling Gemmenført";
        break;
    case 'Annuleret':
        # code...
        echo "Betaling Annuleret";
        break;
    
    default:
        # code...
        echo "What wen wrong ?!?!?";
        break;
}


?>
<?php

## Do stuff with Submit
if (isset($_POST["a_rights"])) {
    
    $arr_rights = $_POST["Rights"];

    $db_conn->query("DELETE FROM access_asign");

    foreach ($arr_rights as $key => $value) {
        $right_split = explode('-', $value);
        $accessID   = $right_split[0]; // = access ID
        $UserID    = $right_split[1]; // = user ID

        $db_conn->query("INSERT INTO access_asign (FK_UserID, FK_AccessID) VALUES ('$UserID','$accessID') ");
        #echo '<pre>';
        #print_r($right_split);
        #echo '</pre>';
    }

}



# Check for Module Rights
if (!isset($_SESSION["AccessControll"]) && $_SESSION["AccessControll"] != 1 ) {
    $_SESSION['MsgForUser'] = "du har ikke adgang til modulet GLHF :P";
    header("Location: index.php?page=Admin");
  }

  $admins_result = $db_conn->query("SELECT * From Users Where Admin = 1");
?>

<form method="post" action="index.php?page=Admin&subpage=accesse#admin_menu">
<table class="table table-striped table-condensed table-hover LanCMSadminmenu">
    <?php
    while ($row = $admins_result->fetch_assoc()) {
        ?>
        <tr>
        <td><?php echo $row['Username']; ?></td><br/>
        <?php
            $tempuserid = $row['UserID'];
            $access_result = $db_conn->query("SELECT * from accesse");
            while ($row_access = $access_result->fetch_assoc()) {
                
                $moduleID = $row_access['ID'];
                #echo $tempuserid;
                $have_access = $db_conn->query("SELECT * FROM access_asign Where FK_AccessID = '$moduleID' AND FK_UserID = '$tempuserid' limit 1");
                #echo "<pre>";
                #print_r($have_access->num_rows);
                #echo "</pre>";
                if ($have_access->num_rows) {
                    $have = 1;
                }else{$have = 0;}

                
                echo "<td>";
                echo $row_access['Module'].': ';
                ?>
                <input id='<?php echo $row_access['ID']; ?>' value="<?php echo $row_access['ID'].'-'.$tempuserid; ?>" name="Rights[]" type='checkbox' <?php if ($have == 1){echo ' checked';} ?>>

                <?php
                echo "</td>";
            }
        
        ?>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td><input type="submit" name="a_rights" id="a_rights"></td>
    </tr>
</table>
</form>
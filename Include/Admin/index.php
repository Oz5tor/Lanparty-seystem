<!-- Last edit: Rosenheim - 03-01-2017: 10:00 -->
<?php if($_SESSION['Admin'] != 1) { header("Location: index.php"); } /* Fuck off... */ ?>
<div class="row">
  <div class="col-lg-12 col-sm-12 hlpf_newsborder hlpf_adminmenu">
    <ul class="list-group">
      <?php
        $result = $db_conn->query( "SELECT
          (SELECT COUNT(*) FROM Users) as Users, 
          (SELECT COUNT(*) FROM News) as News,
          (SELECT COUNT(*) FROM Pages) as Pages
        ");
        if( $result -> num_rows ) {
            $row = $result->fetch_assoc();
        }
      ?>
      <a href=""><li class="list-group-item col-lg-2">
        <span class="badge"><?php echo $row['Users']?></span>
          Brugere
      </li></a>
      <a href=""><li class="list-group-item col-lg-2">
        <span class="badge"><?php echo $row['News']?></span>
          Nyheder
      </li></a>
      <a href=""><li class="list-group-item col-lg-2">
        <span class="badge"><?php echo $row['Pages']?></span>
          Sider
      </li></a>
    </ul>
  </div>
  <div class="col-lg-12 col-sm-12 hlpf_newsborder">
  <div id="admin_panel" class="table-responsive">
      <!-- Load pages here... -->
      <?php include("Include/Admin/Users.php"); ?>
  </div>
  </div>
</div>

<!-- Last edit: Rosenheim - 03-01-2017: 10:00 -->
<?php if($_SESSION['Admin'] != 1) { header("Location: index.php"); } /* Fuck off... */ ?>
<div class="row">
  <div class="col-lg-12 col-sm-12 hlpf_newsborder hlpf_adminmenu">
    <ul class="list-group">
      <?php
        $result = $db_conn->query( "SELECT
          (SELECT COUNT(*) FROM Users) as Users, 
          (SELECT COUNT(*) FROM News) as News,
          (SELECT COUNT(*) FROM Pages) as Pages,
          (SELECT COUNT(*) FROM NewsLetter) as NewsLetter,
          (SELECT COUNT(*) FROM Event) as Event,
          (SELECT COUNT(*) FROM Sponsors) as Sponsors,
          (SELECT COUNT(*) FROM Competitions) as Competitions
        ");
        if( $result -> num_rows ) {
            $row = $result->fetch_assoc();
        }
      ?>
      <a href="?page=Admin&subpage=Users"><li class="<?php if($subpage == "Users") echo "active "; ?>list-group-item col-lg-2">
        <span class="badge"><?php echo $row['Users']?></span>
          Brugere
      </li></a>
      <a href="?page=Admin&subpage=News"><li class="<?php if($subpage == "News") echo "active "; ?>list-group-item col-lg-2">
        <span class="badge"><?php echo $row['News']?></span>
          Nyheder
      </li></a>
      <a href="?page=Admin&subpage=Pages"><li class="<?php if($subpage == "Pages") echo "active "; ?>list-group-item col-lg-2">
        <span class="badge"><?php echo $row['Pages']?></span>
          Sider
      </li></a>
      <a href="?page=Admin&subpage=NewsLetters"><li class="<?php if($subpage == "NewsLetters") echo "active "; ?>list-group-item col-lg-2">
        <span class="badge"><?php echo $row['NewsLetter']?></span>
          Nyheds Breve
      </li></a>
      <a href="?page=Admin&subpage=Events"><li class="<?php if($subpage == "Events") echo "active "; ?>list-group-item col-lg-2">
        <span class="badge"><?php echo $row['Event']?></span>
          Arrangemnter
      </li></a>
      <a href="?page=Admin&subpage=Sponsors"><li class="<?php if($subpage == "Sponsors") echo "active "; ?>list-group-item col-lg-2">
        <span class="badge"><?php echo $row['Sponsors']?></span>
          Sponsore
      </li></a>
      <a href="?page=Admin&subpage=Competitions"><li class="<?php if($subpage == "Competitions") echo "active "; ?>list-group-item col-lg-2">
        <span class="badge"><?php echo $row['Competitions']?></span>
          Konkurrencer
      </li></a>
    </ul>
  </div>
  <div class="col-lg-12 col-sm-12 hlpf_newsborder">
  <div id="admin_panel" class="table-responsive">
    <!-- Load pages here... -->
    <?php
    if($subpage !=''){
      include("Include/Admin/" . $subpage . ".php");
    }else{
      echo "<h3 class='text-center'>Velkommen til administrationen Sir.Admin</h3>";
    }     
    ?>
  </div>
  </div>
</div>

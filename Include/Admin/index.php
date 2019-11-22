<?php if($_SESSION['Admin'] != 1) { header("Location: /Website-2017/index.php"); } /* Fuck off... */
require_once("class/FileUpload.php");
require_once("class/GetUsernameFromID.php");
?>
<?php if (!empty($_SESSION['SQLStatus'])) { ?>
  <div class="row">
    <div class="LanCMScontentbox col-lg-12 col-sm-12">
      <h4>SQL Message</h4>
      <pre><?= $_SESSION['SQLStatus']; ?></pre>
    </div>
  </div>
<?php
  unset($_SESSION['SQLStatus']);
}
?>
<div class="row">
  <div id="admin_menu" class="col-lg-12 col-sm-12 LanCMScontentbox LanCMSadminmenu">
    <ul class="list-group">
      <?php
      $adminTabs= [
        // File-name  =>  Writen ON the site.
        // For links  =>  What the users see.
        // key        =>  Value
        // Change the order here, and you cange it on the site.
        "Users"       => "Brugere",
        "Pages"       => "Side Redigering",
        "News"        => "Nyheder",
        "NewsLetter"  => "Nyheds Brev",
        "Event"       => "Arrangementer",
        "Competitions" =>  "Konkurrencer",
        "Seatmap"     => "Seatmaps",
        "Sponsors"    => "Sponsore",
      ];
      $result = $db_conn->query( "SELECT
        ( SELECT COUNT(*) FROM Users WHERE Inactive = 0 AND UserID NOT BETWEEN 2 AND 20  ) as Users,
        ( SELECT COUNT(*) FROM News ) as News,
        ( SELECT COUNT(*) FROM Pages ) as Pages,
        ( SELECT COUNT(*) FROM NewsLetter ) as NewsLetter,
        ( SELECT COUNT(*) FROM Event ) as Event,
        ( SELECT COUNT(*) FROM Sponsors ) as Sponsors,
        ( SELECT COUNT(*) FROM Competitions ) as Competitions,
        ( SELECT COUNT(*) FROM Seatmap ) as Seatmap
      ");
      if( $result -> num_rows ) {
        $row = $result->fetch_assoc();
      }
      while(list($key, $value) = each($adminTabs)) {
        echo "<a href='?page=Admin&subpage=$key#admin_menu'><li class='";
        if($subpage == $key) {
          echo "active ";
        }
        echo "list-group-item col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-12'>
                <span class='badge'>".$row[$key]."</span>$value</li></a>";
      }
      ?>
    </ul>
  </div>
  <div class="col-lg-12 col-sm-12 LanCMScontentbox">
    <div id="admin_panel" class="table-responsive">
      <!-- Load pages here... -->
      <?php
      if($subpage !=''){
        include("Include/Admin/".$subpage."/".$subpage.".php");
      } else {
        echo "<h3 class='text-center'>Velkommen til administrationen, Sir. Admin</h3>";
      }
      ?>
    </div>
  </div>
</div>

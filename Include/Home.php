<?php
# ========= Check if any sticky news ================
$OldestNewsQ = $db_conn->query("Select NewsID From News Order by NewsID ASC Limit 1");
$LowesNewsID = $OldestNewsQ->fetch_assoc();
$LowesNewsID = $LowesNewsID['NewsID'];
$LowesNewsID;

$i = $LowesNewsID;
$amountOfNewsQ = $db_conn->query("Select NewsID From News");
$amountOfNews = $amountOfNewsQ->num_rows;
while($i <= $amountOfNews+$LowesNewsID-1){
    $NewsQ = $db_conn->query("Select * From News Where NewsID = '$i' AND Online = 1");
    $NewsRow = $NewsQ->fetch_assoc();
    $NewspupTime = $NewsRow['PublishDate'];
    $NoneStickyNews[$NewspupTime] = $NewsRow['Content'];
    $i++;
}
$NoneStickyNews[] = krsort($NoneStickyNews);
#echo "<pre>";
#print_r($NoneStickyNews);
#echo "</pre>";
?>
   

   
<div class="row LanCMSequal">
    <!-- Main News Start -->
    <div class="col-lg-5 LanCMScontentbox">
       <?php
        echo array_values($NoneStickyNews)[0];
        ?>
    </div>
    <!-- Main News End -->
    <!-- Spacer start -->
    <div class="col-lg-2">
    </div>
    <!-- Spacer end -->
    <!-- Lastest News start -->
    <div class="col-lg-5 LanCMScontentbox">
        <?php echo array_values($NoneStickyNews)[1]; ?>
    </div>
    <!-- Lastest News End -->
</div>
<!-- =========================== -->
<?php require_once("Include/TilesAndTournament.php"); ?>
<!-- =========================== -->
<div class="row LanCMSequal">
    <div class="col-lg-4  LanCMScontentbox img-thumbnail">
        <?php echo array_values($NoneStickyNews)[2]; ?>
    </div>
    <div class="col-lg-4 LanCMScontentbox img-thumbnail">
        <?php echo array_values($NoneStickyNews)[3]; ?>
    </div>
    <div class="col-lg-4 LanCMScontentbox img-thumbnail">
        <?php echo array_values($NoneStickyNews)[4]; ?>
    </div>
</div>
<hr>
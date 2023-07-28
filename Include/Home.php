<?php
$Showtime = time();
$GetNews = $db_conn->query("Select * From News Where Online = '1' AND PublishDate <= '$Showtime'  ORDER By PublishDate DESC LIMIT 5");
while ($Newsrow = $GetNews->fetch_assoc()) {
    $LatestNewsSet[] = array('Content' => $Newsrow['Content'],
                             'ID'      => $Newsrow['NewsID'],
                             'Title'   => $Newsrow['Title'],
                             'Author'  => $Newsrow['AuthorID'],
                             'Publish' => $Newsrow['PublishDate']);
    
}

#$NoneStickyNews[] = krsort($NoneStickyNews);
#echo "<pre>";
#print_r($LatestNewsSet);
#echo "</pre>";
?>
   
<div class="row LanCMSequal">
    <!-- Main News Start -->
    <div class="col-lg-5 col-xs-12 col-sm-12 col-md-12  LanCMScontentbox">
       <h4 style="float:left"><?php echo $LatestNewsSet[0]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[0]['Publish']);?></h6>
       <hr style=" clear:both">
       <?php echo $LatestNewsSet[0]['Content']; ?>
    </div>
    <!-- Main News End -->
    <!-- Spacer start -->
    <div class="col-lg-2">
    </div>
    <!-- Spacer end -->
    <!-- Lastest News start -->
    <div class="col-lg-5 col-xs-12 col-sm-12 col-md-12 LanCMScontentbox">
       <h4 style="float:left"><?php echo $LatestNewsSet[1]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[1]['Publish']);?></h6>
       <hr style=" clear:both">
        <?php echo $LatestNewsSet[1]['Content']; ?>
    </div>
    <!-- Lastest News End -->
</div>
<!-- =========================== -->
<?php require_once("Include/TilesAndTournament.php"); ?>
<!-- =========================== -->
<div class="row LanCMSequal">
    <div class="col-lg-4 col-xs-12 col-sm-12 col-md-12 LanCMScontentbox img-thumbnail">
       <h4 style="float:left"><?php echo $LatestNewsSet[2]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[2]['Publish']);?></h6>
       <hr style=" clear:both">
        <?php echo $LatestNewsSet[2]['Content']; ?>
    </div>
    <div class="col-lg-4 col-xs-12 col-sm-12 col-md-6 LanCMScontentbox img-thumbnail">
       <h4 style="float:left"><?php echo $LatestNewsSet[3]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[3]['Publish']);?></h6>
       <hr style=" clear:both">
       <?php echo $LatestNewsSet[3]['Content']; ?>
    </div>
    <div class="col-lg-4 col-xs-12 col-sm-12 col-md-6 LanCMScontentbox img-thumbnail">
       <h4 style="float:left"><?php echo $LatestNewsSet[4]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[4]['Publish']);?></h6>
       <hr style=" clear:both">
       <?php echo $LatestNewsSet[4]['Content']; ?>
    </div>
</div>
<hr>

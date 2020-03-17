<?php
$GetNews = $db_conn->query("Select * From News Where Online = '1' ORDER By PublishDate DESC LIMIT 5");
while ($Newsrow = $GetNews->fetch_assoc()) {
    $LatestNewsSet[] = array('content' => $Newsrow['Content'],
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
    <div class="col-lg-5 LanCMScontentbox">
       <h4 style="float:left"><?php echo $LatestNewsSet[0]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[0]['Publish']);?></h6>
       <hr style=" clear:both">
       <?php echo $LatestNewsSet[0]['content']; ?>
    </div>
    <!-- Main News End -->
    <!-- Spacer start -->
    <div class="col-lg-2">
    </div>
    <!-- Spacer end -->
    <!-- Lastest News start -->
    <div class="col-lg-5 LanCMScontentbox">
       <h4 style="float:left"><?php echo $LatestNewsSet[1]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[1]['Publish']);?></h6>
       <hr style=" clear:both">
        <?php echo $LatestNewsSet[1]['content']; ?>
    </div>
    <!-- Lastest News End -->
</div>
<!-- =========================== -->
<?php require_once("Include/TilesAndTournament.php"); ?>
<!-- =========================== -->
<div class="row LanCMSequal">
    <div class="col-lg-4  LanCMScontentbox img-thumbnail">
       <h4 style="float:left"><?php echo $LatestNewsSet[2]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[2]['Publish']);?></h6>
       <hr style=" clear:both">
        <?php echo substr($LatestNewsSet[2]['content'], 0, 256); ?>
    </div>
    <div class="col-lg-4 LanCMScontentbox img-thumbnail">
       <h4 style="float:left"><?php echo $LatestNewsSet[3]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[3]['Publish']);?></h6>
       <hr style=" clear:both">
        <?php echo substr($LatestNewsSet[3]['content'], 0, 256); ?>
    </div>
    <div class="col-lg-4 LanCMScontentbox img-thumbnail">
       <h4 style="float:left"><?php echo $LatestNewsSet[4]['Title'];?></h4>
       <h6 style="float:right"><?php echo date('d.M Y',$LatestNewsSet[4]['Publish']);?></h6>
       <hr style=" clear:both">
        <?php echo substr($LatestNewsSet[4]['content'], 0, 256); ?>
    </div>
</div>
<hr>
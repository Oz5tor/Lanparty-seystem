<?php 
    $newsID = strip_tags($_GET['id']);
    $news_sql = $db_conn->query("SELECT * FROM News Where Online = '1' AND NewsID = $newsID");
?>


<div class="col-lg-12 LanCMScontentbox">
<?php
	if ($news_row = $news_sql->fetch_assoc())
	{
?>
    <div>
        <h2 style="float:left"><?php echo $news_row['Title'];?></h2>
		
        <h6 style="float:right"><?php echo date('d.M Y',$news_row['PublishDate']);?></h6>
        <hr style=" clear:both">
		<?php echo '<p>'.$news_row['Content'].'</p>'; ?>
        <hr style="clear:both;"/>
    </div>
    <?php
    }else {
        header("Location: index.php");
    }
    ?>
</div>
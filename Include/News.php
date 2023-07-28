<?php
	$Showtime = time();
	$news_sql = "SELECT News.Title, News.Content FROM News Where Online = '1' AND PublishDate <= '$Showtime'";
	$news_result = mysqli_query($db_conn, $news_sql) or die (mysqli_error($db_conn));
	$total_records = mysqli_num_rows($news_result); // Total number of data
	$scroll_page = 5; // Number of pages to be scrolled
	$per_page = $_GLOBAL["g_results_per_page"]; // Number of pages to display each page

	if(isset($_GET['npage'])) {
		$current_page = strip_tags($_GET['npage']); // Found page
	} else {
		$current_page = 1;
	}
	$pager_url = "index.php?page=$page&npage="; // The address where the paging is done
	$inactive_page_tag = 'id="current_page"'; // Format for inactive page link
	$previous_page_text = '&nbsp;<&nbsp;'; // Previous page text (such as <img src = "...)
	$next_page_text = '>&nbsp;'; // Next page text (such as <img src = "...)
	$first_page_text = '<<'; // First page text (such as <img src = "...)
	$last_page_text = '>>'; // Last page text (such as <img src = "...)
	$pager_url_last = ' ';

	include("class/kgPager.php");
	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ -> pager_set($pager_url , $total_records , $scroll_page , $per_page , $current_page , $inactive_page_tag , $previous_page_text , $next_page_text , $first_page_text , $last_page_text , $pager_url_last);
	$albums_result = mysqli_query($db_conn,$news_sql." ORDER BY PublishDate DESC LIMIT ".$kgPagerOBJ -> start.", ".$kgPagerOBJ -> per_page."");
?>
	<div class="col-lg-12 LanCMScontentbox">
<?php
	while ($news_row = mysqli_fetch_assoc($albums_result))
	{
?>

    <div>
		<?php echo '<h2>'.$news_row['Title'].' </h2>'; ?>
		<?php echo '<p>'.$news_row['Content'].'</p>'; ?>
        <hr style="clear:both;"/>
    </div>
    <?php } ?>
    <div class="text-center">
    <?php
    		echo '<ul class="pagination pagination-lg">';

    if($current_page > 1) {
		  echo '<li>'.$kgPagerOBJ -> first_page.'</li>' ;
		  echo '<li>'.$kgPagerOBJ -> previous_page.'</li>' ;
	  } else {
		  echo '<li class="disabled"><a><<</a></li>';
		  echo '<li class="disabled"><a><</a></li>';
	  }
	  echo '<li>'.$kgPagerOBJ -> page_links.'</li>' ;
	  if($current_page >= $kgPagerOBJ -> total_pages) {
		  echo '<li class="disabled"><a>></a></li>';
		  echo '<li class="disabled"><a>>></a></li>';
	  } else {
		  echo '<li>'.$kgPagerOBJ -> next_page.'</li>' ;
		  echo '<li>'.$kgPagerOBJ -> last_page.'</li>' ;
	  }
    ?>
    </div>
</div>

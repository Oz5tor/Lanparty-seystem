<?php 
	$news_sql = "SELECT News.Title, News.Content FROM News";
	$news_result = mysqli_query($db_conn, $news_sql) or die (mysqli_error($db_conn));
	$total_records = mysqli_num_rows($news_result); // Total number of data
	$scroll_page = 5; // Number of pages to be scrolled
	$per_page = 20; // Number of pages to display each page
	
	if(isset($_GET['npage']))
	{
		$current_page = strip_tags($_GET['npage']); // Found page
	}
	else
	{
		$current_page = 1;
	}
	$pager_url = "index.php?page=$page&npage="; // The address where the paging is done
	$inactive_page_tag = 'id="current_page"'; // Format for inactive page link
	$previous_page_text = '&nbsp;<&nbsp;'; // Previous page text (such as <img src = "...)
	$next_page_text = '>&nbsp;'; // Next page text (such as <img src = "...)
	$first_page_text = '<<'; // First page text (such as <img src = "...)
	$last_page_text = '>>'; // Last page text (such as <img src = "...)
	$pager_url_last = ' ';
	
	include("Include/Nyhedsarkiv/kgPager.php");
	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ -> pager_set($pager_url , $total_records , $scroll_page , $per_page , $current_page , $inactive_page_tag , $previous_page_text , $next_page_text , $first_page_text , $last_page_text , $pager_url_last);
	$albums_result = mysqli_query($db_conn,$news_sql." ORDER BY CreatedDate DESC LIMIT ".$kgPagerOBJ -> start.", ".$kgPagerOBJ -> per_page."");
?>
	<div class="col-lg-12 hlpf_newsborder">
<?php
	while ($news_row = mysqli_fetch_assoc($albums_result))
	{
?>   
    
    <div>
		<?php echo '<h2>'.$news_row['Title'].' </h2>'; ?>
		<?php echo '<p>'.$news_row['Content'].'</p>'; ?>
        <hr style="clear:both;"/>
    </div>
    <?php }

	echo '<center><div style="clear:both; height:15px; width:700px;">';
	

      if($current_page > 1)
	  {
		  echo '<p style="display:inline; ">'.$kgPagerOBJ -> first_page.'</p>' ;
		  echo '<p style="display:inline; ">'.$kgPagerOBJ -> previous_page.'</p>' ;
	  }
	  else
	  {
		  echo '<p style="display:inline;">&nbsp;<<&nbsp;</p>';
		  echo '<p style="display:inline;"><&nbsp;</p>';
	  }
	  echo '<p style="display:inline; line-height: 22px; ">'.$kgPagerOBJ -> page_links.'</p>' ;
	  if($current_page >= $kgPagerOBJ -> total_pages)
	  {
		  echo '<p style="display:inline;">&nbsp;>&nbsp;</p>';
		  echo '<p style="display:inline;">>>&nbsp;</p>';
	  }
	  else
	  {
		  echo '<p style="display:inline;">'.$kgPagerOBJ -> next_page.'</p>' ;
		  echo '<p style="display:inline;">'.$kgPagerOBJ -> last_page.'</p>' ;
	  }
      echo '</div></center>';
	 ?>
	 </div>
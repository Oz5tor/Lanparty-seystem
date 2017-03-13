<?php
	if(isset($_GET['category']) && $_GET['category'] != ''){
	  $CID = $db_conn->real_escape_string($_GET['category']);
	}

	if(isset($_GET['thread']) && $_GET['thread'] != ''){
		$ID = $db_conn->real_escape_string($_GET['thread']);
	}

	if(isset($_POST['Send_form'])) // Submit form start
  {
    require_once('Include/Forum/FormSubmit.php');
  }// Form submit end
	// Pagination //
	$ThreadReplies_sql = "SELECT * FROM `ForumReplies` WHERE ThreadID = " . $ID;
	$ThreadReplies = mysqli_query($db_conn, $ThreadReplies_sql) or die (mysqli_error($db_conn));
	$total_records = mysqli_num_rows($ThreadReplies); // Total number of data

	$scroll_page = 5; // Number of pages to be scrolled
	$per_page = 5; // Number of pages to display each page

	if(isset($_GET['npage'])) {
		$current_page = strip_tags($_GET['npage']); // Found page
	} else {
		$current_page = 1;
	}

	$pager_url = "index.php?page=Forum&category=" . $CID . "&thread=" . $ID . "&npage="; // The address where the paging is done
	$inactive_page_tag = 'id="current_page"'; // Format for inactive page link
	$previous_page_text = '&nbsp;<&nbsp;'; // Previous page text (such as <img src = "...)
	$next_page_text = '>&nbsp;'; // Next page text (such as <img src = "...)
	$first_page_text = '<<'; // First page text (such as <img src = "...)
	$last_page_text = '>>'; // Last page text (such as <img src = "...)
	$pager_url_last = ' ';

	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ -> pager_set($pager_url , $total_records , $scroll_page , $per_page , $current_page , $inactive_page_tag , $previous_page_text , $next_page_text , $first_page_text , $last_page_text , $pager_url_last);
	$albums_result = mysqli_query($db_conn,$ThreadReplies_sql." ORDER BY CreationDate ASC LIMIT ".$kgPagerOBJ -> start.", ".$kgPagerOBJ -> per_page."");
	// ==================================== //
?>

<div class='col-lg-12 hlpf_contentbox'>
	<div class='row'>
		<div class='col-lg-12'>
			<h1>Forum:</h1>
		</div>
		<div class='col-lg-12' style='margin-bottom: 20px;'> <!-- CONTENT BEGIN -->
			<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-10 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Content</p>
				</div>
				<div class='col-lg-2 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Oprettet af:</p>
				</div>
			</div>

			<?php
		  if( $ThreadReplies -> num_rows ) {
		  	while ($Replies = mysqli_fetch_assoc($albums_result)) { ?>
					<div class='row' style='padding-right: 20px; padding-left: 20px;'>
						<div class='col-lg-10 hlpf_Black_Border'>
							<p> <?php echo $Replies['Content'] ?> </p>
						</div>
						<div class='col-lg-2 hlpf_Black_Border'>
							<p> <?php echo TorGetUserName($Replies['Author'], $db_conn); ?> </p>
						</div>
					</div>
		  	<?php }
		  } ?>
		  <hr>
			<!-- Pagination -->
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
		</div> <!-- CONTENT END -->
		<?php if(isset($_SESSION['UserID'])){ ?>
		<div class='col-lg-12'>
			<hr>
		</div>
		<div class='row' style='padding-right: 20px; padding-left: 20px;'>
			<div class='col-lg-12'><h1>Besvar tråd:</h1></div>
	    <form action='' method='post'>
	      <div class='form-group col-lg-12'>
	        <label class='control-label' for='Reply'>Svar:</label>
	        <input type='text' class='form-control' id='Reply' name='Reply'>
	      </div>
	      <div class='form-group col-xs-12 col-sm-5 col-md-6 col-lg-3'>
	        <input type='submit' value='Send besked' class='btn btn-default' name='Send_form'>
	      </div>
	      <?php
	      if(isset($RegErroMSG) && $RegErroMSG == ''){
	      	echo '<ul class="alert alert-danger" role="alert"><b>Feltkravene er ikke opfyldt:</b>';
		      foreach($RegErroMSG as $i){
		      	echo '<li>'.$i.'</li>';
		      }
	      	echo '</li></ul>';
	      }
	      unset($RegErroMSG);
	      ?>
	    </form><!-- Form end -->
		</div>
		<?php } ?>
	</div>
</div>
<?php //} ?>

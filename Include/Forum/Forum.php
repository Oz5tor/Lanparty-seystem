<?php
	require_once('class/GetUsernameFromID.php');
	// Make variables independant from URL controller //
	if(isset($_GET['category']))
	{
		$category = mysqli_real_escape_string($db_conn,strip_tags($_GET['category']));
	}
	else
	{
		$category = '';
	}

	if(isset($_GET['thread']))
	{
		$thread = mysqli_real_escape_string($db_conn,strip_tags($_GET['thread']));
	}
	else
	{
		$thread = '';
	}
	// ==================================== //
	if(isset($_POST['Send_form'])) // Submit form start
  {
    require_once('Include/Forum/FormSubmit.php');
  }// Form submit end
	// Pagination //
	$ForumCategories_sql = "SELECT * FROM `ForumCategory`";
	$ForumCategories = mysqli_query($db_conn, $ForumCategories_sql) or die (mysqli_error($db_conn));
	$total_records = mysqli_num_rows($ForumCategories); // Total number of data

	$scroll_page = 5; // Number of pages to be scrolled
	$per_page = 10; // Number of pages to display each page

	if(isset($_GET['npage'])) {
		$current_page = strip_tags($_GET['npage']); // Found page
	} else {
		$current_page = 1;
	}
	$pager_url = "index.php?page=Forum&npage="; // The address where the paging is done
	$inactive_page_tag = 'id="current_page"'; // Format for inactive page link
	$previous_page_text = '&nbsp;<&nbsp;'; // Previous page text (such as <img src = "...)
	$next_page_text = '>&nbsp;'; // Next page text (such as <img src = "...)
	$first_page_text = '<<'; // First page text (such as <img src = "...)
	$last_page_text = '>>'; // Last page text (such as <img src = "...)
	$pager_url_last = ' ';

	include("class/kgPager.php");
	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ -> pager_set($pager_url , $total_records , $scroll_page , $per_page , $current_page , $inactive_page_tag , $previous_page_text , $next_page_text , $first_page_text , $last_page_text , $pager_url_last);
	$albums_result = mysqli_query($db_conn,$ForumCategories_sql." ORDER BY CreationDate ASC LIMIT ".$kgPagerOBJ -> start.", ".$kgPagerOBJ -> per_page."");
	// Pagination end //

	if (isset($category) AND !empty($category)) {
		include_once 'Include/Forum/Category.php';
	} else {
?>

<div id='ForumPanel' class='col-lg-12 hlpf_contentbox'>
	<div class='row'>
		<div class='col-lg-12'>
			<h1>Forum:</h1>
		</div>
		<div class='col-lg-12' style='margin-bottom: 20px;'> <!-- CONTENT BEGIN -->
			<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-10 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Forum</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Tråde</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Svar</p>
				</div>
			</div>

			<!--<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-12 hlpf_Black_Border'>
					<p>Sæson x år y (Denne skal gøres dynamisk og have noget logik)</p>
				</div>
			</div>  Consider if this should be in the design or not, it needs logic-->
			<?php
		  if( $ForumCategories -> num_rows ) {
		  	while ($Categories = mysqli_fetch_assoc($albums_result)) { ?>
					<div class='row' style='padding-right: 20px; padding-left: 20px;'>
						<div class='col-lg-10 hlpf_Black_Border'>
							<p> <?php echo "<a href='?page=Forum&category=" . $Categories['CategoryID'] . "#CategoryPanel'>" . $Categories['Name'] . "</a>" ?> </p>
							<p> <?php echo $Categories['Description'] ?> </p>
						</div>
						<?php
						$CategoryCount = $db_conn->query("SELECT * FROM `ForumThread` WHERE CategoryID = " . $Categories['CategoryID']);
					  $CCount = mysqli_num_rows($CategoryCount); ?>
						<div class='col-lg-1 hlpf_Black_Border'>
							<p> <?php echo $CCount ?> </p>
							<p> &nbsp; </p>
						</div>
						<?php
						$TempRCount = 0;
						$RCount = 0;
						$CategoryThreads = $db_conn->query("SELECT * FROM `ForumThread` WHERE CategoryID = " .$Categories['CategoryID'] . " ORDER BY CreationDate ASC");
					  if( $CategoryThreads -> num_rows ) {
					  	while ($Threads = $CategoryThreads->fetch_assoc()) {
								$ReplyCount = $db_conn->query("SELECT * FROM `ForumReplies` WHERE ThreadID = " . $Threads['ThreadID']);
							  $TempRCount = mysqli_num_rows($ReplyCount);
							  $RCount = $RCount + $TempRCount;
						  }
					  } ?>
						<div class='col-lg-1 hlpf_Black_Border'>
							<p> <?php echo $RCount ?> </p>
							<p> &nbsp; </p>
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
	    </div> <!-- Pagination end -->
		</div> <!-- CONTENT END -->
		<?php if(isset($_SESSION['Admin'])){ ?>
		<div class='col-lg-12'>
			<hr>
		</div>
		<div class='row' style='padding-right: 20px; padding-left: 20px;'>
			<div class='col-lg-12'><h1>Opret kategori:</h1></div>
	    <form action='' method='post'>
	      <div class='form-group col-lg-12'>
	        <label class='control-label' for='CategoryName'>Kategori navn:</label>
	        <input type='text' class='form-control' id='CategoryName' name='CategoryName'>
	      </div>
	      <div class='form-group col-lg-12'>
	        <label class='control-label' for='CategoryDesc'>Kategori beskrivelse:</label>
	        <input type='text' class='form-control' id='CategoryDesc' name='CategoryDesc'>
	      </div>
	      <div class='form-group col-xs-12 col-sm-5 col-md-6 col-lg-3'>
	        <input type='submit' value='Opret kategori' class='btn btn-default' name='Send_form'>
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
<?php } ?>

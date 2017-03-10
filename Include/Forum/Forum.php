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
	if (isset($category) AND !empty($category)) {
		include_once 'Include/Forum/Category.php';
	} else {
?>

<div class='col-lg-12 hlpf_contentbox'>
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

			<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-12 hlpf_Black_Border'>
					<p>Sæson x år y (Denne skal gøres dynamisk og have noget logik)</p>
				</div>
			</div>
			<?php
			$ForumCategories = $db_conn->query("SELECT * FROM `ForumCategory` ORDER BY CreationDate ASC");
		  if( $ForumCategories -> num_rows ) {
		  	while ($Categories = $ForumCategories->fetch_assoc()) { ?>
					<div class='row' style='padding-right: 20px; padding-left: 20px;'>
						<div class='col-lg-10 hlpf_Black_Border'>
							<p> <?php echo "<a href='?page=Forum&category=" . $Categories['CategoryID'] . "'>" . $Categories['Name'] . "</a>" ?> </p>
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
						$RCount = 0;
						$CategoryThreads = $db_conn->query("SELECT * FROM `ForumThread` WHERE CategoryID = " .$Categories['CategoryID'] . " ORDER BY CreationDate ASC");
					  if( $CategoryThreads -> num_rows ) {
					  	while ($Threads = $CategoryThreads->fetch_assoc()) {
								$ReplyCount = $db_conn->query("SELECT * FROM `ForumReplies` WHERE ThreadID = " . $Threads['ThreadID']);
							  $RCount = mysqli_num_rows($ReplyCount); 
						  }
					  } ?>
						<div class='col-lg-1 hlpf_Black_Border'>
							<p> <?php echo $RCount ?> </p> <!-- Almost, but it only shows the count of the last thread -->
							<p> &nbsp; </p>
						</div>
					</div>
		  	<?php }
		  } ?>
		</div> <!-- CONTENT END -->
		<?php if($_SESSION['Admin'] == 1){ ?>
		<hr>
		<div class='row' style='padding-right: 20px; padding-left: 20px;'>
			<div class='col-lg-12'><h1>Opret kategori:</h1></div>
	    <form action='' method='post'>
	      <div class='form-group col-lg-12'>
	        <label class='control-label' for='CategoryName'>Kategori navn:</label>
	        <input type='text' class='form-control' placeholder='Santa Claus' id='CategoryName'
	               value='<?php if(isset($CategoryName)){ echo $CategoryName;} ?>' name='CategoryName'>
	      </div>
	      <div class='form-group col-lg-12'>
	        <label class='control-label' for='CategoryDesc'>Kategori beskrivelse:</label>
	        <input type='text' class='form-control' placeholder='Santa Claus' id='CategoryDesc'
	               value='<?php if(isset($CategoryDesc)){ echo $CategoryDesc;} ?>' name='CategoryDesc'>
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

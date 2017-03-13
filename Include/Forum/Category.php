<?php
	if(isset($_GET['category']) && $_GET['category'] != ''){
	  $ID = $db_conn->real_escape_string($_GET['category']);
	}

	if(isset($_POST['Send_form'])) // Submit form start
	  {
	    require_once('Include/Forum/FormSubmit.php');
	  }// Form submit end

	if (isset($thread) AND !empty($thread)) {
		include_once 'Include/Forum/Thread.php';
	} else {
?>

<div class='col-lg-12 hlpf_contentbox'>
	<div class='row'>
		<div class='col-lg-12'>
			<h1>Forum:</h1>
		</div>
		<div class='col-lg-12' style='margin-bottom: 20px;'> <!-- CONTENT BEGIN -->
			<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-9 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Tråde</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Svar</p>
				</div>
				<div class='col-lg-2 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Oprettet af:</p>
				</div>
			</div>

			<?php
			$CategoryThreads = $db_conn->query("SELECT * FROM `ForumThread` WHERE CategoryID = " . $ID . " ORDER BY CreationDate ASC");
		  if( $CategoryThreads -> num_rows ) {
		  	while ($Threads = $CategoryThreads->fetch_assoc()) { ?>

					<!-- Original row -->
					<div class='row' style='padding-right: 20px; padding-left: 20px;'>
						<div class='col-lg-9 hlpf_Black_Border'>
							<p> <?php echo "<a href='?page=Forum&category=" . $Threads['CategoryID'] . "&thread=" . $Threads['ThreadID'] . "'>" . $Threads['Name'] . "</a>" ?> </p>
						</div>
						<?php
						$ReplyCount = $db_conn->query("SELECT * FROM `ForumReplies` WHERE ThreadID = " . $Threads['ThreadID']);
					  $Count = mysqli_num_rows ($ReplyCount); ?>
						<div class='col-lg-1 hlpf_Black_Border'>
							<p> <?php echo $Count ?> </p>
						</div>
						<div class='col-lg-2 hlpf_Black_Border'>
							<p> <?php echo TorGetUserName($Threads['Author'], $db_conn); ?> </p>
						</div>
					</div>
		  	<?php }
		  } ?>
		</div> <!-- CONTENT END -->
		<?php if(isset($_SESSION['UserID'])){ ?>
		<hr>
		<div class='row' style='padding-right: 20px; padding-left: 20px;'>
			<div class='col-lg-12'><h1>Opret tråd:</h1></div>
	    <form action='' method='post'>
	      <div class='form-group col-lg-12'>
	        <label class='control-label' for='ThreadName'>Trådnavn:</label>
	        <input type='text' class='form-control' id='ThreadName' name='ThreadName'>
	      </div>
	      <div class='form-group col-lg-12'>
	        <label class='control-label' for='ReplyMessage'>Besked:</label>
	        <input type='text' class='form-control' id='ReplyMessage' name='ReplyMessage'>
	      </div>
	      <div class='form-group col-xs-12 col-sm-5 col-md-6 col-lg-3'>
	        <input type='submit' value='Opret tråd' class='btn btn-default' name='Send_form'>
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

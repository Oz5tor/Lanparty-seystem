<?php
	if(isset($_GET['thread']) && $_GET['thread'] != ''){
		$ID = $db_conn->real_escape_string($_GET['thread']);
	}

	if(isset($_POST['Send_form'])) // Submit form start
	  {
	    require_once('Include/Forum/FormSubmit.php');
	  }// Form submit end
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
			$ThreadReplies = $db_conn->query("SELECT * FROM `ForumReplies` WHERE ThreadID = " . $ID . " ORDER BY CreationDate ASC");
		  if( $ThreadReplies -> num_rows ) {
		  	while ($Replies = $ThreadReplies->fetch_assoc()) { ?>

					<!-- Original row -->
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
		</div> <!-- CONTENT END -->
		<?php if(isset($_SESSION['UserID'])){ ?>
		<hr>
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

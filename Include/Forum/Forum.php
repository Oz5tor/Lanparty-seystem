<?php
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
					<p>Forum</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Tråde</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: lightblue;'>
					<p>Svar</p>
				</div>
			</div>
			
			<?php for ($i = 1; $i < 5; $i++) { ?> 
			<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-12 hlpf_Black_Border' style='background-color: lightgreen;'>
					<p>Sæson x år y</p>
				</div>
			</div>
			<!-- Original row -->
			<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-10 hlpf_Black_Border' style='background-color: pink;'>
					<p>Test</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: pink;'>
					<p>1</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: pink;'>
					<p>12</p>
				</div>
			</div>

			<!-- Copy row -->
			<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-10 hlpf_Black_Border' style='background-color: pink;'> 
					<p>Test</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: pink;'>
					<p>1</p>
				</div>
				<div class='col-lg-1 hlpf_Black_Border' style='background-color: pink;'>
					<p>12</p>
				</div>
			</div>
			<?php } ?>
		</div> <!-- CONTENT END -->
		<?php if($_SESSION['Admin'] == 1){ ?>  
		<hr>
		<div class='row' style='padding-right: 20px; padding-left: 20px;'>
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
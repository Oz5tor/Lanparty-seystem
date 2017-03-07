<?php

?>

<div class='col-lg-12 hlpf_contentbox'>
	<div class='row'>
		<div class='col-lg-12'>
			<h1>Forum:</h1>
		</div>
		<div class='col-lg-12'> <!-- CONTENT BEGIN -->
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
				<div class='col-lg-12 hlpf_Black_Border' style='background-color: lightgreen;'> <!-- SEASONS BEGIN - Fucking border mand -->
					<p>Sæson x år y</p>
				</div> <!-- SEASONS END -->
			</div>
			<!-- Original row -->
			<div class='row' style='padding-right: 20px; padding-left: 20px;'>
				<div class='col-lg-10 hlpf_Black_Border' style='background-color: pink;'> <!-- INDIVIDUAL FORUMS BEGIN -->
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
				</div> <!-- INDIVIDUAL FORUMS END -->
			</div>
			<?php } ?>
		</div> <!-- CONTENT END -->
	</div>
</div>
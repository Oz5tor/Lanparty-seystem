<?php
	$result = $db_conn->query(
    	"SELECT Event.EventID, Event.Title, Event.Poster, Event.StartDate, Event.EndDate, Event.Location, Event.Network, Event.SeatsOpen, Event.Seatmap, Event.Rules FROM Event ORDER BY StartDate DESC LIMIT 0, 1" // Not using * as I want to be able to see here which columns exist in database
    );
    if( $result -> num_rows ) {
        $row = $result->fetch_assoc();
    }
?>

<div class="col-lg-12 hlpf_newsborder"> <!-- Ret class til-->
	<div class="col-lg-6 row">
		<div class="row col-lg-12">
			<p><h2>Information:</h2></p>
		</div>

		<!-- Tid og sted -->
		<div class="row col-lg-12">
			<p><h4>Tid og sted:</h4></p>
		</div>
		<div class="row col-lg-4">
			<p>Navn:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo $row['Title'] ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Dato:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo date("d", $row['StartDate']) ?> - <?php echo date("d M Y", $row['EndDate']) ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Start:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo date("d M Y - H:i:s", $row['StartDate']) ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Slut:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo date("d M Y - H:i:s", $row['EndDate']) ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Adresse:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo $row['Location'] ?></p>
		</div>

		<!-- Pladser og priser -->
		<div class="row col-lg-12">
			<p><h4>Pladser og priser:</h4></p>
		</div>
		<div class="row col-lg-4">
			<p>Pladser:</p>
		</div>
		<div class="row col-lg-8">
			<p>On the way ...<?php //echo $row['Location'] ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Information om billetpriser er på vej ...</p>
		</div>
		<div class="row col-lg-8">
			<p>On the way ...<?php //echo $row['Location'] ?></p>
		</div>
	</div>

	<div class="col-lg-6 row">
		<div class="row col-lg-12">
			<?php if ($row['Poster'] == null || $row['Poster'] == "") {
				echo '<img class="img-responsive" src="Images/EventPoster/noposter.png">';
			}else{
				echo '<img class="img-responsive" src="Images/EventPoster/'.$row['Poster'] . '">';
			} ?>
		</div>
	</div>
</div>

<?php $result -> close(); ?>

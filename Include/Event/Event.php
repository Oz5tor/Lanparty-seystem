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
		<div class="row col-lg-12">
			<p><h4>Tid og sted:</h4></p>
		</div>
		<div class="row col-lg-4">
			<p>Navn:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo $row['Title'] ?></p>
		</div>
		<div class="row col-lg-12">
			<p>Dato: <?php echo date("d", $row['StartDate']) ?> - <?php echo date("d M Y", $row['EndDate']) ?></p>
		</div>
		<div class="row col-lg-12">
			<p>Start: <?php echo date("d M Y - H:i:s", $row['StartDate']) ?></p>
		</div>
		<div class="row col-lg-12">
			<p>Slut: <?php echo date("d M Y - H:i:s", $row['EndDate']) ?></p>
		</div>
		<div class="row col-lg-12">
			<p>Adresse: <?php echo $row['Location'] ?></p>
		</div>
		<div class="row col-lg-12">
			<p>Tilmelding begynder den <?php echo date("d M Y - H:i:s", $row['SeatsOpen']) ?>.</p>
		</div>
		<div class="row col-lg-12">
			<p>Arrangementet finder sted på addressen <?php echo $row['Location'] ?> og vil vare fra den 
			<?php echo date("d M Y - H:i:s", $row['StartDate']) ?> til den <?php echo date("d M Y - H:i:s", $row['EndDate']) ?>.</p>
		</div>
		<div class="row col-lg-12">
			<button class="button">Tilmelding</button>
		</div>
	</div>
	<div class="col-lg-6 row">
		<div class="row col-lg-12">
			<?php echo '<img class="img-responsive" src="Images/Sponsore/'.$row['Poster'] . '">' ?>
			<br>
			<br>
		</div>
	</div>
</div>

<?php $result -> close(); ?>

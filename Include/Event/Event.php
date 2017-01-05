<?php
	$result = $db_conn->query(
    	"SELECT Event.EventID, Event.Title, Event.Poster, Event.StartDate, Event.EndDate, Event.Location, Event.Network, Event.SeatsOpen, Event.Seatmap, Event.Rules FROM Event ORDER BY StartDate DESC LIMIT 0, 1" // Not using * as I want to be able to see here which columns exist in database
    );
    if( $result -> num_rows ) {
        $row = $result->fetch_assoc();
    }
?>

<div class="col-lg-12 hlpf_newsborder"> <!-- Ret class til-->
	<div class="row col-lg-12">
		<h2><?php echo $row['Title'] ?></h2>
	</div>
	<div class="row col-lg-12">
		Event brødtekst.
		<br>
		<br>
	</div>
	<div class="row col-lg-4">
		Tilmelding begynder den <?php echo date("d M Y - H:i:s", $row['SeatsOpen']) ?>.
		<br>
		<br>
	</div>
	<div class="row col-lg-8">
		<?php echo $row['Poster'] ?> <!-- Der skal måske lige noget til for faktisk at vise et billede her -->
	</div>
	<div class="row col-lg-11">
		Arrangementet finder sted på addressen <?php echo $row['Location'] ?> og vil vare fra den 
		<?php echo date("d M Y - H:i:s", $row['StartDate']) ?> til den <?php echo date("d M Y - H:i:s", $row['EndDate']) ?>.
	</div>
	<div class="row col-lg-1">
		<button class="button">Tilmelding</button>
	</div>
</div>

<?php $result -> close(); ?>
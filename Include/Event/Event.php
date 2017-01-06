<?php
	$result = $db_conn->query(
    	//"SELECT e.Title, e.Poster, e.StartDate, e.EndDate, e.Location, e.Network, e.SeatsOpen, e.Seatmap, e.Rules, tp.EndTime, tp.Price FROM Event as e INNER JOIN TicketPrices as tp ON e.EventID = tp.EventID ORDER BY StartDate DESC LIMIT 0, 1" // This should be used when website is done. NOT UPDATED YET.

      "SELECT e.Title, e.Poster, e.StartDate,
              e.EndDate, e.Location, e.Network,
              e.SeatsOpen, e.Seatmap, e.Rules,
              tp.StartTime, tp.EndTime, tp.Price 
      FROM Event as e
      INNER JOIN TicketPrices as tp
        ON e.EventID = tp.EventID
      WHERE e.EventID = 2 AND tp.Type = 'Member'
      ORDER BY
        tp.EndTime ASC LIMIT 0, 1" // Use this while project is being tested.
    );
    if( $result -> num_rows ) {
        $row = $result->fetch_assoc();
    }
    $result2 = $db_conn->query(
      ## This should be used when website is done. NOT UPDATED YET.       
    	#"SELECT e.Title, e.Poster, e.StartDate, e.EndDate, e.Location, e.Network, e.SeatsOpen, e.Seatmap, e.Rules,
      #        tp.EndTime, tp.Price
      #FROM Event as e
      #INNER JOIN TicketPrices as tp
      #  ON e.EventID = tp.EventID
      #ORDER BY
      #  StartDate DESC
      #LIMIT 0, 1"
    	"SELECT e.Title, e.Poster, e.StartDate,
              e.EndDate, e.Location, e.Network,
              e.SeatsOpen, e.Seatmap, e.Rules,
              tp.StartTime, tp.EndTime, tp.Price 
      FROM Event as e
      INNER JOIN TicketPrices as tp
        ON e.EventID = tp.EventID
      WHERE e.EventID = 2 AND tp.Type = 'Nonmember'
      ORDER BY
        tp.StartTime ASC LIMIT 0, 1" // Use this while project is being tested.
    );
    if( $result2 -> num_rows ) {
        $row2 = $result2->fetch_assoc();
    }
?>

<div class="col-lg-12 hlpf_newsborder"> <!-- Ret class til-->
	<div class="col-lg-6 row">
		<div class="row col-lg-12">
			<p><h2>Information</h2></p>
		</div>

		<!-- Tid og sted -->
		<div class="row col-lg-12">
			<p><h4>Tid og sted</h4></p>
		</div>
    <table class="table table-striped">
      <tr>
        <td width="40%">Navn</td>
        <td><?php echo $row['Title'] ?></td>
      </tr>
      <tr>
        <td width="40%">Dato</td>
        <td><?php echo date("d", $row['StartDate']) ?> - <?php echo date("d M Y", $row['EndDate']) ?></td>
      </tr>
      <tr>
        <td width="40%">Start</td>
        <td><?php echo date("d M Y - H:i:s", $row['StartDate']) ?></td>
      </tr>
      <tr>
        <td width="40%">Slut</td>
        <td><?php echo date("d M Y - H:i:s", $row['EndDate']) ?></td>
      </tr>
      <tr>
        <td width="40%">Adresse</td>
        <td><?php echo $row['Location'] ?></td>
      </tr>
    </table>
		<!-- Pladser og priser -->
		<div class="row col-lg-12">
			<p><h4>Pladser og priser</h4></p>
		</div>
		<div class="row col-lg-4">
			<p>Pladser</p>
		</div>
		<div class="row col-lg-8">
			<?php if ($row['Seatmap'] == null || $row['Seatmap'] == "") { ?>
				<p>Ingen information tilgængelig.</p>
			<?php }else{
				echo $row['Seatmap'];
			} ?>
		</div>
		<div class="row col-lg-4">
			<p>Billetpris for medlemmer:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo $row['Price'] ?></p>
		</div>
		<div class="row col-lg-9">
			<br> <!-- SPACE -->
		</div>
		<div class="row col-lg-4">
			<p>Billetpris for alle:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo $row2['Price'] ?></p>
		</div>

		<!-- Tilmelding åbner -->
		<div class="row col-lg-12">
			<p><h4>Tilmelding åbner</h4></p>
		</div>
		<div class="row col-lg-4">
			<p>For medlemmer:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo date("d M Y - H:i:s", $row['StartTime']) ?></p>
		</div>
		<div class="row col-lg-4">
			<p>For alle:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo date("d M Y - H:i:s", $row2['StartTime']) ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Senest tilmelding for medlemmer:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo date("d M Y - H:i:s", $row['EndTime']) ?></p>
		</div>
		<div class="row col-lg-9">
			<br> <!-- SPACE -->
		</div>
		<div class="row col-lg-4">
			<p>Senest tilmelding for alle:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo date("d M Y - H:i:s", $row2['EndTime']) ?></p>
		</div>

		<!-- Netværk -->
		<div class="row col-lg-12">
			<p><h4>Netværk:</h4></p>
		</div>
		<div class="row col-lg-4">
			<p>Lokalnetværk:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo $row['Network'] ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Internet:</p>
		</div>
		<div class="row col-lg-8">
			<p>100 Mbit / 100 Mbit</p>
		</div>

		<!-- Faciliteter -->
		<!--
		<div class="row col-lg-12">
			<p><h4>Faciliteter:</h4></p>
		</div>
		<div class="row col-lg-4">
			<p>Soverum:</p>
		</div>
		<div class="row col-lg-8">
			<p>On the way ...<?php //echo $row['Location'] ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Bad:</p>
		</div>
		<div class="row col-lg-8">
			<p>On the way ...<?php //echo $row['Location'] ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Kiosk:</p>
		</div>
		<div class="row col-lg-8">
			<p>On the way ...<?php //echo $row['Location'] ?></p>
		</div>
		-->

		<!-- Regler -->
		<div class="row col-lg-12">
			<p><h4>Regler:</h4></p>
		</div>
		<!--
		<div class="row col-lg-4">
			<p>Rygning tilladt:</p>
		</div>
		<div class="row col-lg-8">
			<p>On the way ...<?php //echo $row['Location'] ?></p>
		</div>
		<div class="row col-lg-4">
			<p>Alkohol tilladt:</p>
		</div>
		<div class="row col-lg-8">
			<p>On the way ...<?php //echo $row['Location'] ?></p>
		</div>
		-->
		<div class="row col-lg-4">
			<p>Se regelset:</p>
		</div>
		<div class="row col-lg-8">
			<p><?php echo $row['Rules'] ?></p>
		</div>

		<!-- Arrangør -->
		<div class="row col-lg-12">
			<p><h4>Arrangør:</h4></p>
		</div>
		<div class="row col-lg-12">
			<p>
				HLParty arrangeres af foreningen Hovedstadens LanParty Forening. Foreningen er en folkeoplysende forening, godkendt i Hillerød kommune. Foreningens formål er (uddrag af vedtægter): 
				<br>
				<br>
				"Foreningens formål er at samle unge mennesker, primært i hovedstadsområdet, med interesse for computere og IT, for derved at medvirke til at styrke medlemmernes sociale kompetencer, skabe kontakt på tværs af kommunegrænser, etnicitet, køn og alder og styrke medlemmernes almennyttige IT kundskaber til glæde for den enkelte, såvel som for samfundet." 
				<br>
				<br>
				Overskud fra et arrangement går til drift af foreningen (f.eks. webhotel, vedligeholdelse og nyinkøb af servere, switche, netværkskabler mv.), samt til at sikre fremtidige arrangementer. 
			</p>
		</div>
		<div class="row col-lg-12">
			<p>Læs mere om foreningen bag HLParty på adressen <a href="https://hlpf.dk" target="_blank">https://hlpf.dk</a>.</p>
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

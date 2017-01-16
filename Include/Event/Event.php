<?php
    # Member price info ASC
    $result = $db_conn->query("SELECT e.Title, e.Poster, e.StartDate, e.EndDate, e.Location, e.Network, e.SeatsOpen, e.Seatmap, e.Rules, tp.StartTime, tp.EndTime, tp.Price FROM Event as e INNER JOIN TicketPrices as tp ON e.EventID = tp.EventID WHERE e.EventID = 2 AND tp.Type = 'Member' ORDER BY tp.EndTime ASC LIMIT 0, 1" );
    if( $result -> num_rows ) { $row = $result->fetch_assoc(); }
    # Nonmamber price info ASC
    $result2 = $db_conn->query("SELECT e.Title, e.Poster, e.StartDate, e.EndDate, e.Location, e.Network, e.SeatsOpen, e.Seatmap, e.Rules, tp.StartTime, tp.EndTime, tp.Price FROM Event as e INNER JOIN TicketPrices as tp ON e.EventID = tp.EventID WHERE e.EventID = 2 AND tp.Type = 'Nonmember' ORDER BY tp.StartTime ASC LIMIT 0, 1");
    if( $result2 -> num_rows ) { $row2 = $result2->fetch_assoc(); }
    # Member price info DESC
    $result3 = $db_conn->query("SELECT e.Title, e.Poster, e.StartDate, e.EndDate, e.Location, e.Network, e.SeatsOpen, e.Seatmap, e.Rules, tp.StartTime, tp.EndTime, tp.Price FROM Event as e INNER JOIN TicketPrices as tp ON e.EventID = tp.EventID WHERE e.EventID = 2 AND tp.Type = 'Member' ORDER BY tp.EndTime DESC LIMIT 0, 1");
    if( $result3 -> num_rows ) { $row3 = $result3->fetch_assoc(); }
    # Nonmamber price info DESC
    $result4 = $db_conn->query("SELECT e.Title, e.Poster, e.StartDate, e.EndDate, e.Location, e.Network, e.SeatsOpen, e.Seatmap, e.Rules, tp.StartTime, tp.EndTime, tp.Price FROM Event as e INNER JOIN TicketPrices as tp ON e.EventID = tp.EventID WHERE e.EventID = 2 AND tp.Type = 'Nonmember' ORDER BY tp.EndTime DESC LIMIT 0, 1");
    if( $result4 -> num_rows ) { $row4 = $result4->fetch_assoc(); }
    # Supplement price info ASC
    $result5 = $db_conn->query("SELECT tp.StartTime, tp.EndTime, tp.Price FROM Event as e INNER JOIN TicketPrices as tp	ON e.EventID = tp.EventID WHERE e.EventID = 2 AND tp.Type = 'Supplement' ORDER BY tp.StartTime ASC LIMIT 0, 1");
if( $result5 -> num_rows ) { $row5 = $result5->fetch_assoc(); }
?>

<div class="col-lg-12 hlpf_contentbox"> <!-- Ret class til-->
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
    	  <td width="10%">Adresse</td>
    		<td><?php echo $row['Location'] ?></td>
  	  </tr>
  	</table>

		<!-- Pladser-->
		<div class="row col-lg-12">
			<p><h4>Pladser</h4></p>
		</div>
    <table class="table table-striped">
    <tr>
		  <td width="40%">Pladser</td>
      <td>
        <?php if ($row['Seatmap'] == null || $row['Seatmap'] == "") { ?>
          Ingen information tilgængelig.
        <?php }else{
          echo $row['Seatmap'];
        } ?>
      </td>
    </tr>
    </table>

		<!-- Tilmelding og priser -->
		<div class="row col-lg-12">
			<p><h4>Tilmelding og priser</h4></p>
		</div>
		<table class="table table-striped">
      <tr>
  			<td width="70%">Tilmelding for medlemmer før prisstigning:</td><td></td>
      </tr>
      <tr>
  			<td width="40%"><?php echo date("d M Y - H:i:s", $row['StartTime']) ?></td>
        <td>Pris: <?php echo $row['Price'] ?></td>
      </tr>
      <tr>
  			<td width="70%">Tilmelding for alle før prisstigning:</td><td></td>
      </tr>
      <tr>
        <td width="40%"><?php echo date("d M Y - H:i:s", $row2['StartTime']) ?></td>
        <td>Pris: <?php echo $row2['Price'] ?></td>
      </tr>
      <tr>
        <td width="70%">Tilmelding for medlemmer efter prisstigning:</td><td></td>
      </tr>
      <tr>
        <td width="40%"><?php echo date("d M Y - H:i:s", $row3['StartTime']) ?></td>
        <td>Pris: <?php echo $row3['Price'] ?></td>
      </tr>
      <tr>
        <td width="70%">Tilmelding for alle efter prisstigning:</td><td></td>
      </tr>
      <tr>
        <td width="40%"><?php echo date("d M Y - H:i:s", $row4['StartTime']) ?></td>
        <td>Pris: <?php echo $row4['Price'] ?></td>
      </tr>
      <tr>
  			<td width="70%">Senest tilmelding for medlemmer:</td><td></td>
      </tr>
      <tr>
  			<td width="70%"><?php echo date("d M Y - H:i:s", $row3['EndTime']) ?></td><td></td>
      </tr>
      <tr>
  			<td width="70%">Senest tilmelding for alle:</td><td></td>
      </tr>
      <tr>
  			<td width="70%"><?php echo date("d M Y - H:i:s", $row4['EndTime']) ?></td><td></td>
      </tr>
		</table>

		<!-- Netværk -->
		<div class="row col-lg-12">
			<p><h4>Netværk:</h4></p>
		</div>
    <table class="table table-striped">
      <tr>
        <td width="40%">Lokalnetværk:</td>
        <td><?php echo $row['Network'] ?></td>
      </tr>
      <tr>
        <td width="40%">Internet:</td>
        <td>100 Mbit / 100 Mbit</td>
      </tr>
    </table>

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
    <table class="table table-striped">
      <tr>
        <td width="40%">Se regelset:</td>
        <td><?php echo $row['Rules'] ?></td>
      </tr>
    </table>

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

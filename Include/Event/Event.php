<?php
  # Latest event - Get the ID.
  $event = $db_conn->query("SELECT Event.Title, Event.EventID, Event.Poster, Event.StartDate, Event.EndDate, Event.Location, Event.Network, Event.SeatsOpen, Event.Seatmap, Event.Rules FROM Event ORDER BY EventID DESC LIMIT 1");
  if( $event -> num_rows ) { $eventrows = $event->fetch_assoc(); }
  # Member price info
  $SqlPricesMember = $db_conn->query("SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $eventrows["EventID"] . " AND TicketPrices.Type = 'Member'" );
  if( $SqlPricesMember -> num_rows ) { $pricesMember = $SqlPricesMember->fetch_assoc(); }
  # Nonmamber price info
  $SqlPricesNonMember = $db_conn->query("SELECT tp.StartTime, tp.EndTime, tp.Price FROM Event as e INNER JOIN TicketPrices as tp ON e.EventID = tp.EventID WHERE e.EventID = " . $eventrows["EventID"] . " AND tp.Type = 'Nonmember' ORDER BY tp.StartTime ASC LIMIT 0, 1");
  if( $SqlPricesNonMember -> num_rows ) { $pricesNonMember = $SqlPricesNonMember->fetch_assoc(); }
  # Supplement price info
  $result5 = $db_conn->query("SELECT tp.StartTime, tp.EndTime, tp.Price FROM Event as e INNER JOIN TicketPrices as tp	ON e.EventID = tp.EventID WHERE e.EventID = " . $eventrows["EventID"] . " AND tp.Type = 'Supplement' ORDER BY tp.StartTime ASC LIMIT 0, 1");
  if( $result5 -> num_rows ) { $row5 = $result5->fetch_assoc(); }

  if (true) {
    echo "<pre>";
    var_dump($SqlPricesMember);
    echo "</pre>";
  }
?>

<div class="col-lg-12 hlpf_contentbox"> <!-- Ret class til-->
  <div class="col-lg-8 row">
    <h2>Information</h2>
    <!-- Tid og sted -->
    <h3>Tid og sted</h3>
    <table class="table table-responsive">
      <thead>
        <tr>
          <th>Navn</th>
          <th>Start tidspunkt</th>
          <th>Slut tidspunkt</th>
          <th>Sted</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $eventrows['Title'] ?></td>
          <td><?php echo date("d M Y - H:i:s", $eventrows['StartDate']) ?></td>
          <td><?php echo date("d M Y - H:i:s", $eventrows['EndDate']) ?></td>
          <td><?php echo $eventrows['Location'] ?></td>
        </tr>
      </tbody>
    </table>

    <!-- Pladser-->
    <h4>Pladser</h4>
    <table class="table table-striped">
    <tr>
      <td>Pladser</td>
      <td>
        <img class="img-responsive" src="Images/Temp/seatmaphere.png">
        <?php if ($eventrows['Seatmap'] == null || $eventrows['Seatmap'] == "") {
          echo "Ingen information tilgængelig.";
        } else {
          echo $eventrows['Seatmap'];
        } ?>
      </td>
    </tr>
    </table>
    <!-- Tilmelding og priser -->
    <h4>Tilmelding og priser</h4>
    <table class="table table-responsive table-striped table-hover">
    <thead>
      <tr>
        <th><!-- Empty field. --></th>
        <?php
          for ($i=0; $i < $SqlPricesMember -> num_rows; $i++) {
            echo "<th>" . $TEXT_HERE . "</th>";
          }
        ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Medlem</th>
        <?php
          for ($i=0; $i < $pricerowscounterthingy; $i++) {
            echo "<th>" . $TEXT_HERE . "</th>";
          }
        ?>
      </tr>
      <tr>
        <th>Ikke medlem</th>
        <?php
          for ($i=0; $i < $pricerowscounterthingy; $i++) {
            echo "<th>" . $TEXT_HERE . "</th>";
          }
        ?>
      </tr>
    </tbody>
    </table>

    <table class="table table-striped">
      <tr>
        <th>Tilmelding for medlemmer - Før prisstigning</th>
        <th>Pris</th>
      </tr>
      <tr>
        <td><?php echo date("d M Y - H:i:s", $pricesMember['StartTime']) ?></td>
        <td><?php echo $pricesMember['Price'] ?> DKK</td>
      </tr>
      <tr>
        <th>Tilmelding for medlemmer - Efter prisstigning</th>
        <th>Pris</th>
      </tr>
      <tr>
        <td><?php echo date("d M Y - H:i:s", $row3['StartTime']) ?></td>
        <td><?php echo $row3['Price'] ?> DKK</td>
      </tr>
      <tr>
        <th>Tilmelding for alle - Før prisstigning</th><th>Pris</th>
      </tr>
      <tr>
        <td><?php echo date("d M Y - H:i:s", $row2['StartTime']) ?></td>
        <td><?php echo $row2['Price'] ?> DKK</td>
      </tr>
      <tr>
        <th>Tilmelding for alle - Efter prisstigning</th>
        <th>Pris</th>
      </tr>
      <tr>
        <td><?php echo date("d M Y - H:i:s", $row4['StartTime']) ?></td>
        <td><?php echo $row4['Price'] ?> DKK</td>
      </tr>
      <tr>
        <td>Senest tilmelding for medlemmer</td><td></td>
      </tr>
      <tr>
        <td><?php echo date("d M Y - H:i:s", $row3['EndTime']) ?></td><td></td>
      </tr>
      <tr>
        <td>Senest tilmelding for alle</td><td></td>
      </tr>
      <tr>
        <td><?php echo date("d M Y - H:i:s", $row4['EndTime']) ?></td><td></td>
      </tr>
    </table>

    <!-- Netværk -->
    <h4>Netværk</h4>
    <table class="table table-striped">
      <tr>
        <td>Lokalnetværk</td>
        <td><?php echo $eventrows['Network'] ?></td>
      </tr>
      <tr>
        <td>Internet</td>
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
    <h4>Regler for arrangementet kan ses <a href="?page=<?php echo $eventrows['Rules'] ?>"><i>her</i></a></h4>
    <hr>
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

    <!-- Arrangør -->
    <h4>Arrangør</h4>
    <p>
      HLParty arrangeres af foreningen Hovedstadens LanParty Forening. Foreningen er en folkeoplysende forening, godkendt i Hillerød kommune. Foreningens formål er (uddrag af vedtægter):
      <br>
      <br>
      "Foreningens formål er at samle unge mennesker, primært i hovedstadsområdet, med interesse for computere og IT, for derved at medvirke til at styrke medlemmernes sociale kompetencer, skabe kontakt på tværs af kommunegrænser, etnicitet, køn og alder og styrke medlemmernes almennyttige IT kundskaber til glæde for den enkelte, såvel som for samfundet."
      <br>
      <br>
      Overskud fra et arrangement går til drift af foreningen (f.eks. webhotel, vedligeholdelse og nyinkøb af servere, switche, netværkskabler mv.), samt til at sikre fremtidige arrangementer.
    </p>
    <p>Læs mere om foreningen bag HLParty på adressen <a href="https://hlpf.dk" target="_blank">https://hlpf.dk</a>.</p>
  </div>

  <div class="col-lg-4">
    <div class="col-lg-12">
    <?php if ($eventrows['Poster'] == null || $eventrows['Poster'] == "") {
      echo '<img class="img-responsive" src="Images/EventPoster/noposter.png">';
    }else{
      echo '<img class="img-responsive" src="Images/EventPoster/'.$row['Poster'] . '">';
    } ?>
    </div>
  </div>
</div>

<?php

  $event -> close();
?>

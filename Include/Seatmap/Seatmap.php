<!-- jQuery UI -->
<script type="text/javascript" src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- jQuery-Seat-Charts -->
<script type="text/javascript" src="submodules/jQuery-Seat-Charts/jquery.seat-charts.js"></script>
<!-- Mini-version <script type="text/javascript" src="submodules/jQuery-Seat-Charts/jquery.seat-charts.min.js"></script> -->
<?php
  if (isset($_GET['seatmap']) AND $_GET['seatmap'] == "Generate") {

  } elseif (isset($_GET['seatmap']) AND $_GET['seatmap'] == "New") {
    # code...
  } else {
    // TODO: UPDATE QUERY, GET ALL THE INFO.
    $result = $DBConn->query("SELECT * FROM  Seatmap ORDER BY  SeatmapID DESC LIMIT 1");
    if ($result -> num_rows) {
      $row = $result->fetch_assoc();
      #echo print_r($row);
      $seats = str_split($row['SeatString'], $row['Width']);
    }
  }
?>
<script type="text/javascript">
$(document).ready(function() {
  var chosenSeats = 0;
  var sc = $('#seat-map').seatCharts({
    map: [
      <?php
      for ($i=0; $i < count($seats); $i++) {
        echo "'";
        $i_iteration = 0;
        for ($j=0; $j < strlen($seats[$i]); $j++) {
          if (substr($seats[$i], $j, 1) != "_") {
            $i_iteration += 1;
            // See https://github.com/mateuszmarkowski/jQuery-Seat-Charts#map
            // Grab a single seat in the row.    ## a[ID,LABEL]
            echo substr($seats[$i], $j, 1) . "[" . ($i + 1) . "_" . $i_iteration . "," . $i_iteration . "]";
          } else { echo substr($seats[$i], $j, 1); }
        }
        echo "',";
      }
      unset($seats, $result, $i_iteration);
      ?>],
    seats: {
      a: {
        price: 10,
        category : 'Normal plads',
        description : 'Normal plads.'
      },
      c: {
        price: 20,
        category : 'Crew plads',
        classes: 'seatStyle_Crew',
        description : 'Kun til crew.'
      }
    },
    legend : {
      node  : $('#seat-map-legend'),
      items : [
        [ 'a', 'available', 'Fri plads' ],
        <?php if (strpos($row['SeatString'], "c")) { echo "[ 'c', 'unavailable', 'Crew plads'],"; } ?>
        [ 'a', 'unavailable', 'Optaget' ]
      ]
    },
    click: function () {
      if (this.status() == 'available') {
        chosenSeats++;
        if (chosenSeats > 10) {
          alert("I am an alert box!");
        }
        return 'selected';
      } else if (this.status() == 'selected') {
        chosenSeats--;
        return 'available';
      } else if (this.status() == 'unavailable') {
        //seat has been already booked
        return 'unavailable';
      } else {
        return this.style();
      }
    }
  });
  //Make all available 'c' seats unavailable
  sc.find('c.available').status('unavailable');

  //Make unavailable seats, unavailable...
  <?php
    $query = "SELECT Tickets.SeatNumber FROM Tickets INNER JOIN TicketPrices ON Tickets.TicketPriceID = TicketPrices.TicketPriceID INNER JOIN Event ON TicketPrices.EventID = Event.EventID WHERE Event.EventID = 2";
    $result = $DBConn->query($query);
    if ($result -> num_rows) {
      echo "sc.get([";
      while ($row = $result->fetch_assoc()) {
        echo "'" . $row['SeatNumber'] . "',";
      }
      echo "]).status('unavailable')";
    }
    unset($row, $query, $result);
  ?>
});
</script>

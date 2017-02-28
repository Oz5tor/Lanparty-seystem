<?php

/*
TTTTTTT EEEEEEE MM   MM PPPPPP
   T    E       M M M M P    PP
   T    EEEE    M  M  M PPPPPP
   T    E       M     M P
   T    EEEEEEE M     M P
*/
$_SESSION['EventPrice'] = 350;
$_SESSION['EventId'] = 35;
/*
PPPPPP  RRRRRR  IIIIIII   CCCCC EEEEEEE
P    PP R    RR    I     C      E
PPPPPP  RRRRRR     I    C       EEEE
P       R RR       I     C      E
P       R  RR   IIIIIII   CCCCC EEEEEEE
*/

if (!isset($_SESSION['UserID'])) {
  header("Location: index.php");
}
$legit = true; // If this is ever false, kick them out...
if (isset($_POST['checkoutCart']) AND !empty($_POST['checkoutCart'])) {
  $json = json_decode($_POST['checkoutCart']);
  if (count($json) > $_GLOBAL['g_max_seats_selection']) {
    header("Location: index.php?page=Buy"); // Hacker detected! Terminate!
  } else {
    $query = "SELECT Seatmap.Seats
        FROM Seatmap
        INNER JOIN Event
          ON Event.Seatmap = Seatmap.SeatmapID
        WHERE Event.EventID = " . $_SESSION['EventId'];
    $seats = $db_conn->query($query)->fetch_assoc();
    for ($i=0; $i < count($json); $i++) {
      $seatNumber = preg_replace("(cart-item-)", "", $json[$i]);
      $query = "SELECT Seatmap.Seats
          FROM Seatmap
          INNER JOIN Event
            ON Event.Seatmap = Seatmap.SeatmapID
          WHERE Event.EventID = " . $_SESSION['EventId'];
      $seats = $db_conn->query($query)->fetch_assoc();
      if ($seatNumber <= 0 OR $seatNumber > $seats['Seats']) {
        $legit = false;
      } else {
        $query = "SELECT Tickets.SeatNumber
          FROM Tickets
          WHERE Tickets.EventID = " . $_SESSION['EventId'] . "
            AND Tickets.SeatNumber = " . $seatNumber;
        $checkSeatNumber = $db_conn->query($query)->fetch_assoc();
        if (!$checkSeatNumber == "") {
        } // else { Everything is okay. }
      }
    }
  }
  if (count($json) == 1) {
    $seat = preg_replace("(cart-item-)", "", $json[0]);
    $timeAddTen = time() + (10 * 60);
    $query = "INSERT INTO hlparty.Tickets (UserID, EventID, SeatNumber, OderedDate, RevokeDate) VALUES (" . $_SESSION['UserID'] . ", " . $_SESSION['EventId'] . ", " . $seat . ", " . time() . ", " . $timeAddTen . ")";
    $_SESSION['SQLStatus'] = $db_conn->query($query);
  }
  for ($i=0; $i < count($json); $i++) {
    $timeAddTen = time() + (10 * 60);
    $query = "INSERT INTO hlparty.Tickets (UserID, EventID, SeatNumber, OderedDate, RevokeDate) VALUES (" . $_SESSION['UserID'] . ", " . $_SESSION['EventId'] . ", " . $seat . ", " . time() . ", " . $timeAddTen . ")";
    $_SESSION['SQLStatus'] = $db_conn->query($query);
  }
} else {
  include_once 'class/seatmap.php';
  $query = "SELECT Seatmap.Width AS Width, Seatmap.SeatString AS SeatString
      FROM Event
      INNER JOIN Seatmap
        ON Event.Seatmap = Seatmap.SeatmapID
      ORDER BY Event.StartDate DESC LIMIT 1";
  $theEvent = $db_conn->query($query)->fetch_assoc();
?>

<div class="hlpf_contentbox col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <div id="map" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div id="Seatmap"></div>
  </div>
  <div id="map-extras">
    <div id="Seatmap-Legend"></div>
    <div id="Seatmap-Cart">
      <h4>Dit valg (<span id="Seatmap-Counter">0</span>)</h4>
      <ul id="Seatmap-Cart-Items"></ul>
      <p>Total pris: <span id="Seatmap-Total">0</span>,-</p>
      <button id="CheckoutButton" class="btn btn-default" onclick="checkoutButton()">Køb &raquo;</button>
    </div>
  </div>
</div>
<form id="hiddenForm" class="hidden" action="" method="POST">
  <input type="hidden" id="checkoutCart" name="checkoutCart" class="hidden">
</form>
<script src="JS/seat-charts/jquery.seat-charts.min.js"></script>

<script type="text/javascript">
var counter = 0;
$(document).ready(function() {
  var seatsSelected = 0,
      $cart = $('#Seatmap-Cart-Items'),
      $counter = $('#Seatmap-Counter'),
      $total = $('#Seatmap-Total'),
      sc = $('#Seatmap').seatCharts({
      map: [<?php seatmap_generation($theEvent['SeatString'], $theEvent['Width']) ?>],
      seats: {
        a: {
          price: <?php if (isset($_SESSION['EventPrice'])) { echo $_SESSION['EventPrice']; } ?>,
          category: 'Sæde' // This will be shown to the costumer when they pick a seat.
        },
        A: { classes: 'seatStyle_Arkade' },
        s: { classes: 'seatStyle_Stage' },
        c: { classes: 'seatStyle_Crew' },
        k: { classes: 'seatStyle_Kiosk' }
      },
      legend : {
        node  : $('#Seatmap-Legend'),
        items : [
          [ 'a', 'available', 'Fri plads' ],
          [ 'a', 'unavailable', 'Optaget' ],
          [ 'c', 'unavailable', 'Crew plads'],
          [ 's', 'unavailable', 'Scene / Storskærm'],
          [ 'A', 'unavailable', 'Arkade'],
          [ 'k', 'unavailable', 'Kiosk'],
        ]
      },
      click: function () {
        if (this.status() == 'available') {
          seatsSelected++;
          if (seatsSelected >= <?php
          if (isset($_GLOBAL['g_max_seats_selection'])) {
            echo ($_GLOBAL['g_max_seats_selection'] + 1);
          } else {
            echo "11";
          } ?>) {
            alert("Du kan højst vælge <?=$_GLOBAL['g_max_seats_selection'];?> sæder.");
            seatsSelected--;
            return 'available';
          } else {
            //let's create a new <li> which we'll add to the cart items
            $('<li>'+this.data().category+' #'+this.settings.label+': <b>'+this.data().price+'DKK</b></li>')
              .attr('id', 'cart-item-'+this.settings.id)
              .data('seatId', this.settings.id)
              .appendTo($cart);

            $counter.text(sc.find('selected').length+1);
            $total.text(calculateTotal(sc)+this.data().price);

            return 'selected';
          }
        } else if (this.status() == 'selected') {
          seatsSelected--;
          if (seatsSelected < 0) {
            seatsSelected = 0;
          }
          //update the counter
          $counter.text(sc.find('selected').length-1);
          //and total
          $total.text(calculateTotal(sc)-this.data().price);
          //remove the item from our cart
          $('#cart-item-'+this.settings.id).remove();
          //seat has been vacated
          return 'available';
        } else if (this.status() == 'unavailable') {
          //seat has been already booked
          return 'unavailable';
        } else {
          return this.style();
        }
      }
  });
  // Make all these seats unavailable.
  // Unless you want people to buy them, ofcourse.
  sc.find('A.available').status('unavailable');
  sc.find('c.available').status('unavailable');
  sc.find('s.available').status('unavailable');
  sc.find('k.available').status('unavailable');
  <?php
    $query = "SELECT Tickets.SeatNumber FROM Tickets WHERE Tickets.EventID = " . $_SESSION['EventId'];
  ?>
  sc.get(<?php echo "" ?>).status('unavailable');
});

function calculateTotal(sc) {
  var total = 0;
  //basically find every selected seat and sum its price
  sc.find('selected').each(function () {
    total += this.data().price;
  });
  return total;
}

function checkoutButton() {
  var lis = document.getElementById("Seatmap-Cart-Items").
        getElementsByTagName("li");
  var arr = [];
  for (var i = lis.length - 1; i >= 0; i--) {
    arr.push(lis[i].id);
  }
  var json = JSON.stringify(arr);
  document.getElementById('checkoutCart').value = json;
  document.getElementById('hiddenForm').submit();
}

</script>
<?php } // End else ?>

<?php

$_SESSION['EventPrice'] = 350;

if (!isset($_SESSION['UserID'])) {
  header("Location: index.php");
}
$event = $db_conn->query("SELECT e.EventID, e.Seatmap FROM Event as e ORDER BY e.EventID DESC LIMIT 1");
$event = $event->fetch_assoc();
if (isset($_POST['checkoutCart']) AND !empty($_POST['checkoutCart'])) {
  /*
    STEP TWO - WHO SITS WHERE?
  */
  $json = json_decode($_POST['checkoutCart']);
  if (count($json) > $_GLOBAL['g_max_seats_selection']) {
    header("Location: index.php?page=Buy"); // Hacker detected! Terminate!
  } else {
    $query = "SELECT Seatmap.Seats
        FROM Seatmap
        INNER JOIN Event
          ON Event.Seatmap = Seatmap.SeatmapID
        WHERE Event.EventID = " . $event['EventID'];
    $seats = $db_conn->query($query)->fetch_assoc();
    for ($i=0; $i < count($json); $i++) {
      $seatNumber = preg_replace("(cart-item-)", "", $json[$i]);
      if ($seatNumber <= 0 OR $seatNumber > $seats['Seats']) {
        // Chosen seat is somehow less than 0 or more than there is.
        header("Location: index.php?page=Buy");
      } else {
        $query = "SELECT Tickets.SeatNumber
          FROM Tickets
          WHERE Tickets.EventID = " . $event['EventID'] . "
            AND Tickets.SeatNumber = " . $db_conn->real_escape_string($seatNumber);
        $checkSeatNumber = $db_conn->query($query)->fetch_assoc();
        if ($checkSeatNumber === 0) {
        } // else { Everything is okay. }
      }
    }
  }
  if (count($json) == 1) {
    // Only one seat chosen...
    /*
      CHECK IF USER HAS A TICKET ALREADY.
      \/ \/ \/ \/
    */
    $query = "SELECT Tickets.UserID FROM Tickets WHERE Tickets.EventID = ". $event['EventID'];
    $result = $db_conn->query($query)->fetch_assoc();
    if (!empty($result)) {
      // User has a ticket.
      $_SESSION['MsgForUser'] = "Du har allerede en bilet til dette arrangement";
      header("Location: index.php?page=Buy");
    } else {
      $seat = preg_replace("(cart-item-)", "", $json[0]);
      $query = "INSERT INTO hlparty.Tickets (UserID, EventID, SeatNumber, OderedDate)
          VALUES (" . $_SESSION['UserID'] . ", " . $event['EventID'] . ", " . $seat . ", " . time() . ")";
      $_SESSION['SQLStatus'] = $db_conn->query($query);
      /*
        SEND USER TO PAYPAL HERE?
      */
      echo "Send nudes to PayPal";
    }
  } else {
    sort($json);
?>
<div class="hlpf_contentbox row col-lg-12">
  <h1>Hvem skal side hvor?</h1>
  <p>Skriv brugernanvet på den person der skal side på den enkelte plads.</p>
  <div class="row" id="namesForSeats">
<?php
    for ($i=0; $i < count($json); $i++) {
      $seat = preg_replace("(cart-item-)", "", $json[$i]);
?>
    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-12">
      <label class="control-label" for="<?= $seat ?>">Sæde #<?= $seat; ?></label>
      <input class="form-control" id="<?= $seat ?>" type="text">
    </div>
<?php } // end for loop?>
  </div>
  <div class="col-lg-12">
    <form id="setNamesForSeats" class="" action="" method="POST">
      <input type="text" id="nameForSeat" name="nameForSeat">
    </form>
    <button onclick="checkName()" class="btn btn-primary" style="float:right">Næste &raquo;</button>
  </div>
</div>
<script type="text/javascript">
function checkName() {
  var lis = document.getElementById("namesForSeats").getElementsByTagName("input");
  var arr = [];
  for (var i = lis.length - 1; i >= 0; i--) {
    arr.push(lis[i].id + ":" + lis[i].value);
  }
  var json = JSON.stringify(arr);
  document.getElementById("nameForSeat").value = json;
  document.getElementById("setNamesForSeats").submit();
}
</script>
<?php
  }
} elseif (isset($_POST['nameForSeat']) AND !empty($_POST['nameForSeat'])) {
  /*
    STEP THREE - CONFIRMATION AND FINAL CHECK BEFORE PAYPAL
    CHECK IF ONE OR MORE USERS ALREADY HAS A TICKET
  */
  include_once 'class/GetUsernameFromID.php';
  $jsonSeats = json_decode($_POST['nameForSeat']);
  $arr = [];
  for ($i=0; $i < count($jsonSeats); $i++) {
    $arr[substr($jsonSeats[$i], 0, 3)] = substr($jsonSeats[$i], 4);
  }
  if(count(array_unique($arr))<count($arr)) {
    //
    // Same name was used twice
    //
  } else {
    //
    // All names are unique, continue
    //

  }
  #echo "Send nudes to PayPal";
} else {
  /*
    STEP ONE - CHOOSE SEATS
  */
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
    $query = "SELECT Tickets.SeatNumber FROM Tickets WHERE Tickets.EventID = " . $event['EventID'];
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
  var lis = document.getElementById("Seatmap-Cart-Items").getElementsByTagName("li");
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

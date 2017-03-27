<?php

$eventPrice = 0;

if (!isset($_SESSION['UserID'])) {
  $_SESSION['MsgForUser'] = "Du skal være logget ind for at se denne side.";
  header("Location: index.php");
  exit;
}

require 'class/PayPalCheckout.php';
$event = $db_conn->query("SELECT e.EventID, e.Seatmap, e.Title FROM Event as e
                          ORDER BY e.EventID DESC LIMIT 1")->fetch_assoc();

$query = "SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] .
    " AND TicketPrices.Type = 'Ikke medlem' AND " .
    time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
$result = $db_conn->query($query)->fetch_assoc();
$eventPrice = $result['Price'];

if (isset($_POST['checkoutCart']) AND !empty($_POST['checkoutCart'])) {
  /*
    STEP TWO - WHO SITS WHERE?
  */
  // Decode json...
  $json = json_decode($_POST['checkoutCart']);
  if (count($json) > $_GLOBAL['g_max_seats_selection']) {
    // Hacker detected! Terminate!
    $_SESSION['MsgForUser'] = "Du har valgt " . count($json) .
        " sæder, men vi tillader kun at vælge " .
        $_GLOBAL['g_max_seats_selection'] . " sæder.";
    header("Location: index.php?page=Buy");
    exit;
  } else {
    // How many seats does the current seatmap have?
    $query = "SELECT Seatmap.Seats
        FROM Seatmap
        INNER JOIN Event
          ON Event.Seatmap = Seatmap.SeatmapID
        WHERE Event.EventID = " . $event['EventID'];
    $seats = $db_conn->query($query)->fetch_assoc();
    for ($i=0; $i < count($json); $i++) {
      $seatNumber = preg_replace("(cart-item-)", "", $json[$i]);
      if ($seatNumber <= 0 OR $seatNumber > $seats['Seats']) {
        // Chosen seat is somehow less than 0 or more than there are.
        $_SESSION['MsgForUser'] = "Fejl kode: 0x000D0001.";
        header("Location: index.php?page=Buy");
        exit;
      } else {
        $query = "SELECT count(Tickets.SeatNumber) AS seats
            FROM Tickets
            WHERE Tickets.EventID = " . $event['EventID'] . "
              AND Tickets.SeatNumber = " . $db_conn->real_escape_string($seatNumber) . "
              AND Tickets.RevokeDate IS NULL";
        $checkSeatNumber = $db_conn->query($query)->fetch_assoc();
        if ($checkSeatNumber['seats'] >= 1) {
          $_SESSION['MsgForUser'] = "Sæde " . $seatNumber . " er optaget.";
          header("Location: index.php?page=Buy");
          exit;
        } // else { Everything is okay. }
      }
    }
  }
  $query = "SELECT Tickets.UserID FROM Tickets
      WHERE Tickets.EventID = ". $event['EventID'] .
      " AND Tickets.UserID = ". $_SESSION['UserID'] .
      " AND Tickets.RevokeDate IS NULL";
  $result = $db_conn->query($query);
  if ($result -> num_rows) {
    // User has a ticket.
    $_SESSION['MsgForUser'] = "Du har allerede en billet til dette arrangement og kan derfor ikke købe flere billetter.";
    header("Location: index.php?page=Buy");
    exit;
  }
  if (empty($json)) {
    // Empty post data.
    header("Location: index.php?page=Buy");
    exit;
  }
  if (count($json) == 1) {
    // Only one seat chosen...
    $seat = preg_replace("(cart-item-)", "", $json[0]);
    $query = "INSERT INTO hlparty.Tickets (UserID, EventID, SeatNumber, OderedDate)
        VALUES (" . $_SESSION['UserID'] . ", " . $event['EventID'] . ", " . $seat . ", " . time() . ")";
    if (!$db_conn->query($query)) {
      $_SESSION['MsgForUser'] = "Fejl ved resevering af sæde...";
      header("Location: index.php?page=Buy");
      exit;
    }
    /*
      SINGLE TICKET
    */
    $ticketPrice = 0;
    $query = "SELECT UserMembership.UserID FROM UserMembership WHERE UserID = " . $_SESSION['UserID'] . " AND UserMembership.Year = " . date("Y");
    if ($db_conn->query($query)->num_rows) {
      $query = "SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] . " AND TicketPrices.Type = 'Medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
      if ($ticket = $db_conn->query($query)->fetch_assoc()) {
        $ticketPrice = $ticket['Price'];
      } else {
        $_SESSION['MsgForUser'] = "Fejl kode: 0x000D0011";
        $query = "DELETE FROM Tickets WHERE UserID = '" . $_SESSION['UserID'] . "' AND OderedDate IS NOT NULL AND RevokeDate = NULL";
        $db_conn->query($query);
        header("Location: index.php?page=Buy");
        exit;
      }
    } else {
      $query = "SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] . " AND TicketPrices.Type = 'Ikke medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
      if ($ticket = $db_conn->query($query)->fetch_assoc()) {
        $ticketPrice = $ticket['Price'];
      } else {
        $_SESSION['MsgForUser'] = "Fejl kode: 0x000D0012";
        $query = "DELETE FROM Tickets WHERE UserID = '" . $_SESSION['UserID'] . "' AND OderedDate IS NOT NULL AND RevokeDate = NULL";
        $db_conn->query($query);
        header("Location: index.php?page=Buy");
        exit;
      }
    }
    $Cart[] = [
      'Price' => $ticketPrice,
      'Quantity' => 1,
      'Currency' => 'DKK',
      'Name' => "Billet til " . $event['Title'],
      'Desc' => "Sæde #" . $seat
    ];
    $_SESSION['BuyingTicketSingle'] = 1;
    $_SESSION['Cart'] = $Cart;
    PayPalCheckOut($Cart, $db_conn, "index.php?page=Buy&subpage=PaypalConfirm", uniqid(), $ROOTURL);
  } else {
    /*
      MULTIPLE SEATS SELECTED
    */
    sort($json);
    $timeNow = time();
    for ($i=0; $i < count($json); $i++) {
      $query = "INSERT INTO hlparty.Tickets (UserID, EventID, SeatNumber, OderedDate)
          VALUES (" . $_SESSION['UserID'] . ", " . $event['EventID'] . ", " . substr($json[$i], -3) . ", " . $timeNow . ")";
      $db_conn->query($query);
    }
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
      <label class="control-label" for="<?= $seat ?>">Sæde #<?= $seat ?></label>
      <input class="form-control" id="<?= $seat ?>" type="text">
    </div>
<?php } // end for loop ?>
  </div>
  <button onclick="checkName()" class="btn btn-primary">Næste &raquo;</button>
  <form id="setNamesForSeats" class="hidden" action="" method="POST">
    <input type="hidden" id="nameForSeat" name="nameForSeat">
  </form>
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
  /*
    arr = [
      Seatnumber => Username
    ]
  */
  if(count( array_unique($arr)) < count($arr) ) {
    // Same name was used twice
    $_SESSION['MsgForUser'] = "En person kan ikke have to sæder...";
    // Remove the resevation, so the user can pick the seats again.
    $query = "DELETE FROM hlparty.Tickets WHERE Tickets.UserID = " . $_SESSION['UserID'] .
        " AND Tickets.EventID = " . $event['EventID'] .
        " AND Tickets.RevokeDate IS NULL AND Tickets.TransactionCode IS NULL";
    $db_conn->query($query);
    header("Location: index.php?page=Buy");
    exit;
  } else {
    // All names are unique, continue
    // Check if the users exist...
    $naughtyUsers = [];
    foreach ($arr as $key => $value) {
      $query = "SELECT Username FROM Users WHERE Username = '" . $value . "'";
      $result = $db_conn->query($query);
      if (!$result -> num_rows) {
        $naughtyUsers[] = $value;
      }
    }
    if (!empty($naughtyUsers)) {
      $_SESSION['MsgForUser'] = "Følgende brugere eksistere ikke: ";
      for ($i=0; $i < count($naughtyUsers); $i++) {
        $_SESSION['MsgForUser'] = $_SESSION['MsgForUser'] . $naughtyUsers[$i] . " ";
      }
      // Remove the resevation, so the user can pick the seats again.
      $query = "DELETE FROM hlparty.Tickets WHERE Tickets.UserID = " . $_SESSION['UserID'] .
          " AND Tickets.EventID = " . $event['EventID'] .
          " AND Tickets.RevokeDate IS NULL";
      $db_conn->query($query);
      header("Location: index.php?page=Buy");
      exit;
    }
    // Check if the users already have tickets...
    $naughtyUsers = [];
    foreach ($arr as $key => $value) {
      $query = "SELECT Tickets.UserID FROM Tickets WHERE Tickets.EventID = ".
          $event['EventID'] . " AND Tickets.RevokeDate IS NULL AND Tickets.TransactionCode IS NOT NULL AND Tickets.UserID = " . GetIDFromUsername($value, $db_conn);
      $result = $db_conn->query($query);
      if ($result -> num_rows) {
        $naughtyUsers[] = $value;
      }
    }
    if (!empty($naughtyUsers)) {
      $_SESSION['MsgForUser'] = "Følgende brugere har allerede en billet: ";
      for ($i=0; $i < count($naughtyUsers); $i++) {
        $_SESSION['MsgForUser'] = $_SESSION['MsgForUser'] . $naughtyUsers[$i] . " ";
      }
      // Remove the resevation, so the user can pick the seats again.
      $query = "DELETE FROM hlparty.Tickets WHERE Tickets.UserID = " . $_SESSION['UserID'] .
          " AND Tickets.EventID = " . $event['EventID'] .
          " AND Tickets.RevokeDate IS NULL";
      $db_conn->query($query);
      header("Location: index.php?page=Buy");
      exit;
    }
    echo "<pre>";
    print_r($naughtyUsers);
    print_r($arr);
    echo "</pre>";
    foreach ($arr as $key => $value) {
      $query = "UPDATE Tickets SET Tickets.UserID = " . GetIDFromUsername($value, $db_conn) .
        " WHERE Tickets.UserID = " . $_SESSION['UserID'] .
          " AND Tickets.RevokeDate IS NULL AND Tickets.TransactionCode IS NULL AND Tickets.SeatNumber = " . $key;
          $_SESSION['SQL'] = $query;
      $db_conn->query($query);
    }
    $cart = [];
    foreach ($arr as $key => $value) {
      $ticketPrice = 0;
      $query = "SELECT UserMembership.UserID FROM UserMembership WHERE UserID = " . GetIDFromUsername($value, $db_conn) . " AND UserMembership.Year = " . date("Y");
      if ($db_conn->query($query)->num_rows) {
        $query = "SELECT TicketPrices.Price FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] .
            " AND TicketPrices.Type = 'Medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
        if ($ticket = $db_conn->query($query)->fetch_assoc()) {
          $ticketPrice = $ticket['Price'];
        } else {
          $_SESSION['MsgForUser'] = "Fejl kode: 0x000D0021";
          header("Location: index.php?page=Buy");
          exit;
        }
      } else {
        $query = "SELECT TicketPrices.Price FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] .
            " AND TicketPrices.Type = 'Ikke medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
        if ($ticket = $db_conn->query($query)->fetch_assoc()) {
          $ticketPrice = $ticket['Price'];
        } else {
          $_SESSION['MsgForUser'] = "Fejl kode: 0x000D0022";
          header("Location: index.php?page=Buy");
          exit;
        }
      }
      $cart[] = [
        'Price' => $ticketPrice,
        'Quantity' => 1,
        'Currency' => 'DKK',
        'Name' => "Billet til " . $event['Title'],
        'Desc' => "Sæde #" . $key . " til " . $value
      ];
    }
    $_SESSION['BuyingTicketMulti'] = 1;
    $_SESSION['Cart'] = $cart;
    PayPalCheckOut($cart, $db_conn, "index.php?page=Buy&subpage=PaypalConfirm", uniqid(), $ROOTURL);
  }
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

<div class="hlpf_contentbox row">
  <div id="map" class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
    <div id="Seatmap"></div>
  </div>
  <div id="map-extras" class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
    <div id="Seatmap-Legend"></div>
    <div id="Seatmap-Cart">
      <h4>Dit valg (<span id="Seatmap-Counter">0</span>)</h4>
      <ul id="Seatmap-Cart-Items"></ul>
      <p>Total pris: <span id="Seatmap-Total">0</span>,-</p>
      <p><i><small>
        Prisen er vejledende og kan variere alt efter om du er medlem af foregningen.
        Den endelige pris vil blive vist hos PayPal før du betaler.
      </small></i></p>
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
          price: <?php if (isset($eventPrice)) { echo $eventPrice; } ?>,
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
          [ 'a', 'selected', 'Dit valg' ],
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
  sc.get(<?php
        $query = "SELECT  Tickets.SeatNumber
            FROM  Tickets
            WHERE Tickets.EventID = ". $event['EventID'] ."
            AND Tickets.RevokeDate IS NULL";
        echo "[";
        if ($result = $db_conn->query($query)) {
          while ($row = $result->fetch_assoc()) {
            echo "'" . sprintf('%03d', $row['SeatNumber']) . "',";
          }
        }
        echo "]";
          ?>).status('unavailable');
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

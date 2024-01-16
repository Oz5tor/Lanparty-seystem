<?php

$eventPrice = 0;

if (!isset($_SESSION['UserID'])) {
  $_SESSION['MsgForUser'] = "Du skal være logget ind for at se denne side.";
  header("Location: index.php");
  exit;
}

require 'class/PayPalCheckout.php';
$eventID = $_GLOBAL["EventID"];
$event = $db_conn->query("SELECT e.EventID, e.Seatmap, e.Title FROM Event as e WHERE EventID = '$eventID' ORDER BY e.EventID DESC LIMIT 1")->fetch_assoc();

# SQL if more than only member tickets is avaible else use active none memberprice
$OnlyOneTypeActive = $db_conn->query("SELECT * FROM TicketPrices WHERE ". time() ." BETWEEN StartTime AND EndTime")->num_rows;

if($OnlyOneTypeActive == 1){
    $query = "SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] . " AND TicketPrices.Type = 'Medlem' AND " .
    time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
}else{
    $query = "SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] . " AND TicketPrices.Type = 'ikke-medlem' AND " .
    time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
}
$result = $db_conn->query($query)->fetch_assoc();
$eventPrice = $result['Price'];

if (isset($_POST['checkoutCart']) AND !empty($_POST['checkoutCart'])) {
  # =======================================
  # Discount Code AVG code Start


  if (isset($_POST['discontcode']) AND !empty($_POST['discontcode'])) {
    $DiscountCodes = $db_conn->real_escape_string(trim($_POST['discontcode']));
    $CodesArr = explode(',', $DiscountCodes);
    $CodesArr = array_unique($CodesArr);
    print_r($CodesArr);
    #print_r($CodesArr);
    $CodesDontExist = 0;
    foreach ($CodesArr as $code => $value) { // Check if codes exists in Table
      $query = "SELECT * FROM discountcodes WHERE Code = '$value' limit 1";
      $Cresult = $db_conn->query($query);
      #print_r($Cresult);
      #echo $row_cnt = $Cresult->num_rows;
      if ($Cresult->num_rows == 0) {
        $CodesDontExist = 1;
      }
    }
    #echo '$CodesDontExist: '.$CodesDontExist;
    if ($CodesDontExist == 0) {
      $UsedCodes = array();
      $TotalDiscount = 0;

      foreach ($CodesArr as $code => $value) {
        $query = "SELECT * FROM discountcodes WHERE Code = '$value' limit 1";
        $CodeFetch = $db_conn->query($query)->fetch_assoc();

        $LimitUse   = $CodeFetch['LimitUse'];
        $Used       = $CodeFetch['Used'];
        $Amount     = $CodeFetch['Amount'];
        
        if ($LimitUse > $Used) {
          // Discount valid
          $TotalDiscount = $TotalDiscount+$Amount;
          $json = json_decode($_POST['checkoutCart']);
          $avgDiscount = $TotalDiscount / count($json);
          $UsedCodes[] .= $value;
        }else {
          // Discount not valid
          $_SESSION['MsgForUser'] = "En eller flere af dine koder er ikke gyldig";
          header("Location: index.php?page=Buy");
          exit;
        }
      }
      $avgDiscount2 = round($avgDiscount, 0);
      //print_r($avgDiscount2);
      $_SESSION['UsedCodes'] = $UsedCodes;
      $_SESSION['avgDiscount'] = $avgDiscount2;
    }
    
  # Discount Code AVG code End
  # =======================================
  }
  
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

    # TEST OF ADDING TicketPriceID before paypal start
    $query_userMember = $db_conn->query("SELECT UserMembership.UserID FROM UserMembership WHERE UserID = " . $_SESSION['UserID'] . " AND UserMembership.Year = " . date("Y"))->num_rows;
    if ($query_userMember == 1) {
      $query_TicketPrice = $db_conn->query("SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] . " AND TicketPrices.Type = 'Medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime");
      
      if ($row = $query_TicketPrice->fetch_assoc()) {
        
        $ticketPrice = $row['Price'];
        $TicketPriceID = $row['TicketPriceID'];
      }
    }else {
      $query_TicketPrice = $db_conn->query("SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] . " AND TicketPrices.Type = 'ikke-medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime");

      if ($row = $query_TicketPrice->fetch_assoc()) {
        $ticketPrice = $row['Price'];
        $TicketPriceID = $row['TicketPriceID'];
      }
    }
    # TEST OF ADDING TicketPriceID before paypal End
    


    $query = "INSERT INTO Tickets (UserID, EventID, SeatNumber, OrderedDate, TicketPriceID)
        VALUES (" . $_SESSION['UserID'] . ", " . $eventID . ", " . $seat . ", " . time() . "," .$TicketPriceID. ")";
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
        $query = "DELETE FROM Tickets WHERE UserID = '" . $_SESSION['UserID'] . "' AND OrderedDate IS NOT NULL AND RevokeDate = NULL";
        $db_conn->query($query);
        header("Location: index.php?page=Buy");
        exit;
      }
    } else {
      $query = "SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $event['EventID'] . " AND TicketPrices.Type = 'ikke-medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
      if ($ticket = $db_conn->query($query)->fetch_assoc()) {
        $ticketPrice = $ticket['Price'];
      } else {
        $_SESSION['MsgForUser'] = "Fejl kode: 0x000D0012";
        $query = "DELETE FROM Tickets WHERE UserID = '" . $_SESSION['UserID'] . "' AND OrderedDate IS NOT NULL AND RevokeDate = NULL";
        $db_conn->query($query);
        header("Location: index.php?page=Buy");
        exit;
      }
    }
    $Cart[] = [
      'Price' => $ticketPrice-$_SESSION['avgDiscount'],
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
      $query = "INSERT INTO Tickets (BuyersID, EventID, SeatNumber, OrderedDate)   # Need re-Think to avoide next user submit step where multiple tickets has same user
          VALUES (" . $_SESSION['UserID'] . ", " . $event['EventID'] . ", " . substr($json[$i], -3) . ", " . $timeNow . ")";
      $db_conn->query($query);
    }
?>
<?php # ======================================================= ?>
<div class="LanCMScontentbox row col-lg-12">
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
    $query = "DELETE FROM Tickets WHERE Tickets.BuyersID = " . $_SESSION['UserID'] .
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
      $query = "SELECT Username FROM Users WHERE Username = '" . $value . "' AND OneallUserToken != '' AND Inactive != '1'";
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
      $query = "DELETE FROM Tickets WHERE Tickets.BuyersID = " . $_SESSION['UserID'] .
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
      $query = "DELETE FROM Tickets WHERE Tickets.BuyersID = " . $_SESSION['UserID'] .
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

      $tempUserID2 = GetIDFromUsername($value, $db_conn);

      # TEST OF ADDING TicketPriceID before paypal start
      $query_userMember = $db_conn->query("SELECT UserMembership.UserID FROM UserMembership WHERE UserID = " . $tempUserID2 . " AND UserMembership.Year = " . date("Y"))->num_rows;
      if ($query_userMember == 1) {
        $query_TicketPrice = $db_conn->query("SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $_GLOBAL['EventID'] . " AND TicketPrices.Type = 'Medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime");
        
        if ($row = $query_TicketPrice->fetch_assoc()) {
          $ticketPrice = $row['Price'];
          $TicketPriceID = $row['TicketPriceID'];
        }
      }else {
        $query_TicketPrice = $db_conn->query("SELECT * FROM TicketPrices WHERE TicketPrices.EventID = " . $_GLOBAL['EventID'] . " AND TicketPrices.Type = 'ikke-medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime");

        if ($row = $query_TicketPrice->fetch_assoc()) {
          $ticketPrice = $row['Price'];
          $TicketPriceID = $row['TicketPriceID'];
        }
      }
      # TEST OF ADDING TicketPriceID before paypal End

      $query = "UPDATE Tickets SET Tickets.UserID = " . GetIDFromUsername($value, $db_conn) .
        ", TicketPriceID = '$TicketPriceID' WHERE Tickets.BuyersID = " . $_SESSION['UserID'] .
          " AND Tickets.RevokeDate IS NULL AND Tickets.TransactionCode = '' AND Tickets.SeatNumber = " . $key;
          //$_SESSION['SQL'] = $query;
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
            " AND TicketPrices.Type = 'ikke-medlem' AND " . time() . " BETWEEN TicketPrices.StartTime AND TicketPrices.EndTime";
        if ($ticket = $db_conn->query($query)->fetch_assoc()) {
          $ticketPrice = $ticket['Price'];
        } else {
          $_SESSION['MsgForUser'] = "Fejl kode: 0x000D0022";
          header("Location: index.php?page=Buy");
          exit;
        }
      }
      $cart[] = [
        'Price' => $ticketPrice-$_SESSION['avgDiscount'],
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
  require_once 'class/seatmap.php';
  
  $query = "SELECT Seatmap.Width AS Width, Seatmap.SeatString AS SeatString
      FROM Event
      INNER JOIN Seatmap
        ON Event.Seatmap = Seatmap.SeatmapID
      ORDER BY Event.StartDate DESC LIMIT 1";
  $theEvent = $db_conn->query($query)->fetch_assoc();
?>

<div class="LanCMScontentbox row">
  <div id="map" class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
    <div id="Seatmap"></div>
  </div>
  <div id="map-extras" class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
    <div id="Seatmap-Legend"></div>
    <div id="Seatmap-Cart">
      <h4>Dit valg (<span id="Seatmap-Counter">0</span>)</h4>
      <ul id="Seatmap-Cart-Items"></ul>
      <p>Total pris: <span id="Seatmap-Total">0</span>,-</p>
      <p>Kampange kode: 
<form id="hiddenForm" class="" action="" method="POST">
        <input name="discontcode" class="show" type="text">
      </p>
      <p><i>
      <small>
        Prisen er vejledende og kan variere alt efter om du er medlem af foregningen.
        Den endelige pris vil blive vist hos PayPal før du betaler. <br/><br/>
        Ved Køb Acceptere man også <a target="_blank" href="?page=Handelsebetingelser">Handelsebetingelser</a>
      </small></i></p>
      <button id="CheckoutButton" class="btn btn-default" onclick="checkoutButton()">Køb &raquo;</button>
    </div>
  </div>
</div>

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
      map: [<?php
              seatmap_generation($theEvent['SeatString'], $theEvent['Width']) 
            ?>],
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
  document.getElementById('discontcode').value = json;
  document.getElementById('hiddenForm').submit();
}
</script>
<?php } // End else ?>
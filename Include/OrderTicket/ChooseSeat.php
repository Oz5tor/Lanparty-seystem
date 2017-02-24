<?php

/*
TTTTTTTTT EEEEEEEEE MM       MM PPPPPP    PPPPPP  RRRRRR  IIIII   CCCCCcc EEEEEEEEE
    T     E         M M     M M P    PP   P    PP R    RR   I    C        E
    T     EEEEE     M  M   M  M PPPPPP    PPPPPP  RRRRRR    I   C         EEEEE
    T     E         M   M M   M P         P       R RR      I    C        E
    T     EEEEEEEEE M    M    M P         P       R  RR   IIIII   CCCCCcc EEEEEEEEE
*/
$_SESSION['EventPrice'] = 350;



if (!isset($_SESSION['UserID'])) {
  header("Location: /Website-2017/index.php");
}
include_once 'class/seatmap.php';
$query = "SELECT Seatmap.Width AS Width, Seatmap.SeatString AS SeatString FROM Event INNER JOIN Seatmap
    ON Event.Seatmap = Seatmap.SeatmapID ORDER BY Event.StartDate DESC LIMIT 1";
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
      <p>Total pris: <span id="Seatmap-Total"></span></p>
    </div>
  </div>
</div>

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
          price: <?php if (isset($_SESSION['EventPrice'])) {echo $_SESSION['EventPrice'];} ?>,
          category: 'Sæde'
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
          [ 'c', 'unavailable', 'Crew plads'],
          [ 's', 'unavailable', 'Scene / Storskærm'],
          [ 'A', 'unavailable', 'Arkade'],
          [ 'k', 'unavailable', 'Kiosk'],
          [ 'a', 'unavailable', 'Optaget' ]
        ]
      },
      click: function () {
        if (this.status() == 'available') {
          seatsSelected++;
          if (seatsSelected >= <?php
          if (isset($_GLOBAL['g_max_seats_selection'])) { echo ($_GLOBAL['g_max_seats_selection'] + 1); } else {
            echo "11";
          } ?>) {
            alert("Maks 10 sæder, boi...");
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
});

function calculateTotal(sc) {
      var total = 0;
      //basically find every selected seat and sum its price
      sc.find('selected').each(function () {
        total += this.data().price;
      });
      return total;
    }

</script>

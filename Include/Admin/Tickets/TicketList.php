<?php $result = $db_conn->query(" SELECT * FROM Tickets WHERE EventID = '$tabsEventID' ");

#print_r($result->fetch_assoc());
?>
<div class="col-lg-12 text-center alert alert-info" role="alert">
    Der vises kun biletter for aktuelt Event ( <?= $_GLOBAL['EventName']; ?> )
  </div>

  <div class="col-lg-12 col-sm-12 col-xs-12">
    <a href="?page=Admin&subpage=Tickets&action=ReserveSeat#admin_menu" alt="Ny Konkurrence" type="button" class="text-center btn btn-info">Reserver Pladser</a>
  </div>
<table class="table table-striped table-condensed table-hover LanCMSadminmenu">
  <thead>
    <tr>
      <th class="text-center">Ticket No.</th>
      <th class="text-center">Brugernavn</th>
      <th class="text-center">Bestillings Dato</th>
      <th class="text-center">Annulerings Dato</th>
      <th class="text-center">Transaction kode</th>
      <th class="text-center">Plads Nummer</th>
      <th class="text-center">Pris</th>
      <th class="text-center">Billet Type</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td class="text-center"><?= $row['TicketID']; ?></td>
      <td class="text-center"><?= TorGetUserName($row['UserID'], $db_conn); ?></td>
      <td class="text-center"><?= date("d M Y", $row['OrderedDate']); ?></td>
      <td class="text-center">
        <?php
        if($row['RevokeDate'] == ''){
            ?>
            <a href="?page=Admin&subpage=Tickets&action=CancelTicket&id=<?= $row['TicketID']?>" onclick="if(confirm('Er du sikker på at du vil Anullere Billet ID.: <?= $row['TicketID'].' med Brugeren.: '.TorGetUserName($row['UserID'], $db_conn) ?>')){return true;}else{event.stopPropagation(); event.preventDefault();}" class="btn btn-info">Annuller Billet!</a>
            <?php
        }else{
            ?>
            <button class="btn btn-danger"><?= date("d M Y", $row['RevokeDate']);?></button>
            <?php
        }
         ?>
      </td>
      <td class="text-center">
        <?php 
        if($row['TransactionCode'] == ''){
            ?>
            <a href="?page=Admin&subpage=Tickets&action=PayTicket&id=<?= $row['TicketID']?>" onclick="if(confirm('Er du sikker på at du vil sætte Billet ID.: <?= $row['TicketID'].' med Brugeren.: '.TorGetUserName($row['UserID'], $db_conn).' Som Betat' ?>')){return true;}else{event.stopPropagation(); event.preventDefault();}" class="btn btn-danger">Sæt Manuelt bataling</a>
            <?php
        }else{
            ?>
            <button onclick="alert('KAGE')" class=" btn btn-success"><?= $row['TransactionCode'];?></button>
            <?php
        }
        ?>
      </td>
      <td class="text-center"><?= $row['SeatNumber']; ?></td>
      <td class="text-center">
        <?php
        
          $priceID = $row['TicketPriceID'];
          $ticketPrice = $db_conn->query("Select * From TicketPrices WHERE TicketPriceID = '$priceID'");
          if($ticketPrice2 = $ticketPrice->fetch_assoc()){
            #print_r($ticketPrice2);
            echo $ticketPrice2['Price'].'.-';
          }
        ?>
      </td>
      <td class="text-center">
        <?php
          if(isset($ticketPrice2)){
            echo $ticketPrice2['Type'];
          }
        ?>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
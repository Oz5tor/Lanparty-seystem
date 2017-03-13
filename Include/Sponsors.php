<div class="container">
    <div class="text-center">
        <?php
            if( $result = $db_conn->query( "SELECT Sponsors.Url, Sponsors.Banner FROM Sponsors WHERE MainSponsor = 1" ) ) {
                if( $result -> num_rows ) {
                    $row = $result->fetch_assoc();
                    echo "<a href='" . $row[ 'Url' ] . "' class='thumbnail hlpf_no_round_border'><img src='Images/Sponsore/" . $row[ 'Banner' ] . "'></a>";
                } else { return null; }
            $result -> close();
            }
        ?>
    </div>
</div>
<div class="container">
    <div class="row">
      <?php
        $SmallSponsResult = $db_conn->query("Select * From Sponsors Where Sponsors.Online = '1' And Sponsors.MainSponsor = '0' Order By RAND() Limit 3");
        while($row = $SmallSponsResult->fetch_assoc())
        {
      ?>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <a href="<?php echo $row['Url']; ?>" class="thumbnail">
                <img class="img-responsive" alt="<?php echo $row['Name']; ?>" src="Images/Sponsore/<?php echo $row['Banner']; ?>">
            </a>
        </div>
      <?php
        }
      ?>
    </div>
</div>

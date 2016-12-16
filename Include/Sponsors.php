<div class="container">
    <div class="text-center">
        <h2>Sponsorer</h2>
    </div>
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
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <a href="#" class="thumbnail">
                <img class="img-responsive" src="Images/Sponsore/bequiet.gif">
            </a>    
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <a href="#" class="thumbnail">
                <img class="img-responsive" src="Images/Sponsore/geekunit.png">
            </a>    
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <a href="#" class="thumbnail">
                <img class="img-responsive" src="Images/Sponsore/SSNetwork.png">
            </a>    
        </div>
    </div>
</div>

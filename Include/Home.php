 <!-- Headsponsor and Latest news start -->
<div class="row">
    <!-- Main sponsor post Start -->
    <div class="col-lg-5 LanCMScontentbox">
        <?php
            // Don't you just love SQL?
            if( $result = $db_conn->query( "SELECT Description, Banner FROM Sponsors WHERE Sponsors.MainSponsor = 1" ) ){
                if( $result -> num_rows ){
                    $row = $result->fetch_assoc();
                }
            }
        ?>
        <div class="row">
            <div class="col-lg-12 LanCMSlarge_news_box">
                <img class="img-responsive" src="Images/Sponsore/<?php echo $row[ 'Banner' ]; ?>">
                <hr/>
                <div class="LanCMSflex">
                    <div class="LanCMSnews">
                        <?php
                            echo "<p>" . $row[ 'Description' ] . "</p>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <?php $result -> close(); ?>
    </div>
    <!-- Main sponsor post End -->
    <div class="col-lg-2">
        <!--<div class="fb-page" data-href="https://www.facebook.com/HLParty/" data-width="185" data-height="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/HLParty/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/HLParty/">HLParty</a></blockquote></div>-->
    </div>
    <!-- Lastest News start -->
    <div class="col-lg-5 LanCMScontentbox">
        <div class="row">
            <div class="col-lg-12 LanCMSlarge_news_box">
            <!-- <img class="img-responsive" src="Images/image-slider-5.jpg"> -->
                <h1 class="text-center">Seneste nyhed</h1>
                <hr/>
                <div class="LanCMSflex">
                    <div class="LanCMSnews">
                        <?php
                            // Get the latest news that is online.
                            if( $result = $db_conn->query( "SELECT News.Content, News.Title From News WHERE Online = '1' ORDER BY PublishDate DESC limit 1" ) ){
                                if( $result -> num_rows ){
                                    $row = $result->fetch_assoc();
                                    echo "<h4>" . $row[ 'Title' ] . "</h4>";
                                    echo "<p>" . $row[ 'Content' ] . "</p>";
                                }
                            $result -> close();
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <!-- News archive button start -->
        <div class="row">
            <div class="col-lg-12">
                <a href="?page=Newsarchive" class="btn btn-primary btn-xs btn-block">Nyhedsarkiv</a>
            </div>
        </div>
        <br>
        <!-- News archive button end -->
    </div>
    <!-- Lastest News End -->
</div>
<!-- Headsponsor and Latest news end -->

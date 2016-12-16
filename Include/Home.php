 <!-- Headsponsor and Latest news start -->
<div class="row">
    <!-- Main sponsor post Start -->
    <div class="col-lg-5 hlpf_newsborder">
        <?php
            // Don't you just love SQL?
            if( $result = $db_conn->query( "SELECT
                                              Pages.Content,
                                              Pages.PageTitle,
                                              Sponsors.Banner
                                            FROM
                                              Pages INNER JOIN
                                              Sponsors
                                                ON Sponsors.PageID = Pages.PageID
                                            WHERE
                                              Sponsors.MainSponsor = 1" ) ){
                if( $result -> num_rows ){
                    $row = $result->fetch_assoc();
                }
            }
        ?>
        <div class="row">
            <div class="col-lg-12 hlpf_large_news_box">
                <img class="img-responsive" src="Images/Sponsore/<?php echo $row[ 'Banner' ]; ?>">
                <hr/>
                <div class="hlpf_flex">
                    <div class="hlpf_news">
                        <?php
                            echo "<h4>" . $row[ 'PageTitle' ] . "</h4>";
                            echo "<p>" . $row[ 'Content' ] . "</p>";
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
        <div class="fb-page" data-href="https://www.facebook.com/HLParty/" data-width="185" data-height="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/HLParty/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/HLParty/">HLParty</a></blockquote></div>
    </div>
    <!-- Lastest News start -->
    <div class="col-lg-5 hlpf_newsborder">
        <div class="row">
            <div class="col-lg-12 hlpf_large_news_box">
            <!-- <img class="img-responsive" src="Images/image-slider-5.jpg"> -->
                <h2 class="text-center">Seneste nyhed</h2>
                <hr/>
                <div class="hlpf_flex">
                    <div class="hlpf_news">
                        <?php
                            // Get the latest news that is online.
                            if( $result = $db_conn->query( "SELECT
                                                                News.Content,
                                                                News.Title
                                                            From
                                                                News
                                                            Where
                                                                News.Online = 1
                                                            order by
                                                                News.NewsID 
                                                            desc limit 1" ) ){
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
                <button type="button" class="btn btn-primary btn-xs btn-block">
                    Nyhedsarkiv
                </button>
            </div>
        </div>
        <br>
        <!-- News archive button end -->  
    </div>
    <!-- Lastest News End -->
</div>
<!-- Headsponsor and Latest news end -->

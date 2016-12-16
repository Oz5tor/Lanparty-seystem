 <!-- Headsponsor and Latest news start -->
        <div class="row">
            <!-- Main sponsor post Start -->
            <div class="col-lg-5 hlpf_newsborder">
                <div class="row">
                    <div class="col-lg-12 hlpf_large_news_box">
                        <img class="img-responsive" src="Images/image-slider-5.jpg">
                        <hr/>
                        <div class="hlpf_flex">
                            <div class="hlpf_news">
                                <?php
                                    // lATEST SPONSOR NEWS
                                    if( $result = $db_conn->query( "SELECT * FROM Sponsors ORDER BY NewsID DESC LIMIT 1" ) ){
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
                <hr/>
            <!-- Facebook Social like thingy start -->
                <div class="row">
                    <div class="hlpf_facebok_like col-lg-12 col-sm-12 col-md-12 col-xs-12 text-center">
                        <div class="fb-like" data-href="https://www.facebook.com/HLParty" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
                    </div>
                </div>
            <br>
            <!-- Facebook Social like thingy end -->
            </div>
            <!-- Main sponsor post End -->
            <div class="col-lg-2"></div>
            <!-- Lastest News start -->
            <div class="col-lg-5 hlpf_newsborder">
                <div class="row">
                    <div class="col-lg-12 hlpf_large_news_box">
                        <img class="img-responsive" src="Images/image-slider-5.jpg">
                        <hr/>
                        <div class="hlpf_flex">
                            <div class="hlpf_news">
                                <?php
                                    // Get latest news.
                                    if( $result = $db_conn->query( "SELECT * FROM News ORDER BY NewsID DESC LIMIT 1" ) ){
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

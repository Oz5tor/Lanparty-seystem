<div class="row">
    <div class="col-lg-12 LanCMScontentbox">
        <?php
          $result = $db_conn->query("SELECT * From Pages WHERE PageTitle = '$page' && Online = '1' ");
          //print_r($result);

          if( $result -> num_rows ){
            $row = $result->fetch_assoc();
          } else {
            // TO THE FRONT-PAGE!
            #header("Location: index.php");
          }
        if($row['AdminOnly'] == 1){
             # header("Location: index.php");
        }else{
        ?>
        <div class="row">
            <div class="col-lg-12 LanCMSlarge_news_box">
                <h3>
                    <?php echo str_replace('_',' ',$row[ 'PageTitle' ]); ?>
                </h3>
                <hr/>
                <div class="LanCMSflex">
                        <!-- Content -->
                        <?php echo "<p>" . $row[ 'Content' ] . "</p>"; ?>
                </div>
            </div>
        </div>
        <hr/>
        <?php
        }
        $result -> close(); 
         
        ?>
    </div>
</div>

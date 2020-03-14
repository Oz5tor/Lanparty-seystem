<div class="row">
    <div class="col-lg-12 LanCMScontentbox">
        <?php
          if( ctype_digit( strval( $page ) ) ) {
            $result = $db_conn->query( "
              SELECT
                P.Content,
                P.PageTitle,
                U1.Username AS Creator,
                U2.Username AS Editor,
                P.CreatedDate,
                P.LastEditedDate,
                AdminOnly
              FROM Pages P
                INNER JOIN Users U1
                  ON P.AuthorID = U1.UserID
                INNER JOIN Users U2
                  ON P.LastEditedID = U2.UserID
                WHERE P.PageID = $page AND P.Online = '1' AND AdminOnly = '0'
              " ); // End query.
          } elseif (! ctype_digit( strval( $page ) )) {
            $result = $db_conn->query( "
              SELECT
                P.Content,
                P.PageTitle,
                U1.Username AS Creator,
                U2.Username AS Editor,
                P.CreatedDate,
                P.LastEditedDate,
                AdminOnly
              FROM Pages P
                INNER JOIN Users U1
                  ON P.AuthorID = U1.UserID
                INNER JOIN Users U2
                  ON P.LastEditedID = U2.UserID
                WHERE P.PageTitle = '$page' AND P.Online = '1' AND AdminOnly = '0'
            " ); // End query.
          }

          if( $result -> num_rows ){
            $row = $result->fetch_assoc();
          } else {
            // TO THE FRONT-PAGE!
            header("Location: index.php");
          }
        if($row['AdminOnly'] == 1){
              header("Location: index.php");
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

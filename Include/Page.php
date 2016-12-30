<div class="row">
    <div class="col-lg-12 hlpf_newsborder">
        <?php
            if( $result = $db_conn->query( "
                SELECT
                  P.Content,
                  P.PageTitle,
                  U1.Username AS Creator,
                  U2.Username AS Editor,
                  P.CreatedDate,
                  P.LastEditedDate
                FROM
                  Pages P INNER JOIN
                  Users U1
                    ON P.AuthorID = U1.UserID INNER JOIN
                  Users U2
                    ON P.LastEditedID = U2.UserID
                WHERE
                  P.PageID = $page
            " ) ){
                if( $result -> num_rows ){
                    $row = $result->fetch_assoc();
                } else {
                    // GOTO 404 PAGE
                }
            }
        ?>
        <div class="row">
            <div class="col-lg-12 hlpf_large_news_box">
                <h3>
                    <?php echo $row[ 'PageTitle' ]; ?>
                </h3>
                <hr/>
                <div class="hlpf_flex">
                    <div class="hlpf_news">
                        <!-- Content -->
                        <?php echo "<p>" . $row[ 'Content' ] . "</p>"; ?>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <?php $result -> close(); ?>
    </div>
</div>

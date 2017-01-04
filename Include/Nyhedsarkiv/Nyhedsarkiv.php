<div class="col-lg-12 hlpf_newsborder">
	<div class="row col-lg-12">
		<p>
			Nyhedsarkiv
		</p>
	</div>
	<?php
		$result1 = $db_conn->query(
        	"SELECT COUNT(*) as News FROM News"
        );
        if( $result1 -> num_rows ) {
            $row1 = $result1->fetch_assoc();
            $cpp = ($row1["News"] / 20);
        }else{
        	$cpp = 0;
        }
	?>
	<div class="row col-lg-12">
		<ul class="pagination">
			<li><a href="#">&laquo;</a></li>

			<?php for($p = 0; $p < $cpp; $p++) {?>
				<li><a href="#"><?php echo $p + 1?></a></li>
			<?php } ?>

			<li><a href="#">&raquo;</a></li>
		</ul>
	</div>
	<div class="row">
		<p>
			<?php
			$result2 = $db_conn->query( "
			    SELECT
			      News.Title, News.Content
			    FROM News ORDER BY CreatedDate DESC LIMIT 0, 20
			");
			?>
			<?php while ($row2 = $result2->fetch_assoc()) { ?>
				<div class="col-lg-12">
					<div class="col-lg-12">
						<h2><?php echo $row2['Title']?></h2>
					</div>
					<div class="col-lg-12">
						<?php echo $row2['Content']?>
					</div>
					<hr>
				</div>
			<?php } ?>
			<?php $result2 -> close(); ?>
		</p>
	</div>
</div>
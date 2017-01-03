<div class="col-lg-12 hlpf_newsborder">
	<div class="row col-lg-12">
		<p>
			Nyhedsarkiv
		</p>
	</div>
	<div class="row col-lg-12">
		<ul class="pagination pagination-sm">
			<li><a href="#">&laquo;</a></li>
			<li><a href="#">1</a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">4</a></li>
			<li><a href="#">5</a></li>
			<li><a href="#">6</a></li>
			<li><a href="#">&raquo;</a></li>
		</ul>
	</div>
	<div class="row">
		<p>
			<?php
			$result = $db_conn->query( "
			    SELECT
			      News.Title, News.Content
			    FROM News ORDER BY CreatedDate DESC
			");
			?>
			<?php while ($row = $result->fetch_assoc()) { ?>
				<div class="col-lg-12">
					<div class="col-lg-12">
						<h2><?php echo $row['Title']?></h2>
					</div>
					<div class="col-lg-12">
						<?php echo $row['Content']?>
					</div>
					<hr>
				</div>
			<?php } ?>
		</p>
	</div>
</div>
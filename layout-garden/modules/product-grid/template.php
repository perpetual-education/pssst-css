
<product-grid>
	<h2 class='attention-voice'>Product grid</h2>

	<ol role='list'>
		<?php foreach ([1, 2, 3, 4, 1, 3] as $p) { ?>
			<li>
				<?php include('modules/product-preview/template.php'); ?>
			</li>
		<?php } ?>
	</ol>
</product-grid>

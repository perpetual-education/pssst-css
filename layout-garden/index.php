<!doctype html>

<html lang='en'>
	<head>
		<title>PSSST CSS example with PHP</title>

		<link rel='stylesheet' href='../styles/index.css' />
		<link rel='stylesheet' href='modules/modules.css' />
	</head>

	<body>
		<header class='site-header'>
			<inner-column>
				<?php include('modules/mast-head/template.php'); ?>
			</inner-column>
		</header>

		<main>
			<section>
				<inner-column>
					<?php include('modules/generic-text/template.php'); ?>
				</inner-column>
			</section>

			<section style='--backdrop: purple'>
				<inner-column>
					<?php include('modules/big-heading/template.php'); ?>
				</inner-column>
			</section>

			<section>
				<inner-column>
					<?php include('modules/big-heading/template.php'); ?>
				</inner-column>
			</section>

			<section class='america'>
				<inner-column>
					<?php include('modules/big-heading/template.php'); ?>
				</inner-column>
			</section>

			<section>
				<inner-column>
					<?php include('modules/call-to-action/template.php'); ?>
				</inner-column>
			</section>

			<section class='wild-times'>
				<inner-column>
					<?php include('modules/call-to-action/template.php'); ?>
				</inner-column>
			</section>

			<section>
				<inner-column>
					<?php include('modules/big-image/template.php'); ?>
				</inner-column>
			</section>

			<section>
				<inner-column>
					<?php include('modules/graphic-diptych/template.php'); ?>
				</inner-column>
			</section>

			<section class='new-products'>
				<inner-column>
					<?php include('modules/product-grid/template.php'); ?>
				</inner-column>
			</section>

			<section class='flipped'>
				<inner-column>
					<?php include('modules/graphic-diptych/template.php'); ?>
				</inner-column>
			</section>

			<section class='centered'>
				<inner-column>
					<?php include('modules/graphic-diptych/template.php'); ?>
				</inner-column>
			</section>

			<section class='centered flipped'>
				<inner-column>
					<?php include('modules/graphic-diptych/template.php'); ?>
				</inner-column>
			</section>
		</main>

		<footer>
			<inner-column>
				<?php include('modules/site-map/template.php'); ?>
			</inner-column>
		</footer>
	</body>
</html>
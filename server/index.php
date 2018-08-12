<?php
require_once("config.php");
require_once('authenticate.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/style.css?v=3<?=rand()?>" rel="stylesheet">
</head>
<body>

<main>
	<section>
		<h2>Existing products</h2>
		<div class="products" id="ul-previous-codes">
		</div>
	</section>

	<section>
		<h2>Missing products</h2>
		<div class="products" id="ul-missed-codes">
		</div>
	</section>
</main>

<footer>
	<div id="last_scan">Last Scan</div>
	<button id="btn-scan" style="width:auto;">Scan</button>
</footer>


<script src="js/main.js?v=<?=rand()?>"></script>
</body>
</html>

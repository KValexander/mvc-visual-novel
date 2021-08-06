<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SPA</title>

	<!-- Connections styles -->
	<link rel="stylesheet" href="style.css">

	<!-- Connecting libraries -->
	<script src="client/library/jquery-3.6.0.min.js"></script>

	<!-- Connecting objects -->
	<script src="client/request.js"></script>
	<script src="client/route.js"></script>
	<script src="client/auth.js"></script>
	<script src="client/message.js"></script>
	<script src="client/query.js"></script>
	<script src="client/output.js"></script>
	<script src="client/slider.js"></script>
	<!-- Connecting scripts -->
	<script src="client/script.js"></script>

</head>
<body>
	<!-- Header -->
	<header>
		<div class="content">
			<a onclick="route.redirect('index')"><h1>Novel re</h1></a>
			<nav id="menu"></nav>
		</div>
	</header>

	<!-- Content part of the page -->
	<div id="app"></div>

	<!-- Block for displaying messages -->
	<div id="message"></div>
</body>
</html>
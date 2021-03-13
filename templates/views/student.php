<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Speed Coding Academy</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/milligram/1.4.0/milligram.css">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Homemade+Apple&display=swap" rel="stylesheet">
<link href="https://unpkg.com/blueprint-css@3.1.1/dist/blueprint.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= BASE_URL ?>css/custom.css">
</head>
<body>

<div id="main">
	<div class="container">
		<div class="row top-row" bp="grid vertical-center">
			<div bp="12 4@lg">
				<div>David Connelly's</div>
				<div>Speed Coding Academy</div>
				<div id="open-btn" bp="hide@lg" onclick="openSideNav()">☰</div>
			</div>
			<div bp="5 hide show@lg">
				<a href="<?= BASE_URL ?>">Home</a>
				<a href="<?= BASE_URL ?>contactus">Get In Touch</a>
			</div>
			<div bp="3 hide show@lg text-right">
				<a class="button" href="<?= BASE_URL ?>members/login">Members' Login</a>
			</div>
		</div>
	</div>

	<?= Template::display($data) ?>

	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="column">
					<p>
						<a href="<?= BASE_URL ?>members/login">Members' Login</a>
						<a href="<?= BASE_URL ?>contactus">Get In Touch</a>
					</p>
					<p class="smaller">© Copyright 2099 - Your Name etc</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="side-nav">
	<div id="close-btn" onclick="closeSideNav()">×</div>
	<ul>
		<li><a href="#">Home</a></li>
		<li><a href="#">About</a></li>
		<li><a href="#">Get In Touch</a></li>
		<li><a href="<?= BASE_URL ?>members/login">Members' Login</a></li>
	</ul>
</div>


</body>
</html>
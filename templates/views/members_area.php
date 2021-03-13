<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Milligram CSS Basic Template</title>
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/milligram/1.4.0/milligram.css">
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Parisienne&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
<link href="https://unpkg.com/blueprint-css@3.1.1/dist/blueprint.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= BASE_URL ?>css/members_area.css" />

</head>
<body>
<div id="main">
	<div class="container top-row" bp="grid vertical-center">
		<div bp="11 4@lg">
			<div>David Connelly's</div>
			<div>Speed Coding Academy</div>
		</div>
		<div bp="8 hide show@lg text-right">
			<a href="<?= BASE_URL ?>dashboard"><i class="fa fa-tachometer-alt"></i> Dashboard</a>
			<a href="<?= BASE_URL ?>downloads"><i class="fa fa-download"></i> Downloads</a>
			<a href="<?= BASE_URL ?>forums"><i class="fa fa-comments"></i> Forums</a>
			<a href="<?= BASE_URL ?>members/your_account"><i class="fa fa-user-circle"></i> Your Account</a>
			<a href="<?= BASE_URL ?>members/logout"><i class="fa fa-sign-out-alt"></i> Logout</a>
		</div>
		<div bp="1 hide@lg text-right"><i id="open-btn" class="fa fa-bars" onclick="openSideNav()"></i></div>
	</div>

	<div class="container silver-frame">
		<div class="row">
			<div class="column stage">
            <?= Template::display($data) ?>
			</div>
		</div>
	</div>


	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="column">
					<p>
						<a href="<?= BASE_URL ?>members/logout">Logout</a>
						<a href="<?= BASE_URL ?>contactus">Get In Touch</a>
					</p>
					<p class="smaller">© Copyright 2099 - Your Name etc</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="side-nav">
	<i class="fa fa-times" id="close-btn" onclick="attemptCloseSideNav()"></i>
	<ul>


		<li><a href="<?= BASE_URL ?>dashboard"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?= BASE_URL ?>downloads"><i class="fa fa-download"></i> Downloads</a></li>
			<li><a href="<?= BASE_URL ?>forums"><i class="fa fa-comments"></i> Forums</a></li>
			<li><a href="<?= BASE_URL ?>members/your_account"><i class="fa fa-user-circle"></i> Your Account</a></li>
			<li><a href="<?= BASE_URL ?>members/logout"><i class="fa fa-sign-out-alt"></i> Logout</a></li>

	</ul>
</div>

<script src="<?= BASE_URL ?>js/tilting_nav.js"></script>
</body>
</html>
<!doctype html>
<html class="no-js" lang="">


<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Fluttec School Management | Home </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?=BASE_URL ?>akkhor/<?=BASE_URL ?>akkhor/img/favicon.png">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="<?=BASE_URL ?>akkhor/css/normalize.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?=BASE_URL ?>akkhor/css/main.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?=BASE_URL ?>akkhor/css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?=BASE_URL ?>akkhor/css/all.min.css">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="<?=BASE_URL ?>akkhor/fonts/flaticon.css">
    <!-- Full Calender CSS -->
    <link rel="stylesheet" href="<?=BASE_URL ?>akkhor/css/fullcalendar.min.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="<?=BASE_URL ?>akkhor/css/animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?=BASE_URL ?>akkhor/style.css">
    <!-- Modernize js -->
    <script src="<?=BASE_URL ?>akkhor/js/modernizr-3.6.0.min.js"></script>
</head>

<body>
    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <div id="wrapper" class="wrapper bg-ash">
    <?= Template::partial('members_files/topbar') ?>
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
        <?= Template::partial('members_files/sidebar') ?>
            <div class="dashboard-content-one">
                   <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>UI Elements</h3>
                    <ul>
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>UI Elements</li>
                        <li>Button</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->

                <?= Template::display($data) ?>
                <!-- Social Media End Here -->
                <?= Template::partial('members_files/footer') ?>
            </div>
        </div>
        <!-- Page Area End Here -->
    </div>
    <!-- jquery-->
    <script src="<?=BASE_URL ?>akkhor/js/jquery-3.3.1.min.js"></script>
    <!-- Plugins js -->
    <script src="<?=BASE_URL ?>akkhor/js/plugins.js"></script>
    <!-- Popper js -->
    <script src="<?=BASE_URL ?>akkhor/js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="<?=BASE_URL ?>akkhor/js/bootstrap.min.js"></script>
    <!-- Counterup Js -->
    <script src="<?=BASE_URL ?>akkhor/js/jquery.counterup.min.js"></script>
    <!-- Moment Js -->
    <script src="<?=BASE_URL ?>akkhor/js/moment.min.js"></script>
    <!-- Waypoints Js -->
    <script src="<?=BASE_URL ?>akkhor/js/jquery.waypoints.min.js"></script>
    <!-- Scroll Up Js -->
    <script src="<?=BASE_URL ?>akkhor/js/jquery.scrollUp.min.js"></script>
    <!-- Full Calender Js -->
    <script src="<?=BASE_URL ?>akkhor/js/fullcalendar.min.js"></script>
    <!-- Chart Js -->
    <script src="<?=BASE_URL ?>akkhor/js/Chart.min.js"></script>
    <!-- Custom Js -->
    <script src="<?=BASE_URL ?>akkhor/js/main.js"></script>

</body>

</html>
<?php error_reporting(E_ALL ^ ( E_NOTICE | E_WARNING | E_DEPRECATED | E_STRICT)); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>etunti</title>
        <meta name="keywords" content="HTML5 Template" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,700' rel='stylesheet' type='text/css'>
        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/css/style.css?v=2.13"/>
        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/css/theme-responsive.css"/>
        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/css/skins/blue/blue.css?v=2" />
        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/inc/lomake/lomake.css?v=2" />
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/modernizr.js"></script>

        <!-- styles for IE -->
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="../css/ie/ie.css" type="text/css" media="screen" />
        <![endif]-->


        <!--[if lte IE 8]>
            <script src="../js/responsive/html5shiv.js"></script>
            <script src="../js/responsive/respond.js"></script>
        <![endif]-->
    </head>
    <body>

    <!--Preloader-->
    <div class="preloader">
        <div class="status">&nbsp;</div>
    </div>
    <!--End Preloader-->

    <!-- layout-->
    <div id="layout" class="layout-wide">
        <!-- Login Client -->
        <div class="jBar">
            <div class="container">
                <div class="row">
                <div class="col-sm-7 col-md-8 padding-bottom">
                        <h4>Tuki</h4>
                        <p><i class="fa fa-phone"></i> <a href="#">020 236 5899</a></p>
                        <p><i class="fa fa-envelope"></i> <a href="mailto:tuki@etunti.fi">tuki@etunti.fi</a></p>

                   </div>
                    <!-- Login-->
                    <div class="col-sm-5 col-md-4">
                        <h4>Kirjaudu</h4>
                        <form>
                            <input type="text" placeholder="Domain" required>
                            <input type="text" placeholder="Käyttäjätunnus" required>
                            <input type="password" placeholder="Salasana" required>
                            <input type="submit" class="btn btn-lg" value="Kirjaudu">
                        </form>
                    </div>
                    <!-- ENd Login-->

                    <span class="jTrigger downarrow"><i class="fa fa-minus"></i></span>
                </div>
            </div>
        </div>
        <span class="jRibbon jTrigger up" title="Login">KIRJAUDU <i class="fa fa-plus"></i></span>
        <div class="line"></div>
        <!-- End Login Client -->

        <!-- Header-->
        <header>
            <!-- Container-->
            <div class="container">
                <!-- Row-->
                <div class="row">
                    <!-- Logo-->
                    <div class="col-md-3">
                        <div class="logo">
                            <a href="index.html" title="Return Home">
                                <img src="../img/logo.png" alt="Logo" class="logo_img">
                            </a>
                        </div>
                    </div>
                    <!-- End Logo-->

                    <!-- Nav-->
                    <div class="col-md-9 slogan">
                        Voita siivousalan haasteet
                    </div>
                    <!-- End Nav-->
                </div>
                <!-- End Row-->
            </div>
            <!-- End Container-->
        </header>
        <!-- End Header-->

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

<?php
$sub = explode(".",$_SERVER['HTTP_HOST']);
if (
		(! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off') 
		and ($_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1')
		and $sub[0] != 'staging'
) {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url");
    exit();
}
?>


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
                        <p><i class="fa fa-phone"></i> <a href="#">+358 40 7614 366</a></p>
                        <p><a href="#">Tukinumero vastaa 9 - 17</a></p>
                        <p><i class="fa fa-envelope"></i> <a href="mailto:tuki@etunti.fi">tuki@etunti.fi</a></p>

                   </div>
                    <!-- Login-->
                    <div class="col-sm-5 col-md-4">
                        <h4>Kirjaudu</h4>
                        <form action="index.php/user/login" method="POST">
                            <input type="text" name="UserLogin[domain]" placeholder="Domain" required>
                            <input type="text" name="UserLogin[username]" placeholder="Käyttäjätunnus" required>
                            <input type="password" name="UserLogin[password]" placeholder="Salasana" required>
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
                            <a href="index.php" title="Return Home">
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/img/logo.png" alt="Logo" class="logo_img">
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



	<?php echo $content; ?>



    <!-- ======================= JQuery libs =========================== -->
<?php
/*
    <!-- jQuery local-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/jquery.js"></script>
*/
?>
    <!--Nav-->
     <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/nav/tinynav.js"></script>

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/nav/jquery.sticky.js" type="text/javascript"></script>
    <!--Totop-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/totop/jquery.ui.totop.js" ></script>
    <!--Slide Revolution-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/rs-plugin/js/jquery.themepunch.tools.min.js" ></script>
    <script type='text/javascript' src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
    <!--Ligbox-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/fancybox/jquery.fancybox.js"></script>
    <!-- carousel.js-->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/carousel/carousel.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/inc/lomake/lomake.js"></script>
    <!-- Parallax-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/parallax/jquery.inview.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/parallax/nbw-parallax.js"></script>
    <!--Theme Options-->
<!--     <script type="text/javascript" src="js/theme-options/theme-options.js"></script>
    <script type="text/javascript" src="js/theme-options/jquery.cookies.js"></script> -->
    <!-- Bootstrap.js-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/bootstrap/bootstrap.js"></script>
    <!--MAIN FUNCTIONS-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/main.js?v=1.13"></script>
    <!-- ======================= End JQuery libs =========================== -->

    <!--Slider Function-->
    <script type="text/javascript">
        var revapi;
        jQuery(document).ready(function() {
           revapi = jQuery('.tp-banner').revolution(
            {
                delay:9000,
                startwidth:1170,
                startheight:580,
                spinner:"spinner4",
                hideThumbs:10,
                fullWidth:"on",
                navigationType:"none",
                navigationArrows:"solo",
                navigationStyle:"preview4",
                forceFullWidth:"on"
            });
        });
    </script>
    <!--End Slider Function-->




        <!-- footer Center-->
        <footer class="footer-center">
            <div class="container-fluid">

                <!-- Info Top - Footer Center-->
<style>
.img-thumbnail{
	margin-top : 5px;
	box-radius: 0;
	box-radius: 0;
	padding : 0;
}
</style>
                <div class="row">
                   <div class="col-lg-3">
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/veikokuva.jpg" class="img-thumbnail">
                   </div>

                   <div class="col-lg-3 col-lg-6 item-center">
                        <h3>+358 40 761 4366</h3>

                        <a href="#">
                        <i class="fa fa-phone"></i>
                        <h4>Soita</h4>
                        </a>
                   </div>

                   <div class="col-lg-3 col-lg-6 item-center">
                        <h3><a href="mailto:veiko.poldkivi@etunti.fi">veiko.poldkivi@etunti.fi</a></h3>

                       <a href="#">
                        <i class="fa fa-envelope"></i>
                        <h3><a href="mailto:veiko.poldkivi@etunti.fi">Lähetä viesti</a></h3>
                        </a>
                   </div>

                   <div class="col-lg-3 col-lg-6 item-center">
                        <div class="logo">
			<br><br><br>
                            <a href="index.php" title="Return Home">
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/logo.png" alt="Logo" class="logo_img">
                            </a>
                        </div>
                   </div>


<!--
                   <div class="col-md-3 col-xs-6 item-center">
                        <h3><a href="#">Myynti</a></h3>

                        <a href="#">
                        <i class="fa fa-comment"></i>
                        <h4>Live Chat</h4>
                        </a>
                   </div>
                   <div class="col-md-3 col-xs-6 item-center">
                        <h3>Some</h3>

                        <ul class="social">

                            <li data-toggle="tooltip" title data-original-title="Twitter">
                                <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li data-toggle="tooltip" title data-original-title="Youtube">
                                <a href="#" target="_blank"><i class="fa fa-youtube"></i></a>
                            </li>
                        </ul>


-->
                </div>

                <!-- End Info Top - Footer Center-->


            </div>
        </footer>
        <!-- End footer Center-->

        <!-- footer bottom-->

        <footer class="footer-bottom">
            <div class="container">
               <div class="row">

                    <!-- Nav-->

                               <p class="text-center">&copy; 2015 Etunti</p>

                    <!-- End Nav-->

               </div>

            </div>
        </footer>
        <!-- End footer bottom-->
    </div>
    <!-- End layout-->



<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function()
{ (i[r].q=i[r].q||[]).push(arguments)}
,i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-72418912-1', 'auto');
ga('send', 'pageview');
</script>


    </body>
</html>

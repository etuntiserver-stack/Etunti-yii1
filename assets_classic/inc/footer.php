
        <!-- footer Center-->
        <footer class="footer-center">
            <div class="container-fluid">
                <!-- Info Top - Footer Center-->
                <div class="row">
                   <div class="col-md-3 col-xs-6 item-center">
                        <h3>020 236 5899</h3>

                        <a href="#">
                        <i class="fa fa-phone"></i>
                        <h4>Soita</h4>
                        </a>
                   </div>
                   <div class="col-md-3 col-xs-6 item-center">
                        <h3><a href="#">Myynti</a></h3>

                        <a href="#">
                        <i class="fa fa-comment"></i>
                        <h4>Live Chat</h4>
                        </a>
                   </div>
                   <div class="col-md-3 col-xs-6 item-center">
                        <h3><a href="mailto:myynti@etunti.fi">myynti@etunti.fi</a></h3>

                       <a href="#">
                        <i class="fa fa-envelope"></i>
                        <h4>Lähetä viesti</h4>
                        </a>
                   </div>
                   <div class="col-md-3 col-xs-6 item-center">
                        <h3>Some</h3>
                                                <!-- Menu-->
                        <ul class="social">
                            <!-- <li data-toggle="tooltip" title data-original-title="Facebook">
                                <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                            </li> -->
                            <li data-toggle="tooltip" title data-original-title="Twitter">
                                <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li data-toggle="tooltip" title data-original-title="Youtube">
                                <a href="#" target="_blank"><i class="fa fa-youtube"></i></a>
                            </li>
                        </ul>
                        <!-- End Menu-->
                   </div>
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

    <!-- ======================= JQuery libs =========================== -->
    <!-- jQuery local-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/jquery.js"></script>
    <!--Nav-->
     <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/nav/tinynav.js"></script>

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/nav/jquery.sticky.js" type="text/javascript"></script>
    <!--Totop-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/totop/jquery.ui.totop.js" ></script>
    <!--Slide Revolution-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/rs-plugin/js/jquery.themepunch.tools.min.js" ></script>
    <script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/rs-plugin/js/jquery.themepunch.revolution.min.js'></script>
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

    </body>
</html>

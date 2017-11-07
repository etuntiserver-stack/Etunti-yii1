<?php ?>


    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/etusivu_2.css">

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
                        <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/user/login" method="POST">
                            <input type="text" name="UserLogin[domain]" placeholder="<?php echo Yii::t('main', 'Yritystunnus')?>" required>
                            <input type="text" name="UserLogin[username]" placeholder="<?php echo Yii::t('main', 'Käyttäjätunnus')?>" required>
                            <input type="password" name="UserLogin[password]" placeholder="<?php echo Yii::t('main', 'Salasana')?>" required>
                            <input type="submit" class="btn btn-lg" value="Kirjaudu">
                        </form>
                            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/salasanan_palauttaminen" class="link"><?php echo Yii::t('main', 'Unohditko salasanasi?'); ?></a>
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
                            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php" title="Return Home">
                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/img/logo.png" alt="Logo" class="logo_img">
                            </a>
                        </div>
                    </div>
                    <!-- End Logo-->

                    <!-- Nav-->
                    <div class="col-md-9 slogan">
                        Voita toiminnan haasteet
                    </div>
                    <!-- End Nav-->
                </div>
                <!-- End Row-->
            </div>
            <!-- End Container-->
        </header>
        <!-- End Header-->



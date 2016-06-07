<?php ?>



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





        <!-- Slide Section-->
        <div class="tp-banner-container">
            <div class="tp-banner">
                <ul>
                    <!-- SLIDE  01-->
                    <li data-transition="zoomout" data-slotamount="7" data-masterspeed="1500" >
                        <!-- MAIN IMAGE -->
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/img/slides/header3.jpg"   alt="kenburns6"  data-bgposition="center center" data-kenburns="on" data-duration="25000" data-ease="Linear.easeNone" data-bgfit="100" data-bgfitend="120" data-bgpositionend="center top">

                        <!-- LAYER NR. 1 -->

                        <div class="tp-caption large_text lft boxi"
                            data-x="55"
                            data-y="150"
                            data-speed="100"
                            data-start="500"
                            data-easing="Power4.easeOut"
                            data-splitin="chars"
                            data-splitout="none"
                            data-elementdelay="0.1"
                            data-endelementdelay="0.1"
                            data-endspeed="300"
                            style="z-index: 5; max-width: 700px; width:50%; line-height: 60px; max-height: auto; white-space: normal;">
                            Markkinoiden paras
                        </div>
                        <!-- END LAYER NR. 1 -->

                        <!-- LAYER NR. 2 -->
                        <div class="tp-caption large_bold_white lft"
                            data-x="60"
                            data-y="210"
                            data-speed="500"
                            data-start="1400"
                            data-easing="Power4.easeOut"
                            data-splitin="none"
                            data-splitout="none"
                            data-elementdelay="0.1"
                            data-endelementdelay="0.1"
                            data-endspeed="300"
                            style="z-index: 5; max-width: 700px; line-height: 60px; max-height: auto; white-space: normal;">
                            MOBIILITYÖKALU
                        </div>
                        <!-- END LAYER NR. 2 -->
                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption small_light_white sfb stb"
                            data-x="60"
                            data-y="270"
                            data-speed="500"
                            data-start="1200"
                            data-splitin="none"
                            data-splitout="none"
                            data-easing="easeOutExpo"
                            style="font-size: 20px;"><br>Kehitetty työnajan hallintaan puhdistusalan työntekijöille ja esimiehille.
                        </div>

                    </li>
                    <!-- END SLIDE  01-->

                    <!-- SLIDE 02-->
                    <li data-transition="zoomout" data-slotamount="7"  data-masterspeed="1500">
                        <!-- MAIN IMAGE -->
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/img/slides/header0.jpg"  alt="slidebg1"  data-bgposition="left center" data-kenburns="on" data-duration="10000" data-ease="Linear.easeNone" data-bgfit="130" data-bgfitend="100" data-bgpositionend="right center">

                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption large_text lft boxi"
                            data-x="55"
                            data-y="150"
                            data-speed="100"
                            data-start="500"
                            data-easing="Power4.easeOut"
                            data-splitin="chars"
                            data-splitout="none"
                            data-elementdelay="0.1"
                            data-endelementdelay="0.1"
                            data-endspeed="300"
                            style="z-index: 5; max-width: 700px; width:50%; line-height: 60px; max-height: auto; white-space: normal;">Kehitetty tehostamaan
                        </div>

                        <!-- LAYER NR. 2 -->
                        <div class="tp-caption large_bold_white sft stb"
                            data-x="60"
                            data-y="200"
                            data-speed="300"
                            data-start="1000"
                            data-splitin="none"
                            data-splitout="none"
                            data-easing="easeOutExpo">TOIMINTAA
                        </div>

                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption small_light_white sfb stb"
                            data-x="65"
                            data-y="265"
                            data-speed="500"
                            data-start="1200"
                            data-splitin="none"
                            data-splitout="none"
                            data-easing="easeOutExpo"
                            style="font-size: 20px;"><br>Suunniteltu yhteistyössä  puhdistusalan ammattilaisten kanssa.
                        </div>

                    </li>
                    <!-- END SLIDE 02-->

                </ul>
                <div class="tp-bannertimer"></div>
            </div>
        </div>
        <!-- End Slide Section-->



        <section class="box-action">
            <div class="container">
                <div class="title">
                    <p class="lead">Tilaa Etunti -uutiskirje</p>
                </div>
                <div class="button">
                    <a href="#liitytestaajaksi" >TÄSTÄ</a>
                </div>
            </div>
        </section>

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
                            style="font-size: 20px;">työajan hallintaan. Kehitetty siivousyrityksen työntekijöille ja esimiehille.
                        </div>

                    </li>
                    <!-- END SLIDE  01-->

                    <!-- SLIDE 02-->
                    <li data-transition="zoomout" data-slotamount="7"  data-masterspeed="1500">
                        <!-- MAIN IMAGE -->
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/img/slides/header0.jpg"  alt="slidebg1"  data-bgposition="left center" data-kenburns="on" data-duration="10000" data-ease="Linear.easeNone" data-bgfit="130" data-bgfitend="100" data-bgpositionend="right center">

                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption large_text boxi lft stl"
                            data-x="60"
                            data-y="160"
                            data-speed="300"
                            data-start="800"
                            data-splitin="none"
                            data-splitout="none"
                            data-easing="easeOutExpo">Kehitetty tehostamaan
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
                            style="font-size: 20px;">yhteistyössä siivousalan ammattilaisten kanssa.
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
                    <p class="lead">Liity nyt testiryhmään ja tienaa 500 €</p>
                </div>
                <div class="button">
                    <a href="#liitytestaajaksi" >TÄSTÄ</a>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->
                        <h1 class="title-subtitle text-center">Haluatko kehittää työnhallintaa?
                            <span>
                              Etunti on erinomainen ratkaisu yritystoimintaasi varten.
                            </span>
                        </h1>
                        <hr>
                    <!-- End Titles Heading -->
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 lead">
                            <p>
                            Etunnin avulla pidät kaikki yrityksesi toiminnot ajan tasalla helposti. Kattavalla työnhallintaratkaisulla parannat palveluitasi, lisäät työntekijöitesi ja asiakkaitesi keskinäistä luottamusta ja tehostat liiketoimintaasi.
                            </p>
                            <p class="">
                            Ei enää kirjautumisia useisiin eri ohjelmistoihin, epäselvyyksiä työtunneissa tai unohdettuja asiakaskäyntejä!</p>
                        <h2 class="title-subtitle text-center">Miksi Etunti on paras?
                        </h2>
                        <ul class="stars">
                            <li>Säästät 20-50 % yrityksesi henkilöstökuluja</li>
                            <li>Laskutusohjelman avulla säästät jopa 2 työpäivää laskuttajan työajasta kuukausittain</li>
                            <li>Online siivousvarauskalenterin avulla lisäät myyntiä</li>
                            <li>CRM asiakuudenhallintajärjestelmän avulla lisäät asiakasuskollisuutta</li>
                            <li>Edistyksellisellä suositustyökalulla saat lisämyyntiä</li>
                            <li>Manager-raportointityökalulla kasvatat liikevaihtoasi jopa 5 % vuodessa</li>
                        </ul>
                        <br>
                        <h3>OTA YHTEYTTÄ JA HANKI YRITYKSELLESI ETUNTI.FI -OHJELMA:</h3>
                        <p>Veiko Põldkivi<br>
email: etunimi.sukunimi@etunti.fi<br>
p. 040 761 4366
</p>
                        </div>
                    </div>
                </div>
                <!-- End Container-->
            </div>
        </section>        <!-- Services -->
        <section class="content_info modulit">

                <div class="container paddings">
                    <div class="row">
                        <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
                            <div class="boxes-info etuntibox etuntibox-iso selected pakollinen">
                                <div class="cont">
                                <h3>Etunti mobiilityökalu</h3>
                                <h5>Työajanseuranta liikkuvalle työlle + Mobiilisovellus</h5>
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".e-mobiili">Tutustu</button>
                                <div class="check"><div class="checkbox"><i class="fa fa-check"></i></div></div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade e-mobiili">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3>Etunti mobiilityökalu</h3>
                                    <h4>Työajanseuranta liikkuvalle työlle ja Mobiilisovellus</h4>
                                  </div>
                                  <div class="modal-body">
                                     <p>Etunti seuraa reaaliajassa, missä työntekijäsi liikkuvat. Työntekijäsi kuittaavat tehdyt työt ja käytetyt matka-ajat helposti kännykällä. Työtunnit kirjataan ja lasketaan oikein, eikä manuaalisia tuntiraportteja enää tarvita.
                                    <br><br>
                                    Voit lähettää viestejä ja ohjeita työntekijällesi ja hyväksyttää tehdyt tunnit asiakkaalla sähköisesti. Lisämoduuleilla voit virittää oman Etuntisi säästämään aikaa ja rahaa.</p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                    </div>
                    <div class="titles-heading">
                        <h2>Lisätyökalut auttavat sinua menestymään
                            <span>
                              <i class="fa fa-star"></i>
                              Paranna yrityksesi tuottavuutta Etunti -työkaluilla
                              <i class="fa fa-star"></i>
                            </span>
                        </h2>
                    </div>
                    <!-- Row fuid-->
                    <div class="row ominaisuudet">
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="boxes-info selected etuntibox">
                                <div class="cont">


                                <h3>Etunti Työvuoro</h3>
                                <h5>Työvuorojen suunnittelu</h5>

                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".keikat">Tutustu</button>
                                <div class="check"><div class="checkbox"><i class="fa fa-check"></i></div></div>
                                </div>
                            </div>
                           <!-- Modal -->
                            <div class="modal fade keikat">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3>Työvuorokalenteri</h3>
                                    <h4>Työvuorojen suunnittelu</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Lähetä työvuorot työntekijälle matkapuhelimeen yhdellä klikkauksella suoraan työvuorolistalta.</p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="boxes-info etuntibox">
                                <div class="cont">
                                <h3>Etunti CRM</h3>
                                <h5>Asiakkuuksien hallinta</h5>
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".crm">Tutustu</button>
                                <div class="check"><div class="checkbox"><i class="fa fa-check"></i></div></div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade crm">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3>Etunti CRM</h3>
                                    <h4>Asiakkuuksien hallinta</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Hallitse tarjouksia, sopimuksia ja työnkuvauksia samassa paikassa. Lähetä sopimukset asiakkaalle hyväksyttäväksi suoraan sovelluksesta. Asiakkaasi voivat tarkastella työvuoroja, laskuja ja sopimukseen liittyviä asioita Etunti CRM -työkalun avulla. Lisäksi lähetä helposti verkkokirjeitä ja mainoksia tarjouksista ja palveluista.</p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                        <div class="clearfix visible-sm-block"></div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="boxes-info etuntibox">
                                <div class="cont">
                                <h3>Etunti Manager</h3>
                                <h5>Raportointi ja seuranta</h5>
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".teho">Tutustu</button>
                                <div class="check"><div class="checkbox"><i class="fa fa-check"></i></div></div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade teho">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3>Etunti Manager</h3>
                                    <h4>Työnjohdon raportointi- ja seurantatyökalu</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Puutuuko yritykseltäsi nopea ja selkeä tilannekuva toteutuneista työ- ja matkatunneista, liikevaihdosta, työn- ja työntekijän tai asiakkaiden tuottavuudesta? Etunti Managerilla saat nopeasti kuvan yrityksesi tuottavuudesta. Täsmällinen tieto, oikeat päätökset oikeaan aikaan- ja kasvatat yrityksesi liikevoittoa jopa 5 % vuodessa.<br><br></p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                        <div class="clearfix visible-md-block visible-lg-block"></div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="boxes-info etuntibox margin-top0">
                                <div class="cont">
                                <h3>Etunti Laskutus</h3>
                                <h5>Tehokas laskutusohjelma</h5>
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".massi">Tutustu</button>
                                <div class="check"><div class="checkbox"><i class="fa fa-check"></i></div></div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade massi">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3>Etunti Laskutus</h3>
                                    <h4>Etunti Laskutus on markkinoiden paras siivousalan yrityksille suunnattu laskutusohjelma.</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Lähetä toteutuneista työtunneista muodostettavat laskut suoraan asiakkaille verkkolaskuina tai sähköpostiin. Tarkista avoimet saatavat ja tee maksumuistutukset helpommin kuin koskaan aikaisemmin. Etunti Laskutus on markkinoiden paras siivousalan yrityksille suunnattu laskutusohjelma. Säästä laskuttajan työajasta jopa kahden työpäivän tunnit kuukausittain.</p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                        <div class="clearfix visible-sm-block"></div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="boxes-info etuntibox margin-top0">
                                <div class="cont">
                                <h3>Etunti Online</h3>
                                <h5>Online-varauskalenteri ja tilaus</h5>
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".online">Tutustu</button>
                                <div class="check"><div class="checkbox"><i class="fa fa-check"></i></div></div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade online">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3>Etunti Online</h3>
                                    <h4>Online-varauskalenteri ja tilaus</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Ota käyttöösi markkinoiden paras muokattavissa oleva varauskalenteri, jonka integroimme suoraan kotisivuillesi. Se toimii saumattomasti yhteen muiden Etunti -ohjelman työkalujen kanssa. Tee lisämyynti Online –varauskalenterin avulla helpommaksi ja nopeammaksi kuin kilpailijasi.</p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="boxes-info etuntibox disabled margin-top0">
                                <div class="cont">
                                <h3>TULOSSA...</h3>
                                <h5>Kehitämme Etuntia jatkuvasti</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Row fuid-->



                <div class="fixedbottom text-center pyydatarjous">
                    <a href="#" class="sulje">&times;</a>
                    <p><a href="#" class="btn btn-lg btn-default tarjouspyynto" data-toggle="modal" data-target=".pyydatarjous_lomake">Pyydä tarjous</a> <span>valitusta kokonaisuudesta.</span></p>
                </div>
                <!-- Modal -->
                  <div class="modal fade pyydatarjous_lomake">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h3>Pyydä tarjous</h3>
                        </div>
                        <div class="modal-body">
                          <h5>Valittu kokonaisuus:</h5>
                          <p class="valitut_modulit"></p>
                          <?php include Yii::app()->request->baseUrl.'/assets_classic/inc/lomake_tarjouspyynto.php'; ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                        </div>
                      </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                  </div>
                <!-- /.modal -->
                </div>
                <!-- End Container-->

        </section>
        <!-- End Services-->
        <section class="content_info testeri">
            <div class="padding-bottom padding-top green border-top border-white">
                <div class="container">
                    <div class="row">
                    <div class="col-md-6">
                        <h2>Etunti testeri</h2>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                              Testaa, kuinka Etunti tehostaisi työnkulkuasi ja toisi lisäpotkua liiketoimintaasi.
                            </p>
                            <p>
                             Testerin avulla tunnistat yritystoimintasi mahdollisia pullonkauloja ja saat juuri sinun yrityksellesi sopivan ratkaisuehdotuksen, jolla teet lisää rahaa.
                            </p>

                        </div>
                        <div class="col-md-5 col-md-offset-1">
                        <p>Tee testi. Vastauksen saat heti.</p>
                            <div class="">

                            <button type="button"  class="btn btn-xl btn-primary" role="button" data-toggle="modal" data-target=".testeri-modal">TESTAA</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          <?php include Yii::app()->request->baseUrl.'/assets_classic/inc/testeri.php'; ?>
            <div class="paddings grey-white border-top border-white hidden">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
                            <p>Selitystekstiä... Pellentesque habitant morbi senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam. Pellentesque habitant morbi senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam.</p>
                            <form id="newsletterForm" action="php/mailchip/newsletter-subscribe.php">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    <input class="form-control" placeholder="Sähköpostiosoite" name="email"  type="email" required="required">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" name="subscribe" >Lähtä</button>
                                    </span>
                                </div>
                            </form>
                            <div id="result-newsletter"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="content_info" id="liitytestaajaksi">
            <div class="padding-bottom white">
                <div class="container">
                        <div class="titles-heading">
                        <h2>Liity testiryhmään
                            <span>
                              <i class="fa fa-cogs"></i>
                              Vaikuta sovelluksen kehitykseen korvauksen kera
                              <i class="fa fa-cogs"></i>
                            </span>
                        </h2>
                        </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-5 padding-bottom">
                            <p>Etsimme vielä muutamia pilottiasiakkaita osallistumaan Etunti- sovelluksen viimeistelyyn. Jos olet idearikas ja peloton pilotti, ota yhteyttä. Saat mukanaolostasi 500 € ja Etunti- sovelluksen käyttöösi veloituksetta kolmeksi kuukaudeksi. Toimi nopeasti, sillä paikkoja on rajoitetusti!</p>
<!--                             <form id="newsletterForm" action="php/mailchip/newsletter-subscribe.php">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    <input class="form-control" placeholder="Sähköpostiosoite" name="email"  type="email" required="required">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" name="subscribe" >Lähetä</button>
                                    </span>
                                </div>
                            </form> -->
                        </div>
                        <div class="col-md-6 col-sm-7">
                          <?php //include Yii::app()->request->baseUrl.'/assets_classic/inc/lomake_testiryhma.php'; ?>
                          <?php //include 'inc/test.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php include Yii::app()->request->baseUrl.'/assets_classic/inc/footer.php'; ?>

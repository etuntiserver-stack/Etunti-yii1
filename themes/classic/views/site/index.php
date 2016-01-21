<?php ?>
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
                        
<div class="text-center">
<p>
Etunti on monipuolinen ja kattava ohjelma, jonka avulla
yritystoimintaa on helppo hallita, organisoida ja kehittää. Ohjelma toimii mobiilissa ja on vaivatonta 
käyttää. Etunnin avulla helpotat huomattavasti yrityksen liiketoimintaa, säästät aikaa ja parannat samalla 
tuottavuutta sekä kehität palveluita. 
</p>
<p>
Etunnin perus- ja lisätyökalut ovat suunniteltu ratkaisemaan puhdistusalan 
tyypilliset ongelmat. Yhden ohjelman avulla pyörität yritystoimintaasi ennennäkemättömän helposti. </p>

<p>
                        <h1 class="title-subtitle text-center">
                            <span>
                              Etunti on yritystoiminnan herätyskello!
                            </span>
                        </h1>

</p>
</div>
                        <hr>

                        <h2 class="title-subtitle text-center">Miksi Etunti on paras?
                        </h2>
                        <ul class="stars">
                            <li>Säästät 20 - 50 % yrityksesi henkilöstökuluissa</li>
                            <li>Työvuoro-ohjelmalla säästät työaikaasi 10 – 20 % ja kirjaat toistuvat työvuorot helposti</li>
                            <li>CRM -asiakuudenhallintajärjestelmän avulla hoidat asiakassuhdetta tehokkaasti</li>
                            <li>Manager -raportointityökalulla kasvatat liikevoittoasi jopa 5 % vuodessa</li>
                            <li>Laskutusohjelmalla säästät jopa 2 työpäivää kuukaudessa</li>
                            <li>Online -varauskalenterin avulla saat lisää myyntiä vaivattomasti</li>
                        </ul>
                        <br>
                        <h3>Hanki yrityksellesi Etunti -ohjelma.<br>Ota yhteyttä:</h3>
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
                                <h3>Etunti Mobiili</h3>
                                <h5>Työajanseuranta liikkuvalle työlle ja Mobiilisovellus</h5>
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
                                    <h3>Etunti Mobiilityökalu</h3>
                                    <h4>Työajanseuranta liikkuvalle työlle ja mobiilisovellus</h4>
                                  </div>
                                  <div class="modal-body">
                                     <p>Seuraa reaaliajassa, missä työntekijäsi liikkuvat. Työntekijäsi kuittaavat tehdyt työt ja käytetyt matka-ajat helposti kännykällä. Työtunnit kirjataan ja lasketaan oikein, eikä manuaalisia tuntiraportteja tarvita enää.
                                    <br><br>
                                    Voit lähettää viestejä ja ohjeita työntekijällesi ja hyväksyttää tehdyt tunnit asiakkaalla sähköisesti.</p>
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
                                    <h3>Etunti Työvuoro</h3>
                                    <h4>Työvuorojen suunnittelu</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Säästät työaikaasi, kun voit lähettää työvuorot työntekijöille suoraan heidän matkapuhelimeensa yhdellä klikkauksella. Pystyt tekemään toistuvat työvuorot kätevästi eteenpäin, jolloin jokaista työvuoroa ei tarvitse erikseen kirjoittaa. Työvuorot eivät unohdu ja asiakkaat saavat tilaamansa palvelun.</p>
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
                                    <p>Hallitset tarjouksia, sopimuksia ja työnkuvauksia samassa paikassa. Lähetät sopimukset asiakkaalle hyväksyttäväksi suoraan sovelluksesta. Asiakkaasi voivat tarkastella työvuoroja, laskuja ja sopimukseen liittyviä asioita Etunti CRM -työkalun avulla. Lisäksi lähetät helposti verkkokirjeitä ja mainoksia palveluista.</p>
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
                                    <h4>Raportointi ja seuranta</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Puutuuko yritykseltäsi nopea ja selkeä tilannekuva toteutuneista työ- ja matkatunneista, liikevaihdosta, työn, työntekijän tai asiakkaiden tuottavuudesta? Etunti Managerilla saat nopeasti kuvan yrityksesi tuottavuudesta. Täsmällinen tieto ja oikeat päätökset oikeaan aikaan kasvattaa yrityksesi liikevoittoa jopa 5 % vuodessa.<br>

				    Etunti Manager on työnjohdon raportointi- ja seurantatyökalu.
					<br></p>


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
                                    <h4>Tehokas laskutusohjelma.</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Etunti Laskutus on markkinoiden paras siivousalan yrityksille suunnattu laskutusohjelma. Lähetät toteutuneista työtunneista muodostettavat laskut suoraan asiakkaille verkkolaskuina tai sähköpostiin. Tarkistat avoimet saatavat ja teet maksumuistutukset helpommin kuin koskaan aikaisemmin. Säästät laskuttajan työajasta jopa kahden työpäivän tunnit kuukausittain.</p>
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
                                <h5>Online -varauskalenteri ja tilaus</h5>
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
                                    <h4>Online -varauskalenteri ja -tilaus</h4>
                                  </div>
                                  <div class="modal-body">
                                    <p>Saat käyttöösi markkinoiden parhaan muokattavissa olevan varauskalenterin, jonka yhdistämme suoraan kotisivuillesi. Se toimii saumattomasti yhteen muiden Etunti -ohjelman työkalujen kanssa. Teet lisämyyntiä Online –varauskalenterin avulla helpommaksi ja nopeammaksi kuin kilpailijasi.</p>
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
                          <?php $this->renderPartial('/site/lomake_tarjouspyynto'); ?>
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
                              Testaa, miten Etunti -ohjelma tehostaisi yrityksesi työnkulkua ja toisi liiketoimintaan lisäpotkua.
                            </p>
                            <p>
                             Testerin avulla tunnistat yritystoimintasi mahdolliset kehityskohteet ja saat testillä ratkaisuehdotuksen heti.
                            </p>

                        </div>
                        <div class="col-md-5 col-md-offset-1">
                            <div class="">

                            <button type="button"  class="btn btn-xl btn-primary" role="button" data-toggle="modal" data-target=".testeri-modal">TEE TESTI</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




<?php
$kysymykset = array();

$kysymykset[] = array(
'kysymys' => 'Tiedätkö tarkkaan työntekijöidesi sijainnin?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Hyvä juttu! Pystyt näinollen seuraamaan työntekijöitä ja tiedät heidän tuottavuuden. Saat tietoosi työhön käytetyn tarkan ajan ja maksat palkkaa vain tehdystä työstä. Onko työntekijöiden seuraaminen reaaliaikaista ja onko sijaintitieto luotettavaa? Etunti – ohjelman avulla saat työntekijän tarkan sijainnin, jolloin näet luotettavasti onko hän ollut oikeaan aikaan, oikeassa paikassa ja oikean ajan. Saat tehdyt tunnit heti ja voit lähettää ne helposti suoraan palkanlaskentaan.',
'moduli' => '',
);

$kysymykset[] = array(
'kysymys' => 'Joudutko syöttämään työntekijä- ja asiakastietoja sekä työnkuvauksia useaan paikkaan?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Kuulostaa siltä, että yrityksen eri tiedot ovat hyvin sekaisin ja järjestystä on vaikea pitää yllä. Saattaa olla, että käytätte liikaa arvokasta työaikaa tietojen etsimiseen ja moni pienempi asia katoaa kokonaan. Mitä useammassa paikassa tiedot ovat, niin sitä vaikeampaa yritystoiminnan organisointi on ja yrityksestä ei saa irti parasta tuottoa. Sinun onneksi meillä on tarjota Etunti – ohjelma helpottamaan liiketoimintaanne. Etunti – ohjelman avulla pidät kaikki tiedot yhdessä luotettavassa paikassa ja organisoit yrityksen toimintaa aivan uudella tavalla. Voit valita eri työkaluja yrityksen tarpeisiin ja saada merkittäviä säästöjä joka kuukausi.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Vievätkö työpisteiden väliset siirtymäajat mielestäsi liikaa liiketoimintasi kannattavuutta?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Työnorganisointi kaipaa selvästi tehostusta. Siirtymäajat ovat pakollisia ja yritykselle menoja tuottavia, mutta kohteet voidaan suunnitella aina siten, että siirtymäajat ovat minimimittaisia. Siirtymäaikojen ollessa lyhyet, niin työntekijät kerkeävät käymään useammassa kohteessa saman päivän aikana. Etunti Manager – työkalun ansiosta näet yrityksesi tuottavuuden, matkoihin käytetyn ajan ja muuta tietoa yrityksestä ja näet missä asioissa yritys voi vielä kehittyä lisää.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Tiedätkö myynnin kannattavuus- ja talousluvut reaaliaikaisesti?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Hienoa! Näin tiedät millä tasolla yrityksesi on ja mitkä asiat kaipaavat kehitystä. Pystytkö näkemään helposti kaiken tiedon yrityksestäsi? Etunti Manager – työkalun ansiosta näet yrityksesi tuottavuuden, matkoihin käytetyn ajan, toteutuneet työtunnit ja työntekijän tuottavuuden sekä saat tehtyä raportteja, jolla huomaat helposti, jos liiketoimintasi kaipaa parantelua.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Pystytkö lähettämään asiakkaallesi helposti ja vaivattomasti markkinointikirjeitä ja sopimuksia?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Hyvä asia! Ilman asiakkaita ei yritys pyöri ja hyvä, jos voit viestittää asiakkaille helposti ja
vaivattomasti. Entä näkevätkö asiakkaat helposti työvuoroja, sopimuksia ja laskuja? Etunti CRM – työkalulla lähetät helposti uutiskirjeitä, sopimuksia ja tarjouksia sähköisesti asiakkaalle ja samalla asiakkaat näkevät omat tietonsa CRM:n kautta. Ohjelman ansiosta sinun ei tarvitse enää ajaa asiakaskäynnille sopimusten ja tarjousten takia, vaan voi lähettää ne heti asiakkaan luettavaksi ja hyväksyttäväksi. Säästät aikaa, rahaa ja hermoja, kun ei tarvitse enää istua turhaan ratin takana.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Viekö laskujen muodostaminen ja lähettäminen suhteettomasti aikaa?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Oletko laskuja laittaessa ikinä miettinyt, että kunpa tämänkin voisi tehdä vain napin painalluksella? Tämä on nyt mahdollista! Etunti Laskutus – ohjelmalla laskut voi lähettää sähköisesti suoraan asiakkaalle ja samalla ohjelmalla näet onko kaikki laskut maksettu. Helposti lähetät maksumuistutuksia samalla ohjelmalla. Ei enää erillistä kirjautumista pankin sivuille ja laskupinon selaamista, vaan kaikki näkyy järjestyksessä yhdestä paikasta. Näin säästät jopa kaksi kokonaista työpäivää kuukaudessa.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Pystyykö asiakkaasi varaamaan vaivattomasti kalenterista puhdistuspalveluja?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Onneksi olkoon, harvalla on tällainen palvelu käytössä. Online varaaminen on nykypäivää ja sen suosio kasvaa jatkuvasti. Onko sinun yrityksesi varauspalvelu asiakkaalle helppo ja selkeä? Saako asiakas varattua siivoojan vaikka samalle päivälle? Jos vastasit vielä kyllä, niin yrityksesi on hyvin ajan tasalla. Etunti Online – ohjelma tarjoaa kotisivuusi asennettavan ajanvarausjärjestelmän, jolla asiakkaat voivat varata nopeasti puhdistuspalveluja ja ohjelma toimii hyvin yhteen muiden Etunti – työkalujen kanssa.',
'moduli' => '',
);

?>

<div class="modal fade testeri-modal">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3>Etunti testeri</h3>
      </div>
      <div class="modal-body">
        <div class="kylla otsikko"><span>KYLLÄ</span><span>EI</span></div>
        <ul class="kysymykset">
        <?php
            foreach ($kysymykset as $key => $value) {
                echo "<li class=\"{$value['moduli']} {$value['haluttu_valinta']}\">
                {$value['kysymys']}
                <div class=\"radio\">
                    <div class=\"checkbox kylla\"><i class=\"fa fa-check\"></i></div>
                    <div class=\"checkbox ei\"><i class=\"fa fa-check\"></i></div>
                </div>
                <div class=\"vastaus\">{$value['vastaus']}</div>
                </li>";
            }
         ?>

        </ul>
      </div>
      <div class="modal-footer">
        <div class="testerintulos"></div>
        <button type="button" class="btn btn-lg btn-primary">Näytä tulos</button>
        <a href="#" class="btn btn-lg btn-default tarjouspyynto" data-toggle="modal" data-target=".pyydatarjous_lomake">Lähetä tulos sähköpostiini</a>
        <a href="#" class="btn btn-lg btn-default tarjouspyynto" data-toggle="modal" data-target=".pyydatarjous_lomake">Pyydä tarjous</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


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
                              <i class="fa fa-star"></i>
                              Vaikuta sovelluksen kehitykseen korvauksen kera
                              <i class="fa fa-star"></i>
                            </span>
                        </h2>
                        </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-5 padding-bottom">
                            <p>Etsimme vielä muutamia pilottiasiakkaita osallistumaan Etunti- ohjelman viimeistelyyn. Saat aktiivisesta mukanaolostasi 500 € ja Etunti -ohjelman käyttöösi veloituksetta kolmeksi kuukaudeksi. 
<p>Jos olet idearikas ja peloton pilotti, ota yhteyttä. 
<br>
Toimi nopeasti, sillä paikkoja on rajoitetusti!</p></p>
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
			  <?php $this->renderPartial('/site/lomake_testiryhma'); ?>
                          <?php //include Yii::app()->request->baseUrl.'/assets_classic/inc/lomake_testiryhma.php'; ?>
                          <?php //include 'inc/test.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>





        <!-- footer Center-->
        <footer class="footer-center">
            <div class="container-fluid">

                <!-- Info Top - Footer Center-->

                <div class="row">
                   <div class="col-md-3 col-sm-6 item-center">
                        <h3>+358 40 761 4366</h3>

                        <a href="#">
                        <i class="fa fa-phone"></i>
                        <h4>Soita</h4>
                        </a>
                   </div>
                   <div class="col-md-3 col-sm-6 item-center col-sm-offset-2">
                        <h3><a href="mailto:myynti@etunti.fi">veiko.poldkivi@etunti.fi</a></h3>

                       <a href="#">
                        <i class="fa fa-envelope"></i>
                        <h4>Lähetä viesti</h4>
                        </a>
                   </div>


                   <div class="col-md-3 col-sm-6 item-center col-xs-offset-1">
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

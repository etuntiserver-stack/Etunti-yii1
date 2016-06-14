<?php $this->renderPartial('/site/header'); ?>



<ul class="steps expanded even-4">
    <li class="active"><?php echo CHtml::link('Etusivu', Yii::app()->request->baseUrl.'/index.php/site/index'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Ajankohtaista', Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Asiakkaat', Yii::app()->request->baseUrl.'/index.php/site/asiakkaat'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yritys', Yii::app()->request->baseUrl.'/index.php/site/yritys'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yhteystiedot', Yii::app()->request->baseUrl.'/index.php/site/yhteystiedot'); ?></li>
</ul>



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
tuottavuutta sekä kehität palveluja. 
</p>
<p>
Etunnin perus- ja lisätyökalut ovat suunniteltu ratkaisemaan puhdistusalan 
tyypilliset ongelmat. Yhden ohjelman avulla pyörität yritystoimintaasi ennen näkemättömän helposti. </p>

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
                        <p>
etuntimyynti@etunti.fi<br>
+358 40 124 9081
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
                                     <p>Työntekijäsi kuittaavat tehdyt työt ja käytetyt matka-ajat helposti kännykällä. Työtunnit kirjataan ja lasketaan oikein, eikä manuaalisia tuntiraportteja enää tarvita ja tiedät reaaliajassa missä työntekijäsi liikkuvat.
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
                                    <p>Puuttuuko yritykseltäsi nopea ja selkeä tilannekuva toteutuneista työ- ja matkatunneista, liikevaihdosta, työn, työntekijän tai asiakkaiden tuottavuudesta? Etunti Managerilla saat nopeasti kuvan yrityksesi tuottavuudesta. Täsmällinen tieto ja oikeat päätökset oikeaan aikaan kasvattaa yrityksesi liikevoittoa jopa 5 % vuodessa.<br>

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
                                    <h4>Tehokas laskutusohjelma</h4>
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
                                <h5>Online -varauskalenteri ja -tilaus</h5>
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
            <div class="padding-bottom padding-top green border-top border-white" style="padding: 0; margin:0;">
                <div class="container">

                    <div class="row">
                        <div class="col-md-6" style="padding: 20px;border-right:4px white solid;">

                            <h2>Etunti testeri</h2>
                            <p>
                              Testaa, miten Etunti -ohjelma tehostaisi yrityksesi työnkulkua ja toisi liiketoimintaan lisäpotkua.
                            </p>
                            <p>
                             Testerin avulla tunnistat yritystoimintasi mahdolliset kehityskohteet ja saat testillä ratkaisuehdotuksen heti.
                            </p>

                            <button type="button"  class="btn btn-xl btn-primary" role="button" data-toggle="modal" data-target=".lataailmainen_lomake">TEE TESTI</button>

                        </div>
                        <div class="col-md-6" style="padding: 20px;">

                            <h2>Pyydä ilmainen Etunti esittely</h2>
                            <p>
                              Pyydä yritykseesi ilmainen Etunti esittely. Kartoitamme yrityksesi tuottavuuden ja pohdimme yhdessä, millä tavalla yritystoimintaa voi tehostaa. 
                            </p>

                            <button type="button"  class="btn btn-xl btn-primary" role="button" data-toggle="modal" data-target=".pyyda-modal">PYYDÄ ESITTELY</button>

                        </div>
                    </div>
                </div>
            </div>










<?php
$kysymykset = array();

$kysymykset[] = array(
'kysymys' => 'Tiedätkö tarkkaan työntekijöidesi sijainnin?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Hyvä juttu! Pystyt näin ollen seuraamaan työntekijöitä ja tiedät heidän tuottavuuden. Saat tietoosi työhön käytetyn tarkan ajan ja maksat palkkaa vain tehdystä työstä. Onko työntekijöiden seuraaminen reaaliaikaista ja onko sijaintitieto luotettavaa? Etunti – ohjelman avulla saat työntekijän tarkan sijainnin, jolloin näet luotettavasti onko hän ollut oikeaan aikaan, oikeassa paikassa ja oikean ajan. Saat tehdyt tunnit heti ja voit lähettää ne helposti suoraan palkanlaskentaan.',
'vastaus2' => 'Monella muullakin yrityksellä on sama ongelma, mutta onneksi ongelma on ratkaistavissa! Työnantajalla on oikeus tietää missä työntekijät ovat? Tekevätkö he työtä määrätyn ajan? Missä yrityksen autolla ajetaan? Saattaa olla, että maksat työntekijöille täyttä päivää, mutta todellisuudessa he eivät ehkä tee sitä ja etkä voi olla asiasta mitenkään varma. Työntekijöiden seuranta parantaa yrityksen liiketoimintaa huomattavasti, kun työntekijöiden työnkulun seurantaan on panostettu. Ohjelmasta saa myös selville, missä yrityksen autolla ajetaan. Etunti – ohjelmalla työ tehostuu ja maksat työntekijöille tehdystä työstä.',
'moduli' => '',
);

$kysymykset[] = array(
'kysymys' => 'Joudutko syöttämään työntekijä- ja asiakastietoja sekä työnkuvauksia useaan paikkaan?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Kuulostaa siltä, että yrityksen eri tiedot ovat hyvin sekaisin ja järjestystä on vaikea  ylläpitää. Saattaa olla, että käytätte liikaa arvokasta työaikaa tietojen etsimiseen ja moni pienempi asia katoaa kokonaan. Mitä useammassa paikassa tiedot ovat, niin sitä vaikeampaa yritystoiminnan organisointi on ja yrityksestä ei saa irti parasta tuottoa. Sinun onneksi meillä on tarjota Etunti – ohjelma helpottamaan liiketoimintaanne. Etunti – ohjelman avulla pidät kaikki tiedot yhdessä luotettavassa paikassa ja organisoit yrityksen toimintaa aivan uudella tavalla. Voit valita eri työkaluja yrityksen tarpeisiin ja saada merkittäviä säästöjä joka kuukausi.',
'vastaus2' => ' Hienoa! Säästät huomattavasti aikaa ja vaivaa, kun kaikki tiedot ovat yhdessä paikassa ja ne eivät mene sekaisin eikä tietoja unohdu. Kannattaa miettiä onko ohjelma luotettava, johon kirjaat kaikki tiedot? Ovatko yksittäiset tiedot helposti löydettävissä? Onko tiedot organisoitu loogisesti? Vaikka tiedot löytyvät yhdestä paikasta, niin ne saattavat olla epäselvästi esillä tai niistä puuttuu yhtenäisyys, jolloin uusia tietoja on vaikea lisätä, kun ei ole selkeää kuvaa, miten tiedot kirjataan. Etunti – ohjelmassa kaikki tiedot ovat samassa paikassa ja hyvässä järjestyksessä. Tietojen lisääminen ja poistaminen on helppoa, ja tietojen käsittelyn lisäksi voi tehdä monia muita asioita samassa ohjelmassa.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Vievätkö työpisteiden väliset siirtymäajat mielestäsi liikaa liiketoimintasi kannattavuutta?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Työnorganisointi kaipaa selvästi tehostusta. Siirtymäajat ovat pakollisia ja yritykselle menoja tuottavia, mutta kohteet voidaan suunnitella aina siten, että siirtymäajat ovat minimimittaisia. Siirtymäaikojen ollessa lyhyet työntekijät kerkeävät käymään useammassa kohteessa saman päivän aikana. Etunti Manager – työkalun ansiosta näet yrityksesi tuottavuuden, matkoihin käytetyn ajan ja muuta tietoa yrityksestä sekä näet myös missä asioissa yritys voi vielä lisäkehittyä.',
'vastaus2' => 'Erinomaista! Siirtymäajoista tulee vain yritykselle menoja, koska niitä ei voi asiakkailta veloittaa, joten on hyvä, jos siirtymäajat ovat mahdollisimman lyhyet. Tehokkaat siirtymäajat eivät kuitenkaan takaa yrityksen menestymistä, vaikka se itsessään onkin hyvä saavutus. Kannattaa tarkastaa onko yrityksen muutkin toiminnot tehokkaita. Onko laskujen lähettäminen ja rahaliikenteen seuranta tehokasta myös? Esimerkiksi Etunti Laskutus – työkalu tehostaa yrityksen rahaliikennettä. Tutustu tarkemmin Etunti – ohjelmaan ja sen tuomiin etuihin, joilla saat kaiken hyödyn irti omasta liiketoiminnastasi! Me autamme myös tässä selvitystyössä. Ota rohkeasti yhteyttä ja haasta!',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Tiedätkö myynnin kannattavuus- ja talousluvut reaaliaikaisesti?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Hienoa! Tiedät millä tasolla yrityksesi on ja mitkä asiat kaipaavat kehitystä. Pystytkö näkemään helposti kaiken tiedon yrityksestäsi? Etunti Manager – työkalun ansiosta näet yrityksesi tuottavuuden, matkoihin käytetyn ajan, toteutuneet työtunnit ja työntekijän tuottavuuden sekä saat tehtyä raportteja, jolla huomaat helposti, jos liiketoimintasi kaipaa parantelua.',
'vastaus2' => 'Yritykselle tärkeiden tietojen saaminen reaaliaikaisesti auttaa tiedostamaan yrityksen toiminnan tason. Raporteista näkyy käytetäänkö esimerkiksi matkoihin liikaa aikaa suhteessa työmäärään tai onko joku kustannus alkanut muuttua yllättäen. Kun yrityksen toiminnan muutoksen näkee reaaliajassa, niin niihin pystytään reagoimaan nopeammin. Suosittelemme yrityksellenne työkaluksi Etunti Manager -työkalua, jolla kaikki yrityksen tiedot ovat heti saatavillasi.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Pystytkö lähettämään asiakkaallesi helposti ja vaivattomasti markkinointikirjeitä ja sopimuksia?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Hyvä asia! Ilman asiakkaita ei yritys pyöri ja hyvä, jos voit viestittää asiakkaille helposti ja
vaivattomasti. Entä näkevätkö asiakkaat helposti työvuoroja, sopimuksia ja laskuja? Etunti CRM – työkalulla lähetät helposti uutiskirjeitä, sopimuksia ja tarjouksia sähköisesti asiakkaalle ja samalla asiakkaat näkevät omat tietonsa CRM:n kautta. Ohjelman ansiosta sinun ei tarvitse enää ajaa asiakaskäynnille sopimusten ja tarjousten takia, vaan voi lähettää ne heti asiakkaan luettavaksi ja hyväksyttäväksi. Säästät aikaa ja rahaa.',
'vastaus2' => ' Vaikuttaa siltä, että kulutat liikaa aikaasi markkinointikirjeiden ja sopimusten kanssa ja saatat istua pitkiäkin aikoja autossa, kun olet matkalla asiakkaan luokse sopimuksen kanssa. Voisit käyttää kaiken tuon ajan paremminkin, jos lähettäisit sopimukset, markkinointikirjeet ja muun yritysviestinnän suoraan asiakkaalle yhdellä klikkauksella. Etunti CRM -työkalu on juuri tätä varten kehitetty ja se sopii hyvin yritystoimintasi tarpeisiin. Sen lisäksi, että sinä saat asiakkaille lähetettyä kaiken tarvittavan vaivattomasti, niin asiakkaat näkevät omat tietonsa ja he voivat tarkastella mm. sopimuksia ja työtunteja.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Viekö laskujen muodostaminen ja lähettäminen suhteettomasti aikaa?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Oletko laskuja laittaessa ikinä miettinyt, että kunpa tämänkin voisi tehdä vain napin painalluksella? Tämä on nyt mahdollista! Etunti Laskutus – ohjelmalla laskut voi lähettää sähköisesti suoraan asiakkaalle ja samalla ohjelmalla näet onko kaikki laskut maksettu. Helposti lähetät maksumuistutuksia samalla ohjelmalla. Ei enää erillistä kirjautumista pankin sivuille ja laskupinon selaamista, vaan kaikki näkyy järjestyksessä yhdestä paikasta. Näin säästät jopa kaksi kokonaista työpäivää kuukaudessa.',
'vastaus2' => 'Erinomaista, jos yrityksessä laskut liikkuvat tehokkaasti eteenpäin. Tehokkaan laskujen lähettämisen lisäksi sinun tulisi pystyä seuraamaan helposti saatavia ja lähettää maksumuistutuksia. Jos näin ei ole, niin Etunti Laskutus – työkalu on yritystäsi varten. Sillä lähetät sähköisesti laskut nopeasti asiakkaille ja seuraat saapuvia maksuja ja lähetät maksumuistutuksia. Ohjelman avulla et tarvitse enää tuskailla pankin tai laskupinon kanssa.',
'moduli' => '',
);
$kysymykset[] = array(
'kysymys' => 'Pystyykö asiakkaasi varaamaan vaivattomasti kalenterista puhdistuspalveluja?',
'haluttu_valinta' => 'kylla',
'vastaus' => 'Onneksi olkoon, harvalla on tällainen palvelu käytössä. Online varaaminen on nykypäivää ja sen suosio kasvaa jatkuvasti. Onko sinun yrityksesi varauspalvelu asiakkaalle helppo ja selkeä? Saako asiakas varattua siivoojan vaikka samalle päivälle? Jos vastasit vielä kyllä, niin yrityksesi on hyvin ajan tasalla. Etunti Online – ohjelma tarjoaa kotisivuusi asennettavan ajanvarausjärjestelmän, jolla asiakkaat voivat varata nopeasti puhdistuspalveluja ja ohjelma toimii hyvin yhteen muiden Etunti – työkalujen kanssa.',
'vastaus2' => 'Online varaus on tätä päivää ja hyvänä kilpailuetuna puhdistusalalla on online -varausjärjestelmä, jonka saat kätevästi Etunti Online – työkalun avulla. Online – työkalu asennetaan yrityksen kotisivulle ja asiakkaat voivat varata puhdistuspalveluita helposti ja nopeasti jopa samalle päivälle. Varausjärjestelmää tehostamalla säästät kuluissa, samalla tehostat yrityksen toimintaa ja kasvatat liikevaihtoa.',
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
                <div class=\"vastaus\" style='display:none'>{$value['vastaus']}</div>
                <div class=\"vastaus2\" style='display:none'>{$value['vastaus2']}</div>
                </li>";
            }
         ?>

        </ul>
      </div>
      <div class="modal-footer">
        <!--<div class="testerintulos"></div>-->
        <button type="button" class="btn btn-lg btn-primary">Näytä tulos</button>
        <!--<a href="#" class="btn btn-lg btn-default tarjouspyynto" data-toggle="modal" data-target=".pyydatarjous_lomake">Lähetä tulos sähköpostiini</a>-->
        <a href="#" class="btn btn-lg btn-default tarjouspyynto" data-toggle="modal" data-target=".pyydatarjous_lomake">Pyydä tarjous</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade pyyda-modal">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3>PYYDÄ ESITTELY</h3>
      </div>
      <div class="modal-body">
	<span style="color: #333">
			  <?php $this->renderPartial('/site/lomake_testiryhma'); ?>
	</span>
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
                                        <button class="btn btn-primary" type="submit" name="subscribe" >Lähetä</button>
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
                        <h2>Jätä tarjouspyyntö ja tilaa Etunti -uutiskirje
                            <!--<span>
                              <i class="fa fa-star"></i>
                              Vaikuta sovelluksen kehitykseen korvauksen kera
                              <i class="fa fa-star"></i>-->
                            </span>
                        </h2>
                        </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-5 padding-bottom">
<p>Jättämällä yhteystietosi,<br>
olemme sinuun yhteydessä.  <br>
Etunti -uutiskirjeen tilaajana saat<br>
ajankohtaista tietoa ja vinkkejä siitä,<br>
miten kehität yrityksen liiketoimintaa,<br>
säästät aikaa ja parannat samalla tuottavuutta.</p>
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


<?php $this->renderPartial('/site/footer'); ?>

<?php $this->renderPartial('/site/header'); ?>


<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/etusivu_2.css">
<ul class="steps expanded even-4">
    <li class="disabled"><?php echo CHtml::link('Etusivu', Yii::app()->request->baseUrl.'/index.php/site/index'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Ajankohtaista', Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Asiakkaat', Yii::app()->request->baseUrl.'/index.php/site/asiakkaat'); ?></li>
    <li class="active"><?php echo CHtml::link('Yritys', Yii::app()->request->baseUrl.'/index.php/site/yritys'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yhteystiedot', Yii::app()->request->baseUrl.'/index.php/site/yhteystiedot'); ?></li>
</ul>



        <!-- Services -->
        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->
                        <h1 class="title-subtitle text-center">Yritys
                        </h1>
                        <hr>
                    <!-- End Titles Heading -->
                    <div class="row">
                        <div class="col-md-12">
                        
<div class="text-center">
<p>


<p>
Etunti on ohjelma, joka kehittyy koko ajan yhteistyössä asiakkaiden mukana ja asiakkailta saamien palautteiden mukaan. Vuoden 2016 loppuun mennessä valmistuu kaikki Etunnin työkalut. Asiakkaidemme mielipide on meille tärkeää ja saadun palautteen avulla kehitämme Etuntia asiakkaita palvelevaksi, monipuoliseksi ohjelmakokonaisuudeksi.  
</p>


<div class="text-center">
  <div class="row">
   <div class="col-md-6 col-md-offset-3">
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/etusivuimg/digisten.png" class="img-thumbnail">
   </div>
  </div>
</div>
<br>

                        <h1 class="title-subtitle text-center">Digistenin arvot, missio ja visio
			<span>Arvot – Luotettavasti, Nopeasti, Ekologisesti</span>
                        </h1>

 
<p>
Arvomme ovat asiakaslähtöiset. Ymmärrämme, tuemme ja autamme asiakkaitamme ja haluamme toimia luetettavana kumppanina yritystoiminnan kehityksessä ekologisesti. Ekologisuus korostuu, kun Etunnin ansiosta ei tarvitse erikseen käydä hyväksyttämässä työtunteja ja matka-aikoja voidaan lyhentää tehokkaan työvuorosuunnittelun ansiosta. Sähköisen ohjelman ansiosta paperityötä tulee huomattavasti vähemmän, mitkä kaikki yhdessä tukevat meidän ekologisia arvoja.
</p>
 
			<h1 class="title-subtitle text-center">
			<span>Missio</span>
			</h1>
 
<p>
Etunnin missiona on parantaa työn tehokkuuden hallintaa kokonaisvaltaisella tavalla.  Puhtaasti siivousalan tarpeisiin ei ole vielä markkinoilla ohjelmistoa, joka tuntisi alan tarpeet ja mahdollistaisi yrityksen kasvun. Yrityksen kasvua tuetaan Etunti -ohjelmalla, jolla voidaan hoitaa monipuolisesti yrityksen tarpeet työvuorosuunnittelusta laskutukseen, työajanseurannasta asiakkaiden hallintaan ja monia muita asioita. Etunnin avulla yritys voi keskittyä tulokselliseen toimintaan ja samalla seurata yrityksen tilaa ja sen kehitystä reaaliaikaisesti. Tärkeintä on, että ohjelma on kokonaisvaltainen, mutta silti käyttäjäystävällinen, helppo ja selkeä.
</p>
 

			<h1 class="title-subtitle text-center">
			<span>Visio</span>
			</h1>

<p>
Visiona on tuottaa asiakkaillemme kustannussäästöjä ja enemmän voittovaroja auttamalla asiakashankinnoissa ja yritystoiminnan tehostamisessa. Lähivuosien visio on päästä kansainvälisille markkinoille ja kehittää ohjelmaa palvelemaan useampia toimialoja.
</p>
</p>
</div>
                        <hr>

                        </div>
                    </div>
                </div>
                <!-- End Container-->
            </div>
        </section>        <!-- Services -->


<?php $this->renderPartial('/site/footer'); ?>

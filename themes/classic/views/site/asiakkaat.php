<?php $this->renderPartial('/site/header'); ?>


<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/etusivu_2.css">
<ul class="steps expanded even-4">
    <li class="disabled"><?php echo CHtml::link('Etusivu', Yii::app()->request->baseUrl.'/index.php/site/index'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Ajankohtaista', Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista'); ?></li>
    <li class="active"><?php echo CHtml::link('Asiakkaat', Yii::app()->request->baseUrl.'/index.php/site/asiakkaat'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yritys', Yii::app()->request->baseUrl.'/index.php/site/yritys'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yhteystiedot', Yii::app()->request->baseUrl.'/index.php/site/yhteystiedot'); ?></li>
</ul>



        <!-- Services -->
        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->
                        <h1 class="title-subtitle text-center">Asiakkaat
			<span>Etunnin avulla asiakkaamme voivat saavuttaa merkittäviä säästöjä ja kehittää omaa liiketoimintaansa.

</span>
                        </h1>



<!-- Asiakkaat -->
                    <hr>
                    <div class="row">
                        <div class="col-md-7">


                        </div>
                        <div class="col-md-5">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/etusivuimg/siivouspalvelukota.png" class="img-thumbnail">
				<p class="small">Kuvassa: Juha Mannermaa, Petteri Kotamäki ja Anna Kotamäki</p>
                        </div>
                    </div>
		    <hr>

                    <!-- End Titles Heading -->
                    <div class="row">
                        <div class="col-md-12">
			<p>

                        <h3 class="title-subtitle text-left">Mitä asiakkaamme ovat Etunnilla saavuttaneet:</h3>

                        <ul class="stars text-left">
			<li>Etunnilla avulla suunniteltiin yrityksessä uusiksi kohteiden siirtymäajat. Kolmessa kuukaudessa yritys säästi ohjelman avulla yhden työntekijän kuukausipalkan verran rahaa.</li>
			<li>Etunnilla tehostettiin työajanseurantaa. Puolessa vuodessa yritys sai kolme prosenttia lisää liikevoittoa.</li>
			<li>Etunnilla saatiin aikavarkaudet kitkettyä pois ja yritys säästi rahaa, kun tehdyistä tunneista maksettiin työntekijöille oikein.</li>
			<li>Etunnilla tehtyjen kirjausten avulla on todistettu työaikakirjanpito päteväksi, kun asiakas väitti työntekijöiden huijanneen ja häntä veloitettiin tunneista, joita ei asiakkaan mukaan ollut. Asiakas vei asian kuluttajariitalautakuntaan, mutta yritys todisti siivoojien olleen kohteessa Etunnin RFID-tarran ja sähköisen työajanseurannan avulla.</li>

                        </ul>

			</p>
                        </div>
                    </div>
<!-- Asiakkaat -->


                    <hr>



                </div>
                <!-- End Container-->
            </div>
        </section>        <!-- Services -->


<?php $this->renderPartial('/site/footer'); ?>

<script type="text/javascript">
$(document).ready(function(){



    $("html, body").delay(2000).animate({
        scrollTop: 700
    }, 2000);


});
</script>

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
                        <hr>
                    <!-- End Titles Heading -->
                    <div class="row">
                        <div class="col-md-12">
                        
<div class="text-center">
<p>

                        <ul class="stars">
			<li></li>Yrityksessä A otettiin käyttöön Etunti työvuorosuunnitteluohjelma. Sen avulla suunniteltiin yrityksessä uusiksi kohteiden siirtymäajat. Kolmessa kuukaudessa yritys säästi ohjelman avulla yhden työntekijän kuukausipalkan verran rahaa.
			<li></li>Yrityksessä B tehostettiin Etunnin avulla työajaseurantaa. Puolessa vuodessa yritys sai kolme prosenttia lisää liikevoittoa.
			<li></li>Yrityksessä C Etunnin avulla saatiin aikavarkaudet kitkettyä pois ja yritys säästi rahaa, kun tehdyistä tunneista maksettiin oikein työntekijöille.
			<li></li>Yritys D joutui ikävään tilanteeseen, kun asiakas väitti työntekijöiden huijanneen ja häntä veloitettiin tunneista, joita ei asiakkaan mukaan ollut. Asiakas vei asian kuluttajariitalautakuntaan, mutta yritys todisti siivoojien olleen kohteessa Etunnin RFID-tarran ja sähköisen työajanseurannan avulla.

                        </ul>

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

<script type="text/javascript">
$(document).ready(function(){



    $("html, body").delay(2000).animate({
        scrollTop: 700
    }, 2000);


});
</script>

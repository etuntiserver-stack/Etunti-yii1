<?php $this->renderPartial('/site/header'); ?>



<ul class="steps expanded even-4">
    <li class="disabled"><?php echo CHtml::link('Etusivu', Yii::app()->request->baseUrl.'/index.php/site/index'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Ajankohtaista', Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Asiakkaat', Yii::app()->request->baseUrl.'/index.php/site/asiakkaat'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yritys', Yii::app()->request->baseUrl.'/index.php/site/yritys'); ?></li>
    <li class="active"><?php echo CHtml::link('Yhteystiedot', Yii::app()->request->baseUrl.'/index.php/site/yhteystiedot'); ?></li>
</ul>



        <!-- Services -->
        <section class="esittely">
            <div class="paddings">
                <div class="container text-center">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->
                        <h1 class="title-subtitle">Yhteystiedot</h1>
                        <hr>
                    <!-- End Titles Heading -->
                    <div class="row">
                        <div class="col-md-12 lead">
                        
<div class="text-left">
  <div class="row">
   <div class="col-md-1">
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/etusivuimg/etuntipikkulogo.jpg" class="img-thumbnail">
   </div>
   <div class="col-md-8">
    <span class="small yhteysInfo">
	<p>
	Myynti<br>
	etuntimyynti@etunti.fi<br>
	+358 40 124 9081
	</p>
    </span>
   </div>
  </div>
  <br>
  <div class="row">
   <div class="col-md-1">
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/etusivuimg/johtaja.jpg" class="img-thumbnail">
   </div>
   <div class="col-md-8">
    <span class="small yhteysInfo">
	<p>
	Liiketoimintajohtaja<br>
	Veiko Põldkivi<br>
	veiko.poldkivi@etunti.fi<br>
	+358 40 761 4366
	</p>
    </span>
   </div>
  </div>
  <br>
  <div class="row">
   <div class="col-md-1">
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/etusivuimg/toimitusjohtaja.jpg" class="img-thumbnail">
   </div>
   <div class="col-md-8">
    <span class="small yhteysInfo">
	<p>
	Toimitusjohtaja<br>
	Turkka Rantanen<br>
	turkka.rantanen@etunti.fi<br>
	+358 40 124 9082
	</p>
    </span>
   </div>
  </div>
</div>



<br>

<div class="text-left">
  <div class="row">
   <div class="col-md-12">
<p>
<p>
Markkinointi<br>
etuntimarkkinointi@etunti.fi
</p>
Toimistomme sijaitsee historiallisesti arvokkaan ja luonnonkauniin Vanhankaupungin vieressä. Hämeentie 157, H9, 00560 Helsinki.
</p>

   </div>
  </div>
</div>

<br>
<!--
<div class="text-center">
  <div class="row">
   <div class="col-md-12">
    <img src="<?php echo Yii::app()->request->baseUrl; ?>/etusivuimg/veiko_ja_tuomo.png" class="img-thumbnail">
   </div>
  </div>
</div>
-->






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

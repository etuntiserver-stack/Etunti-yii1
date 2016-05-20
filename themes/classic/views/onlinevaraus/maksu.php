<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_2.css">

<div class="container-fluid">
<br>

<div class="row">
 <div class="form-inline col-sm-12">
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
   </div><div class="form-group col-sm-offset-3">
	<h2>Online-Varaus</h2>
   </div>
 </div>
</div>


<div class="">
<ul id="green_and_orange_step_menu">
<li class="first tehty"><?php echo CHtml::link('PALVELU','index'); ?></li>
<li class="tehty"><?php echo CHtml::link('AIKA','aika'); ?><span class="teh_teh"></span></li>
<li class="tehty"><?php echo CHtml::link('OSOITE','osoite'); ?><span class="teh_teh"></span></li>
<li class="aktiivinen"><?php echo CHtml::link('MAKSU','maksu'); ?><span class="teh"></span></li>
</ul>
</div>

<br>

<br><br>
<div class="row">

 <div class="col-sm-8">
   <?php 
   if(isset($_SESSION['onlinevaraus']['paapalvelu']) and isset($_SESSION['onlinevaraus']['modelKohde']))
   {
	$k = Kohteet::model()->findbypk($_SESSION['onlinevaraus']['modelKohde']);
	if(isset($k->id))
	{
	$return = $this->renderPartial('checkout', array(
			'amount'=>$_SESSION['onlinevaraus']['amount'],
			'kesto'=>$_SESSION['onlinevaraus']['sumTunti'],
			'etu_suku_nimet'=>$k->etu_suku_nimet,
			'osoite'=>$k->osoite,
			'postinumero'=>$k->pnumero,
			'kaupunki'=>$k->kaupunki,
	), true); 
   	echo $return;
	}
   }
   ?>
 </div>

 <div class="col-sm-4">
   <div id="panGetContent">
   <?php 
   if(isset($_SESSION['onlinevaraus']['paapalvelu']))
   {
	$return = $this->renderPartial('palvelu_save_ajax', array('sivu'=>''), true); 
   	echo json_decode($return, true);
   }
   ?>
   </div>
 </div>
</div>





</div>




<script type="text/javascript">
$(document).ready(function(){




});
</script>




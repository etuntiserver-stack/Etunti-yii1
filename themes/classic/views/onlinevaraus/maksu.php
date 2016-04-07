<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus.css">

<div class="container-fluid">
<br><br>
<div class="row">
 <div class="form-inline col-sm-12">
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
   </div><div class="form-group col-sm-offset-3">
	<h2>Online-Varaus</h2>
   </div>
 </div>
</div>

<div class="stepwizard">
    <div class="stepwizard-row">
        <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle"><?php echo CHtml::link('1','index'); ?></button>
            <p>PALVELU</p>
        </div>
        <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle"><?php echo CHtml::link('2','aika'); ?></button>
            <p>AIKA</p>
        </div>
        <div class="stepwizard-step">
            <button type="button" class="btn btn-default btn-circle"><?php echo CHtml::link('3','osoite'); ?></button>
            <p>OSOITE</p>
        </div> 
              <div class="stepwizard-step">
            <button type="button" class="btn btn-primary btn-circle"><?php echo CHtml::link('4','maksu'); ?></button>
            <p>MAKSU</p>
        </div>

    </div>
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




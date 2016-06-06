<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);

//print_r($_SESSION['onlinevaraus']);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_2.css">

<div class="container-fluid">
<br>

<div class="row">
 <div class="form-inline col-sm-12">
	<b id="countTimer" class="pull-right"></b>
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
   </div><div class="form-group col-sm-offset-4">
	<h3><?php echo Yii::t('main', 'Online-Varaus'); ?><br>
           <span class="small"><?php echo CHtml::link(Yii::t('main', 'Mikä on online-varaus'),'index'); ?></span>
	</h3>
   </div>
 </div>
</div>


<ul class="steps expanded even-4">
    <li class="tehtty"><?php echo CHtml::link('PALVELU','index'); ?></li>
    <li class="tehtty"><?php echo CHtml::link('AIKA','aika'); ?></li>
    <li class="tehtty"><?php echo CHtml::link('OSOITE','osoite'); ?></li>
    <li class="active"><?php echo CHtml::link('MAKSU','maksu'); ?></li>
</ul>


<br><br>
<div class="row">
 <div class="col-sm-8">
   <?php 
   if(isset($_SESSION['onlinevaraus']['paapalvelu']) and isset($_SESSION['onlinevaraus']['onlinevarausID']))
   {

	$ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
	if(isset($ov->id))
	{
	$return = $this->renderPartial('checkout', array(
			'amount'=>$_SESSION['onlinevaraus']['amount'],
			'kesto'=>$_SESSION['onlinevaraus']['sumTunti'],
			'etu_suku_nimet'=>$ov->yhteyshenkilo,
			'osoite'=>$ov->osoite,
			'postinumero'=>$ov->postinumero,
			'kaupunki'=>$ov->kaupunki,
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

	      <div id="alennuskoodi">
		<div class="boxes-info">
		  <center><h4><?php echo Yii::t('main', 'Alennuskoodi'); ?></h4>
			<form class="input-group">
			<input type="text" class="form-control form-group input-lg">
			<span class="input-group-btn">
			  <input type="submit" class="btn btn-lg btn-group btn-warning" value="<?php echo Yii::t('main', 'Aktivoi'); ?>">
			</span>	
			</form>
		  </center>
		</div>
	      </div>

	      <div>
		<div class="boxes-info sininen">
		  <center><h4><?php echo Yii::t('main', 'Asiakaspalvelu'); ?></h4>

		  </center>
		</div>
	      </div>

 </div>
</div>





</div>




<script type="text/javascript">
$(document).ready(function(){



var step = 41;
var count = step;
function counter(){
    count += -1;


	var time = count*15;
	var minutes = "0" + Math.floor(time / 60);
	var seconds = "0" + (time - minutes * 60);
	jaljella =  minutes.substr(-2) + ":" + seconds.substr(-2);
	$('#countTimer').text('Aikajäljellä: '+jaljella);

    if(count < 1)
    window.location.href="index?keskeyta=true";
}
setInterval(counter, "15000");

});
</script>




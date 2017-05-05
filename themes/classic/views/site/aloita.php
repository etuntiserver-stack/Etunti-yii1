 <?php // $this->renderPartial('/site/header'); ?> 


<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/etusivu_2.css">
<ul class="steps expanded even-4">
    <li class="disabled"><?php echo CHtml::link('Etusivu', Yii::app()->request->baseUrl.'/index.php/site/index'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Ajankohtaista', Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista'); ?></li>
    <li class="active"><?php echo CHtml::link('Asiakkaat', Yii::app()->request->baseUrl.'/index.php/site/asiakkaat'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yritys', Yii::app()->request->baseUrl.'/index.php/site/yritys'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yhteystiedot', Yii::app()->request->baseUrl.'/index.php/site/yhteystiedot'); ?></li>
</ul>


<br><br>

<div class="container">

<?php
	if(isset($vastaus))
		echo $vastaus;
?> 
  

<div class="row">
 <div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('main', 'Aloita Etunnin käyttäminen'); ?></div>
    <div class="panel-body">
      	<form action="#" method="POST">
        <label><?php echo Yii::t('main', 'Yrityksen nimi')?></label>
        <input type="text" id="yrityksen_nimi" name="yrityksen_nimi" class="form-control input-lg" required autofocus>
        <label><?php echo Yii::t('main', 'Yritystunnus')?></label>
        <input type="text" id="yritys_tunnus" name="yritys_tunnus" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Osoite')?></label>
        <input type="text" id="osoite" name="osoite" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Postinumero')?></label>
        <input type="text" id="postinumero" name="postinumero" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Postitoimipaikka')?></label>
        <input type="text" id="postitoimipaikka" name="postitoimipaikka" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Yhteyshenkilö')?></label>
        <input type="text" id="yhteyshenkilo" name="yhteyshenkilo" class="form-control input-lg" required>
        
	<br>
        <button class="btn btn-lg btn-primary btn-group submit" type="submit"><?php echo Yii::t('main', 'Aloita'); ?></button>
    	</form>
	<div id="success"></div>

    </div>
  </div>
 </div>
</div>


</div>



<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);
?>

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

<ul class="nav nav-pills nav-justified">
  <li role="presentation"><?php echo CHtml::link('Palvelu','index'); ?></li>
  <li role="presentation"><?php echo CHtml::link('Aika','aika'); ?></li>
  <li role="presentation"><?php echo CHtml::link('Osoite','osoite'); ?></li>
  <li role="presentation" class="active"><?php echo CHtml::link('Maksu','maksu'); ?></li>
</ul>

<br>

<div class="row">
 <div class="col-sm-8">

 </div>
 <div class="col-sm-4">
 
 </div>
</div>


<div class="row">
 <div class="form-inline col-sm-12">
	<?php echo CHtml::link('<< Edellinen sivu','osoite',array('class'=>'btn btn-success')); ?>
 </div>
</div>


</div>





<?php

Yii::app()->clientScript->registerScript('avoimet', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lasku-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

?>
<?php if(isset($_POST['tulosta'])) : ?>
<?php
 $yritys = '';
 $f = FirmanTiedot::model()->findbypk(1);
 if(!empty($f->tyonantaja))
 $yritys = $f->tyonantaja.', ';
?>
<style>
table{
	width: 200px;
	font-size: 80%;
}
th{
	width: 100%;	
	padding:3px 7px;
}
</style>
<?php endif; ?>

<legend>
<h1> <?php echo Yii::t('main', 'Avoimet laskut ').$palvelu; ?> <i class="glyphicon glyphicon-barcode"></i></h1>
</legend>

<br>

<?php if(!isset($_POST['tulosta'])) : ?>
<div class="row">
 <div class="col-md-12">

  <form action="#" class="form-inline" method="POST">
  <input type="hidden" name="avoimet">

   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['from']; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['to']; ?>">

   <div class="form-group input-group-btn">
      <input type="submit" class="btn btn-primary btn-sm" value="<?php echo Yii::t('main', 'Hae'); ?>">
   </div>
   </form>

 </div>
</div>
<br>
<?php endif; ?>



<table class="table table-bordered table-striped small">
 <tr>
  <th><?php echo Yii::t('main','Laskunro'); ?></th>
  <th><?php echo Yii::t('main','Status'); ?></th>
  <th><?php echo Yii::t('main','Paydate'); ?></th>
  <th><?php echo Yii::t('main','Amount'); ?></th>
  <th><?php echo Yii::t('main','Tapahtuma pvm'); ?></th>
  <th><?php echo Yii::t('main','Yhteensä'); ?></th>
  <th><?php echo Yii::t('main','Asiakas'); ?></th>
 </tr>
 <?php 
 $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_avoimet',
)); 
 ?>
</table>

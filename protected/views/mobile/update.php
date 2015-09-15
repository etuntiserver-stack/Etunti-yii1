<?php
/* @var $this MobileController */
/* @var $model Mobile */

$this->breadcrumbs=array(
	Yii::t('main', 'Mobiili')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'tiedot'),
);

$this->menu=array(
	array('label'=>'Lista mobiili', 'url'=>array('index')),
	//array('label'=>'Create Mobile', 'url'=>array('create')),
	array('label'=>'Katso mobiili', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Hallinta mobiili', 'url'=>array('admin')),
);

  $tag = '';
  $tag = explode("_",$model->asiakas_num);
  if(isset($tag[1]))
  $tag = $tag[1];

?>

<h1><?php echo Yii::t('main', 'Rivi'); ?> <?php echo $model->id; ?>, TAG: <?php echo $tag; ?></h1>

<BR>
<div class="row form">
  <div class="row">
	<div class="col-lg-4">
	<?php echo CHtml::button('Palaa takaisin',array("class"=>"btn btn-info","onClick"=>"history.back();return false;")); ?>
	</div>
  </div>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<hr>

<h1><?php echo Yii::t('main', 'Tärkeimmät tiedot'); ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'asiakas_num',
		'time',
		'requests',
		'puh_numero',
		'imei',
		'bluetooth_name',
		'sim_serial_number',
		'subscriber_id',
		'my_location',
		'osoite',
		'kohde_kannasta',
		'kohdenID',
		'aloitan',
		'loppui',
		'viesti',
		'tid',
		'tekijan_nimi',
		'etaisyys',
		'status',
		'tietoja',
		'admin',
		'hyvaksytty',
	),
)); ?>

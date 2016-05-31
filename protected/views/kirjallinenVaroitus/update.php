<?php
/* @var $this KirjallinenVaroitusController */
/* @var $model KirjallinenVaroitus */

$this->breadcrumbs=array(
	'Kirjallinen Varoituses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List KirjallinenVaroitus', 'url'=>array('index')),
	array('label'=>'Create KirjallinenVaroitus', 'url'=>array('create')),
	array('label'=>'View KirjallinenVaroitus', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage KirjallinenVaroitus', 'url'=>array('admin')),
);
?>

<h1>Update KirjallinenVaroitus <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
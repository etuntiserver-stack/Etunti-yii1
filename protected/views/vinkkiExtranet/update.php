<?php
/* @var $this VinkkiExtranetController */
/* @var $model VinkkiExtranet */

$this->breadcrumbs=array(
	'Vinkki Extranets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List VinkkiExtranet', 'url'=>array('index')),
	array('label'=>'Create VinkkiExtranet', 'url'=>array('create')),
	array('label'=>'View VinkkiExtranet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage VinkkiExtranet', 'url'=>array('admin')),
);
?>

<h1>Update VinkkiExtranet <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
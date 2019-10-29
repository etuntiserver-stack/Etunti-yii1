<?php
/* @var $this IlmoitusKaikkilleController */
/* @var $model IlmoitusKaikkille */

$this->breadcrumbs=array(
	'Ilmoitus Kaikkilles'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List IlmoitusKaikkille', 'url'=>array('index')),
	array('label'=>'Create IlmoitusKaikkille', 'url'=>array('create')),
	array('label'=>'View IlmoitusKaikkille', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage IlmoitusKaikkille', 'url'=>array('admin')),
);
?>

<h1>Update IlmoitusKaikkille <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
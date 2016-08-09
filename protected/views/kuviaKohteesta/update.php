<?php
/* @var $this KuviaKohteestaController */
/* @var $model KuviaKohteesta */

$this->breadcrumbs=array(
	'Kuvia Kohteestas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List KuviaKohteesta', 'url'=>array('index')),
	array('label'=>'Create KuviaKohteesta', 'url'=>array('create')),
	array('label'=>'View KuviaKohteesta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage KuviaKohteesta', 'url'=>array('admin')),
);
?>

<h1>Update KuviaKohteesta <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */

$this->breadcrumbs=array(
	'Crm Tarjouksets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CrmTarjoukset', 'url'=>array('index')),
	array('label'=>'Create CrmTarjoukset', 'url'=>array('create')),
	array('label'=>'View CrmTarjoukset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CrmTarjoukset', 'url'=>array('admin')),
);
?>

<h1>Update CrmTarjoukset <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
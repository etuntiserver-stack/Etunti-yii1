<?php
/* @var $this CrmSopimuksetController */
/* @var $model CrmSopimukset */

$this->breadcrumbs=array(
	'Crm Sopimuksets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CrmSopimukset', 'url'=>array('index')),
	array('label'=>'Create CrmSopimukset', 'url'=>array('create')),
	array('label'=>'View CrmSopimukset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CrmSopimukset', 'url'=>array('admin')),
);
?>

<h1>Update CrmSopimukset <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
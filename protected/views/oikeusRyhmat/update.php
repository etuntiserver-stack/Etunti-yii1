<?php
/* @var $this OikeusRyhmatController */
/* @var $model OikeusRyhmat */

$this->breadcrumbs=array(
	'Oikeus Ryhmats'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OikeusRyhmat', 'url'=>array('index')),
	array('label'=>'Create OikeusRyhmat', 'url'=>array('create')),
	array('label'=>'View OikeusRyhmat', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OikeusRyhmat', 'url'=>array('admin')),
);
?>

<h1>Update OikeusRyhmat <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
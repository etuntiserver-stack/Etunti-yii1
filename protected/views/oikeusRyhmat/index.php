<?php
/* @var $this OikeusRyhmatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Oikeus Ryhmats',
);

$this->menu=array(
	array('label'=>'Create OikeusRyhmat', 'url'=>array('create')),
	array('label'=>'Manage OikeusRyhmat', 'url'=>array('admin')),
);
?>

<h1>Oikeus Ryhmats</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

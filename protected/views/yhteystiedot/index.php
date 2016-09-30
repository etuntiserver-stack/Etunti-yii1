<?php
/* @var $this YhteystiedotController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Yhteystiedots',
);

$this->menu=array(
	array('label'=>'Create Yhteystiedot', 'url'=>array('create')),
	array('label'=>'Manage Yhteystiedot', 'url'=>array('admin')),
);
?>

<h1>Yhteystiedots</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

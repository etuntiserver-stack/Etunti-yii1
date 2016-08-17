<?php
/**
  * Some text describing what the view does goes here
  * @uses CActiveDataProvider $dataProvider The data provider for this model
  * @uses User $model The user model
  */

/* @var $this AsetuksetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Asetuksets',
);

$this->menu=array(
	array('label'=>'Create Asetukset', 'url'=>array('create')),
	array('label'=>'Manage Asetukset', 'url'=>array('admin')),
);
?>

<h1>Asetuksets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<?php
/* @var $this KirjallinenVaroitusController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Kirjallinen Varoituses',
);

$this->menu=array(
	array('label'=>'Create KirjallinenVaroitus', 'url'=>array('create')),
	array('label'=>'Manage KirjallinenVaroitus', 'url'=>array('admin')),
);
?>

<h1>Kirjallinen Varoituses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

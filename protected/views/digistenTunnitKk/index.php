<?php
/* @var $this DigistenTunnitKkController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Digisten Tunnit Kks',
);

$this->menu=array(
	array('label'=>'Create DigistenTunnitKk', 'url'=>array('create')),
	array('label'=>'Manage DigistenTunnitKk', 'url'=>array('admin')),
);
?>

<h1>Digisten Tunnit Kks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

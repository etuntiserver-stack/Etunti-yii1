<?php
/* @var $this SivexkuittiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Luetut kohteet',
);

$this->menu=array(
	array('label'=>'Create Sivexkuitti', 'url'=>array('create')),
	array('label'=>'Manage Sivexkuitti', 'url'=>array('admin')),
);
?>

<h1>Luetut kohteet</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

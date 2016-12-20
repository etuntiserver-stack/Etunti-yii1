<?php
/* @var $this IrtisanomisilmoituksetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Irtisanomisilmoituksets',
);

$this->menu=array(
	array('label'=>'Create Irtisanomisilmoitukset', 'url'=>array('create')),
	array('label'=>'Manage Irtisanomisilmoitukset', 'url'=>array('admin')),
);
?>

<h1>Irtisanomisilmoituksets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

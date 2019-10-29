<?php
/* @var $this IlmoitusKaikkilleController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ilmoitus Kaikkilles',
);

$this->menu=array(
	array('label'=>'Create IlmoitusKaikkille', 'url'=>array('create')),
	array('label'=>'Manage IlmoitusKaikkille', 'url'=>array('admin')),
);
?>

<h1>Ilmoitus Kaikkilles</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

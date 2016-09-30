<?php
/* @var $this VinkkiExtranetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Vinkki Extranets',
);

$this->menu=array(
	array('label'=>'Create VinkkiExtranet', 'url'=>array('create')),
	array('label'=>'Manage VinkkiExtranet', 'url'=>array('admin')),
);
?>

<h1>Vinkki Extranets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

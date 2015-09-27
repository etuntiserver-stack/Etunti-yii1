<?php
/* @var $this LaskutusTuotteetController */
/* @var $model LaskutusTuotteet */

$this->breadcrumbs=array(
	'Laskutus Tuotteets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LaskutusTuotteet', 'url'=>array('index')),
	array('label'=>'Create LaskutusTuotteet', 'url'=>array('create')),
	array('label'=>'View LaskutusTuotteet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage LaskutusTuotteet', 'url'=>array('admin')),
);
?>

<h1>Update LaskutusTuotteet <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - '.Yii::t('main', 'About');
$this->breadcrumbs=array(
	Yii::t('main', 'About'),
);

if(Yii::app()->user->name == 'admin')
{  
$this->widget('zii.widgets.CMenu', array(
    'items'=>array(
        array('label'=>'Muokka (admin)', 'url'=>array('page/update&id=2')),
    ),
));
}

	$data = Page::model()->find("sivu = :n", array(":n"=>"About"));
	echo $data->text; 
?>


<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$criteria = new CDbCriteria();
$criteria->condition = "
	DATE(STR_TO_DATE(pto, '%d.%m.%Y')) < DATE(STR_TO_DATE(pfrom, '%d.%m.%Y'))
";
$tv = ToistuvatTyovuorot::model()->findAll($criteria);
echo '<h3>Väärät aloitus ja lopetus päivät: '.count($tv).'</h3>';
foreach($tv as $item){
	echo 'ID: '.$item->id.'<b> '.$item->pfrom.'-'.$item->pto.', viikkoja-'.$item->viikkoja.' , Osoite-'.$item->osoite.'</b><br>';
}

$criteria = new CDbCriteria();
$criteria->condition = "
	length(new_poistettu_pvm) > 2000
";
$tv = ToistuvatTyovuorot::model()->findAll($criteria);
echo '<h3>Liika poistetut päivät: '.count($tv).'</h3>';
foreach($tv as $item){
	echo 'ID: '.$item->id.' <b>'.$item->pfrom.'-'.$item->pto.', viikkoja-'.$item->viikkoja.' , Osoite-'.$item->osoite.'</b><br>';
}
exit;



if(isset($_GET['mail'])){
	$m = $_GET['mail'];
	$ft = FirmanTiedot::model()->findByPk(1);
	$mail = new YiiMailer();
	$mail->setFrom('no-reply@etunti.com');
	$mail->setTo($m);
	$mail->setSubject('test');
	$mail->setBody('testi');
	if($mail->send())
	{
		echo 'sähköposti lähetetty ok '.$m;
	}
}


$this->breadcrumbs=array(
	Yii::t('main', 'Viestintä')=>array('index'),
	Yii::t('main', 'Hallinta'),
);
/*
$this->menu=array(
	array('label'=>'List Viestinta', 'url'=>array('index')),
	array('label'=>'Create Viestinta', 'url'=>array('create')),
);
*/
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#viestinta-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>


<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<div class="row">
	<h3><?php echo Yii::t('main', 'VIESTIT'); ?> <i class="glyphicon glyphicon-envelope"></i> 
	| <?php echo CHtml::link('Haku','#',array('class'=>'search-button')); ?>
	| <?php echo CHtml::link('Lisää uusi viesti','/index.php/viestinta/create',array('class'=>'')); ?>
   	</h3>
</div>


<div class="row">

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'viestinta-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

	'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css'),
	'cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css',

        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-striped small table-hover',


	'columns'=>array(
		//'id',
               array(
                    'name'=>'time',
                    'value'=>'date("d.m.Y - H:i",strtotime($data->time))',                   
		),
		//'pvm',
               array(
                    'name'=>'viesti',
                    'value'=>'$data->viesti',
		    'type' => 'html',
		),
               array(
                    'name'=>'admin',
                    'value'=>array($this,'lahettajaMuutos'),
		    'type' => 'html',
		),
               array(
                    'name'=>'tekija',
                    'value'=>array($this,'tekijaMuutos'),              
		),
/*
		//'status',
		array(
			'class'=>'CButtonColumn',
		),
*/

array(

        'value' => '
	   CHtml::link("", Yii::app()->createUrl("viestinta/view",array("id"=>$data->id)),array("class"=>"fa fa-pencil-square-o"))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),

	),
)); ?>
</div>

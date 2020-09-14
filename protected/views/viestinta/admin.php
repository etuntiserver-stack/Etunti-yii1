<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$tv = Asiakkaat::model()->findAll();
foreach($tv as $item){

	//if(!empty($item->y_tunnus))
	//	Asiakkaat::model()->updatebypk($item->id, ['tyyppi' => 'yritys']);
	//else
	//	Asiakkaat::model()->updatebypk($item->id, ['tyyppi' => 'henkilo']);

	$k = Kohteet::model()->find(" asiakas_id='".$item->id."' ");
	if(!isset($k->id))
		echo $item->yhteyshenkilo.' '.$item->y_tunnus.' '.$item->tyyppi.'<br>';
/*
	$k = new Kohteet;
	$k->asiakas_id = $item->id;
	$k->etu_suku_nimet = $item->yhteyshenkilo;
	$k->osoite = $item->osoite;
	$k->kaupunki = $item->kaupunki;
	$k->pnumero = $item->postinumero;
	$k->email = $item->sahkoposti;
	$k->puh_nro = $item->puhelin;
	$k->aktiivinen = 1;
	$k->save();
*/
}

/* Asiakas siirto 
$tv = Asiakkaat::model()->findAll();
foreach($tv as $item){

	echo $item->yhteyshenkilo.' '.$item->y_tunnus.' '.$item->tyyppi.'<br>';
	//if(!empty($item->y_tunnus))
	//	Asiakkaat::model()->updatebypk($item->id, ['tyyppi' => 'yritys']);
	//else
	//	Asiakkaat::model()->updatebypk($item->id, ['tyyppi' => 'henkilo']);

	$k = new Kohteet;
	$k->asiakas_id = $item->id;
	$k->etu_suku_nimet = $item->yhteyshenkilo;
	$k->osoite = $item->osoite;
	$k->kaupunki = $item->kaupunki;
	$k->pnumero = $item->postinumero;
	$k->email = $item->sahkoposti;
	$k->puh_nro = $item->puhelin;
	$k->aktiivinen = 1;
	$k->save();

}

exit;
*/
phpinfo();
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

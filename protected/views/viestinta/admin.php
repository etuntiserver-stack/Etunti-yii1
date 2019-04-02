<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

//print_r(Yii::app()->getSession()->getSessionId());

//phpinfo();

/*
	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

			$start_date = "01.05.2018";
			$end_date = "31.05.2018";

			$result = 0;
			$mob_result = 0;
			$tyovuorot_result = 0;

			while (strtotime($start_date) <= strtotime($end_date))
			{
				$mobile = 0;
				$pvm = date( "Y-m-d", strtotime($start_date));
				$start_date = date ("Y-m-d", strtotime($start_date. " +1 day"));
	
	
				// <-- Ensin katsotaan mobile taulusta toteutuneet
		       		$criteria = new CDbCriteria();
		        	$criteria->select = "
					SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
					DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
				";
			        $criteria->condition = " 
					aloitan!='' AND loppui!=''
					AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."'
					AND status=3
					AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
				";
				$lu = Mobile::model()->find($criteria);
	
	
		       		$criteria = new CDbCriteria();
		        	$criteria->select = "
					SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
					DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
				";
			        $criteria->condition = " 
					aloitan!='' AND loppui!=''
					AND status=3
					AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."'
				";
				$tot = Toteutuneet::model()->find($criteria);
		
				if(isset($lu->l_tunnit))
				$mobile += $lu->l_tunnit;
		
				if(isset($tot->l_tunnit))
				$mobile += $tot->l_tunnit;
				// Ensin katsotaan mobile taulusta toteutuneet -->
	
				if( $mobile > 0 )
				{
					$mob_result += $mobile;
				}
	
	
				// <-- tyovuorot
		       		$criteria = new CDbCriteria();
			        $criteria->select = "
					SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(CONCAT(pvm, loppu), '%d.%m.%Y %H:%i'), '%Y-%m-%d  %H:%i'), 
					DATE_FORMAT(STR_TO_DATE(CONCAT(pvm, alku), '%d.%m.%Y %H:%i'), '%Y-%m-%d  %H:%i')))) as l_tunnit
				";
				$criteria->condition = " 
					alku!='' AND loppu!=''
					AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."'
					AND status=3
					AND peruutettu='0'
				";
				$tv = Tyovuoroot::model()->find($criteria);
	
				if(isset($tv->l_tunnit))
				$tyovuorot_result += $tv->l_tunnit;
				//     tyovuorot -->
	
					
			}
	
			if($mob_result > $tyovuorot_result)
			$result = $mob_result;
			if($mob_result < $tyovuorot_result)
			$result = $tyovuorot_result;
	
			$sum_result = $result/3600;


			echo $sum_result;
			exit;
*/



if(isset($_GET['mail'])){
	$m = $_GET['mail'];
	$ft = FirmanTiedot::model()->findByPk(1);
	$mail = new YiiMailer();
	$mail->setFrom('no-reply@etunti.fi');
	$mail->setTo($m);
	$mail->setSubject('test');
	$mail->setBody('testi');
	if($mail->send())
	{
		echo 'sähköposti lähetetty ok '.$m;
	}
}

echo dirname(Yii::app()->getBasePath()).'/img/tekijat/'.strtolower(Yii::app()->user->domain);
//phpinfo();

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

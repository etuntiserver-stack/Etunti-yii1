<?php
       	$criteria = new CDbCriteria();
	$criteria->condition = " status=0 AND tekija='toimisto' ";
	$vi = Viestinta::model()->findAll($criteria);

	if(isset($vi[0]->id) and !empty($vi[0]->id))
	{
	echo '<h2>'.Yii::t('main','Vastaamattomat viestit').'</h2>';
	echo '<div class="row">';
	
	   foreach($vi as $v)
	   {

	   	echo '<div class="col-sm-4" id="v_'.$v->id.'">';
	   	echo '<div class="well">';

		echo '<span>'.str_replace("\n","<br>",$v->viesti).'</span>

		<div class="row">
	 	 <div class="pull-right">
		  '.CHtml::link("Vasta", Yii::app()->request->baseUrl.'/index.php/viestinta/update?id='.$v->id, array('class'=>'btn btn-xs btn-primary')).'
		  <div class="btn btn-xs btn-default vastaanotettu" for="v_'.$v->id.'">'.Yii::t('main','Sulje').'</div>
		 </div>
		</div>';
		echo '</div>';
		echo '</div>';
	   }
	echo '</div>';
	}
?>

  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'T'); ?></th>
  <th><?php echo Yii::t('main', 'Rivi'); ?></th>
  <th><?php echo Yii::t('main', 'Versio'); ?></th>
  <th><?php echo Yii::t('main', 'Päivä'); ?></th>
  <th><?php echo Yii::t('main', 'Kartta'); ?></th>
  <th class="col-sm-4"><?php echo Yii::t('main', 'Työntekijä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Työvuorot'); ?></th>
  <?php endif; ?>


  <th><?php echo Yii::t('main', 'TAG'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th class="col-sm-4"><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th class="col-sm-2"><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th class="col-sm-2"><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><center><?php echo Yii::t('main', 'M'); ?></center></th>
  <th><center><?php echo Yii::t('main', 'P'); ?></center></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'viewData' => array("sivu" => "index" ),
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

  )); ?>
  </table>

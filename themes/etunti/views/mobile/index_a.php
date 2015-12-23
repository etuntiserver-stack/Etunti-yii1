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
		$exAdm = explode(",",$v->admin);

	   	echo '<div class="col-sm-12" id="v_'.$v->id.'">';
		echo '<div class="link pull-right col-sm-1 vastaanotettu" for="v_'.$v->id.'">'.Yii::t('main','Vastaanotto').'</div>';
	   	echo '<div class="alert alert-danger">';
		echo 'ID: '.$v->id.'<br>';
		if(isset($exAdm[1]))
		echo '<b>'.date("d.m H:i",strtotime($v->time)).'<br>'.$exAdm[1].'</b><hr>';
		echo $v->viesti;
		echo '</div>';
		echo '</div>';
	   }
	echo '</div>';
	}
?>

  <table class="table table-striped" id="mobileTable">
  <thead>
  <tr>
  <th class="col-sm-1"><?php echo Yii::t('main', 'T'); ?></th>
  <th><?php echo Yii::t('main', 'ID'); ?></th>
  <th><?php echo Yii::t('main', 'Versio'); ?></th>
  <th><?php echo Yii::t('main', 'Päivä'); ?></th>
  <th><?php echo Yii::t('main', 'Kartta'); ?></th>
  <th class="col-sm-4"><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'TAG'); ?></th>
  <th class="col-sm-4"><?php echo Yii::t('main', 'Osoite/Matka'); ?></th>
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


  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/mobile.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>



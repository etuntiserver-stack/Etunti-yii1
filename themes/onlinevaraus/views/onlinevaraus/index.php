<?php


$asetukset = Asetukset::model()->findbypk(1);

if($asetukset->onlinevaraus_alku == 0){
Asetukset::model()->updatebypk(1, array('onlinevaraus_alku'=>8));
}

if($asetukset->onlinevaraus_loppu == 0){
Asetukset::model()->updatebypk(1, array('onlinevaraus_loppu'=>18));
}

// clear
  if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
	Onlinevaraus::model()->deletebypk($_SESSION['onlinevaraus']['onlinevarausID']);

  if(isset($_SESSION['onlinevaraus']['modelTV']))
	Tyovuoroot::model()->deletebypk($_SESSION['onlinevaraus']['modelTV']);

  unset($_SESSION['onlinevaraus']);

//
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_ver2.css">


<div class="container-fluid ">
<div class="row ">
    <div class="col-sm-6 col-sm-offset-3 select-service">
        <center>
	<div class="row">
	  <div class="col-sm-6 col-sm-offset-3">

		<h4>Valitse palvelu</h4>

		<select class="form-control input-lg" id="palvelu">
		<option value="">Valitse palvelu</option>

		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " nayta_sivuilla=1 ";
	       	$criteria->order = " nimike ";
		$onlineTuotteet = TuotteetPalvelut::model()->findAll($criteria);
		foreach($onlineTuotteet as $data)
		{
		  echo 
		  '
			<option value="'.$data->id.'">'.$data->nimike.'</option>
		  ';
		}
		?>
		</select>
	  </div>
	</div>

	<div class="row" id="toinen_valiko"></div>


	<div class="row" id="toimialueRow">
	  <div class="col-sm-6 col-sm-offset-3">
		<h4><?php echo Yii::t('main', 'Valitse toimialue'); ?></h4>
		<?php
		$exists = Valikkoot::model()->find(" select_type='tyo_toimialue' ");
		if(!isset($exists->id))
		{
		    $valiko = new Valikkoot;
		    $valiko->select_type = 'tyo_toimialue';
		    $valiko->value = 'Test';
		    $valiko->save();
		}

		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='tyo_toimialue' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->value] = $v->value;

        	echo CHtml::dropDownList('tyo_toimialue', 'tyo_toimialue', $list,
		array('class'=>'form-control input-lg'));
        	?>
	      </div>
	    </div>
  </div>

</div>

    <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/onlinevaraus/rekisteriseloste" target="_blank"><?php echo Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'); ?> </a>

   <?php
   foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/onlinevarausehdot.*')) as $file) 
   {
		$explNimi = explode("/",$file);
		// <-- file_safe_opener
		$filepath = 'tiedostot/firma/'.Yii::app()->user->domain.'/'.end($explNimi);
		echo '<br>'.CHtml::link(Yii::t('main', 'Onlinevarausehdot'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	
   }

/*   foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/Konevuokraus_toimitusehdot.*')) as $file) 
   {
		$explNimi = explode("/",$file);
		// <-- file_safe_opener
		$filepath = 'tiedostot/firma/'.Yii::app()->user->domain.'/'.end($explNimi);
		echo '<br>'.CHtml::link(Yii::t('main', 'Konevuokraus toimitusehdot'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	
   }
   */
   ?>
      
</div>

<!-- Order summary footer-->
<div class="onlinevaraus-order-summary">
  <div class="col-sm-6 col-sm-offset-3 order-summary">Order Summary</div>
</div>


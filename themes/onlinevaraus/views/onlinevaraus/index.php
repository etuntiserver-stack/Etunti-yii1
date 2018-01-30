<?php
/* index
*/
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_onlinevaraus/js/onlinevaraus_index.js"></script>
<input type="hidden" id="valinnuPvm">


<div class="container">

 <div class="row">
  <div class="col-sm-6 col-sm-offset-3 select-service">

	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">
		<?=CHtml::link('Keskeytä', array('index', 'keskeyta' =>'true'))?>
	  	<h3><?=Yii::t('main', 'Palvelut')?></h3>

		<select class="form-control input-lg" id="palvelu">
		<option value="">Valitse palvelu</option>

		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " nayta_sivuilla=1 AND kategoria LIKE '%onlinevaraus%' ";
	       	$criteria->order = " nimike ";
		$onlineTuotteet = TuotteetPalvelut::model()->findAll($criteria);
		foreach($onlineTuotteet as $data)
		{
		  if(isset($_SESSION['onlinevaraus']['paapalvelu']) and $_SESSION['onlinevaraus']['paapalvelu'] == $data->id)
		  { 
			$selected = 'selected'; 
		  } else { 
			$selected = ''; 
		  }
		  echo '<option value="'.$data->id.'" '.$selected.'>'.$data->nimike.'</option>';
		}
		?>
		</select>
	  </div>
	</div>

	<!-- Toinen valikko -->
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">
		<div id="toinen_valiko"></div>
	  </div>
	</div>
	<br>
	<div class="row" id="toimialueRow">
	  <div class="col-sm-8 col-sm-offset-2">
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

		if(isset($_SESSION['onlinevaraus']['tyo_toimialue']))
		$options = array('class'=>'form-control input-lg', 'options' => array($_SESSION['onlinevaraus']['tyo_toimialue']=>array('selected'=>true)));
		else
		$options = array('class'=>'form-control input-lg');

        	echo CHtml::dropDownList('tyo_toimialue', 'tyo_toimialue', $list, $options);
        	?>

	    	<div id="lisapalvelulista"></div>

	  </div>
	</div>

  </div>
 </div>

 <div class="row kaksiKalenteria" style="display:none">
  <div class="col-sm-6 col-sm-offset-3 select-service">

	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">

		<div id="kalenterit"></div>
		<br>
		<div id="aikoja"></div>
		<div id="tidTietoja"></div>

	  </div>
	</div>

  </div>
 </div>

 <!-- Order summary footer-->
 <div class="row panGetContent" style="display:none">
  <div class="col-sm-6 col-sm-offset-3 select-service">
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">
	    <div id="panGetContent"></div>
	  </div>
	</div>
  </div>
 </div>

</div><!--container-->

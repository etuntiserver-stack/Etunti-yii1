<?php
/* index
*/
if(isset($_SESSION['onlinevaraus']['modelTV']))
echo $_SESSION['onlinevaraus']['modelTV'];
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

 <!-- Kalenterit-->
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

 <!-- Osoite-->
 <?php $osoite_style = ''; ?>
 <?php if(!isset($_SESSION['onlinevaraus']['sahkoposti'])) : ?>
 <?php $osoite_style = 'style="display:none"'; ?>
 <?php endif; ?>
 <div class="row osoitelaatikko" <?=$osoite_style?>>
  <div class="col-sm-6 col-sm-offset-3 select-service">
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">

		<div id="fullLomake">
		   <h4 class="title-subtitle text-center"><?php echo Yii::t('main', 'Osoite'); ?></h4>
		   <span class="text-sininen"><?php echo Yii::t('main', 'Tunnistaudu sähköpostilla'); ?></span>
	
		      <!--<span class="small"><?php echo Yii::t('main', 'sähköpostillaTeksti'); ?></span>-->
		      <br>

		   <div class="row">
		      <div class="col-sm-12">
	
		     	<div class="sahkoposti">
			<label><?php echo Yii::t('main', 'Sähköposti'); ?></label>
			<input type="text" id="sahkoposti" class="form-control input-lg" placeholder="Sähköposti" value="<?php if(isset($_SESSION['onlinevaraus']['sahkoposti'])) echo $_SESSION['onlinevaraus']['sahkoposti'] ;?>">
		     	</div>

		      </div><div class="col-sm-12">
		       	<div id="loytynytOsoitteet"></div>
		      </div>
		   </div>

		     <br>

		     <span class="text-sininen"><?php echo Yii::t('main', 'Tai täytä yhteystietokentät'); ?></span>
		     <!--<span class="small"><?php echo Yii::t('main', 'taitaytateksti'); ?></span>-->
		     <br>

		     <div id="lomake">
	
		      <div class="row">
		       <div class="col-sm-6">
			<label><?php echo Yii::t('main', 'Asiakastyyppi '); ?></label>
			  <select id="tyyppi" class="form-control input-lg">
			  <option value="henkilo">Yksityishenkilö</option>
			  <option value="yritys">Yritys</option>
			  </select>

		       </div>
		      </div>

		      <div class="row">
		       <div class="col-sm-6">
		
			<label><?php echo Yii::t('main', 'Yhteyshenkilö'); ?></label>
			  <input type="text" id="yhteyshenkilo" class="form-control input-lg">
	
			<label><?php echo Yii::t('main', 'Puhelin'); ?></label>
			  <input type="text" id="puhelin" class="form-control input-lg">
	
		       </div><div class="col-sm-6">

		        <div class="yritys">
			<label><?php echo Yii::t('main', 'Yrityksen Nimi'); ?></label>
			  <input type="text" id="yrityksen_nimi" class="form-control input-lg">
			</div>

		        <div class="yritys">
			<label><?php echo Yii::t('main', 'Y-tunnus'); ?></label>
			  <input type="text" id="y_tunnus" class="form-control input-lg">
			</div>
	
		       </div>
		      </div>

		      <br>
		      <center><h4><?php echo Yii::t('main', 'Osoite'); ?></h4></center>

		      <div class="row">
		       <div class="col-sm-12">
			<label><?php echo Yii::t('main', 'Osoite'); ?></label>
			  <input type="text" id="osoite" class="form-control input-lg">
		       </div>
		      </div>


		      <div class="row">
		       <div class="col-sm-6">
			<label><?php echo Yii::t('main', 'Postinumero'); ?></label>
			  <input type="text" id="postinumero" class="form-control input-lg">
		       </div><div class="col-sm-6">
			<label><?php echo Yii::t('main', 'Postitoimipaikka'); ?></label>
			  <input type="text" id="kaupunki" class="form-control input-lg">
		       </div>
		      </div>

		      <br>
		      <center><h4><?php echo Yii::t('main', 'Lisätietoja'); ?></h4></center>

		      <div class="row">
		       <div class="col-sm-12">
			<label><?php echo Yii::t('main', 'Lisätietoja'); ?></label>
			  <textarea id="lisatietoja" class="form-control input-lg" placeholder="<?php echo Yii::t('main', 'Lemmikkejä, ovikoodi ja muuta lisätietoa'); ?>" rows="5"></textarea>
		       </div>
		      </div>

		      <br>
		      <div id="getMyPictures"></div>
		      <br>
		
		      <div class="row">
		       <div class="col-sm-12">
			<label><?php echo Yii::t('main', 'Kuvien lisääminen'); ?></label>
			<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-filestyle.js"> </script>
		  	<form id="uploadKuva" action="#" method="post" enctype="multipart/form-data">
		     	  <div class="input-group">
				<input type="hidden" name="kuvanLisaaminen">
				<input type="file" name="file" class="filestyle" data-icon="false" data-size="lg" data-buttonName="btn-primary" data-buttonText="<?php echo Yii::t('main', 'Lisää kuva'); ?>">
				<span class="input-group-btn">
		          		<input type="submit" value="Lataa" class="btn btn-primary btn-lg btn-group myBgColors" />
				</span>
		    	  </div>
			</form>
		       </div>
		      </div>
		     </div>

		</div><!-- Full lomake -->

		<p><button class="tallennaUusi btn btn-lg btn-block btn-success">Sirry maksamaan <i class="caret"></i></button></p>

	  </div>
	</div>
  </div>
 </div>

 <!-- Maksu -->
 <?php $maksu_style = ''; ?>
 <?php if(!isset($_SESSION['onlinevaraus']['onlinevarausID'])) : ?>
 <?php $maksu_style = 'style="display:none"'; ?>
 <?php endif; ?>
 <div class="row maksulaatikko" <?=$maksu_style?>>
  <div class="col-sm-6 col-sm-offset-3 select-service">

	<div class="row">
	  <div class="col-sm-12" id="maksu_content">
		<?php 
		if(isset($_SESSION['onlinevaraus']['paapalvelu']) and isset($_SESSION['onlinevaraus']['onlinevarausID']))
		{
			echo $this->renderPartial('maksu', array('json' => false));
		}
		?>
	  </div>
	</div>
  </div>
 </div>
<button data-toggle="collapse" data-target="#order_summary">SHOW</button>
 <!-- Order summary footer-->
 <div class="row panGetContent collapse" id="order_summary">
  <div class="col-sm-6 col-sm-offset-3 select-service">
	<div class="row">
	  <div class="col-sm-8 col-sm-offset-2">
	    <div id="panGetContent"></div>
	  </div>
	</div>
  </div>
 </div>

</div><!--container-->

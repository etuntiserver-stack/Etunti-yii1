<?php
/* index
*/
/*
	$getTyovuorot = $this->getTyovuorot2months();
	echo '<pre>';
	print_r($getTyovuorot);
	echo '</pre>';
	exit;
*/
?>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_onlinevaraus/js/onlinevaraus_index.js"></script>
<input type="hidden" id="valinnuPvm">


<div class="container">
 <div class="row">
  <div class="col-sm-6 col-sm-offset-3">

	<div class = "row panel panel-default select-service-panel">
	 <div class="panel-heading">
     <?=CHtml::link('<span class="text-default fa fa-repeat" id="online_reset"</span>', array('index', 'keskeyta' =>'true'), array('class' => 'pull-right'))?>
	 </div>
	 <div class="row">
   	  <div class="col-sm-8 col-sm-offset-2">   
   	   <div class="panel-body">

	   <!-- Palvelut -->
	   <?php $palvelu_style = ''; ?>
	   <?php if(isset($_SESSION['onlinevaraus']['palvelut_summary'])) : ?>
	   <?php $palvelu_style = 'style="display:none"'; ?>
	   <?php endif; ?>

	   <div class="palvelutlaatikko" <?=$palvelu_style?>>
           <h3>Palvelut</h3>
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
	   <!-- Palvelut -->

	   <!-- Toinen valikko -->
	   <div id="toinen_valiko"></div>
		<h3>Alue</h3>
		<div id="toimialueRow">
		<?php
		$exists = Valikkoot::model()->find(" select_type='tyo_toimialue' ");
		if(!isset($exists->id))
		{
		    $valiko = new Valikkoot;
		    $valiko->select_type = 'tyo_toimialue';
		    $valiko->value = 'Test';
		    $valiko->save();
		}

      		$etsi_tt = Tyontekijat::model()->findAll(" online_varauksen_valmina=1 ");
		$list_tt_toimialue = array();
		foreach($etsi_tt as $item){
		   $dec = json_decode($item->tyo_toimialue, true);
		     if(is_array($dec)){
		     foreach($dec as $item2)
			$list_tt_toimialue[$item2] = $item2;
		     }
		}

		$list = array();
		$impl = "value='".implode("' OR value='", $list_tt_toimialue)."'";
		if( count($list_tt_toimialue) > 0 ){
      		  $l = Valikkoot::model()->findAll(" select_type='tyo_toimialue' AND ($impl)",array('order' => "select_type"));
		  foreach($l as $v){
			$list[$v->value] = $v->value;
		  }
		}

		if(isset($_SESSION['onlinevaraus']['tyo_toimialue']))
		$options = array('class'=>'form-control input-lg', 'options' => array($_SESSION['onlinevaraus']['tyo_toimialue']=>array('selected'=>true)));
		else
		$options = array('class'=>'form-control input-lg');

        	echo CHtml::dropDownList('tyo_toimialue', 'tyo_toimialue', $list, $options);
        	?>
		</div>
	    	<div id="lisapalvelulista"></div>

		<br>
  		<p><button class="kalenteriin btn btn-lg btn-block mybtn">Siirry kalenteriin <i class="caret"></i></button></p>
		<br>
	   </div>
	   <!-- Toinen valikko -->
	   </div><!--palvelutlaatikko-->

	   <!-- Aika-->
	   <?php $palvelu_style = 'style="display:none"'; ?>
	   <?php if(isset($_SESSION['onlinevaraus']['palvelut_summary'])) : ?>
	   <?php $palvelu_style = ''; ?>
	   <?php endif; ?>

	   <div class="container-fluid" id="aika_summary" <?=$palvelu_style?>>
  	    <h4 class="title-subtitle text-center"><?php echo Yii::t('main', 'Aika'); ?></h4>
 		<div id="kalenterit"></div>
 		<div id="aikoja"></div>
 		<div id="tidTietoja"></div>
	   </div>
	   <!-- Aika-->


	   <!-- Osoite-->
	   <?php $osoite_style = ''; ?>
	   <?php if(!isset($_SESSION['onlinevaraus']['modelTV'])) : ?>
	   <?php $osoite_style = 'style="display:none"'; ?>
	   <?php endif; ?>

  	   <div class="container-fluid osoitelaatikko" <?=$osoite_style?>">
  		<div id="fullLomake">
  		   <h4 class="title-subtitle text-center"><?php echo Yii::t('main', 'Osoite'); ?></h4>
  		   <h6 class="text-center"><?php echo Yii::t('main', 'Tunnistaudu sähköpostilla.'); ?></h6>
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
  		   <h6 class="text-center"><?php echo Yii::t('main', 'Oletko uusi tilaaja?<br> Täytä yhteystietokentät.'); ?></h6>

  		     <div id="lomake">
	
		      <!-- Poistettu käytöstä -->
  		      <div class="row" style="display:none">
  		       <div class="col-sm-6">
  			<label><?php echo Yii::t('main', 'Asiakastyyppi '); ?></label>
  			  <select id="tyyppi" class="form-control input-lg">
  			  <option value="henkilo">Yksityishenkilö</option>
  			  <option value="yritys">Yritys</option>
  			  </select>
  		       </div>
  		      </div>
		      <!-- /Poistettu käytöstä -->

  		      <div class="row">
  		       <div class="col-sm-6">
  			<label><?php echo Yii::t('main', 'Asiakkaan nimi'); ?></label>
  			  <input type="text" id="yhteyshenkilo" class="form-control input-lg">
  		       </div><div class="col-sm-6">
  			<label><?php echo Yii::t('main', 'Puhelin'); ?></label>
  			  <input type="text" id="puhelin" class="form-control input-lg">
  		       </div>
  		      </div>

			<!--
  		        <div class="yritys">
  			<label><?php echo Yii::t('main', 'Yrityksen Nimi'); ?></label>
  			  <input type="text" id="yrityksen_nimi" class="form-control input-lg">
  			</div>

  		        <div class="yritys">
  			<label><?php echo Yii::t('main', 'Y-tunnus'); ?></label>
  			  <input type="text" id="y_tunnus" class="form-control input-lg">
  			</div>
			-->
	


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

  		      <div class="row">
  		       <div class="col-sm-12">
  			<label><?php echo Yii::t('main', 'Lisätietoja'); ?></label>
  			  <textarea id="lisatietoja" class="form-control input-lg" placeholder="<?php echo Yii::t('main', 'Lemmikkejä, ovikoodi ja muuta lisätietoa'); ?>" rows="3"></textarea>
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
  				<input type="file" name="file" class="filestyle" data-icon="false" data-size="lg" data-buttonName="btn-primary mybtn" data-buttonText="<?php echo Yii::t('main', 'Lisää kuva'); ?>">
  				<span class="input-group-btn">
  		          		<input type="submit" value="Lataa" class="btn btn-primary btn-lg btn-group mybtn" />
  				</span>
  		    	  </div>
  			</form>
  		       </div>
  		      </div>

  		     </div><!--lomake-->
  		</div><!-- Full lomake -->
		<br>

		<?php
		// <-- Kupongi
		if(isset($_SESSION['onlinevaraus']['kupongi']))
		{
			$kup = Kupongit::model()->findbypk($_SESSION['onlinevaraus']['kupongi']);
			if(isset($kup->id))
			{
				if($kup->maara_tyyppi == 'euro')
				$kup_maara = '-'.$kup->euro_maara.' &euro;';
				if($kup->maara_tyyppi == 'prosentti')
				$kup_maara = '-'.$kup->prosentti_maara.'%';

				echo '
				<div class="row">
				 <div class="col-xs-2">
					<i class="fa fa-star fa-2x" aria-hidden="true"></i>
				 </div><div class="col-xs-10">
					'.Yii::t('main', 'Alennuskoodi').': '.$kup_maara.'
				 </div>
				</div>
				';
			}
		}
		?>

		<?php if(!isset($_SESSION['onlinevaraus']['kupongi'])) : ?>
		<div class="row">
		 <div class="col-xs-12">
		    <div class="input-group">
		      <input type="text" class="form-control input-lg kupongi_id" placeholder="Alennuskoodi">
		      <span class="input-group-btn">
		        <button class="btn btn-warning btn-lg kupongi_add mybtn" type="button"><?=Yii::t('main', 'Käytä')?></button>
		      </span>
		    </div>
		    <div class="kupongi_result"></div>
		 </div>
		</div>
		<?php endif; ?>
		<!-- /Kupongi -->

		<br>
  		<p><button class="tallennaUusi btn btn-lg btn-block mybtn">Siirry maksamaan <i class="caret"></i></button></p>
		<br>
  	   </div><!--osoitelaatikko-->

	   <!-- Maksu -->
	   <?php $maksu_style = ''; ?>
	   <?php if(!isset($_SESSION['onlinevaraus']['onlinevarausID'])) : ?>
	   <?php $maksu_style = 'style="display:none"'; ?>
	   <?php endif; ?>

	   <div class="container-fluid maksulaatikko" <?=$maksu_style?>>
            <h3> Maksu </h3>
   	    <div id="maksu_content">
		<?php 
		if(isset($_SESSION['onlinevaraus']['paapalvelu']) and isset($_SESSION['onlinevaraus']['onlinevarausID']))
		{
			echo $this->renderPartial('maksu', array('json' => false));
		}
		?>
	    </div>
	   </div>
	   <!-- Maksu -->


	  </div>
	 </div>
	</div>

  </div>
 </div>



 <!-- Order summary footer-->
 <div class="row yhteenveto-bottom">
  <div class="col-sm-6 col-sm-offset-3">
   <div class="row">
    <div class="col-sm-10 col-sm-offset-1" >
	    <div id="panGetContent"></div>
    </div>
   </div>
  </div>
 </div>

</div><!--container-->

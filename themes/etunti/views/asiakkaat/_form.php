<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */
/* @var $form CActiveForm */
/*
$asiakasnumero = '';
if(!isset($model->id)){
$nextnum = Asiakkaat::model()->find(array('order'=>'id DESC'));
$asiakasnumero = 'nro. '.($model->id+1).' on vapaa';
} else {
$asiakasnumero = 'voidaan käyttää oleva ID numero';
}
*/
     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

   $asetukset = Asetukset::model()->findbypk(1);
   $criteria = new CDbCriteria();
   $criteria->order = " cast(asiakasnumero as unsigned) DESC  ";
   $anum = Asiakkaat::model()->find($criteria);

   if(isset($anum->id) and !isset($model->id) and $asetukset->lasku_asiakasnumero == 0)
   $asnum = array('value'=>($anum->asiakasnumero+1),'class'=>'form-control');
   else
   $asnum = array('class'=>'form-control');

if(isset($model->id))
$model->hinta = str_replace(",",".",$model->hinta);

if(empty($model->salasana))
$uusiSalasana = $this->generatePassword();

if(isset($_GET['vinkki_id']))
{
	$vinkki = VinkkiExtranet::model()->findbypk($_GET['vinkki_id']);
	if(isset($vinkki->id))
	{
		$model->vinkki_id = $_GET['vinkki_id'];
		$model->tyyppi = 'henkilo';
		$model->sahkoposti = $vinkki->sahkoposti;
		$model->puhelin = $vinkki->puhelin;
	}
}

$model->hinta = round($model->hinta, 2);
$model->hinta_sis_alv = round($model->hinta_sis_alv, 2);

if(!isset($model->id) and isset($asetukset->id)){
	$model->laskutus_kanava = $asetukset->asiakas_laskutus_kanava;
	$model->kirjeenluokka = $asetukset->asiakas_kirjeenluokka;
	$model->viivastyskorko = $asetukset->asiakas_viivastyskorko;
	$model->maksuehto = $asetukset->asiakas_maksuehto;
	$model->myyja = $asetukset->asiakas_myyja;
	$model->hinta_tyyppi = $asetukset->asiakas_hinta_tyyppi;
	$model->alv = $asetukset->asiakas_alv;
	$model->tyoryhma = $asetukset->asiakas_tyoryhma;
	$model->ryhma = $asetukset->asiakas_ryhma;
}
?>

<style>
.hidd,.ashidd,.ashidd_a{
	display:none;
}
.extra-contact-box {
	box-shadow: 0 .25rem .5rem rgba(0,0,0,.15);
	padding-top: 10px;
	padding-bottom: 5px;
	margin-bottom: 5px;
	border-radius: 3px;
	background-color: #eaf0f5;
}

#extra-contact-list p {
	margin: 0;
}

#extra-contact-list > .row {
	margin-bottom: 1rem;
}
</style>




<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakkaat-form',
	'enableAjaxValidation'=>false, // ala laita true, saat monta asiakaita update aikana netvisorissa
)); ?>

	<?php echo $form->hiddenField($model,'vinkki_id'); ?>
	<?php echo $form->errorSummary($model); ?>


<!-- Freshdesk Header (notice) -->
<?php

/** @var Freshdesk object */
$freshdesk = Yii::createComponent('Freshdesk');

if (false && !$freshdesk->isDisabled() && ($model->freshdesk_id ?? 0) != 0) {

  $freshdesk_id = $model->freshdesk_id;

  // $fd_order_by = 'updated_at';
  // $fd_order_type = 'desc';
  // if (!in_array($fd_order_by, ['created_at', 'due_by', 'updated_at', 'status']))
  //   $fd_order_by = 'updated_at';
  // if (!in_array($fd_order_type, ['asc', 'desc']))
  //   $fd_order_type = 'desc';
  // $fd_pager_id = "freshdesk_tickets_orderby_{$fd_order_by}_{$fd_order_type}";
  $fd_pager_id = "freshdesk_tickets_orderby_updated_at_desc";

  // $freshdesk_pager = $freshdesk->getTicketPaginator(10);
  $freshdesk_pager = $freshdesk->getTicketPaginator(10, $fd_pager_id, function ($page, $page_size) use ($freshdesk) {
    return $freshdesk->listTickets(null, null, $page, $page_size, null, ['requester', 'description'], 'updated_at', 'desc');
  });

  $freshdesk_tickets = $freshdesk_pager->filtered(1, function ($item) use ($freshdesk_id) {
    return ($item['requester_id'] == $freshdesk_id);
  });

  foreach ($freshdesk_tickets as $ticket) {
    if (!in_array($ticket['status'] ?? 0, [4, 5])) {
      $freshdesk_link = $freshdesk->getFreshdeskCustomerUrl($freshdesk_id);
      echo '<div id="freshdesk-notice" class="section alert bg-warning">';
      echo CHtml::link(Yii::t('main', 'Tällä asiakkaalla on avoimia tukipyyntöjä Freshdeskissä. Avaa painamalla tästä.'), $freshdesk_link, ['class' => 'text-dark', 'target' => '_blank']);
      echo '</div>';
      break;
    }
  }
}

?>
<!-- Freshdesk // -->


<div class="row">
  <div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Asiakkaan tiedot'); ?></h3></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakasnumero'); ?>
		<?php echo $form->numberField($model,'asiakasnumero',$asnum); ?>
		<?php echo $form->error($model,'asiakasnumero'); ?>
	</div>
<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakastila'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakastila' ",array('order' => "select_type"));
		if(count($l) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "asiakastila";
			$new_val->value = "Aktiivinen";
			if($new_val->save())
	      			$l = Valikkoot::model()->findAll(" select_type='asiakastila' ",array('order' => "select_type"));
			else
				var_dump($new_val->getErrors());
		}
		foreach($l as $val)
			$list[$val->id] = $val->value;

        		echo $form->dropDownList($model, 'asiakastila', $list,
			array('empty'=>'Valitse', 'class'=>'form-control'));
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="asiakastila"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'asiakastila'); ?>
	</div>
*/ ?>

	<div class="section fill mb5 tyyppi">
		<?php echo $form->labelEx($model,'tyyppi'); ?>
		<?php
		$list = array('henkilo'=>Yii::t('main', 'Yksityishenkilö'), 'yritys'=>Yii::t('main', 'Yritys'));
        	echo $form->dropDownList($model, 'tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'tyyppi'); ?>
	</div>

	<div class="section fill mb5 yritys ashidd">
		<?php echo $form->labelEx($model,'yrityksen_nimi'); ?>
		<?php echo $form->textField($model,'yrityksen_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yrityksen_nimi'); ?>
	</div>

	<div class="section fill mb5 y_tunnus ashidd">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'henkilotunnus'); ?>
		<?php echo $form->textField($model,'henkilotunnus',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'henkilotunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'etunimi'); ?>
		<?php echo $form->textField($model,'etunimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'etunimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sukunimi'); ?>
		<?php echo $form->textField($model,'sukunimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sukunimi'); ?>
	</div>
	
	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<!-- Finnish service wish -->
	<div class="section fill mb5">
	<?php 
	
		echo $form->labelEx($model, 'finnish_service_wish');
		$list = [2 => Yii::t("app", "Ei valittu"), 1 => Yii::t("app", "Haluaa"), 0 => Yii::t("app", "Ei väliä")];
		echo $form->dropDownList($model, 'finnish_service_wish', $list,
			array('class'=>'form-control'));
	?>
	</div>
	<!-- /Finnish service wish -->

	<!-- Extra contacts -->
	<div class="row">
			<div class="col-sm-12">
				<?php // new contact input ?>
					<div class="form-group">
						<label class="control-label">
							<?= Yii::t('main', 'Lisä yhteystiedot');?>
						</label>
						<span class="input-group-btn">
							<button class="btn btn-primary new-extra-contact" type="button">
								<i class="fa fa-plus"></i>
							</button>
						</span>
					</div>
				<?php // new contact input / ?>
			</div>
			<?php // old, already existing list ?>
				<div class="col-sm-12">
					<div id="extra-contact-list">
						<div class="row">
						<?php if(is_array(json_decode($model->extra_contacts, true))) : ?>
							<?php foreach(json_decode($model->extra_contacts, true) as $k => $v): ?>
								<div class="wholerow">
								<?php $contact = json_decode($v, true); ?>
									<div class="col-sm-10">
										<p>
											<?php 
												echo $contact["etunimi"];
												echo " ";
												echo $contact["sukunimi"]; 
											?>
										</p>
										<p><?= $contact["phone"]; ?></p>
										<p><?= $contact["email"]; ?></p>
										<p style="font-weight: bold;">
											<?php 
											// there's a chance that invoice could equal to "off", so let's make sure it's "on"
											// "on" and "off" are the checkbox states in html
											if(isset($contact["invoice"]) and $contact["invoice"] === "on") {
												echo "Näytetään laskulla";
											} ?>
										</p>
									</div>
									<div class="col-sm-2">
										<button type="button" class="btn btn-danger btn-sm remove-extra-contact">
											<i class="fa fa-trash"></i>
										</button>
										<button type="button" class="btn btn-primary btn-sm edit-extra-contact">
											<i class="fa fa-pencil"></i>
											<input class="rowdata" type="hidden" name="Asiakkaat[extra_contacts][]" value='<?= $v ?>' />
										</button>
									</div>		
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
						</div>
					</div> <?php // extra-contact-list ?>
				</div>	<?php // col-sm-12 ?>
			<?php // old, already existing list / ?>
		</div>
		<!-- Extra contacts / -->

	<div class="section fill mb5 ashidd_a">

		<?php echo $form->labelEx($model,'myyja'); ?>
		<?php echo $form->dropDownList($model, 'myyja', CHtml::listData(Administrators::model()->findAll(), 'id', 'adm_nimi'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'myyja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoryhma'); ?>
		<?php
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
	       	$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = "select_type='tyoryhma'";
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
		$criteria->addCondition ("value2 LIKE '%\"".Yii::app()->user->adminID."\"%'");
		}

		$listData = Valikkoot::model()->findAll($criteria);
		?>
		<?php echo $form->dropDownList($model, 'tyoryhma', CHtml::listData($listData, 'id', 'value'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>


	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'ryhma'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma_real' ",array('order' => "select_type"));
		if(count($l) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "asiakas_ryhma_real";
			$new_val->value = "Testi ryhmä";
			if($new_val->save())
	      			$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma_real' ",array('order' => "select_type"));
			else
				var_dump($new_val->getErrors());
		}

			$arr = json_decode($model->ryhma);
			echo '<select name="Asiakkaat[ryhma][]" class="ryhmat form-control" multiple title="Valitse">';
			foreach($l as $data)
			{
				if(is_array($arr) and in_array($data->id, $arr))
			    		echo '<option value="'.$data->id.'" selected>'.$data->value.'</option>';
				elseif(!is_array($arr) and $model->ryhma == $data->id)
			    		echo '<option value="'.$data->id.'" selected>'.$data->value.'</option>';
				else
			    		echo '<option value="'.$data->id.'">'.$data->value.'</option>';
			}
			echo '</select>';
		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="asiakas_ryhma_real"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'ryhma'); ?>
	</div>

<?php if(in_array('5',$tas)) : ?>

	<?php if($asetukset->netvisor_kaytto == 1) : ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisorkey'); ?>
		<?php echo $form->numberField($model,'netvisorkey',array('size'=>10,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisorkey'); ?>
	</div>
	<?php endif; ?>

<?php endif ; ?>

<?php if(in_array('5',$tas)) : ?>
	<?php /*
	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'salasana'); ?>

		<?php if(isset($uusiSalasana)) : ?>
		<div class="alert bg-warning"><?php echo Yii::t('main', 'Uusi salasana generoitu ja ei viellä tallennettu'); ?></div>
		<?php $model->salasana = $uusiSalasana; ?>
		<?php endif ; ?>

		<?php echo $form->textField($model,'salasana',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'salasana'); ?>
	</div>
	*/ ?>
	<?php if(isset($model->id) and !empty($model->sahkoposti)): ?>
	<div class="section fill mb5 ashidd_a">
		<?php 
		$str = 'Lähetä eDico tunnukset asiakkaalle';
		$cl = 'btn btn-primary btn-block';
		if(empty($model->salasana) and empty($model->token))
			$str = Yii::t('main', 'Lähetä eDico tunnukset asiakkaalle');
		if(empty($model->salasana) and !empty($model->token)){
			$cl = 'btn btn-warning btn-block';
			$str = Yii::t('main', 'Tunnukset on lähetetty. Lähetä uudelleen');
		}
		if(!empty($model->salasana) and empty($model->token)){
			$cl = 'btn btn-success btn-block';
			$str = Yii::t('main', 'Tunnus on aktiivinen. Lähetä uudelleen');
		}
		?>

		<?php echo CHtml::link($str, 
				array('update', 'id'=>$model->id, 'laheta_tunnukset'=>true), 
				array(
					'class' => $cl,
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä'),
					//'target' => '_blank'
				)
			); 
		?>

	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_kayttoehdot'); ?>
		<?php echo $form->checkbox($model,'app_kayttoehdot',array('class'=>'sw')); ?>
		<?php echo $form->error($model,'app_kayttoehdot'); ?>
	</div>

	<?php if(isset($model->id) and is_array(json_decode($model->alennuskoodit, true))) : ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alennuskoodit'); ?>
		<?php
			$alennuskoodit = json_decode($model->alennuskoodit, true);
			if( is_array($alennuskoodit) ){
			  echo '<textarea class="form-control" name="Asiakkaat[alennuskoodit]">'.implode("\n", $alennuskoodit).'</textarea>';
			}
		?>
	</div>
	<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(1=>Yii::t('main', 'Kyllä'),0=>Yii::t('main', 'Ei'));
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sopimustyyppi'); ?>
		<?php
		$list = array(1=>Yii::t('main', 'Jatkuva'), 2=>Yii::t('main', 'Kerta'), 3=>Yii::t('main', 'Määräaikainen'));
        	echo $form->dropDownList($model, 'sopimustyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'sopimustyyppi'); ?>
	</div>

	<span class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#lopettaminen">Asiakas lopettaa <i class="caret"></i></span>

	<div id="lopettaminen" class="collapse">
	<p>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lopetuksen_pvm'); ?>
		<?php echo $form->textField($model,'lopetuksen_pvm',array('size'=>60,'maxlength'=>100,'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'lopetuksen_pvm'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lopetuksen_syy'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='lopetuksen_syy' ",array('order' => "select_type"));
		if(count($l) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "lopetuksen_syy";
			$new_val->value = "Kallis Hinta";
			if($new_val->save())
	      			$l = Valikkoot::model()->findAll(" select_type='lopetuksen_syy' ",array('order' => "select_type"));
			else
				var_dump($new_val->getErrors());
		}
		foreach($l as $val)
			$list[$val->id] = $val->value;

        		echo $form->dropDownList($model, 'lopetuksen_syy', $list,
			array('empty'=>'Valitse', 'class'=>'form-control'));
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="lopetuksen_syy"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'asiakastila'); ?>
	</div>
	</p>
	</div>

  </div><div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Laskutusosoite'); ?></h3></legend>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('class'=>'form-control','maxlength'=>5)); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'puhelin'); ?> <?php if(!empty($model->puhelin)): ?><a href="tel:<?php echo $model->puhelin; ?>">***soita***</a><?php endif; ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>50,'class'=>'form-control')); ?>
    <?php echo $form->error($model,'puhelin'); ?>
    <div id="puhelin-varoitus" class="alert alert-danger text-dark" style="display:none"><ul></ul></div>
  </div>

  <!-- Enable phone number validation only when Freshdesk is enabled.
       Freshdesk requires phone numbers to have the area code (e.g. +358).
       If Freshdesk is inactive in this domain, the format doesn't matter. -->
  <?php if (!$freshdesk->isDisabled()): ?>
  <script>
    $(function() {

      /**
       * Validate phone number for Freshdesk.
       *
       * Freshdesk requires phone numbers to contain area code (e.g. +358) and
       * no whitespace between digits.
       *
       * Instead of directly preventing "invalid" data, which might break
       * something else, we give notice and hide the submit button until data is
       * valid for Freshdesk.
       *
       * This should only be used when Freshdesk is enabled on the domain.
       */
      const validatePhoneNumber = function() {

        const val = $('#Asiakkaat_puhelin').val();
        let errors = [];

        // Check that the phone number contains area code.
        if (!/^\+.*$/.test(val)) {
          errors.push('Aluekoodi vaaditaan (esim. +358).');
        }

        // Check for spaces in the number.
        if (/\s+/.test(val)) {
          errors.push('Puhelinnumero ei saa sisältää välilyöntejä.');
        }

        if (errors.length > 0) {
          let text = 'Korjaa seuraavat tiedot puhelinnumerossa:<br><ul>';
          errors.forEach((item, index) => { text += `<li>${item}</li>`; });
          text += '<li>Laita muut tiedot sekä numerot allaolevaan "toissijainen puhelinnumero" kenttään.</li></ul>';
          $('#puhelin-varoitus').html(text).show();
          $('#puhelin-submitvaroitus').show();
          $('#asiakas-submit').attr('disabled', 'disabled');
          return false;
        } else {
          $('#puhelin-varoitus').html('').hide();
          $('#puhelin-submitvaroitus').hide();
          $('#asiakas-submit').removeAttr('disabled');
          return true;
        }
      };

      /**
       * Hook phone number validation to keyup event on the number field.
       */
      $('#Asiakkaat_puhelin').keyup(function() {
        validatePhoneNumber();
      });

      /**
       * Hook phone number validation to form submission.
       */
      $('#asiakkaat-form').on('submit', function(e) {
        if (!validatePhoneNumber()) {
          e.preventDefault();
        }
      });
    });
  </script>
  <?php endif; ?>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'toissijainen_puhelinnumero'); ?>
		<?php echo $form->textarea($model,'toissijainen_puhelinnumero',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'toissijainen_puhelinnumero'); ?>
	</div>

	<br>
	<legend><?php echo Yii::t('main', 'Käyntiosoite'); ?> <input type="checkbox" data-toggle="collapse" data-target="#kosoiteet"></legend>

	<div id="kosoiteet" class="collapse">

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'k_osoite'); ?>
		<?php echo $form->textField($model,'k_osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'k_osoite'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'k_postinumero'); ?>
		<?php echo $form->textField($model,'k_postinumero',array('class'=>'form-control','maxlength'=>5)); ?>
		<?php echo $form->error($model,'k_postinumero'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'k_kaupunki'); ?>
		<?php echo $form->textField($model,'k_kaupunki',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'k_kaupunki'); ?>
	</div>

	</div>

  </div>

<!-- Laskutus-->
<div class="col-sm-3">
	
	<legend><h3><?php echo Yii::t('main', 'Laskutus tiedot'); ?></h3></legend>

	<?php if( $asetukset->netvisor_kaytto == 1 ): ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_dimension_name'); ?>
		<?php 
		echo '<select class="form-control" name="Asiakkaat[netvisor_dimension_name]">';
	 	echo '<option>Valitse</option>';
		$l_controller = Yii::app()->createController('Lasku');
		foreach($l_controller[0]->netvisorLaskentaKohteetLista() as $k => $v){
		 foreach($v->DimensionName as $k1 => $v1){
		 	echo '<optgroup label="'.$v1->Name.'">';
			foreach($v1->DimensionDetails->DimensionDetail as $k2 => $v2){
			 	echo '<option value="'.$v1->Name.'//'.$v2->Name.'" '.(( isset($model->netvisor_dimension_name) and !empty($model->netvisor_dimension_name) and isset($model->netvisor_dimension_item) and !empty($model->netvisor_dimension_item) and $model->netvisor_dimension_name.'//'.$model->netvisor_dimension_item == $v1->Name.'//'.$v2->Name )? 'selected':'').'>'.$v2->Name.'</option>';
			}
		 }
		}
		echo '</select>';
		?>
	</div>
	<?php endif; ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus_kanava'); ?>
		<?php
		$list = array(	'posti'=>Yii::t('main','Posti'),
				'verkkolasku'=>Yii::t('main','Verkkolasku'),
				'sahkoposti'=>Yii::t('main','Sähköposti')
				);
        	echo $form->dropDownList($model, 'laskutus_kanava', $list,
		array('empty'=>'Valitse','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'laskutus_kanava'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sahkopostilaskuosoite'); ?>
		<?php echo $form->textField($model,'sahkopostilaskuosoite',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkopostilaskuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kirjeenluokka'); ?>
		<?php
		$list = array(	'1'=>Yii::t('main','Luokka 1'),
				'2'=>Yii::t('main','Luokka 2')
				);
        	echo $form->dropDownList($model, 'kirjeenluokka', $list,
		array('empty'=>'Valitse','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'kirjeenluokka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'muistutuslasku_auto'); ?>
		<?php
		$list = array(	'0'=>Yii::t('main','Kyllä'),
				'1'=>Yii::t('main','Ei')
				);
        	echo $form->dropDownList($model, 'muistutuslasku_auto', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'muistutuslasku_auto'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ovt_tunnus'); ?>
		<?php echo $form->textField($model,'ovt_tunnus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'ovt_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'verkkolaskuosoite'); ?>
		<?php echo $form->textField($model,'verkkolaskuosoite',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'verkkolaskuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'valittajan_tunnus'); ?>
		<?php echo $form->textField($model,'valittajan_tunnus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'valittajan_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->numberField($model,'viivastyskorko',array('size'=>60,'maxlength'=>20,'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'maksuehto'); ?>
		<?php echo $form->numberField($model,'maksuehto',array('size'=>60,'maxlength'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'maksuehto'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'lisatietoja_laskutuksesta'); ?>
		<?php echo $form->textarea($model,'lisatietoja_laskutuksesta',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'lisatietoja_laskutuksesta'); ?>
	</div>

	<legend><h3>Lasku hinnasto.</h3></legend>

	<!-- Tuotteet palvelut -->
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuote'); ?> <b class="pull-right text-success">Uusi valikko</b>
		<?php echo $form->dropDownList($model, 'tuote', CHtml::listData(TuotteetPalvelut::model()->findAll(), 'id', 'nimike'), 
		array('empty'=>'Valitse tuote', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'tuote'); ?>
	</div>
	
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinnasto_id'); ?>
		<?php echo $form->dropDownList($model, 'hinnasto_id', CHtml::listData(Hinnastot::model()->findAll(), 'id', 'hinnaston_otsikko'), 
		array('empty'=>'Valitse hinnasto', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'hinnasto_id'); ?>
	</div>
	<!-- Tuotteet palvelut -->
	
<br>
	<?php $poistetaan = 'Tämä valinta poistetaan 06/2021'; ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv'); ?> <b class="pull-right text-danger"><?=$poistetaan?></b>
		<?php
        	$l = array(0=>0,10=>10,14=>14,24=>24);

        	echo $form->dropDownList($model, 'alv', $l,
		array('empty'=>'Valitse','class'=>'form-control'
		));
        	?>
		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_tyyppi'); ?> <b class="pull-right text-danger"><?=$poistetaan?></b>
		<?php
		$list = array(1=>'tunti',2=>'kk',3=>'kpl');
        	echo $form->dropDownList($model, 'hinta_tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'hinta_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?> <b class="pull-right text-danger"><?=$poistetaan?></b>
		<?php echo $form->numberField($model,'hinta',array('size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'verot'); ?> <b class="pull-right text-danger"><?=$poistetaan?></b>
		<?php echo $form->numberField($model,'verot',array('size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'verot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_sis_alv'); ?> <b class="pull-right text-danger"><?=$poistetaan?></b>
		<?php echo $form->numberField($model,'hinta_sis_alv',array('size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'hinta_sis_alv'); ?>
	</div>


<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	//size: "large",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

  laskurin();

  $("#Asiakkaat_alv").change(function() {
	laskurin();
  });
  $("#Asiakkaat_hinta").keyup(function() {
	laskurin();
  });
  $("#Asiakkaat_hinta_sis_alv").keyup(function() {
	var hinta_sis_alv = parseFloat($(this).val());
	var alv = parseFloat($("#Asiakkaat_alv").val());
	var result = hinta_sis_alv/(1+(alv/100));
	$("#Asiakkaat_hinta").val(result.toFixed(2));
	$("#Asiakkaat_verot").val((hinta_sis_alv-result).toFixed(2));
  });

  function laskurin()
  {
	var alv = parseFloat($("#Asiakkaat_alv").val());
	var hinta = parseFloat($("#Asiakkaat_hinta").val());
	var hinta_sis_alv = ((alv/100)*hinta)+hinta;
	$("#Asiakkaat_hinta_sis_alv").val(hinta_sis_alv.toFixed(2));
	$("#Asiakkaat_verot").val((hinta_sis_alv-hinta).toFixed(2));
  }

});
</script>

  </div>
<!-- Laskutus loppu -->


<div class="col-sm-3">

	<?php if(isset($model->id)): ?>
	<legend><h3><?php echo Yii::t('main', 'Asiakkaaseen liittyviä kohteita'); ?></h3></legend>
	<br>
	<div class="section fill mb5">
		<?php echo CHtml::link(' +','/index.php/kohteet/createfromasiakas?id='.$model->id,array('class'=>'btn btn-default glyphicon glyphicon-home')); ?>
	</div>

	<div class="section fill mb5">
		<?php
		$k = Kohteet::model()->findAll("asiakas_id='".$model->id."'");
        	foreach($k as $v)
		{
		   echo '<div class="section fill mb5">
		   <h3 class="glyphicon glyphicon-home"></h3>&nbsp;&nbsp;&nbsp; 
		   '.CHtml::link($v->osoite,'/index.php/kohteet/update?id='.$v->id,array('class'=>'link')).'	       	  
       </div>';
       $this->renderPartial('//kohteet/omasiistijat', ['kohde_id' => $v->id]);
		}	
        	?>
	</div>
	<?php endif; ?>

  </div>
</div><!-- form -->
<br>

<!-- Muistiinpanot -->
<div class="row">
  <div class="col-sm-6">
	<div class="section fill mb5">
	    <div class="input-group">
	      <span class="form-control"><?php echo Yii::t('main','Muistiinpano'); ?></span>
	      <span class="input-group-btn">
	        <button class="btn btn-primary uusimuistinpanno" type="button"><i class="fa fa-plus"></i></button>
	      </span>
	    </div>  
	    <br>
		<div id="muistiinpanolista">
		 <?php if(is_array(json_decode($model->muistiinpano, true))): ?>
		 <div class="row"><div class="col-sm-12 muistiinpanolista_laatiko">
		 <legend><?php echo Yii::t('main','Muistiinpanot'); ?></legend>
		 <?php foreach(json_decode($model->muistiinpano, true) as $k => $v): ?>
		 <div class="row">
		  <div class="col-sm-11">
		   <?php if( isset($mobile->id) ) : ?>
		    <textarea name="Asiakkaat[muistiinpano][]" class="form-control" readonly><?=$v?></textarea>
		   <?php else: ?>
		    <textarea name="Asiakkaat[muistiinpano][]" class="form-control"><?=$v?></textarea>
		   <?php endif; ?>
		  </div>
		  <div class="col-sm-1 text-right">
			<span class="link text-danger fa fa-trash pois_muistiinpano"></span>
		  </div>
		 </div>
		 <?php endforeach; ?>
		 </div></div><!--row-->
		 <?php endif; ?>
		</div>

	</div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  $(".uusimuistinpanno").click(function(){
    var mp_lista = $("#muistiinpanolista").text().trim();
    if( mp_lista == '' ){
    $("#muistiinpanolista").append('<div class="row"><div class="col-sm-12 muistiinpanolista_laatiko"><legend>Muistiinpanot</legend>');
    }

    $(".muistiinpanolista_laatiko").append('' +
		 '<div class="row">' +
		  '<div class="col-sm-11">' +
		   '<textarea name="Asiakkaat[muistiinpano][]" class="form-control"></textarea>' +
		  '</div>' +
		  '<div class="col-sm-1 text-right">' +
		   '<span class="link text-danger fa fa-trash pois_muistiinpano"></span>' +
		  '</div>' +
 		 '</div>'
    );
    if( mp_lista == '' ){
    $(".muistiinpanolista_laatiko").append('</div></div>');
    }
    $(".muistiinpanolista_laatiko textarea:last").val('<?=date("d.m.Y H:i")?> - <?=Yii::app()->user->nimi?>:\n').focus();
  });

  $(document).delegate(".pois_muistiinpano","click",function(){
   $(this).closest(".row").remove();
  });

});
</script>
<!-- Muistiinpanot //-->


<!-- Freshdesk -->
<?php

if (
  !$freshdesk->isDisabled()
  && ($model->freshdesk_id ?? 0) != 0
  && !empty($freshdesk_tickets)
) {
  // Freshdesk tickets header
  echo '<legend class="mb5"><h3>' . Yii::t('main', 'Freshdesk Tukipyynnöt') . '</h3></legend>';
  echo '<p class="mb20 text-muted">Paina pyyntöä avataksesi tarkemmat tiedot</p>';

  // Loop ticket listing and draw a collapsible box for each one.
  foreach ($freshdesk_tickets as $ticket) {
    $this->renderPartial('freshdesk_ticket', [
      'partial_ticket' => $ticket,
      'style' => 'collapse'
    ]);
  }
}

?>
<br><br>
<!-- Freshdesk // -->


	<div class="section">
    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Luo') : Yii::t('main', 'Tallenna'),array('id' => 'asiakas-submit', 'class'=>'btn btn-primary myBgColors luoTallennaAsiakas')); ?>
    <p id="puhelin-submitvaroitus" class="text-alert" style="display:none">Korjaa puhelinnumero ennen tallentamista.</p>
	</div>


<?php $this->endWidget(); ?>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select_valiko.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	//size: "large",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });


  $(".luoTallennaAsiakas").click(function(e) {
    e.preventDefault()
    var tyyppi = $('#Asiakkaat_tyyppi option:selected').val();
    if( tyyppi == 'henkilo' && $('#Asiakkaat_etunimi').val() == '' ){
	$('#Asiakkaat_etunimi').focus();
	alert('Yksityisasiakkaalle etunimi on pakollinen tieto.');
	return false;
    }
    if( tyyppi == 'yritys' && $('#Asiakkaat_yrityksen_nimi').val() == '' ){
	$('#Asiakkaat_yrityksen_nimi').focus();
	alert('Yrityksen nimi ei saa olla tyhjänä!');
	return false;
    }
    $('#asiakkaat-form').submit();
  });
  $("#Asiakkaat_tyyppi").each(function() {
    var value = $(this).val();
    if(value !== '')
      laskutusTyyppi(value);
    else
      openAll();

  });
  $("#Asiakkaat_sopimustyyppi").change(function() {
	sopimustyyppi();
  });

sopimustyyppi();
function sopimustyyppi(){
	if( $('#Asiakkaat_sopimustyyppi option:selected').val() == '3' ){
		$("#lopettaminen").addClass('in');
		$("#Asiakkaat_lopetuksen_pvm").prop('required',true);
		alert('Päättymispäivä jolloin asiakas menee passiviksi on pakkollinen');
	} else {
		$("#Asiakkaat_lopetuksen_pvm").prop('required',false);
		$("#lopettaminen").removeClass('in');
	}
}

$("#Asiakkaat_tyyppi").change(function() {
    var value = $(this).val();
    laskutusTyyppi(value);
});

$("#Asiakkaat_lopetuksen_pvm").keyup(function(e) {
    if( e.which == 9 ) {
        alert('VAROITUS! Olet laittamassa asiakkaan passiiviseksi ja tämän asiakkaan työvuorot eivät sen jälkeen enää toimi oikein. \n Jos haluat perua tämän siirron, poista päivämäärä kentästä: "Päivämäärä, jolloin asiakas menee passiiviseksi"');
    }
});

/*
$("#Asiakkaat_asiakasnumero").keyup(function() {
    var checkLastAsiakasID = $(this).val();
        $.ajax({
           url: "checkLastAsiakasID",
	   type:'POST',
	   data: { "checkLastAsiakasID" : checkLastAsiakasID },
           success: function(data){
		console.log(data)
		if(parseInt(data) == 1)
		{
		  $("input").prop("disabled", true);
		  $("select").prop("disabled", true);
		  $("#Asiakkaat_asiakasnumero").prop("disabled", false);
		} else {
		  $("input").prop("disabled", false);
		  $("select").prop("disabled", false);
		}
           }
        });
});
*/

 function openAll(){
	$(".ashidd_a").show('slow');
	$(".ashidd").show('slow');
 }

 function laskutusTyyppi(value){

	$(".ashidd_a").show('slow');
    if(value == 'yritys'){
	$(".ashidd").hide('slow');
	$(".yritys").show('slow');
	$(".y_tunnus").show('slow');
	$(".nimi").show('slow');
    }
    if(value == 'henkilo'){
	$(".ashidd").hide('slow');
	$(".nimi").show('slow');
    }

 }

 $('.ryhmat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Ryhmät"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
 });

 // extra contact
 $('.new-extra-contact').click(() => {
        let contact_list = $('#extra-contact-list');
        if(contact_list) {
            contact_list.append(createNewContactBlock());
        } else {
            console.warn("Couldn't find extra-contact-list!");
        }
    });

    // apparently the contents of an arrow function scope can't change
    // so we'll need to use a normal function here
    // (the value of $(this) will be wrong with an arrow function)
    $(document).delegate(".remove-extra-contact", "click", function() {
        $(this).closest(".wholerow").remove();
    });

    $(document).delegate(".edit-extra-contact", "click", function() {
        // find our data
        let rowData = $(this).find(".rowdata")[0].value;
        // append extra-contact-list with a new creation form
        let contact_list = $('#extra-contact-list');
        if(contact_list) {
            contact_list.append(createNewContactBlock(JSON.parse(rowData)));
        } else {
            console.warn("Couldn't find extra-contact-list!");
        }
        // remove the row being edited
        $(this).closest(".wholerow").remove();
    });

    $(document).delegate(".cancel-extra-contact", "click", () => {
        $("#extra-form").remove();
    });

    $(document).delegate(".save-extra-contact", "click", () => {
        // find our form and get the data
        const extraForm = document.forms["extra-contact-form"];
        const data = Object.fromEntries(new FormData(extraForm).entries());

        // validate email
        if(data.email) {
            // source: https://emailregex.com/
            if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(data.email)) {
                $("#extra-contact-email-error").append('<p style="color: red">Tarkista sähköposti</p>');
                return;
            } else {
                // clear errors if OK
                $("#extra-contact-email-error").empty();
            }
        }

        // validate phone number
        if(data.phone) {
            if (!/^\+.*$/.test(data.phone)) {
                $("#extra-contact-phone-error").append('<p style="color: red">Aluekoodi vaaditaan (esim. +358)</p>');
                return;
            } else {
                // clear errors if OK
                $("#extra-contact-phone-error").empty();
            }
        }
        
        // create fields that will be shown to the user,
        // this will also create a hidden input with the form data
        let extraList = $("#extra-contact-list").find(".row");
        if(extraList) {
            extraList.append(createDataRows(data));
        } else {
            console.warn("Couldn't find extra-contact-list!");
        }
        $("#extra-form").remove()
    });

    /**
     * Creates a HTML block that shows the newly created extra contacts information
     * also creates a hidden input which will be used to carry that data to the database
     * when submitting the whole form.
     * Data param is the extra contact creation form data in JSON format:
     * {etunimi: "name", sukunimi: "name", phone: "123", email: "asd@asd.com"}
     * @param {*} data 
     * @returns 
     */
    function createDataRows(data) {
       return `
        <div class="wholerow">
            <div class="col-sm-10">
                <p>${data.etunimi} ${data.sukunimi}</p>
                <p>${data.phone}</p>
                <p>${data.email}</p>
                <p style="font-weight: bold">${data.invoice ? "Näytetään laskulla" : ""}</p>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger btn-sm remove-extra-contact">
                    <i class="fa fa-trash"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm edit-extra-contact">
                    <i class="fa fa-pencil"></i>
                    <input class="rowdata" type="hidden" name="Asiakkaat[extra_contacts][]" value='${JSON.stringify(data)}' />
                </button>
            </div>
        </div>
       `;
    }

    // editObject contains the data of an extra contact
    // the user wants to edit
    // called from .edit-extra-contacts onClick event handler
    //
    // also called from .new-extra-contact onClick event handler
    // without parameters
    function createNewContactBlock(editObject) {

        return `
        <form name="extra-contact-form" id="extra-form">
            <div class="row">
                <div class="col-sm-12 extra-contact-box">
                    <div class="form-group">
                        <label class="control-label">Etunimi</label>
                        <input name="etunimi" type="text" class="form-control" value="${editObject?.etunimi ?? ""}"></input>
                        <label class="control-label">Sukunimi</label>
                        <input name="sukunimi" type="text" class="form-control" value="${editObject?.sukunimi ?? ""}"></input>
                        <label class="control-label">Sähköposti</label>
                        <input name="email" type="text" class="form-control" value="${editObject?.email ?? ""}"></input>
                        <div id="extra-contact-email-error"></div>
                        <label class="control-label">Puhelinnumero</label>
                        <input name="phone" type="text" class="form-control" value="${editObject?.phone ?? ""}"></input>
                        <div id="extra-contact-phone-error"></div>
                        <label class="control-label" for="invoice">Näytetään laskulla</label>
                        <input name="invoice" id="invoice" type="checkbox" ${editObject?.invoice ? "checked" : ""}>
                    </div>
                    <button type="button" class="btn btn-success save-extra-contact">
                        <i class="fa fa-save"></i>
                    </button>
                    ` // show cancel button only if not editing
                      // the user can save even without making any changes
                    + ( editObject ? `` :  
                    `<button type="button" class="btn btn-danger cancel-extra-contact">
                        <i class="fa fa-times"></i>
                    </button>`)
                    
                    +
                    `
                </div>
            </div>
        </form>
        `;
    }

});
</script>



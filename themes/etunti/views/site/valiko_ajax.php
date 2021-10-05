<?php

	if($_POST['select_type'] == 'vuosilomat') $selType = 'Vuosilomat';
	//elseif($r->select_type == 'laskun_tilanne') $selType = 'laskun tilanne';
	//elseif($r->select_type == 'AddTvuoro') $selType = 'Ajan välit';
	//elseif($r->select_type == 'tyoajanlaatu') $selType = 'Työajanlaatu';
	//elseif($r->select_type == 'palkkaan_hinnat') $selType = 'Palkkaan hinnat';
	//elseif($r->select_type == 'Ruokatauko') $selType = 'Ruokatauko';
	elseif($_POST['select_type'] == 'tyoehtosopimus') $selType = 'Työehtosopimus';
	elseif($_POST['select_type'] == 'kortit') $selType = 'Kortit';
	elseif($_POST['select_type'] == 'online_varauksen_valmina') $selType = 'online varaus';
	elseif($_POST['select_type'] == 'Palkkausmuoto') $selType = 'Palkkausmuoto';
	elseif($_POST['select_type'] == 'tilanne') $selType = 'Tilanne';
	elseif($_POST['select_type'] == 'aktiivinen') $selType = 'Työssä Aktiivinen';
	elseif($_POST['select_type'] == 'palkka_tyyppi') $selType = 'Palkkatyypit';
	elseif($_POST['select_type'] == 'tyoajanmerkinta') $selType = 'Työajanmerkinta';
	elseif($_POST['select_type'] == 'admin status') $selType = 'Oikeukset';
	elseif($_POST['select_type'] == 'siivous') $selType = 'Siivous tyyppi';
	elseif($_POST['select_type'] == 'asiakas_ryhma') $selType = 'Toimialue';
	elseif($_POST['select_type'] == 'asiakas_ryhma_real') $selType = 'Asiakasryhmä';
	elseif($_POST['select_type'] == 'laskutus_tuotteet_ryhma') $selType = 'Laskutuksen tuoteryhmä';
	elseif($_POST['select_type'] == 'laskutus_yksikko') $selType = 'Yksikööt';
	elseif($_POST['select_type'] == 'YLITYÖTUNNIT') $selType = 'YLITYÖTUNNIT';
	elseif($_POST['select_type'] == 'tyo_toimialue') $selType = 'Työntekijä toimialue';
	elseif($_POST['select_type'] == 'tyonkuvaus_tilat') $selType = 'Työnkuvaus tilat';
	elseif($_POST['select_type'] == 'asiakastila') $selType = 'Asiakastila';
	elseif($_POST['select_type'] == 'tarjous_tarvikkeet') $selType = 'Tarvikkeet';
	elseif($_POST['select_type'] == 'kategoria') $selType = 'Kategoriat';
	elseif($_POST['select_type'] == 'lopetuksen_syy') $selType = 'Lopetuksen syy';
	elseif($_POST['select_type'] == 'ohjevideo_ryhma') $selType = 'Ohjevideo ryhmä';
	else $selType = $r->select_type;

	$maxlength = "150";
	if($_POST['select_type'] == 'lopetuksen_syy'){
		$maxlength = "50";
	}

       	$criteria = new CDbCriteria();
	$criteria->condition = " select_type='".$_POST['select_type']."' ";
	$r = Valikkoot::model()->find($criteria);
	if(!$r) {
		$r = new Valikkoot();
		$r->select_type = $_POST["select_type"];
	}


	$mod = '
	<script type="text/javascript" src="'.Yii::app()->request->baseUrl.'/js/jscolor.js"></script>
	<input type="hidden" id="select_type" value="'.$_POST['select_type'].'">

	<div class="modal-dialog modal-lg" id="myModal">
	 <div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">'.Yii::t('main','Alasvetovalikon hallinta').'</h4>
	  </div>
	 <div class="modal-body">
	  <div class="row">
		<div class="col-sm-4">
		<br><legend>'.$selType.'</legend>';

		if($_POST['select_type'] == 'aktiivinen')
			$mod .= 'Malli:  Tilanne/ID, Esimerkiksi Aktiivinen/1';		

		$mod .= '</div><div class="col-sm-8">';
		$mod .= '<p><center>';

	       	$criteria = new CDbCriteria();
		$criteria->condition = " select_type = '".$_POST['select_type']."' ";
		$v2 = Valikkoot::model()->findAll($criteria);

		foreach($v2 as $u){

		if(isset($_POST['id']) and $_POST['id'] == $u['id']){ $success = 'btn-success'; } else { $success = ''; }

		$mod .= '
		<div class="row moe" id="rivi_'.$u->id.'">
		 <div class="col-sm-12">
		  <div class="form-inline">
		   <div class="pull-right">';
			if($_POST['select_type'] == 'vuosilomat'){

				$exVari = explode('/',$u->value);
				if(isset($exVari[0]) and isset($exVari[1]) and isset($exVari[2])){
					$ex0 = $exVari[0];
					$ex1 = $exVari[1];
					$ex2 = $exVari[2];
					$mod .= '
					<input type="text" class="m0 form-control form-group" size="3" value="'.$ex0.'" id="m0_'.$u->id.'" placeholder="Merki">
					<input type="text" class="m1 form-control form-group" value="'.$ex1.'" id="m1_'.$u->id.'" placeholder="Nimike">
					<button class="btn btn-default jscolor {valueElement:\'m2_'.$u->id.'\'}">'.Yii::t('main', 'Väri').'</button>
					<input type="hidden" class="color_valinta_vuosilomat" id="m2_'.$u->id.'" value="'.$ex2.'">'; // , onFineChange:\'setTextColor(this)\'
				}
				$mod .= '<input type="hidden" class="form-control color_set" value="'.$u->value.'" id="m_'.$u->id.'">';

			} elseif($_POST['select_type'] == 'tyoajanmerkinta'){

				$exVari = explode('/',$u->value);
				if(isset($exVari[0]) and isset($exVari[1])){
					$ex0 = $exVari[0];
					$ex1 = $exVari[1];
					$mod .= '
					<input type="text" class="m0 form-control form-group" value="'.$ex0.'" id="m0_'.$u->id.'" placeholder="Nimike">
					<button class="btn btn-default jscolor {valueElement:\'m1_'.$u->id.'\'}">'.Yii::t('main', 'Väri').'</button>
					<input type="hidden" class="color_valinta_tyoajanmerkinta" id="m1_'.$u->id.'" value="'.$ex1.'">'; // , onFineChange:\'setTextColor(this)\'
				}
				$mod .= '<input type="hidden" class="form-control color_set" value="'.$u->value.'" id="m_'.$u->id.'">';

			} else {
				$mod .= '<input type="text" class="form-control form-group '.$success.'" value="'.$u->value.'" id="m_'.$u->id.'" maxlength="'.$maxlength.'">';
			}

			$mod .= '
			<span class="form-group">
			   <input type="button" class="btn btn-warning muokkaSelectValikoja" for="m_'.$u->id.'" id="'.$u->id.'" value="Tallenna"></button>
			   <input type="button" class="btn btn-danger deleteFromSelect" id="poista_'.$u->id.'" select_type="'.$u->select_type.'" value="X" variable="'.$u->value.'"></button>
			</span>

		   </div>
		  </div>
		 </div>
		</div>';
		}

		$mod .= '<br>
		<div class="row">
		 <div class="col-sm-12">
		  <div class="form-inline pull-right">
			<input type="text" class="form-control form-group" id="u_'.$r->id.'" maxlength="'.$maxlength.'">
			<button class="btn btn-success form-group uusi_valikkorivi" tyyppi="'.$r->select_type.'" for="u_'.$r->id.'">Uusi</button>
		  </div>
		 </div>
		</div>';


	$mod .= '
	</center></p>
       </div>
       </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-info paivita" select_type="'.$_POST['select_type'].'">Päivitä ikkuna</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="button" class="btn btn-primary tallenna">Tallenna muutokset</button>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->';


	echo $mod;
?>

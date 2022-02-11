<?php

class TyontekijatController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('salasana'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin', 'admin_ajax', 'delete', 'create', 'update', 'index', 'view','merkkipaivat', 'tulosta', 'migraatio', 'verotustiedot', 'muuta_suhteet', 'tyoryhmat_hallinta', 'check_tyovuorot', 'is_aktiivinen_multiple'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function isEtuntiAdmin() {

		if(isset(Yii::app()->user->adminID)){
			$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	        	if(isset($m->id) and $m->id == Yii::app()->user->adminID)
	            		return true;
			else
	            		return false;
		} else {
	            return false;
		}
	}

        public function init()
        {

                if (Yii::app()->controller->isEtuntiAdmin() and !isset(Yii::app()->user->user_theme)) {
                        Yii::app()->theme = 'etunti';
                } elseif (Yii::app()->controller->isEtuntiAdmin() and isset(Yii::app()->user->user_theme)) {
                        Yii::app()->theme = Yii::app()->user->user_theme;
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();
        }


	public function actionIs_aktiivinen_multiple($name_tyontekijat, $aktiivinen, $tyoryhma, $selected)
	{
		$bd = '';
		$site = Yii::app()->createController('Site');
		$tyontekiatLista = $site[0]->tyontekiatLista( 
				$name_tyontekijat, // name
				'null', //class
				'tyontekijat', // id
				null, //selected
				$aktiivinen,
				$tyoryhma
		);

		echo json_encode($tyontekiatLista);
		exit;
	}

	public function actionCheck_tyovuorot($id)
	{
		$bd = '';
       		$criteria = new CDbCriteria();
		$criteria->condition = " 
			tid='".$id."' 
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
		";
		$model=Tyovuoroot::model()->find($criteria);
		if(isset($model->id))
		$bd .= $model->id;

		echo json_encode($bd);
	}

	public function actionMuuta_suhteet()
	{
		if(isset($_POST))
		{
	       		$criteria = new CDbCriteria();
			$criteria->condition = " tid='".$_POST['tid']."' ";
			$model=Tyosuhdet::model()->find($criteria);
			if(isset($model->id))
			{
				$ts = Tyosuhdet::model()->updatebypk($model->id, array($_POST['sarake']=>$_POST['value']));
			} else {
				$ts = new Tyosuhdet;
				$ts->tid = $_POST['tid'];
				$ts->$_POST['sarake'] = $_POST['value'];
				$ts->save();
			}

		}

	}


	public function actionTyoryhmat_hallinta()
	{

	// <-- Oikeudet
	   $checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

/*
		$criteria = new CDbCriteria();
	        $criteria->order = " tekijan_nimi ";
	        $criteria->condition = " aktiivinen=1 ";
		$tyontekijat = Tyontekijat::model()->findAll($criteria);
*/	

	       	$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = " select_type='tyoryhma' ";
		$model=Valikkoot::model()->findAll($criteria);

		if(isset($_POST['update']))
		{
/*
		       	$criteria = new CDbCriteria();
			$criteria->condition = " tyoryhma LIKE '%\"".$_POST['value']."\"%' ";
			$tyontekijat_jolla_oli_tamaryhma = Tyontekijat::model()->findAll($criteria);
			foreach($tyontekijat_jolla_oli_tamaryhma as $tekija){
				$arr = array();
				if(is_array(json_decode($tekija->tyoryhma))){
					$arr = json_decode($tekija->tyoryhma);
				} else {
					if(!empty($tekija->tyoryhma))
						array_push($arr, $tekija->tyoryhma);
				}

				$arr = array_diff($arr, array($_POST['value']));
				if(count($arr) > 0)
					$tyoryhma = json_encode($arr);
				else
					$tyoryhma = '';

				Tyontekijat::model()->updateByPk($tekija->id, array('tyoryhma'=>$tyoryhma));

			}


			$selected_tyontekijat = array();
			if(isset($_POST['selected_tyontekijat']))
			$selected_tyontekijat = $_POST['selected_tyontekijat'];

			foreach($tyontekijat as $tekija)
			{

				$arr = array();
				if(is_array(json_decode($tekija->tyoryhma))){
					$arr = json_decode($tekija->tyoryhma);
				} else {
					if(!empty($tekija->tyoryhma))
						array_push($arr, $tekija->tyoryhma);
				}

				if(!in_array($_POST['value'], $arr))
					array_push($arr, $_POST['value']);

				if(in_array($tekija->id, $selected_tyontekijat))
				{

					if(count($arr) > 0)
						Tyontekijat::model()->updateByPk($tekija->id, array('tyoryhma'=>json_encode($arr)));

					//print_r($arr);
				}
				//Tyontekijat::model()->updateByPk($tekija->id, array('tyoryhma'=>''));


			}
*/



			if(isset($_POST['value2']) and is_array($_POST['value2']))
				$value2 = json_encode($_POST['value2']);
			else
				$value2 = '';

			Valikkoot::model()->updateByPk($_POST['id'], array('value'=>trim($_POST['value']), 'value2'=>$value2 ) );
			exit;
		}
		if(isset($_GET['poista']))
		{
			if(count($model) > 1)
			{
				Valikkoot::model()->deletebypk($_GET['id']);
				$this->redirect(array('tyoryhmat_hallinta'));
			} else {
				$this->redirect(array('tyoryhmat_hallinta?error'));
			}
		}
		if(isset($_POST['uusi_tyoryhma']))
		{
			$m = new Valikkoot;
			$m->select_type = 'tyoryhma';
			$m->value = trim($_POST['uusi_tyoryhma']);
			if($m->save())
				$this->redirect(array('tyoryhmat_hallinta'));
		}



		$this->render('tyoryhmat_hallinta',array(
			'model'=>$model,
			//'tyontekijat'=>$tyontekijat,
		));
	}

	public function actionMerkkipaivat()
	{
		//STR_TO_DATE(sivexkuitti.aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))

	       	$criteria = new CDbCriteria();
		$criteria->select = " 
			SUBSTRING_INDEX(tekijan_henkilotunnus,'-',1) as tunnus
			,t.*
		";
		//$criteria->order = " tekijan_henkilotunnus DESC";
		$criteria->condition = " aktiivinen='1' AND tekijan_henkilotunnus !='' ";

		$model=Tyontekijat::model()->findAll($criteria);
		$this->render('merkkipaivat',array(
			'model'=>$model,
		));
	}

	public function actionVerotustiedot()
	{
       		$criteria = new CDbCriteria();

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
        		$criteria->addCondition (" id IN ($ids)");
		}
		//    Tyoryhmat -->

		if(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] != 'kaikki' and !empty($_POST['aktiivinen']))
	        $criteria->addCondition (" aktiivinen ='".(int)$_POST['aktiivinen']."' ");
		else
	        $criteria->addCondition (" aktiivinen=1 ");

		if(isset($_POST['osoite']) and !empty($_POST['osoite']))
	        $criteria->addCondition (" tekijan_katuosoite LIKE '%".$_POST['osoite']."%' ");

		if(isset($_POST['nimi']) and !empty(trim($_POST['nimi'])))
	        $criteria->addCondition (" tekijan_nimi LIKE '%".$_POST['nimi']."%' ");

		if(isset($_POST['puhelin']) and !empty(trim($_POST['puhelin'])))
	        $criteria->addCondition (" laiten_puh LIKE '%".$_POST['puhelin']."%' OR tekijan_puh LIKE '%".$_POST['puhelin']."%' ");

		if(isset($_POST['sahkoposti']) and !empty(trim($_POST['sahkoposti'])))
	        $criteria->addCondition (" tekijan_email LIKE '%".$_POST['sahkoposti']."%' ");

		$model= Tyontekijat::model()->findAll($criteria);

		//$dataProvider->pagination->pageSize = 50;
		if(isset($_POST['tulosta']))
		{
		        $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('verotustiedot', array('model' => $model),true));
		        $html2pdf->Output();
		} else {
			$this->render('verotustiedot', array('model' => $model));
		}

	}

	public function actionMigraatio()
	{
		$this->render('migraatio');
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionTulosta($id)
	{
		$model = Tyontekijat::model()->findbypk($id); 
		/*
		$html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->WriteHTML();
		$html2pdf->Output();
		*/
		
		$mobile = Yii::app()->createController('Mobile');
		$html	= $this->renderPartial('tulosta_pdf', array('model' => $model), true);
		$mobile[0]->transformHtmlTo('', $html, 'pdf');
		exit;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

	// <-- Oikeudet
	   $checkOikeus = "tyontekijat_1_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->


		$model=new Tyontekijat;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyontekijat']))
		{
			$_POST['Tyontekijat']['tekijan_email'] = trim($_POST['Tyontekijat']['tekijan_email']);
			$model->attributes=$_POST['Tyontekijat'];
			if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 ){
				$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$model->muistiinpano = '';
			}

			if(isset($_POST['tyo_toimialue']))
			$model->tyo_toimialue=json_encode($_POST['tyo_toimialue']);

			if(isset($_POST['kortitVoimassaolo']))
				$model->kortit_voimassaolo=json_encode($_POST['kortitVoimassaolo']);
			else
				$model->kortit_voimassaolo="";

			if(isset($_POST['Tyontekijat']['tyoryhma']))
				$model->tyoryhma=json_encode($_POST['Tyontekijat']['tyoryhma']);
			else
				$model->tyoryhma="";

			if(isset($_POST['Tyontekijat']['onlinevaraus_tuotteet']))
				$model->onlinevaraus_tuotteet=json_encode($_POST['Tyontekijat']['onlinevaraus_tuotteet']);
			else
				$model->onlinevaraus_tuotteet="";

			if(isset($_POST['kortit'])) 
				$model->kortit = implode("##***",$_POST['kortit']);
			else
				$model->kortit = "";

			if($model->save())
			{
				// Kotipuhtaaksi integromat web hook
				$domain = Yii::app()->user->domain;
				if($domain == "kotipuhtaaksi") {
					$this->integromatUpsert($model);
				}
		
				$modelTyosuhteet = Tyosuhdet::model()->find(" tid='".$model->id."' ");
				if(!isset($modelTyosuhteet->id)){
					$tsnew = new Tyosuhdet;
					$tsnew->tid = $model->id;
					$tsnew->alku = date("d.m.Y");
					$tsnew->tuntihinta = 0;
					if(!$tsnew->save()){
						var_dump($tsnew->getErrors());
						Tyontekijat::model()->deletebypk($model->id);
						exit;
					}
					$this->redirect(array('update', 'id' => $model->id));
					exit;
				}

				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Tyontekijat';
				$name_log 	= 'Työntekijät';
				$status_log 	= 'Create';
	
					$old_values = null;
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->


				// <-- Netvisor updater
				$asetukset = Asetukset::model()->findByPk(1);
				if($asetukset->netvisor_kaytto == 1 and $asetukset->netvisor_lahetetaanko_tyontekija == 1)
				{
					$netvisorResponse = $this->netvisorTyontekija('add', $model);
				}
				//     Netvisor updater -->

				$this->redirect(array('update', 'id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model
		));
	}


	public function actionSalasana($domain, $token, $id)
	{

                Yii::app()->theme = 'classic';
		$model=$this->loadModel($id);

		if(isset($model->id) and !empty($model->token) and trim($model->token) == trim($token)){
			$tilanne = 1;
		} elseif(isset($model->id) and empty($model->token)){
			$tilanne = 2;
		} else {
			die('Tämä linkki on käytetty tai vanhentunut!');
		}
		// validate that passwords match
		if(isset($model->id) and isset($_POST['password1']) and $_POST['password1'] == $_POST['password2'])
		{
			$hashed_pw = password_hash($_POST["password1"], PASSWORD_BCRYPT);
			Tyontekijat::model()->updateByPk($model->id, array('salasana' => $hashed_pw, 'token' => ''));
			$tilanne = 3;
		}

		$this->render('salasana', array('tilanne' => $tilanne));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{

		$netvisorResponse = '';
		$model=$this->loadModel($id);
		$orient_start = $model->orientation_start;
		$orient_end = $model->orientation_end;
		// format orientation start and end to d.m.Y, or set them as null if parsing fails.
		// something is very wrong with the dates if the parsing fails.
		// while I'd like to use afterFind and beforeSave in the model for this, it can have some
		// side effects with automation stuff built into yii1, which resulted in errors in production
		if($orient_start) {
			$parsed_orient_start = DateTime::createFromFormat("Y-m-d", $orient_start);
			if($parsed_orient_start) {
				$model->orientation_start = $parsed_orient_start->format("d.m.Y");
			} else {
				$model->orientation_start = null;
			}
		}
		if($orient_end) {
			$parsed_orient_end = DateTime::createFromFormat("Y-m-d", $orient_end);
			if($parsed_orient_end) {
				$model->orientation_end = $parsed_orient_end->format("d.m.Y");
			} else {
				$model->orientation_end = null;
			}
		}

		// <-- Oikeudet
		$checkOikeus = "tyontekijat_2_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		$site[0]->checkOikeus($checkOikeus);
		//  Oikeudet -->

		// <-- FILES
		if(isset($_POST['uploaded_t'])){
			Asetukset::model()->uploadFile(
				Yii::app()->user->domain, 
				'tekijat', 
				$model->id.'_'.$_FILES['file']['name']
			);
		}
		if(isset($_POST['poistaTamaTiedosto'])){
			unlink($_POST['poistaTamaTiedosto']);
			exit;
		}
		if(isset($_POST['uploaded_img'])){
			Asetukset::model()->uploadImage(
				Yii::app()->user->domain, 
				'tekijat', 
				$model->id.'.jpg'
			);
		}
		//     FILES -->

		// <-- Tyosuhteet talteen
		if(isset($_GET['tyosuhteet_talteen']))
		{
			$modelTyosuhteet = Tyosuhdet::model()->find(" tid='".$model->id."' ");

			$th = new TyosuhdeHistoria;
			$th->tid = $model->id;
			$th->arr = json_encode( $modelTyosuhteet->attributes );
			$th->save();
			Yii::app()->user->setFlash('success', "Työsuhteet tallennettu.");
			$this->redirect(array('update', 'id' => $model->id));
		}
		//     Tyosuhteet talteen -->


		// <-- Kuvan poistaminen
		if(isset($_POST['poista_kuva']) and isset($_POST['link']) and file_exists($_POST['link']))
		{
			unlink($_POST['link']);
			exit;
		}
		//     Kuvan poistaminen -->



		// <-- Sulje mobiili
		if(isset($_GET['sulje_mobiili']))
		{
				Tyontekijat::model()->updateByPk($model->id, array('mobiili' => '0'));
				Yii::app()->user->setFlash('warning', "Mobiili on suljettu");
				$this->redirect(array('update', 'id' => $model->id));
		}
		//    Sulje mobiili -->

		// <-- Avaa mobiili
		if(isset($_GET['avaa_mobiili']))
		{
				Tyontekijat::model()->updateByPk($model->id, array('mobiili' => 1));
				Yii::app()->user->setFlash('success', "Mobiili on avattu");
				$this->redirect(array('update', 'id' => $model->id));
		}
		//    Avaa mobiili -->


		// <-- Tunnukset lahetys
		if(isset($_GET['laheta_tunnukset']))
		{

				$token = sha1(uniqid(time().$id, true));
				Tyontekijat::model()->updateByPk($id, array('token' => $token));

				$subject = 'Tervetuloa Etunnin käyttäjäksi. / Welcome to Etunti mobile APP.';
				$message = 'Hei / Hi '.$model->tekijan_nimi.'!<br>
				<b>Domain:</b> '.Yii::app()->user->domain.'<br>
				<b>Käyttäjätunnus:</b> '.$model->tekijan_email.'<br>
				<b>Luo oma salasana / Create password:</b> <a href='.Yii::app()->createAbsoluteUrl('tyontekijat/salasana', array('domain' => Yii::app()->user->domain, 'token' => $token, 'id' => $model->id)).'>tästä / here</a><br>';

				//echo $message;
				//exit;

				$ft = FirmanTiedot::model()->findByPk(1);
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($model->tekijan_email);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $model->tekijan_email;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				Yii::app()->user->setFlash('success', "Lähetys onnistui!");
				$this->redirect(array('index'));
		}
		//     Tunnukset lahetys -->


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyontekijat']))
		{
			$_POST['Tyontekijat']['tekijan_email'] = trim($_POST['Tyontekijat']['tekijan_email']);
			$vanha_attr = $model->attributes;
			$model->attributes=$_POST['Tyontekijat'];
			if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 ){
				$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$model->muistiinpano = '';
			}

			if(isset($_POST['tyo_toimialue']))
			$model->tyo_toimialue=json_encode($_POST['tyo_toimialue']);

			if(isset($_POST['kortitVoimassaolo']))
				$model->kortit_voimassaolo=json_encode($_POST['kortitVoimassaolo']);
			else
				$model->kortit_voimassaolo="";

			if(isset($_POST['Tyontekijat']['tyoryhma']))
				$model->tyoryhma=json_encode($_POST['Tyontekijat']['tyoryhma']);
			else
				$model->tyoryhma="";

			if(isset($_POST['Tyontekijat']['onlinevaraus_tuotteet']))
				$model->onlinevaraus_tuotteet=json_encode($_POST['Tyontekijat']['onlinevaraus_tuotteet']);
			else
				$model->onlinevaraus_tuotteet="";

			if(isset($_POST['kortit'])) 
				$model->kortit = implode("##***",$_POST['kortit']);
			else
				$model->kortit = "";

			// convert orientation fields to Y-m-d if they're in d.m.Y
			if($model->orientation_start) {
				$parsed_start = DateTime::createFromFormat("d.m.Y", $model->orientation_start);
				if($parsed_start) {
					$model->orientation_start = $parsed_start->format("Y-m-d");
				}
			}
			if($model->orientation_end) {
				$parsed_end = DateTime::createFromFormat("d.m.Y", $model->orientation_end);
				if($parsed_end) {
					$model->orientation_end = $parsed_end->format("Y-m-d");
				}
			}

			if($model->save()){

				// <-- LOG
				$model_log 	= 'Tyontekijat';
				$name_log 	= 'Työntekijät';
				$status_log 	= 'Update';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->


				// <-- Netvisor updater
				$asetukset = Asetukset::model()->findByPk(1);
				if($asetukset->netvisor_kaytto == 1 and $asetukset->netvisor_lahetetaanko_tyontekija == 1)
				{
					$netvisorResponse = $this->netvisorTyontekija('edit', $model);
				}
				//     Netvisor updater -->

				// check if meaningful data changes
				// (email, phone, name, active/inactive status, work group)
				$fields = ["tekijan_nimi", "sukunimi", "tekijan_email", "tekijan_puh", "aktiivinen", "tyoryhma"];
				$integromatDataChanged = $this->integromatDataChanged($vanha_attr, $model->attributes, $fields);
				// if contract-changead is not defined let's assume nothing is changed
				$contractChanged = $_POST["contract-changed"] ?? 0;

				// Kotipuhtaaksi integromat webhook
				$domain = Yii::app()->user->domain;
				if($domain == "kotipuhtaaksi" and ($integromatDataChanged or $contractChanged)) {
					$this->integromatUpsert($model);
				}

				Yii::app()->user->setFlash('success', "Tallennettu.");
				$this->redirect(array('index'));
			}
		}


		$this->render('update',array(
			'model'=>$model,
			//'dataToteutuneet'=>$dataToteutuneet
		));
	}

	protected function sprint($val){
	   	    if($val > 0)
		   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
	
	// <-- Oikeudet
	   $checkOikeus = "tyontekijat_3_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->


		$t_d = Tyontekijat::model()->findbypk($id);

			if(isset($t_d->id))
			{
				// <-- LOG
				$model_log 	= 'Tyontekijat';
				$name_log 	= 'Työntekijät';
				$status_log 	= 'Delete';
	
					$old_values = json_encode($t_d->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			}


	   $filename = "../../img/tekijat/".Yii::app()->user->domain."/".$id.".jpg";
	   if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$id.".jpg"))
	   unlink(Yii::app()->basePath.$filename);

		$this->loadModel($id)->delete();
		$ts = Tyosuhdet::model()->find(" tid='".$id."' ");
		if( isset($ts->id) ){ Tyosuhdet::model()->deletebypk($ts->id); }

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

		if(isset($_POST['tyontekijatPerSivu']))
		{
			Yii::app()->user->setState('tyontekijatPerSivu', $_POST['tyontekijatPerSivu']);
			echo json_encode($_POST['tyontekijatPerSivu']);
			exit;
		}

		// <-- Oikeudet
		   $checkOikeus = "tyontekijat_0_".Yii::app()->user->adminStatus;
		   $site = Yii::app()->createController('Site');
		   $site[0]->checkOikeus($checkOikeus);
		//  Oikeudet -->
	
		//To Keep the session data in the SearchForm
                if(isset($_GET['tekijan_nimi']))
                Yii::app()->session['tekijan_nimi']=$_GET['tekijan_nimi'];

                if(isset($_GET['tekijan_puh']))
                Yii::app()->session['tekijan_puh']=$_GET['tekijan_puh'];

                if(isset($_GET['tekijan_email']))
                Yii::app()->session['tekijan_email']=$_GET['tekijan_email'];
                
                if(isset($_GET['tekijan_katuosoite']))
                Yii::app()->session['tekijan_katuosoite']=$_GET['tekijan_katuosoite']; 
                
                if(isset($_GET['aktiivinen']))
                Yii::app()->session['aktiivinen']=$_GET['aktiivinen']; 


       		$criteria = new CDbCriteria();
		//$criteria->select = " t.*, REPLACE(REPLACE(tyoryhma,'\\\u00f6','ö'), '\\\u00e4', 'ä') as tyoryhma ";
		// <-- Return order etu ja sukunimella
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
        		$criteria->addCondition (" id IN ($ids)");
		}
		//    Tyoryhmat -->

		if(isset(Yii::app()->session['aktiivinen']) and Yii::app()->session['aktiivinen'] != 'kaikki')
	        $criteria->addCondition (" aktiivinen ='".(int)Yii::app()->session['aktiivinen']."' ");
		else
	        $criteria->addCondition (" aktiivinen=1 ");

		if(isset(Yii::app()->session['tekijan_katuosoite']) and !empty(Yii::app()->session['tekijan_katuosoite'])){
	        	$criteria->addCondition (" tekijan_katuosoite LIKE '%".Yii::app()->session['tekijan_katuosoite']."%' ");
		}
		if(isset(Yii::app()->session['tekijan_nimi']) and !empty(trim(Yii::app()->session['tekijan_nimi']))){
	        	$criteria->addCondition ("  CONCAT(tekijan_nimi, ' ', sukunimi)  LIKE '%".trim(Yii::app()->session['tekijan_nimi'])."%' ");
		}
		if(isset(Yii::app()->session['tekijan_puh']) and !empty(trim(Yii::app()->session['tekijan_puh']))){
	        	$criteria->addCondition (" laiten_puh LIKE '%".Yii::app()->session['tekijan_puh']."%' OR tekijan_puh LIKE '%".Yii::app()->session['tekijan_puh']."%' ");
		}
		if(isset(Yii::app()->session['tekijan_email']) and !empty(trim(Yii::app()->session['tekijan_email']))){
	        	$criteria->addCondition (" tekijan_email LIKE '%".Yii::app()->session['tekijan_email']."%' ");
		}
		if(isset($_GET['tyoryhma']) and !empty($_GET['tyoryhma'])){
	        	$criteria->addCondition (" REPLACE(REPLACE(tyoryhma,'\\\u00f6','ö'), '\\\u00e4', 'ä') LIKE '%".$_GET['tyoryhma']."%' ");
		}

		$dataProvider=new CActiveDataProvider('Tyontekijat', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		// <-- Check curent version from playmarket
		if( !isset(Yii::app()->session['play_version_check']) ){
			$fg = file_get_contents('https://play.google.com/store/apps/details?id=fi.etunti.local&hl=en');
			preg_match("'Current Version(.*?)</span>'si", $fg, $match);
			if($match) {
				Asetukset::model()->updatebypk(1, array('app_version_playmarket' => strip_tags($match[1])));
			}
			Yii::app()->session['play_version_check'] = true;
		}
		//     Check curent version from playmarket -->

		$asetukset = Asetukset::model()->findByPk(1);
		$current_app_versio = $asetukset->app_version_playmarket;

		$perSivu = 50;
		if(isset(Yii::app()->user->tyontekijatPerSivu)){
			$perSivu = Yii::app()->user->tyontekijatPerSivu;
		}
		$dataProvider->pagination->pageSize = $perSivu;

		$this->render('index', array('dataProvider' => $dataProvider, 'perSivu' => $perSivu, 'current_app_versio' => $current_app_versio));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tyontekijat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyontekijat']))
			$model->attributes=$_GET['Tyontekijat'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAdmin_ajax()
	{

		$model=new Tyontekijat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyontekijat']))
			$model->attributes=$_GET['Tyontekijat'];

		$this->renderPartial('admin_ajax',array(
			'model'=>$model,
		));

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyontekijat the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyontekijat::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyontekijat $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyontekijat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	protected function netvisorTyontekija($tila, $model)
	{

		$modelTyosuhteet = Tyosuhdet::model()->find(" tid='".$model->id."' ");

		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	if(isset($n[0]))
	{
		if( $tila == 'add' )
		$url		= $n[0].'/employee.nv?method=add';
		if( $tila == 'edit')
		$url		= $n[0].'/employee.nv?method=edit';

		$host 		= $n[1];

		$sender 	= $n[2];
		$customerId	= $n[3];
		$partnerId	= $n[4];
		$timestamp	= $n[5];
		$language	= $n[6];
		$organisationIdentifier	= $n[7];
		$transactionIdentifier	= $n[8];
		$userKey 	= $n[9];
		$partnerKey	= $n[10];



	$getMAC = md5(
		$url.'&'.
		$sender.'&'.
		$customerId.'&'.
		$timestamp.'&'.
		$language.'&'.
		$organisationIdentifier.'&'.
		$transactionIdentifier.'&'.
		$userKey.'&'.
		$partnerKey
	 	);
	
	$auth_data = 
	    "Host: $host\r\n".  
	    "X-Netvisor-Authentication-Sender: $sender\r\n".  
	    "X-Netvisor-Authentication-CustomerId: $customerId\r\n".  
	    "X-Netvisor-Authentication-PartnerId: $partnerId\r\n".  
	    "X-Netvisor-Authentication-Timestamp: $timestamp\r\n".
	    "X-Netvisor-Interface-Language: $language\r\n".
	    "X-Netvisor-Organisation-ID: $organisationIdentifier\r\n".  
	    "X-Netvisor-Authentication-TransactionId: $transactionIdentifier\r\n".
	    "X-Netvisor-Authentication-MAC: $getMAC\r\n"
	; 
	
	$payrollrulegroupname = $modelTyosuhteet->palkka_tyyppi;

      	$lisat = '';
	if($tila == 'add'){ $lisat .= '<employeenumber>'.$model->id.'</employeenumber>'; }

	$employeesettlementpoints = '';
	/*
	if( $modelTyosuhteet->tyoelakevakuutuksen_tyyppi == 'TyEL' ){
		$employeesettlementpoints = '
		   <employeesettlementpoints>
		      <employeeworkpensioninsurance>
			<type>'.$modelTyosuhteet->tyottomyysvakuutus_tyyppi.'</type>
			<name>TyEL</name>
		      </employeeworkpensioninsurance>
		   </employeesettlementpoints>
		';
	}
	*/
;
// <-- XML
$xml = '
<root>
  <employee>
    <employeebaseinformation>
      <employeeidentifier>'.$model->tekijan_henkilotunnus.'</employeeidentifier>
      <firstname>'.$model->tekijan_nimi.'</firstname>
      <lastname>'.$model->sukunimi.'</lastname>
      <phonenumber>'.$model->tekijan_puh.'</phonenumber>
      <email>'.$model->tekijan_email.'</email>
    </employeebaseinformation>
    <employeepayrollinformation>
      <streetaddress>'.$model->tekijan_katuosoite.'</streetaddress>
      <postnumber>'.$model->tekijan_pnumero.'</postnumber>
      <city>'.$model->tekijan_ptoimipaikka.'</city>
      <municipality>'.$model->tekijan_ptoimipaikka.'</municipality>
      <country>FI</country>
      <nationality>FI</nationality>
      <language>FI</language>
      '.$lisat.'
      <profession>'.$model->ammattinimike.'</profession>
      <jobbegindate format="ansi">'.date("Y-m-d", strtotime($modelTyosuhteet->alku)).'</jobbegindate>
      <payrollrulegroupname>'.$payrollrulegroupname.'</payrollrulegroupname>
      <bankaccountnumber>'.$model->tekijan_pankkitili.'</bankaccountnumber>
      <bankidentificationcode>'.$model->tekijan_konttori.'</bankidentificationcode>
      <employeeinsurancetype>'.$modelTyosuhteet->tyoelakevakuutuksen_tyyppi.'</employeeinsurancetype>
   </employeepayrollinformation>
   '.$employeesettlementpoints.'
  </employee>
</root>';
//  XML -->
	

	$optsPOST = array(
	  'http'=>array(
	    'method'=>"POST",
	    'header'=>"Accept: text/plain\r\n" .
	              "Content-Type: application/x-www-form-urlencoded\r\n".
	              "Content-Length: ".strlen($xml)."\r\n".
		      $auth_data,
	    'content'=> $xml
	  )
	);
	
	$context = stream_context_create($optsPOST);
	
	$response = file_get_contents($url, false, $context);
	$result = new SimpleXMLElement($response);
	
	
	  if($result->ResponseStatus->Status == 'OK')
	  {
		//if( $tila == 'add' )
		//$return=$result->Replies->InsertedDataIdentifier;
		//if( $tila == 'edit' )
		$return = $response;


	  } else {

		if (strpos($result->ResponseStatus->Status[1], 'Työntekijää ei löydy') !== false and $tila == 'edit') {
			$this->netvisorTyontekija('add', $model);
			$this->redirect(array('index'));
		}
/*
		if (strpos($result->ResponseStatus->Status[1], 'Palkkalaskelmatietoja ei löydy') !== false and $tila == 'edit') {
			$this->netvisorTyontekija('add', $model);
			$this->redirect(array('index'));
		}
*/
		echo '<pre>';
		print_r( $result );
		echo '</pre>';
		exit;

	  }


	} // if isset $n[0]

		return $return;

	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

	public function TyoryhmatTyontekijatHelper($tr_array)
	{
		if(!isset(Yii::app()->user->adminID)){
			$this->redirect(array('/site/logout'));
		}

		$tt_arr = array();
		$asetukset = Asetukset::model()->findbypk(1);
		if( isset($asetukset->tyoryhmat) and $asetukset->tyoryhmat == 0){ return $tt_arr; }

		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');

		$criteria = new CDbCriteria();

		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
		$criteria->condition = "
			select_type='tyoryhma'
			AND value2 LIKE '%\"".Yii::app()->user->adminID."\"%'
		";
		}
		if( is_array($tr_array)){
			$impl = "value='".implode("' OR value='", $tr_array)."'";
			$criteria->condition = "
				select_type='tyoryhma'
				AND ($impl)
			";
		}

		$listData = Valikkoot::model()->findAll($criteria);

		$criteria = new CDbCriteria();
		//$criteria->condition = " aktiivinen = '1' ";
		$tt = Tyontekijat::model()->findAll($criteria);
		$chk_oikeusfields = $site[0]->checkOikeusFields($checkOikeus);
		foreach($tt as $tekija){
			if( $chk_oikeusfields == 1 and $tr_array == null){
				$tt_arr[$tekija->id] = $tekija->id;
				continue;
			}
			foreach($listData as $item){
				if( 
					is_array(json_decode($tekija->tyoryhma)) 
					and in_array($item->value, json_decode($tekija->tyoryhma)) 
				){
					$tt_arr[$tekija->id] = $tekija->id;
				}
			}

		}

	   	return $tt_arr;
	}
	
	/**
	 * Sends worker data to a integromat web hook (hardcoded address for
	 * kotipuhtaaksi, since it's the only company using this integration at the moment)
	 * if this becomes something that is widely used, we'd need to make a field
	 * for the URL in the settings model.
	 * 
	 * This function is called from actionCreate and actionUpdate
	 * 
	 * The endpoint expects the following information:
	 * first and last name, phone number, email and active status.
	 */
	public function integromatUpsert(Tyontekijat $worker)
	{
		// get and parse workers name
		$apiController = Yii::app()->createController("Api")[0];
		$firstName = $apiController->parseName($worker->tekijan_nimi);
		$lastName = $apiController->parseName($worker->sukunimi);
		// get email
		$email = $worker->tekijan_email;
		// get phone number
		$phone = $worker->tekijan_puh;

		// check if worker belongs to "Toimisto" work group.
		$workGroups = $worker->tyoryhma;
		$office_worker = 0;
		if(strpos($workGroups, "Toimisto") !== false) {
			$office_worker = 1;
		}

		// if worker status is not 1 ( Töissä (aktiivinen) ), mark as false
		// otherwise mark as true.
		// the status might be different for other companies, but since this function
		// is only used by kotipuhtaaksi right now, we don't have to think about it now.
		$active = $worker->aktiivinen == 1 ? 1 : 0;

		// get the employees contract, if they have a defined "loppu" field,
		// we can mark the employee as non-active
		$contract = Tyosuhdet::model()->find("tid = " . $worker->id);
		if($contract) {
			if(isset($contract->loppu) and strlen($contract->loppu) > 0) {
				$active = 0;
			}
		}

		// build request body
		$body = [
			"first_name" => $firstName,
			"last_name" => $lastName,
			"email" => $email,
			"phone" => $phone,
			"active" =>  $active,
			"office_worker" => $office_worker,
		];

		// build request headers
		$headers = ["Content-Type: application/json"];
		$url = "https://hook.integromat.com/9kqxhc4veacs8j3tx0sfomrtuff9zyl9";

		// open curl
		$ch = curl_init();
		// capture response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// set url
		curl_setopt($ch, CURLOPT_URL, $url);
		// set http verb to POST
		curl_setopt($ch, CURLOPT_POST, true);
		// set post body
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
		// define headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// I tnink this actually sets the headers into the request
		// "CURLOPT_HEADER - pass headers to the data stream"
		curl_setopt($ch, CURLOPT_HEADER, true);
		$response = curl_exec($ch);

		// close curl
		curl_close($ch);

		return $response;
	}

	/**
	 * Compares attributes that we would send to integromat, if the old models
	 * attributes don't match with the new attributes that would be saved to the datab ase,
	 * return true for "has changed". Otherwise return false for "not changed".
	 * This can be used to reduce the number of requests sent to integromat.
	 */
	private function integromatDataChanged($old_attr, $new_attr, $fields)
	{
		foreach($fields as $field) {
			// if any of the fields don't match, return true (for data is changed)
			// and don't even bother looking at the rest
			if($old_attr[$field] != $new_attr[$field]) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Light version of FromToSuunnitellutAll to be used for checking if
	 * an employee has shifts in the future. Called in _form.php
	 * which displays an warning text if the employee has
	 * shifts in the future.
	 */
	public function hasUpcomingShifts($employeeId)
	{
		$crit = new CDbCriteria();
		$crit->limit = 1;
		$crit->addCondition("tid = $employeeId");
		$crit->addCondition("STR_TO_DATE(pvm, '%d.%m.%Y') >= CURDATE()");
		$shifts = Tyovuoroot::model()->findAll($crit);
		if(count($shifts) > 0) {
			return true;
		}

		$crit = new CDbCriteria();
		$crit->limit = 1;
		$crit->addCondition("tid = $employeeId");
		$crit->addCondition("tyopaari LIKE '%\"$employeeId\"%'", "OR");
		$crit->addCondition("STR_TO_DATE(pto, '%d.%m.%Y') >= CURDATE()");
		$repeating_shifts = ToistuvatTyovuorot::model()->findAll($crit);
		if(count($repeating_shifts) > 0) {
			return true;
		}

		return false;
	}

}

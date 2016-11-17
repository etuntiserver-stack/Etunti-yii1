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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin', 'admin_ajax', 'delete', 'create', 'update', 'index', 'view','merkkipaivat', 'tulosta', 'migraatio', 'verotustiedot', 'muuta_suhteet', 'tyoryhmat_hallinta'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function isEtuntiAdmin() {

		if(isset(Yii::app()->user->adminID))
		{
		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	        if($m->id == Yii::app()->user->adminID)
	            return true;
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

		$criteria = new CDbCriteria();
	        $criteria->order = " tekijan_nimi ";
	        $criteria->condition = " aktiivinen=1 ";
		$tyontekijat = Tyontekijat::model()->findAll($criteria);
	

	       	$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = " select_type='tyoryhma' ";
		$model=Valikkoot::model()->findAll($criteria);

		if(isset($_POST['update']))
		{

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
			'tyontekijat'=>$tyontekijat,
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
	        $criteria->order = "  id DESC ";

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
	
		        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('tulosta_pdf', array('model' => $model),true));
		        $html2pdf->Output();
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
			$model->attributes=$_POST['Tyontekijat'];
			$model->tyo_toimialue=json_encode($model->tyo_toimialue);

			if(isset($_POST['kortitVoimassaolo']))
				$model->kortit_voimassaolo=json_encode($_POST['kortitVoimassaolo']);
			else
				$model->kortit_voimassaolo="";

			if(isset($_POST['Tyontekijat']['tyoryhma']))
				$model->tyoryhma=json_encode($_POST['Tyontekijat']['tyoryhma']);
			else
				$model->tyoryhma="";

			if(isset($_POST['kortit'])) 
				$model->kortit = implode("##***",$_POST['kortit']);
			else
				$model->kortit = "";

			if($model->save())
			{

				$d = Domainit::model()->find("domain='".Yii::app()->user->domain."'");
				$yr =  '';
				if(isset($d->yritys))
				$yr =  $d->yritys;

				$subject = 'Tervetuloa Etunnin käyttäjäksi.';
				$message = 'Hei '.$model->tekijan_nimi.'!<br>
				<b>Domain:</b> '.Yii::app()->user->domain.'<br>
				<b>Käyttäjätunnus:</b> '.$model->tekijan_email.'<br>
				<b>Salasana:</b> '.$model->salasana.'<br>
<p>
				Tervetuloa Etunnin käyttäjäksi. '.$yr.' on lisännyt sinulle profiilin Etuntiin. Lataa sovellus puhelimeesi alla olevien linkkien kautta.
</p><br>
				<br>
				<p>Ystävällisin terveisin</p>
				Etunti<br>

<p>
<a href="https://www.microsoft.com/store/apps/9nblggh4nd0w?ocid=badge"><img src="https://assets.windowsphone.com/85864462-9c82-451e-9355-a3d5f874397a/English_get-it-from-MS_InvariantCulture_Default.png" alt="Get it from Microsoft" height="70" /></a>

<a href="https://play.google.com/store/apps/details?id=fi.etunti.local&utm_source=global_co&utm_medium=prtnr&utm_content=Mar2515&utm_campaign=PartBadge&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1"><img alt="Get it on Google Play" src="https://play.google.com/intl/en_us/badges/images/generic/en-play-badge.png" height="70" /></a>

<a href="https://geo.itunes.apple.com/fi/app/etunti/id1100648690?mt=8"><img src="http://linkmaker.itunes.apple.com/images/badges/en-us/badge_appstore-lrg.svg" height="70" ></a>
</p>
				';

				$mail = new YiiMailer();
				$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
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

				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{

	// <-- Oikeudet
	   $checkOikeus = "tyontekijat_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyontekijat']))
		{

			$model->attributes=$_POST['Tyontekijat'];
			$model->tyo_toimialue=json_encode($model->tyo_toimialue);
		
			if(isset($_POST['kortitVoimassaolo']))
				$model->kortit_voimassaolo=json_encode($_POST['kortitVoimassaolo']);
			else
				$model->kortit_voimassaolo="";

			if(isset($_POST['Tyontekijat']['tyoryhma']))
				$model->tyoryhma=json_encode($_POST['Tyontekijat']['tyoryhma']);
			else
				$model->tyoryhma="";

			if(isset($_POST['kortit'])) 
				$model->kortit = implode("##***",$_POST['kortit']);
			else
				$model->kortit = "";

			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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


	   $filename = "../../img/tekijat/".Yii::app()->user->domain."/".$id.".jpg";
	   if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$id.".jpg"))
	   unlink(Yii::app()->basePath.$filename);

		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

	// <-- Oikeudet
	   $checkOikeus = "tyontekijat_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";

		if(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] != 'kaikki')
	        $criteria->addCondition (" aktiivinen ='".(int)$_POST['aktiivinen']."' ");
		else
	        $criteria->addCondition (" aktiivinen=1 ");

		if(isset($_POST['tekijan_katuosoite']) and !empty($_POST['tekijan_katuosoite']))
	        $criteria->addCondition (" tekijan_katuosoite LIKE '%".$_POST['tekijan_katuosoite']."%' ");

		if(isset($_POST['tekijan_nimi']) and !empty(trim($_POST['tekijan_nimi'])))
	        $criteria->addCondition (" tekijan_nimi LIKE '%".$_POST['tekijan_nimi']."%' ");

		if(isset($_POST['tekijan_puh']) and !empty(trim($_POST['tekijan_puh'])))
	        $criteria->addCondition (" laiten_puh LIKE '%".$_POST['tekijan_puh']."%' OR tekijan_puh LIKE '%".$_POST['tekijan_puh']."%' ");

		if(isset($_POST['tekijan_email']) and !empty(trim($_POST['tekijan_email'])))
	        $criteria->addCondition (" tekijan_email LIKE '%".$_POST['tekijan_email']."%' ");

		$dataProvider=new CActiveDataProvider('Tyontekijat', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('index', array('dataProvider' => $dataProvider));
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
}

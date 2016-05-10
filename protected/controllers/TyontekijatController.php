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
				'actions'=>array('admin', 'admin_ajax', 'delete', 'create', 'update', 'index', 'view','merkkipaivat', 'tulosta', 'migraatio', 'verotustiedot', 'muuta_suhteet', 'varoitus'),
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

	public function actionVaroitus()
	{

		Yii::import('ext.yiiword.YiiWord', true);
		Yii::registerAutoloader(array('YiiWord', 'autoload'), true);

		if (!file_exists(Yii::app()->basePath."/../tiedostot/varoitukset/".Yii::app()->user->domain)) {
		 	mkdir(Yii::app()->basePath."/../tiedostot/varoitukset/".Yii::app()->user->domain, 0777, true);
		}

		$file = '';
		
		if(isset($_POST['aika']))
		{


			$firma = FirmanTiedot::model()->findbypk(1);
			$tt = Tyontekijat::model()->findbypk($_POST['tyontekija']);
		
			$PHPWord = new PHPWord();
			$document = $PHPWord->loadTemplate('tiedostot/templates/varoitus_template.docx');

			if(!empty($firma->tyonantaja)) 
			   $document->setValue('tyonantaja', iconv('UTF-8','ISO-8859-1',$firma->tyonantaja));
			else
			   $document->setValue('tyonantaja', '');

			if(!empty($firma->osoite)) 
			   $document->setValue('osoite', iconv('UTF-8','ISO-8859-1',$firma->osoite));
			else
			   $document->setValue('osoite', '');

			if(!empty($firma->y_tunnus)) 
			   $document->setValue('y_tunnus', $firma->y_tunnus);
			else
			   $document->setValue('y_tunnus', '');

			if(!empty($firma->puhelin)) 
			   $document->setValue('puhelin', $firma->puhelin);
			else
			   $document->setValue('puhelin', '');


			if(!empty($firma->sahkoposti))
			   $document->setValue('sposti', $firma->sahkoposti);
			else
			   $document->setValue('sposti', '');



			if(!empty($tt->tekijan_nimi))
			   $document->setValue('tekijan_nimi', iconv('UTF-8','ISO-8859-1',$tt->tekijan_nimi));
			else
			   $document->setValue('tekijan_nimi', '');

			if(!empty($tt->tekijan_katuosoite))
			   $document->setValue('katuosoite', iconv('UTF-8','ISO-8859-1',$tt->tekijan_katuosoite));
			else
			   $document->setValue('katuosoite', '');

			if(!empty($tt->tekijan_henkilotunnus))
			   $document->setValue('henkilotunnus', $tt->tekijan_henkilotunnus);
			else
			   $document->setValue('henkilotunnus', '');

			if(!empty($tt->tekijan_puh))
			   $document->setValue('tekijan_puh', $tt->tekijan_puh);
			else
			   $document->setValue('tekijan_puh', '');

			if(!empty($tt->tekijan_email))
			   $document->setValue('tekijan_email', $tt->tekijan_email);
			else
			   $document->setValue('tekijan_email', '');

			$file = 'tiedostot/varoitukset/'.Yii::app()->user->domain.'/'.$_POST['tyontekija'].'_'.$_POST['aika'].'.docx';
			$document->setValue('aika', $_POST['aika']);
			$document->setValue('paikka', iconv('UTF-8','ISO-8859-1',$_POST['paikka']));
			$document->setValue('johtaja', iconv('UTF-8','ISO-8859-1',$_POST['johtaja']));
			$document->setValue('allekirjoitus', iconv('UTF-8','ISO-8859-1',$tt->tekijan_nimi));
			$document->setValue('varoitus', iconv('UTF-8','ISO-8859-1',$_POST['text']));
		  	$document->save($file);

		}

		$this->render('varoitus', array('file'=>$file));
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

		if(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] != 'kaikki')
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

		$dataProvider=new CActiveDataProvider('Tyontekijat', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('verotustiedot', array('dataProvider' => $dataProvider));

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

			if(isset($_POST['kortit'])) $model->kortit = implode("##***",$_POST['kortit']);
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

				$this->redirect(array('update','id'=>$model->id));
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

			if(isset($_POST['Tyontekijat']['imei']))
			$imei=Tyontekijat::model()->find(" id!='".$model->id."' and imei='".$_POST['Tyontekijat']['imei']."' and imei!='' ");
			if(isset($imei->imei))
			{
			echo Yii::t('main', 'Tämän imei on jo käytössä');

			exit;
			}

			if(isset($_POST['kortit'])) $model->kortit = implode("##***",$_POST['kortit']);
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['osoite']) and !empty($_POST['osoite']))
	        $criteria->addCondition (" tekijan_katuosoite LIKE '%".$_POST['osoite']."%' ");

		if(isset($_POST['nimi']) and !empty(trim($_POST['nimi'])))
	        $criteria->addCondition (" tekijan_nimi LIKE '%".$_POST['nimi']."%' ");

		if(isset($_POST['puhelin']) and !empty(trim($_POST['puhelin'])))
	        $criteria->addCondition (" laiten_puh LIKE '%".$_POST['puhelin']."%' OR tekijan_puh LIKE '%".$_POST['puhelin']."%' ");

		if(isset($_POST['sahkoposti']) and !empty(trim($_POST['sahkoposti'])))
	        $criteria->addCondition (" tekijan_email LIKE '%".$_POST['sahkoposti']."%' ");

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

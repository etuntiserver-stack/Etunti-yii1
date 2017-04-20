<?php

class TyotodistusController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'tekijan_tiedot'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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


	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Tyotodistus;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyotodistus']))
		{
			$model->attributes=$_POST['Tyotodistus'];
			if($model->save())
			{
				$tiedosto = $this->kansio().'_'.str_replace(" ", "_", $this->etuSukunimi($model->tid)).'_'.date('Y-m-d').'_'.$model->id;
				Tyotodistus::model()->updateByPk($model->id, array('tiedosto'=>$tiedosto));

				$this->docxsave($model, $tiedosto);
				$this->redirect(array('view','id'=>$model->id));
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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyotodistus']))
		{
			$model->attributes=$_POST['Tyotodistus'];
			if($model->save())
			{
				$tiedosto = $this->kansio().'_'.str_replace(" ", "_", $this->etuSukunimi($model->tid)).'_'.date('Y-m-d').'_'.$model->id;
				Tyotodistus::model()->updateByPk($model->id, array('tiedosto'=>$tiedosto));

				$this->docxsave($model, $tiedosto);
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	protected function template_variables()
	{
		$var = '
			#tyonantaja#
			#tyonantaja_osoite#
			#tyonantaja_y_tunnus#
			#tyonantaja_puhelin#
			#tyonantaja_sahkoposti#

			#tyontekija_nimi#
			#tyontekija_osoite#
			#tyontekija_henkilotunnus#
			#tyontekija_puhelin#
			#tyontekija_sahkoposti#

			#aika#
			#paikka#
			#johtajan_nimi#

			#Alku#
			#Loppu#
			#Tyokohde#
			#TyosuhteenPaattamisenSyy#
			#Kaytos#
			#Arvio#
			#NimikeTehtava#
			#Tyotehtavat#';

		return $var;

	}

	protected function kansio()
	{
		return 'tyotodistukset';
	}

	protected function templates_polkku()
	{
		return 'tiedostot/templates/'.Yii::app()->user->domain.'/'.$this->kansio().'/';
	}

	protected function valmiit_polkku()
	{
		return 'tiedostot/'.$this->kansio().'/'.Yii::app()->user->domain;
	}

	protected function docxsave($model, $tiedosto)
	{

			if (!file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku() )) {
			 	mkdir( Yii::app()->basePath.'/../'.$this->valmiit_polkku(), 0777, true );
			}

			define('PHPDOCX_INCLUDE_PATH', (dirname(Yii::app()->basePath)).'/protected/vendors/phpdocx');
			spl_autoload_unregister(array('YiiBase','autoload'));
			require_once PHPDOCX_INCLUDE_PATH.'/lib/pdf/dompdf_config.inc.php';
			//require_once PHPDOCX_INCLUDE_PATH.'/classes/TransformDocAdv.inc';
			require_once PHPDOCX_INCLUDE_PATH.'/classes/CreateDocx.inc';
			spl_autoload_register(array('AutoLoader','load'));
			spl_autoload_register(array('YiiBase', 'autoload'));

			$template_tiedosto = $this->templates_polkku().$model->template;

			$docx = new CreateDocxFromTemplate($template_tiedosto);
			$docx->setTemplateSymbol('#');
			$variables = array(
				'tyonantaja' => $model->tyonantaja,
				'tyonantaja_osoite' => $model->osoite,
				'tyonantaja_y_tunnus' => $model->y_tunnus,
				'tyonantaja_puhelin' => $model->puhelin,
				'tyonantaja_sahkoposti' => $model->sahkoposti,
				'tyontekija_nimi' => $model->tekijan_nimi,
				'tyontekija_osoite' => $model->tekijan_katuosoite,
				'tyontekija_henkilotunnus' => $model->tekijan_henkilotunnus,
				'tyontekija_puhelin' => $model->tekijan_puh,
				'tyontekija_sahkoposti' => $model->tekijan_email,
				'aika' => $model->Paivays,
				'paikka' => $model->Paikka,
				'johtajan_nimi' => $model->TyonantajanEdustaja,
			);
			$docx->replaceVariableByText($variables);

			$variables_2 = array(
				'Alku' => $model->Alku,
				'Loppu' => $model->Loppu,
				'Tyokohde' => $model->Tyokohde,
				'TyosuhteenPaattamisenSyy' => $model->TyosuhteenPaattamisenSyy,
				'Kaytos' => $model->Kaytos,
				'Arvio' => $model->Arvio,
				'NimikeTehtava' => $model->NimikeTehtava,
				'Tyotehtavat' => $model->Tyotehtavat,
			);
			$docx->replaceVariableByText($variables_2);

			$path = 'tiedostot/'.$this->kansio().'/'.Yii::app()->user->domain.'/'.$tiedosto;
			$docx->createDocx($path);

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			{
			$transform = new TransformDocAdvLibreOffice();
			$transform->transformDocument($path.'.docx', $path.'.pdf');
			}

			$this->redirect(array('index'));
	}

	public function actionDelete($id)
	{
		// <-- tiedoston poistaminen
		$model = $this->loadModel($id);
		$model->delete();
		if (file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku().'/'.$model->tiedosto.'.docx' )) 
			unlink(Yii::app()->baseUrl.$this->valmiit_polkku().'/'.$model->tiedosto.'.docx');
		if (file_exists( Yii::app()->basePath.'/../'.$this->valmiit_polkku().'/'.$model->tiedosto.'.pdf' )) 
			unlink(Yii::app()->baseUrl.$this->valmiit_polkku().'/'.$model->tiedosto.'.pdf');
		//     tiedoston poistaminen -->

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

		if( Yii::app()->request->getPost('poistaTemplate') ){
			unlink( Yii::app()->request->getPost('poistaTemplate') );
			exit;
		}

		if( isset($_POST['file_upload']) )
		{

			if (!file_exists( Yii::app()->basePath.'/../'.$this->templates_polkku() )) {
				mkdir( Yii::app()->basePath.'/../'.$this->templates_polkku(), 0777, true );
			}

			$uploaddir = Yii::app()->basePath.'/../'.$this->templates_polkku();
			$uploadfile = $uploaddir . basename($_FILES["file"]["name"]);
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		
			} else {
				echo Yii::t('main', 'Lataaminen ei onnistuu');
		    	}
		}


		$dataProvider=new CActiveDataProvider('Tyotodistus');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tyotodistus('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyotodistus']))
			$model->attributes=$_GET['Tyotodistus'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyotodistus the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyotodistus::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyotodistus $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyotodistus-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionTekijan_tiedot()
	{
		$model = Tyontekijat::model()->findbypk($_POST['tid']);
		$tiedot = array(
			'tekijan_email' => $model->tekijan_email,
			'tekijan_nimi' => $model->tekijan_nimi,
			'tekijan_katuosoite' => $model->tekijan_katuosoite,
			'tekijan_pnumero' => $model->tekijan_pnumero,
			'tekijan_ptoimipaikka' => $model->tekijan_ptoimipaikka,
			'tekijan_puh' => $model->tekijan_puh,
			'tekijan_henkilotunnus' => $model->tekijan_henkilotunnus
		);
		echo json_encode($tiedot);
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

}

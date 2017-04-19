<?php

class TyosuhteenPaattaminenController extends Controller
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


	public function polkku()
	{
		return 'tiedostot/tyosuhteen_paattaminen/'.Yii::app()->user->domain;
	}

	public function tiedostonNimike()
	{
		return 'tyosuhteen_paattaminen';
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
				'actions'=>array('admin','delete','create','update','index','view','tekijan_tiedot'),
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
                } elseif (isset(Yii::app()->user->asiakas)) {
                        Yii::app()->theme = 'customer';
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();
        }


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	public function actionTekijan_tiedot()
	{
		$model = Tyontekijat::model()->findbypk($_POST['tid']);
		$tiedot = array(
			'tekijan_email' => $model->tekijan_email,
			'tekijan_nimi' => $this->etuSukunimi($model->id),
			'tekijan_katuosoite' => $model->tekijan_katuosoite,
			'tekijan_pnumero' => $model->tekijan_pnumero,
			'tekijan_ptoimipaikka' => $model->tekijan_ptoimipaikka,
			'tekijan_puh' => $model->tekijan_puh,
			'tekijan_henkilotunnus' => $model->tekijan_henkilotunnus
		);
		echo json_encode($tiedot);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TyosuhteenPaattaminen;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TyosuhteenPaattaminen']))
		{
			$tiedosto = date('Y-m-d').'_'.$_POST['TyosuhteenPaattaminen']['tid'];
			$model->attributes=$_POST['TyosuhteenPaattaminen'];
			$model->tiedosto=$tiedosto;
			if($model->save())
			{
				$this->docxsave($model, $tiedosto);
				//$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['TyosuhteenPaattaminen']))
		{
			$model->attributes=$_POST['TyosuhteenPaattaminen'];
			if($model->save())
			{
				$tiedosto = $model->tiedosto;
				$this->docxsave($model, $tiedosto);
				//$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	protected function docxsave($model, $tiedosto)
	{

			if (!file_exists(Yii::app()->basePath."/../".$this->polkku() )) {
			 	mkdir(Yii::app()->basePath."/../".$this->polkku(), 0777, true);
			}

			define('PHPDOCX_INCLUDE_PATH', (dirname(Yii::app()->basePath)).'/protected/vendors/phpdocx');
			spl_autoload_unregister(array('YiiBase','autoload'));
			require_once PHPDOCX_INCLUDE_PATH.'/lib/pdf/dompdf_config.inc.php';
			//require_once PHPDOCX_INCLUDE_PATH.'/classes/TransformDocAdv.inc';
			require_once PHPDOCX_INCLUDE_PATH.'/classes/CreateDocx.inc';
			spl_autoload_register(array('AutoLoader','load'));
			spl_autoload_register(array('YiiBase', 'autoload'));

			$template_tiedosto = 'tiedostot/templates/'.Yii::app()->user->domain.'/'.$this->tiedostonNimike().'.docx';

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
				'titteli' => $model->titteli,
				'alku_pvm' => $model->alku_pvm,
				'loppu_pvm' => $model->loppu_pvm,
				'teksti' => $model->teksti,
				'kuuleminen' => $model->kuuleminen,
				'tyosuhteen_paattaminen' => $model->tyosuhteen_paattaminen,
			);
			$docx->replaceVariableByText($variables_2);

			$path = $this->polkku().'/'.$tiedosto;
			$docx->createDocx($path);

			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			{
			$transform = new TransformDocAdvLibreOffice();
			$transform->transformDocument($path.'.docx', $path.'.pdf');
			}

			$this->redirect(array('index'));

	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$t = $this->polkku().'/'.$model->tiedosto;
		if(file_exists(Yii::app()->basePath."/../".$t.".*"))
			unlink($t.".*");

	
		$model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
       		$criteria = new CDbCriteria();
		$criteria->order = " id DESC ";

		$dataProvider=new CActiveDataProvider('TyosuhteenPaattaminen', array(
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
		$model=new TyosuhteenPaattaminen('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TyosuhteenPaattaminen']))
			$model->attributes=$_GET['TyosuhteenPaattaminen'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TyosuhteenPaattaminen the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TyosuhteenPaattaminen::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TyosuhteenPaattaminen $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyosuhteen-paattaminen-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}
}

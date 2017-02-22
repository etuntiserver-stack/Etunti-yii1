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
			$tiedosto = date('Y-m-d').'_'.$_POST['Tyotodistus']['tid'];
			$model->attributes=$_POST['Tyotodistus'];
			$model->tiedosto=$tiedosto;
			if($model->save())
			{
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
			$tiedosto = date('Y-m-d').'_'.$_POST['Tyotodistus']['tid'];
			$model->attributes=$_POST['Tyotodistus'];
			$model->tiedosto=$tiedosto;
			if($model->save())
			{
				$this->docxsave($model, $tiedosto);
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	protected function docxsave($model, $tiedosto)
	{


			Yii::import('ext.yiiword.YiiWord', true);
			Yii::registerAutoloader(array('YiiWord', 'autoload'), true);

			if (!file_exists(Yii::app()->basePath."/../tiedostot/tyotodistukset/".Yii::app()->user->domain)) {
			 	mkdir(Yii::app()->basePath."/../tiedostot/tyotodistukset/".Yii::app()->user->domain, 0777, true);
			}

		
			$PHPWord = new PHPWord();
			$document = $PHPWord->loadTemplate('tiedostot/templates/'.Yii::app()->user->domain.'/template_tyotodistus.docx');


			$document->setValue('tyonantaja', iconv('UTF-8','ISO-8859-1',$model->tyonantaja));
			$document->setValue('tyonantaja_osoite', iconv('UTF-8','ISO-8859-1',$model->osoite));
			$document->setValue('tyonantaja_y_tunnus', $model->y_tunnus);
			$document->setValue('tyonantaja_puhelin', $model->puhelin);
			$document->setValue('tyonantaja_sahkoposti', $model->sahkoposti);


			$document->setValue('tyontekija_nimi', iconv('UTF-8','ISO-8859-1',$model->tekijan_nimi));
			$document->setValue('tyontekija_osoite', iconv('UTF-8','ISO-8859-1',$model->tekijan_katuosoite));
			$document->setValue('tyontekija_henkilotunnus', $model->tekijan_henkilotunnus);
			$document->setValue('tyontekija_puhelin', $model->tekijan_puh);
			$document->setValue('tyontekija_sahkoposti', $model->tekijan_email);

			$document->setValue('aika', $model->Paivays);
			$document->setValue('paikka', iconv('UTF-8','ISO-8859-1', $model->Paikka));
			$document->setValue('johtajan_nimi', iconv('UTF-8','ISO-8859-1', $model->TyonantajanEdustaja));

			$document->setValue('Alku', iconv('UTF-8','ISO-8859-1', $model->Alku));
			$document->setValue('Loppu', iconv('UTF-8','ISO-8859-1', $model->Loppu));
			$document->setValue('Tyokohde', iconv('UTF-8','ISO-8859-1', $model->Tyokohde));
			$document->setValue('TyosuhteenPaattamisenSyy', iconv('UTF-8','ISO-8859-1', $model->TyosuhteenPaattamisenSyy));
			$document->setValue('Kaytos', iconv('UTF-8','ISO-8859-1', $model->Kaytos));
			$document->setValue('Arvio', iconv('UTF-8','ISO-8859-1', $model->Arvio));
			$document->setValue('NimikeTehtava', iconv('UTF-8','ISO-8859-1', $model->NimikeTehtava));
			$document->setValue('Tyotehtavat', iconv('UTF-8','ISO-8859-1', $model->Tyotehtavat));

			$file = 'tiedostot/tyotodistukset/'.Yii::app()->user->domain.'/'.$tiedosto;
		  	$document->save($file.'.docx');

			shell_exec('unoconv -f pdf '.$file.'.docx'); // ei localhostina
			$this->redirect(array('index'));

	}


	public function actionDelete($id)
	{
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

}

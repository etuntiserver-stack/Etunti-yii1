<?php

class ViestintaController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','vastaanotettu', 'get_viestit'),
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

	public function actionGet_viestit()
	{

		$return = '';

       		$criteria = new CDbCriteria();
        	$criteria->order = " id DESC LIMIT 5";
        	$criteria->condition = " tekija='toimisto'";

		$model = Viestinta::model()->findAll($criteria);

		foreach($model as $data)
		{
		$id = '';
		$expl = explode(",",$data->admin);
		if(isset($expl[0]))
		$id = str_replace("tt_", "", $expl[0]);

		if(!empty($id) and isset($expl[1]))
		{
		   $src = '';
		$filename = Yii::app()->request->baseUrl."/img/tekijat/".Yii::app()->user->domain."/".$id.".jpg";
		if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$id.".jpg"))
		   $src =  $filename;
		else
		   $src = Yii::app()->request->baseUrl.'/img/tekijat/noname.jpg';

		$return .= '
            <li class="media">
              <a class="media-left" href="#"> <img src="'.$src.'" class="mw40" alt="avatar"> </a>
              <div class="media-body">
                <h5 class="media-heading">'.Yii::t('main','Viesti').'
                  <small class="text-muted">- '.date("d.m.Y",strtotime($data->time)).'</small>
                </h5> '.$data->viesti.'
                <a class="text-system" href="'.Yii::app()->request->baseUrl.'/index.php/tyontekijat/update?id='.$id.'"> '.$expl[1].' </a>
              </div>
            </li>';
		}
		}

		echo json_encode($return);

	}


	public function actionVastaanotettu($id)
	{
		Viestinta::model()->updateByPk($id,array('status'=>'1'));
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
		$model=new Viestinta;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Viestinta']))
		{
			$model->attributes=$_POST['Viestinta'];
			$model->viesti=date("d.m H:i").", ".Yii::app()->user->nimi.": ".$_POST['Viestinta']['viesti'];
			if($model->save()){

			Domainit::PushNotify($model->tekija,"ETUNTI",$model->viesti);
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

		if(isset($_POST['Viestinta']))
		{
			$model->attributes=$_POST['Viestinta'];
			$model->viesti=$_POST['Viestinta']['edellinen_viesti']."\n".date("d.m H:i").", ".Yii::app()->user->nimi.": ".$_POST['Viestinta']['viesti'];
			$model->status=0;
			if($model->save()){

			Domainit::PushNotify($model->tekija,"ETUNTI",$model->viesti);
			$this->redirect(array('view','id'=>$model->id));

			}
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
       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";

		if(isset($_POST['id']) and !empty($_POST['id']))
	        $criteria->addCondition (" id = '".$_POST['id']."' ");

		if(isset($_POST['pvm']) and !empty($_POST['pvm']))
	        $criteria->addCondition (" DATE(time) = '".$_POST['pvm']."' ");

		if(isset($_POST['sisalto']) and !empty(trim($_POST['sisalto'])))
	        $criteria->addCondition (" viesti LIKE '%".$_POST['sisalto']."%' ");


		$dataProvider=new CActiveDataProvider('Viestinta', array(
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
		$model=new Viestinta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Viestinta']))
			$model->attributes=$_GET['Viestinta'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Viestinta the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Viestinta::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Viestinta $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='viestinta-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    	protected function tekijaMuutos($data,$row)
	{ 
		if($data->tekija != 'toimisto')
		{
		    $tt = Tyontekijat::model()->findbypk($data->tekija);
		    if(isset($tt->tekijan_nimi) and !empty($tt->tekijan_nimi))
		    $data->tekija = $tt->tekijan_nimi;
		}
            	return $data->tekija;
	}

    	protected function lahettajaMuutos($data,$row)
	{ 

		$expl = explode(",",$data->admin);
		if(isset($expl[1]))
		{
			$mystring = $expl[0];
			$findme   = 'tt_';
			$pos = strpos($mystring, $findme);

			    $toimistoTekija = '';
			if ($pos === false) {
			    $toimistoTekija = '<b>'.Yii::t('main','Järjestelmänvalvoja<br>').'</b>';
			} else {
			    $toimistoTekija = '<b>'.Yii::t('main','Työntekijä<br>').'</b>';
			}

			$data->admin = $toimistoTekija.$expl[1];
		}
            	return $data->admin;
	}   



    	protected function lahettajaMuutosTheme($admin)
	{ 

		$expl = explode(",",$admin);
		if(isset($expl[1]))
		{
			$mystring = $expl[0];
			$findme   = 'tt_';
			$pos = strpos($mystring, $findme);

			    $toimistoTekija = '';
			if ($pos === false) {
			    $toimistoTekija = '<b>'.Yii::t('main','Järjestelmänvalvoja<br>').'</b>';
			} else {
			    $toimistoTekija = '<b>'.Yii::t('main','Työntekijä<br>').'</b>';
			}

			$admin = $toimistoTekija.$expl[1];
		}
            	return $admin;
	}  


    	protected function tekijaMuutosTheme($tekija)
	{ 
		if($tekija != 'toimisto')
		{
		    $tt = Tyontekijat::model()->findbypk($tekija);
		    if(isset($tt->tekijan_nimi) and !empty($tt->tekijan_nimi))
		    $tekija = $tt->tekijan_nimi;
		}
            	return $tekija;
	}

}

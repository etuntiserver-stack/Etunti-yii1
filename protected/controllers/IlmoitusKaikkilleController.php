<?php

class IlmoitusKaikkilleController extends Controller
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
				'actions'=>array('index', 'create', 'update', 'delete', 'admin', 'view'),
				'expression' => "Yii::app()->controller->isDigisten()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function isDigisten() {

		if($this->tasot(999))
		{
	            return true;
		} else {
	            return false;
		}
	}

	protected function tasot($num)
	{
		$tas = array();
		if(isset(Yii::app()->user->adminPaketti)) 
		$tas = explode(",",Yii::app()->user->adminPaketti);
		if(in_array($num,$tas))
		return true;
		else
		return false;
	}

        public function init()
        {

                if (Yii::app()->user->isAdmin()){
                        Yii::app()->theme = 'admin';
		} elseif($this->isDigisten()){
                        Yii::app()->theme = 'etunti';
                } else {
                        Yii::app()->theme = 'classic';
		}
                parent::init();
        }

	public function domainitMulti($name, $class, $id, $selected, $aktiivinen)
	{
		$return = '';
		$criteria = new CDbCriteria();
		$criteria->condition = " aktiivinen=1 ";

		if($name != null) 	$nm = ' name="'.$name.'" '; else $nm = '';
		if($class != null) 	$cl = ' class="'.$class.'" '; else $cl = '';
		if($id != null)		$i = ' id="'.$id.'" '; else $i = '';

		$list = Domainit::model()->findAll($criteria);
		$return .= '<select '.$nm.' '.$cl.' '.$i.' multiple title="Domainit">';
		foreach($list as $val){
			if(in_array($val->id, $selected)){
				$return .= '<option value="'.$val->domain.'" selected>'.$val->domain.'</option>';
			} else {
				$return .= '<option value="'.$val->domain.'">'.$val->domain.'</option>';
			}
		}
		$return .= '</select>';

		return $return;
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new IlmoitusKaikkille;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IlmoitusKaikkille']))
		{
			$model->attributes=$_POST['IlmoitusKaikkille'];
			$model->vastaanottajat=json_encode($_POST['vastaanottajat']);
			$model->aloitus=date("Y-m-d H:i:s", strtotime($model->aloitus));
			$model->lopetus=date("Y-m-d H:i:s", strtotime($model->lopetus));
			if($model->save()){
				Yii::app()->user->setFlash('success','Uusi ilmoitus on valmis. Nyt voit lisätä tiedostoja.');
				$this->redirect(array('update', 'id' => $model->id, 'from' => 'create'));
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
	public function actionUpdate($id, $from=null)
	{
		$model=$this->loadModel($id);

		// <-- FILES
		if(isset($_POST['uploaded_t'])){
			Asetukset::model()->uploadFile(
				'digisten', 
				'digisten_ilmoitukset', 
				$model->id.'_'.$_FILES['file']['name']
			);
			$this->redirect(array('update', 'id' => $model->id));
		}
		if(isset($_POST['poistaTamaTiedosto'])){
			unlink($_POST['poistaTamaTiedosto']);
			exit;
		}
		//     FILES -->

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IlmoitusKaikkille']))
		{
			$model->attributes=$_POST['IlmoitusKaikkille'];
			$model->vastaanottajat=json_encode($_POST['vastaanottajat']);
			$model->aloitus=date("Y-m-d H:i:s", strtotime($model->aloitus));
			$model->lopetus=date("Y-m-d H:i:s", strtotime($model->lopetus));
			if($model->save())
				$this->redirect(array('admin'));
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('IlmoitusKaikkille');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new IlmoitusKaikkille('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['IlmoitusKaikkille']))
			$model->attributes=$_GET['IlmoitusKaikkille'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return IlmoitusKaikkille the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=IlmoitusKaikkille::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param IlmoitusKaikkille $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ilmoitus-kaikkille-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

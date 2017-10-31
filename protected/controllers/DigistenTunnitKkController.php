<?php

class DigistenTunnitKkController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete', 'digisten_hinnasto'),
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

        public function init()
        {
                Yii::app()->theme = 'etunti';
                parent::init();
        }

	protected function tilanne($taso, $maara, $dh)
	{

		$return = 0;

		// <-- eTyö
		$val = 1; // Tunti ja työvuorot
		if( $taso == $val and $maara <= 500 ){
			$return += $dh->etyo_1000;
		} elseif( $taso == $val and ($maara > 500 and $maara <= 1500) ){
			$return += $dh->etyo_1000_2000;
		} elseif( $taso == $val and ($maara > 1500 and $maara <= 3000) ){
			$return += $dh->etyo_2000_3000;
		} elseif( $taso == $val and ($maara > 3000 and $maara <= 6000) ){
			$return += $dh->etyo_3000_6000;
		} elseif( $taso == $val and ($maara > 6000 and $maara <= 9000) ){
			$return += $dh->etyo_6000_9000;
		} elseif( $taso == $val and $maara > 9000 ){
			$return += $dh->etyo_9000_plus;
		}
		//     eTyö -->

		// <-- eLasku
		$val = 3; // Laskutus
		if( $taso == $val and $maara <= 500 ){
			$return += $dh->elasku_1000;
		} elseif( $taso == $val and ($maara > 500 and $maara <= 1500) ){
			$return += $dh->elasku_1000_2000;
		} elseif( $taso == $val and ($maara > 1500 and $maara <= 3000) ){
			$return += $dh->elasku_2000_3000;
		} elseif( $taso == $val and ($maara > 3000 and $maara <= 6000) ){
			$return += $dh->elasku_3000_6000;
		} elseif( $taso == $val and ($maara > 6000 and $maara <= 9000) ){
			$return += $dh->elasku_6000_9000;
		} elseif( $taso == $val and $maara > 9000 ){
			$return += $dh->elasku_9000_plus;
		}
		//     eLasku -->

		// <-- eOnline
		$val = 4; // Onlinevaraus
		if( $taso == $val and $maara <= 500 ){
			$return += $dh->eonline_1000;
		} elseif( $taso == $val and ($maara > 500 and $maara <= 1500) ){
			$return += $dh->eonline_1000_2000;
		} elseif( $taso == $val and ($maara > 1500 and $maara <= 3000) ){
			$return += $dh->eonline_2000_3000;
		} elseif( $taso == $val and ($maara > 3000 and $maara <= 6000) ){
			$return += $dh->eonline_3000_6000;
		} elseif( $taso == $val and ($maara > 6000 and $maara <= 9000) ){
			$return += $dh->eonline_6000_9000;
		} elseif( $taso == $val and $maara > 9000 ){
			$return += $dh->eonline_9000_plus;
		}
		//     eOnline -->

		// <-- eDico
		$val = 5; // CRM / eDico
		if( $taso == $val and $maara <= 500 ){
			$return += $dh->edico_1000;
		} elseif( $taso == $val and ($maara > 500 and $maara <= 1500) ){
			$return += $dh->edico_1000_2000;
		} elseif( $taso == $val and ($maara > 1500 and $maara <= 3000) ){
			$return += $dh->edico_2000_3000;
		} elseif( $taso == $val and ($maara > 3000 and $maara <= 6000) ){
			$return += $dh->edico_3000_6000;
		} elseif( $taso == $val and ($maara > 6000 and $maara <= 9000) ){
			$return += $dh->edico_6000_9000;
		} elseif( $taso == $val and $maara > 9000 ){
			$return += $dh->edico_9000_plus;
		}
		//     eDico -->


		if($return > 0)
			return $return;
		else
			return false;
	}

	protected function tuntihinta_laskin($id)
	{

		$model = $this->loadModel($id);
		$return = 0;

		$dh = DigistenHinnasto::model()->findByPk(1);
		if(!isset($dh->id))
		return 0;

		if(isset($model->id) and isset($dh->id))
		{
			$tasot = explode(",", $model->tasot);
			if( is_array($tasot) )
			{
				foreach($tasot as $k=>$v)
				{
					if($v == 2) continue; // tyovuorot ei tarvitse erikseen
					if($this->tilanne($v, $model->tunnit, $dh))
					$return += $this->tilanne($v, $model->tunnit, $dh);
				}
			}
		}

		return $return;
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


	public function actionDigisten_hinnasto()
	{

		$chk = DigistenHinnasto::model()->findByPk(1);
		if(isset($chk->id))
			$model = $chk;
		else
			$model = new DigistenHinnasto;

		if(isset($_POST['DigistenHinnasto']))
		{
			$model->attributes=$_POST['DigistenHinnasto'];
			if($model->save())
				Yii::app()->user->setFlash('success', "Hinnasto tallennettu!");
		}

		$this->render('digisten_hinnasto',array(
			'model'=>$model,
		));
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
		$model=new DigistenTunnitKk;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DigistenTunnitKk']))
		{
			$model->attributes=$_POST['DigistenTunnitKk'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['DigistenTunnitKk']))
		{
			$model->attributes=$_POST['DigistenTunnitKk'];
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
	        $criteria->order = " id DESC ";

		$dataProvider=new CActiveDataProvider('DigistenTunnitKk', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$this->render('index', array(
			'dataProvider' => $dataProvider, 
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DigistenTunnitKk('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DigistenTunnitKk']))
			$model->attributes=$_GET['DigistenTunnitKk'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DigistenTunnitKk the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DigistenTunnitKk::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DigistenTunnitKk $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='digisten-tunnit-kk-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

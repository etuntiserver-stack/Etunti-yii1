<?php

class KupongitController extends Controller
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
				'actions'=>array('admin', 'delete', 'create', 'update', 'index', 'view', 'laheta'),
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

	public function actionLaheta($id)
	{
		if(isset($_POST['asiakas_id']))
		{

			$asiakkaat = Asiakkaat::model()->findByPk($_POST['asiakas_id']);
			$kupongit = Kupongit::model()->findByPk($id);

			if(isset($asiakkaat->id))
			{

				// <-- asiakas_id_lista
				$asiakas_id_lista = array();
				if(is_array(json_decode($kupongit->lahetetyt_asiakas_id_lista, true)))
				   $asiakas_id_lista = json_decode($kupongit->lahetetyt_asiakas_id_lista, true);

				if( count($asiakas_id_lista) > 0 and $kupongit->jatkuva == 0)
				{
					Yii::app()->user->setFlash('danger', "Koodi on jo käytössä ja se on määritetty kertakäyttöiseksi.");
					$this->redirect(array('index'));
				}

				if(!isset($asiakas_id_lista[$asiakkaat->id]))
				   $asiakas_id_lista[$asiakkaat->id] = $asiakkaat->id;

				Kupongit::model()->updateByPk($kupongit->id, array('lahetetyt_asiakas_id_lista' => json_encode($asiakas_id_lista)));
				//     asiakas_id_lista -->

				// <-- asiakas tallentaminen
				$alennuskoodit = array();
				if(is_array(json_decode($asiakkaat->alennuskoodit, true)))
				$alennuskoodit = json_decode($asiakkaat->alennuskoodit, true);

				if(isset($alennuskoodit[$_POST['id']]) and $alennuskoodit[$_POST['id']] == $_POST['kupongin_id'])
				{
					Yii::app()->user->setFlash('warning', "Tämä alennuskoodi on jo lähetetty tälle henkilölle.");
					$this->redirect(array('index'));
				}
				$alennuskoodit[$_POST['id']] = $_POST['kupongin_id'];
				Asiakkaat::model()->updateByPk($asiakkaat->id, array('alennuskoodit' => json_encode($alennuskoodit)));
				//     asiakas tallentaminen -->

				// <-- Lahetys
				$message = Yii::t('main', 'Uusi alennuskoodi on').': '.$_POST['kupongin_id'].'<br>';

				if(isset($asiakkaat->sahkoposti) and !empty($asiakkaat->sahkoposti))
				{
				$subject = Yii::t('main', 'Uusi alennuskoodi');
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($asiakkaat->sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $asiakkaat->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->
				//     Lahetys -->
				}

			}
				Yii::app()->user->setFlash('success', "Lähetetty.");
				$this->redirect(array('index'));
		}

		$this->render('laheta',array(
			'model'=>$this->loadModel($id),
		));
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Kupongit;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Kupongit']))
		{
			$model->attributes=$_POST['Kupongit'];
			$model->voimassa=date("Y-m-d", strtotime($_POST['Kupongit']['voimassa']));
			if($model->save())
				$this->redirect(array('index'));
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

		if(isset($_POST['Kupongit']))
		{
			$model->attributes=$_POST['Kupongit'];
			$model->voimassa=date("Y-m-d", strtotime($_POST['Kupongit']['voimassa']));
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */

	protected function generateRandomString($length) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function actionIndex()
	{
       		$criteria = new CDbCriteria();
		$criteria->order = "id DESC";


		if(isset($_POST['kupongin_maara']) and $_POST['kupongin_maara'] > 0)
		{
		    for ($i = 1; $i <= $_POST['kupongin_maara']; $i++)
		    {
			$model = new Kupongit;
			$model->attributes=$_POST['Kupongit'];

			$model->kupongin_id = $this->generateRandomString($_POST['merkkien_maara']);
			if( $model->maara_tyyppi == 'euro' )
			$model->euro_maara=$_POST['maara'];

			if( $model->maara_tyyppi == 'prosentti' )
			$model->prosentti_maara=$_POST['maara'];

			if(!empty($_POST['Kupongit']['voimassa']))
			$model->voimassa=date("Y-m-d", strtotime($_POST['Kupongit']['voimassa']));

			if(!$model->save())
				die(var_dump($model->getErrors()));
		    }
				$this->redirect(array('index'));
		}

		$dataProvider=new CActiveDataProvider('Kupongit', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));


		$perSivu = 50;
		if(isset(Yii::app()->user->asiakkaatPerSivu))
		$perSivu = Yii::app()->user->asiakkaatPerSivu;

		$dataProvider->pagination->pageSize = $perSivu;

		$a = Asetukset::model()->findbypk(1);
		if($a->netvisor_kaytto == 1)
		$netvisor = true;
		else
		$netvisor = false;

		$this->render('index', array(
			'dataProvider' => $dataProvider, 
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Kupongit('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Kupongit']))
			$model->attributes=$_GET['Kupongit'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Kupongit the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Kupongit::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Kupongit $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='kupongit-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

<?php

class KorvauksetController extends Controller
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

	public function accessRules()
	{
		return array(

			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin', 'delete', 'create', 'create_netvisor', 'update', 'index','tidfromto'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

	public function actionTidfromto($from,$to,$tid)
	{
		$this->render('tidfromto',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$tid
		));
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
		$model=new Korvaukset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Korvaukset']))
		{
			$model->attributes=$_POST['Korvaukset'];
			$model->pvm=date("Y-m-d", strtotime($_POST['Korvaukset']['pvm']));
			if($model->save()){

				echo json_encode('ok');
				exit;
			}


		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionCreate_netvisor()
	{
		$model=new Korvaukset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Korvaukset']))
		{
			$model->attributes=$_POST['Korvaukset'];
			$model->pvm=date("Y-m-d", strtotime($_POST['Korvaukset']['pvm']));
			if($model->save()){

				$asetukset=Asetukset::model()->findbypk(1);
				if($asetukset->netvisor_kaytto == 1)
				{
					$returnNV = $this->netvisorPayrollperiodcollector($model);
					echo json_encode($returnNV);
					if( isset($returnNV['ERROR']) ){
						$this->loadModel($model->id)->delete();
					}
					exit;
				}
				echo json_encode('ok');
				exit;
			}


		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	protected function netvisorPayrollperiodcollector($model)
	{
		$henkkari = '';
		$tyontekija = Tyontekijat::model()->findByPk($model->tid);
		if(isset($tyontekija->id)){ $henkkari = $tyontekija->tekijan_henkilotunnus; }
		$asetukset = Asetukset::model()->findByPk(1);

		$return = array();
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	   if(isset($n[0]))
	   {

		$url		= $n[0].'/payrollperiodcollector.nv';

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
		
	

// <-- XML
$xml = '
<root>
  <payrollperiodcollector>
    <date>'.date("Y-m-d", strtotime($model->pvm)).'</date>
    <employeeidentifier type="personalidentificationnumber">'.$henkkari.'</employeeidentifier>
    <payrollratioline>
    	<amount>'.$model->korvaus.'</amount>
    	<payrollratio type="number">'.(int)$model->syy.'</payrollratio>
    </payrollratioline>
  </payrollperiodcollector>
</root>';
//  XML -->
//    <employeeidentifier type="number">'.$model->tid.'</employeeidentifier>
	

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
			$return = array('OK'=>$response);
		} else {
			$return = array('ERROR'=>$response);
		}


	   } // if isset $n[0]

		return $return;

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

		if(isset($_POST['Korvaukset']))
		{
			$model->attributes=$_POST['Korvaukset'];
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('mobile/palkkataulukko'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Korvaukset');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Korvaukset('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Korvaukset']))
			$model->attributes=$_GET['Korvaukset'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Korvaukset the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Korvaukset::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Korvaukset $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='korvaukset-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

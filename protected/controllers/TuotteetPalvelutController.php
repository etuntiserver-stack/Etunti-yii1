<?php

class TuotteetPalvelutController extends Controller
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
				'actions'=>array('index','view'),
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
		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
		if($m->id == Yii::app()->user->adminID)
			return true;
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

	// <-- Oikeudet
	   $checkOikeus = "onlineTuotteet_1_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$asetukset=Asetukset::model()->findbypk(1);
		if($asetukset->netvisor_kaytto == 1){ $netvisor = true; } else { $netvisor = false; }

		$model=new TuotteetPalvelut;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['TuotteetPalvelut']))
		{

			//echo '<pre>';
			//print_r($_POST['toinen_valiko']);
			//echo '</pre>';
			//exit;

			$model->attributes=$_POST['TuotteetPalvelut'];

			if(isset($_POST['toinen_valiko']))
				$model->toinen_valikko_rakenne=json_encode($_POST['toinen_valiko']);
			else
				$model->toinen_valikko_rakenne='';

			if(isset($_POST['lisapalvelut']))
				$model->lisapalvelut=json_encode($_POST['lisapalvelut']);
			else
				$model->lisapalvelut='';

			if(isset($_POST['TuotteetPalvelut']['kategoria']))
				$model->kategoria=json_encode($_POST['TuotteetPalvelut']['kategoria']);
			else
				$model->kategoria='';

			// <-- Dimension
			if( isset($_POST['TuotteetPalvelut']['netvisor_dimension_name']) ){
			   $dimension = explode("//", $_POST['TuotteetPalvelut']['netvisor_dimension_name']);
			   if( isset($dimension[0]) and isset($dimension[1]) ){
				$model->netvisor_dimension_name = $dimension[0];
				$model->netvisor_dimension_item = $dimension[1];
			   }
			}
			//     Dimension -->
			
			if($model->save())
			{
			   // <-- Netvisor
			   $a = Asetukset::model()->findbypk(1);
			   if($a->netvisor_kaytto == 1)
			   {
					$InsertedDataIdentifier = $this->netvisorProduct("add", $model);
					if(!empty($InsertedDataIdentifier))
					TuotteetPalvelut::model()->updateByPk($model->id, array('netvisorkey'=>$InsertedDataIdentifier));

			   }
			   //  Netvisor -->

				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'netvisor' => $netvisor
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
	   $checkOikeus = "onlineTuotteet_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$asetukset=Asetukset::model()->findbypk(1);
		if($asetukset->netvisor_kaytto == 1){ $netvisor = true; } else { $netvisor = false; }

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['TuotteetPalvelut']))
		{
			$model->attributes=$_POST['TuotteetPalvelut'];

			if(isset($_POST['toinen_valiko']))
				$model->toinen_valikko_rakenne=json_encode($_POST['toinen_valiko']);
			else
				$model->toinen_valikko_rakenne='';

			if(isset($_POST['lisapalvelut']))
				$model->lisapalvelut=json_encode($_POST['lisapalvelut']);
			else
				$model->lisapalvelut='';

			if(isset($_POST['TuotteetPalvelut']['kategoria']))
				$model->kategoria=json_encode($_POST['TuotteetPalvelut']['kategoria']);
			else
				$model->kategoria='';


			if(isset($_POST['uploaded_image']) and !empty($_POST['uploaded_image']))
			{

			  	$path = Yii::app()->basePath."/../tiedostot/onlinevaraus_tuote/".Yii::app()->user->domain;
			  	if (!file_exists($path)) {
			  		mkdir($path, 0777, true);
			  	}

			  	$uploadfile = $path. '/' . basename($model->id.'.jpg');
			  	if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
			     		//echo "";
			  	} 
			}

			// <-- Dimension
			if( isset($_POST['TuotteetPalvelut']['netvisor_dimension_name']) ){
			   $dimension = explode("//", $_POST['TuotteetPalvelut']['netvisor_dimension_name']);
			   if( isset($dimension[0]) and isset($dimension[1]) ){
				$model->netvisor_dimension_name = $dimension[0];
				$model->netvisor_dimension_item = $dimension[1];
			   }
			}
			//     Dimension -->

			if($model->save())
			{

			   // <-- Netvisor
			   $a = Asetukset::model()->findbypk(1);
			   if($a->netvisor_kaytto == 1)
			   {
				if($model->netvisorkey == 0)
				{
					$InsertedDataIdentifier = $this->netvisorProduct("add", $model);
					if(!empty($InsertedDataIdentifier))
					TuotteetPalvelut::model()->updateByPk($model->id, array('netvisorkey'=>$InsertedDataIdentifier));

				} else {

					$InsertedDataIdentifier = $this->netvisorProduct("edit", $model);

				}
			    }
			   //  Netvisor -->

				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model' => $model,
			'netvisor' => $netvisor
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
	   $checkOikeus = "onlineTuotteet_3_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

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
	   $checkOikeus = "onlineTuotteet_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		if(isset($_POST['oletustuote'])){
			TuotteetPalvelut::model()->updateAll(array('oletustuote'=>'0'));
			TuotteetPalvelut::model()->updatebypk($_POST['oletustuote'], array('oletustuote' => 2));
			echo $_POST['oletustuote'];
			exit;
		}

		$asetukset=Asetukset::model()->findbypk(1);
		if($asetukset->netvisor_kaytto == 1){ $netvisor = true; } else { $netvisor = false; }

       		$criteria = new CDbCriteria();
	        $criteria->order = " palvelu=0 DESC,nimike ASC ";


		if(isset($_GET['nayta_sivuilla']) and $_GET['nayta_sivuilla'] == 0 and $_GET['nayta_sivuilla'] != 'kaikki')
	        	$criteria->addCondition (" nayta_sivuilla=0 ");
		elseif(isset($_GET['nayta_sivuilla']) and $_GET['nayta_sivuilla'] == 1 and $_GET['nayta_sivuilla'] != 'kaikki')
	        	$criteria->addCondition (" nayta_sivuilla=1 ");
		elseif(isset($_GET['nayta_sivuilla']) and $_GET['nayta_sivuilla'] == 'kaikki')
	        	$criteria->addCondition (" nayta_sivuilla=1 OR nayta_sivuilla=0 ");
		elseif(!isset($_GET['nayta_sivuilla']))
	        	$criteria->addCondition (" nayta_sivuilla=1 ");

		if(isset($_GET['nimike']) and !empty(trim($_GET['nimike'])))
	        $criteria->addCondition (" nimike LIKE '%".$_GET['nimike']."%' ");

		if(isset($_GET['kategoria']) and !empty(trim($_GET['kategoria'])))
	        $criteria->addCondition (" kategoria LIKE '%".$_GET['kategoria']."%' ");

		$dataProvider=new CActiveDataProvider('TuotteetPalvelut', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$from = date("d.m.Y", strtotime("-1 month"));
		$to = date("d.m.Y");
		if(isset($_GET['from']) and !empty($_GET['from']))
			$from = date("d.m.Y", strtotime($_GET['from']));
		if(isset($_GET['to']) and !empty($_GET['to']))
			$to = date("d.m.Y", strtotime($_GET['to']));

		$dataProvider->pagination->pageSize = 50;
		$this->render('index', array('dataProvider' => $dataProvider, 'netvisor' => $netvisor, 'from' => $from, 'to' => $to));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TuotteetPalvelut('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TuotteetPalvelut']))
			$model->attributes=$_GET['TuotteetPalvelut'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TuotteetPalvelut the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TuotteetPalvelut::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TuotteetPalvelut $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='onlinevaraus-tuotteet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    	protected function palvelu($p)
	{ 
		$return = '';
		if($p == 1)
		$return = 'Lisäpalvelu';
		elseif($p == 0)
		$return = 'Pääpalvelu';

            	return $return;
	}

	protected function netvisorProduct($tila, $model)
	{

		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	if(isset($n[0]))
	{
		if( $tila == 'add' and $model->netvisorkey == 0)
		$url		= $n[0].'/product.nv?method=add';
		elseif( $tila == 'edit' and $model->netvisorkey != 0)
		$url		= $n[0].'/product.nv?id='.$model->netvisorkey.'&method=edit';

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
	
	$ryhma = '';
	if(isset($model->ryhma))
	{
	  $r = Valikkoot::model()->findbypk($model->ryhma);
	   if(isset($r->id))
	   $ryhma = $r->value;
	}

$model->hinta_alv_0 = str_replace(",",".",$model->hinta_alv_0);
$model->hinta_alv_sis = str_replace(",",".",$model->hinta_alv_0);

$xml = '
<root>
  <product>
    <productbaseinformation>
      <productcode>'.$model->id.'</productcode>
      <productgroup>'.$ryhma.'</productgroup>
      <name>'.$model->nimike.'</name>
      <description></description>
      <unitprice type="net">'.$model->hinta_alv_0.'</unitprice>
      <unit>'.$model->yksikko.'</unit>
      <unitweight>1</unitweight>
      <purchaseprice>'.$model->hinta_alv_sis.'</purchaseprice>
      <tariffheading></tariffheading>
      <comissionpercentage>0</comissionpercentage>
      <isactive>'.$model->aktiivinen.'</isactive>
      <issalesproduct>'.$model->myyntituote.'</issalesproduct>
      <inventoryenabled>'.$model->varastoitava.'</inventoryenabled>
    </productbaseinformation>
    <productbookkeepingdetails>
      <defaultvatpercentage>'.$model->alv.'</defaultvatpercentage>
    </productbookkeepingdetails>
  </product>
</root>';
	
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
		if( $tila == 'add' )
		$return=$result->Replies->InsertedDataIdentifier;
		if( $tila == 'edit' )
		$return=$result;

	  } else {

		echo '<pre>';
		print_r( $response );
		echo '</pre>';

	  }

	


	} // if isset $n[0]

		return $return;


	}


}

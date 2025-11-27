<?php
echo 'suljettu';
exit;
class LaskutusTuotteetController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view', 'netvisor_sync'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view'),
                		'message'=>Yii::t('main', 'Tämä TASO ei kuuluu teille'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function isEtuntiAdmin() {

	$tas = '';
	if(isset(Yii::app()->user->adminPaketti))
	$tas = explode(",",Yii::app()->user->adminPaketti);

		if(isset(Yii::app()->user->adminID) and in_array('3',$tas))
		{
		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	       	if($m->id == Yii::app()->user->adminID)
	       	  return true;
		else
	       	   $this->redirect(array('/site/otakaytoon', 'tila' => 'lasku'));		

		} else {
	            $this->redirect(array('/site/otakaytoon', 'tila' => 'lasku'));
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

		$asetukset = Asetukset::model()->findByPk(1);
		if(isset($asetukset->palvelu_tyyppi) and $asetukset->palvelu_tyyppi == 0)
		{
	  		$this->redirect(array('/site/otakaytoon', 'tila' => 'lasku'));
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
		$model=new LaskutusTuotteet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['LaskutusTuotteet']))
		{
			$model->attributes=$_POST['LaskutusTuotteet'];
			$model->hinta_alv_0=str_replace(",",".",$_POST['LaskutusTuotteet']['hinta_alv_0']);
			$model->hinta_alv_sis=str_replace(",",".",$_POST['LaskutusTuotteet']['hinta_alv_sis']);
			if($model->save())
			{

			   // <-- Netvisor
			   $a = Asetukset::model()->findbypk(1);
			   if($a->netvisor_kaytto == 1)
			   {
					$InsertedDataIdentifier = $this->netvisorProduct("add", $model);
					if(!empty($InsertedDataIdentifier))
					LaskutusTuotteet::model()->updateByPk($model->id, array('netvisorkey'=>$InsertedDataIdentifier));

			   }
			   //  Netvisor -->


				$this->redirect(array('index'));
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

		if(isset($_POST['LaskutusTuotteet']))
		{
			$model->attributes=$_POST['LaskutusTuotteet'];
			$model->hinta_alv_0=str_replace(",",".",$_POST['LaskutusTuotteet']['hinta_alv_0']);
			$model->hinta_alv_sis=str_replace(",",".",$_POST['LaskutusTuotteet']['hinta_alv_sis']);
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
					LaskutusTuotteet::model()->updateByPk($model->id, array('netvisorkey'=>$InsertedDataIdentifier));

				} else {

					$InsertedDataIdentifier = $this->netvisorProduct("edit", $model);

				}
			    }
			   //  Netvisor -->

				$this->redirect(array('index'));
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{


       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";


		if(isset($_POST['tuotenimi']) and !empty($_POST['tuotenimi']))
	        $criteria->addCondition (" tuotenimi LIKE '%".$_POST['tuotenimi']."%' ");

		if(isset($_POST['hinta_alv_0']) and !empty(trim($_POST['hinta_alv_0'])))
	        $criteria->addCondition (" hinta_alv_0 LIKE '%".$_POST['hinta_alv_0']."%' ");

		if(isset($_POST['hinta_alv_sis']) and !empty(trim($_POST['hinta_alv_sis'])))
	        $criteria->addCondition (" hinta_alv_sis LIKE '%".$_POST['hinta_alv_sis']."%' ");

		if(isset($_POST['alv']) and !empty(trim($_POST['alv'])))
	        $criteria->addCondition (" alv LIKE '%".$_POST['alv']."%' ");

		if(isset($_POST['yksikko']) and !empty(trim($_POST['yksikko'])))
	        $criteria->addCondition (" yksikko LIKE '%".$_POST['yksikko']."%' ");

		$dataProvider=new CActiveDataProvider('LaskutusTuotteet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;

		$a = Asetukset::model()->findbypk(1);
		if($a->netvisor_kaytto == 1)
		$netvisor = true;
		else
		$netvisor = false;

		$this->render('index', array('dataProvider' => $dataProvider, 'netvisor' => $netvisor));

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new LaskutusTuotteet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LaskutusTuotteet']))
			$model->attributes=$_GET['LaskutusTuotteet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LaskutusTuotteet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=LaskutusTuotteet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param LaskutusTuotteet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='laskutus-tuotteet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



	protected function netvisorProduct($tila, $model)
	{

		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	if(isset($n[0]))
	{
		$base_action = "product.nv?";
		$action = $base_action;

		if( $tila == 'add' and $model->netvisorkey == 0) {
			$url		= $n[0].'/product.nv?method=add';
			$action .= "method=add";
		}
		elseif( $tila == 'edit' and $model->netvisorkey != 0) {
			$url		= $n[0].'/product.nv?id='.$model->netvisorkey.'&method=edit';
			$action .= "id={$model->netvisorkey}&method=edit";
		}

		$auth_data = $site[0]->netvisorStringHeaders($action);
		$url = $site[0]->netvisorParams($action)["url"];

	
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
      <name>'.$model->tuotenimi.'</name>
      <description></description>
      <unitprice type="net">'.$model->hinta_alv_0.'</unitprice>
      <unit>'.$model->yksikko.'</unit>
      <unitweight>1</unitweight>
      <purchaseprice>'.$model->hinta_alv_sis.'</purchaseprice>
      <tariffheading></tariffheading>
      <comissionpercentage>0</comissionpercentage>
      <isactive>'.$model->is_active.'</isactive>
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


	protected function onkoNetvisor($id)
	{

		$return = 'vv';
		$as = LaskutusTuotteet::model()->findbypk($id);
		if(isset($as->id) and $as->netvisorkey != 0)
		{
		$return = CHtml::Button(Yii::t('main', 'Sync'), array(
		'submit'=>array('netvisor_sync', "tila"=>"edit", "id"=>$id), 
		'confirm' => 'Haluatko varmaasti synkronoida Netvisoriin?',
		'class'=>'btn btn-warning btn-block'
		));
		} elseif(isset($as->id) and empty($as->netvisorkey)){
		$return = CHtml::Button(Yii::t('main', 'Tuonti'), array(
		'submit'=>array('netvisor_sync', "tila"=>"add", "id"=>$id), 
		'confirm' => 'Haluatko varmaasti synkronoida Netvisoriin?',
		'class'=>'btn btn-success btn-block'
		));
		}

		return $return;
	}


	public function actionNetvisor_sync($tila, $id)
	{
		$model = LaskutusTuotteet::model()->findbypk($id);
		$return = '';
		if($tila == 'add')
		{
		   $return = $this->netvisorProduct("add", $model);
		   if(!empty($return))
		   LaskutusTuotteet::model()->updateByPk($id, array('netvisorkey'=>$return));

		} elseif($tila == 'edit') {
		   $return = $this->netvisorProduct("edit", $model);
		}

		//echo $return;
		//exit;

		$this->redirect(array('index'));
	}

}

<?php

class TyovuorootController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','did'),
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
		else
	            return false;
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */

	public function actionShowohje($id)
	{
		$m = Kohteet::model()->findbypk($id);
		echo $m->toimenpiteet;
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionDid()
	{
		if(isset($_POST['id']))
		  $tv=$this->loadModel($_POST['id']);
		else
		  $tv = Tyovuoroot::model()->find("id !='' order by id desc");

		$did = date("Ymd",strtotime($tv->pvm));
		echo '<div class="small laatikko latikkoAsetukset" pvm="'.$tv->pvm.'" tid="'.$tv->tid.'" id="'.$did.'_'.$tv->tid.'">';

		$tv = Tyovuoroot::model()->findAll("tid = '".$tv->tid."' and pvm = '".$tv->pvm."' ",array('select'=>'kohde')); 
		foreach($tv as $tvVal)
		{
		$k = Kohteet::model()->findbypk($tvVal->kohde);

	  	    $strlen = strlen($k['osoite']);

	     	  if($strlen > 18)
	  	    $k['osoite'] = substr($k['osoite'],0,18).'..';
	   	  else
		    $k['osoite'] = $k['osoite'];

		  if($tvVal->alku > 0 and $tvVal->loppu > 0)
		    $al = $tvVal->alku.'-'.$tvVal->loppu;
		  else
		    $al = '';

		  echo '<a href="#" class="link tv_edit" id="tv_'.$tvVal->id.'">'.$al.' '.$k['osoite'].'</a><br>';

		}

	  	echo '</div>';
		?>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>
		<?php

	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

  	$tnimi = '';
	if(isset($_POST['tid'])){
  	  $tekija = Tyontekijat::model()->findbypk($_POST['tid']);
	  $tnimi = $tekija->tekijan_nimi;
	}
	?>
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Työvuoroon suunnittelu').' '.$tnimi; ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">
	<?php

		$model=new Tyovuoroot;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyovuoroot']))
		{
			$model->attributes=$_POST['Tyovuoroot'];
			$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
			$model->save();
				//$this->redirect(array('view','id'=>$model->id));
		}

		$this->renderPartial('create',array(
			'model'=>$model,
		));
	?>
	</div>
	</div> <!-- end modal-body -->
	<?php
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{

		$model=$this->loadModel($id);
	  	$t = Tyontekijat::model()->findbypk($model->tid);

	?>
	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Työvuoroon suunnittelu').' '.$t->tekijan_nimi; ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">
	<?php



		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyovuoroot']))
		{
			$_POST['Tyovuoroot']['pvm'] = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
			$model->attributes=$_POST['Tyovuoroot'];
			$model->save();
		}

		$this->renderPartial('update',array(
			'model'=>$model,
		));
	?>
	</div>
	</div> <!-- end modal-body -->
	<?php
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
		$this->render('index');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tyovuoroot('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyovuoroot']))
			$model->attributes=$_GET['Tyovuoroot'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyovuoroot the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyovuoroot::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyovuoroot $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyovuoroot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

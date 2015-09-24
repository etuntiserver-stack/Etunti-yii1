<?php

class LaskuController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','etsikohde'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','etsikohde'),
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
	       	   return false;		

		} else {
	            return false;
		}
	}


	public function actionEtsikohde($id)
	{
	?>
	<script type="text/javascript">
 	  $(document).ready(function(){
		$('.selectpicker').selectpicker({
		      style: 'btn-default',
		      //size: 4
		});

		$('.selectpicker').on('change', function(){
			$(".tyyppi").show('slow');
			$("#kohteistaRivit").val($(this).val());
		});
	  });
	</script>
	<?php
		$k = Kohteet::model()->findAll(" asiakas_id='".$id."' ");
		$body = '<label>'.Yii::t('main','Asiakkaan kohteet').'</label>';
		$body .= '<select id="kohteet" class="form-control selectpicker" multiple title="Valitse kohteet">';
		foreach($k as $a)
		$body .= '<option value="'.$a->id.'">'.$a->osoite.'</option>';
		$body .= '</select>';
		echo $body;
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionCreate()
	{

	function yksikkot($row){
		$body = '';
		if($row)
		$body .= '<option value="'.$row.'">'.$row.' kpl</option>';
	
		$body .= '
			<option value="kpl">kpl</option>
			<option value="h">h</option>
			<option value="min">min</option>
			<option value="kk">kk</option>
			<option value="kg">kg</option>
		';
		return $body;
	}
	function alv($row){
		$body = '';
		if($row)
		$body .= '<option value="'.$row.'">'.$row.' %</option>';
		$body .= '<option value="24">24 %</option>';
		for ($i = 0; $i <= 24 ; $i++) {
		    $body .= '<option value='.$i.'>'.$i.' %</option>';
		}
		return $body;
	}

		$model=new Lasku;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Lasku']))
		{
			$model->attributes=$_POST['Lasku'];
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

		if(isset($_POST['Lasku']))
		{
			$model->attributes=$_POST['Lasku'];
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Lasku');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Lasku('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Lasku']))
			$model->attributes=$_GET['Lasku'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Lasku the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Lasku::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Lasku $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lasku-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

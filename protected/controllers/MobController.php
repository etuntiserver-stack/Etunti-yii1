<?php


class MobController extends Controller
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
			//'postOnly + delete', // we only allow deletion via POST request

        	array(
        	        'ext.starship.RestfullYii.filters.ERestFilter + 
	                REST.GET, REST.PUT, REST.POST, REST.DELETE'
            		),
       
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


            		array('allow', 'actions'=>array('REST.GET', 'REST.PUT', 'REST.POST'), //'REST.DELETE'
                		//'expression'=>"Yii::app()->controller->imeiCheck()",
				'users'=>array('*'),
            		),
            		array('deny', 'actions'=>array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
                		'message' => Yii::t('main', 'Imei error'),
            		),

			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actions()
	{

	header("Access-Control-Allow-Origin: *");


	$identity=new UserIdentity('demo','111111');
	if($identity->authenticate())
	    Yii::app()->user->login($identity);
	else
	    echo $identity->errorMessage;

	//print_r($_POST);

	        return array(
	            'REST.'=>'ext.starship.RestfullYii.actions.ERestActionProvider',
	        );


	}


	public function imeiCheck() {

		if(isset($_SESSION['imei']))
		$m = Tyontekijat::model()->find(" imei = '".$_SESSION['imei']."' ");

	        if(isset($m->imei) and $m->imei == $_SESSION['imei'])
	            return true;
		else
	            return false;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

	}

}

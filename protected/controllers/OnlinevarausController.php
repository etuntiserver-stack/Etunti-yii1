<?php

class OnlinevarausController extends Controller
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
				'actions'=>array('index','view', 'check', 'aika', 'osoite', 'maksu', 'palvelu_ajax', 'palvelu_save_ajax', 'lisat_ajax'),
                		'users'=>array("*"),
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

	$tas = '';
	if(isset(Yii::app()->user->adminPaketti))
	$tas = explode(",",Yii::app()->user->adminPaketti);

		if(isset(Yii::app()->user->adminID) and in_array('4',$tas))
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

                Yii::app()->theme = 'classic';
		parent::init();
		if(isset($_GET['domain']))
		$this->redirect(array('index'));
        }


	public function actionCheck($pvm)
	{
/*
		$criteria=new CDbCriteria;
		$criteria->condition = " pvm='".date("d.m.Y", strtotime($_POST['pvm']))."' ";
		$tyovuorot = Tyovuoroot::model()->find($criteria);
		if(isset($tyovuorot->id))
		  echo 'varattu';
		else
		  echo 'vapaa';
*/
	}

	public function actionLisat_ajax()
	{
	   if(isset($_POST['id']))
	   {
		$model = OnlinevarausTuotteet::model()->findbypk($_POST['id']);
		if(isset($model->id))
		{
		    if($_POST['checked'] == 1)
		    {
			$_SESSION['onlinevaraus']['lisapalvelut'][$model->id] = $model->id;
			echo 'save';
		    }
		    if($_POST['checked'] == 0)
		    {
			unset($_SESSION['onlinevaraus']['lisapalvelut'][$model->id]);
			echo 'deleted';
		    }


		}
	   }
	}

	public function actionPalvelu_ajax()
	{

	   if(isset($_POST['clear']) and $_POST['clear'] == 'all')
	   {
		unset($_SESSION['onlinevaraus']);
		echo 'cleared';
		exit;
	   }

	   if(isset($_POST['word']))
	   {
		$word = trim($_POST['word']);
		$criteria=new CDbCriteria;
		$criteria->condition = " nimike='".$word."' ";
		$criteria->order = " SUBSTRING_INDEX(nelio,'-',1) ";
		$data = OnlinevarausTuotteet::model()->findAll($criteria);

		$this->renderPartial('palvelu_ajax',array(
			'data'=>$data,
		));
	   }
	}

	public function actionPalvelu_save_ajax()
	{
	   if(isset($_POST['palvelu']) and isset($_POST['nelio']))
	   {
		$criteria=new CDbCriteria;
		$criteria->condition = " palvelu='".$_POST['palvelu']."' AND nelio='".$_POST['nelio']."' ";
		$model = OnlinevarausTuotteet::model()->find($criteria);
		if(isset($model->id))
		$_SESSION['onlinevaraus']['paapalvelu'] = $model->id;

		$this->renderPartial('palvelu_save_ajax',array(
			'model'=>$model,
			'sivu'=>'index',
		));
	   }
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
		$model=new Onlinevaraus;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Onlinevaraus']))
		{
			$model->attributes=$_POST['Onlinevaraus'];
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

		if(isset($_POST['Onlinevaraus']))
		{
			$model->attributes=$_POST['Onlinevaraus'];
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
		$this->render('index');
	}

	public function actionAika()
	{
		$this->render('aika');
	}

	public function actionOsoite()
	{
		$this->render('osoite');
	}

	public function actionMaksu()
	{
		$this->render('maksu');
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Onlinevaraus('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Onlinevaraus']))
			$model->attributes=$_GET['Onlinevaraus'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Onlinevaraus the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Onlinevaraus::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Onlinevaraus $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='onlinevaraus-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


protected function build_calendar($month,$year,$dateArray) {

     // Create array containing abbreviations of days of week.
     $daysOfWeek = array('Ma','Ti','Ke','To','Pe','La','Su');

     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,7,$year);

     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);

     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);

     // What is the name of the month in question?
     $monthName = $dateComponents['month'];

     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];

     // Create the table tag opener and day headers

     $calendar = "<table class='table'>";
     $calendar .= "<caption>$monthName $year</caption>";
     $calendar .= "<tr>";

     // Create the calendar headers

     foreach($daysOfWeek as $day) {
          $calendar .= "<th class='header'>$day</th>";
     } 

     // Create the rest of the calendar

     // Initiate the day counter, starting with the 1st.

     $currentDay = 1;

     $calendar .= "</tr><tr>";

     // The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.

     if ($dayOfWeek > 0) { 
          $calendar .= "<td colspan='$dayOfWeek'>&nbsp;</td>"; 
     }
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while ($currentDay <= $numberDays) {

          // Seventh column (Saturday) reached. Start a new row.

          if ($dayOfWeek == 7) {

               $dayOfWeek = 0;
               $calendar .= "</tr><tr>";

          }
          
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";

		$lopputulos = array();
		$tila = '';
		$on = true;
		$vuorot = '';
		$criteria=new CDbCriteria;
		$criteria->condition = "online_varauksen_valmina=1 ";
		$tyontekijat = Tyontekijat::model()->findAll($criteria);

		foreach($tyontekijat as $t)
		{
		$on = true;
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			pvm='".date("d.m.Y", strtotime($date))."' 
			AND tid='".$t->id."'
			AND SUBSTRING_INDEX(alku,':',1) <= '18'
		";
		$tyovuorot = Tyovuoroot::model()->findAll($criteria);

		if(!isset($tyovuorot[0]))
		{
			//$vuorot .= $t->id.'<br>';
		  	$tila = $vuorot;
			$on = true;
			break;
		}

		$a = 0;
		$l = 0;
		$sumTunti = ((float)$_SESSION['onlinevaraus']['sumTunti']*3600)+3599;

		    foreach($tyovuorot as $tv)
		    {
			$on = true;

			if($l > 0 and (strtotime($tv->alku)-$l) <= $sumTunti)
			$on = false;

			if(strtotime("18:00")-strtotime($tv->loppu) >= $sumTunti)
			$on = true;

			$a = strtotime($tv->alku);
			$l = strtotime($tv->loppu);
			//$vuorot .= $tv->alku.' '.$tv->loppu.'<br>';

			$lopputulos[$on] = $on;

		    }
		
			print_r($lopputulos);
			//$vuorot .= $lopputulos.'<br>';
		  	//$tila = $vuorot;
		}





	  if($on == true)
		 $tila .= '<b class="btn btn-success btn-block">'.$currentDay.'</b>';
	  else
		$tila .= '<b class="btn btn-warning btn-block">'.$currentDay.'</b>';


          $calendar .= "<td class='day' rel='$date'>$tila</td>";

          // Increment counters
 
          $currentDay++;
          $dayOfWeek++;

     }
     
     

     // Complete the row of the last week in month, if necessary

     if ($dayOfWeek != 7) { 
     
          $remainingDays = 7 - $dayOfWeek;
          $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>"; 

     }
     
     $calendar .= "</tr>";

     $calendar .= "</table>";

     return $calendar;

}



}

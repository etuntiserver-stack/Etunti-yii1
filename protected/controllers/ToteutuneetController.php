<?php

class ToteutuneetController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','luetutpvmtid','totpvmtid','al','yhteensapvm','deletebyajax','kk','hyvaksy'),
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

		if(isset(Yii::app()->user->adminID) and in_array('2',$tas))
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


	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	public function actionKk()
	{

		if(isset($_POST['ilman']))
		{
		  unset(Yii::app()->session['Lounastauko']);
		  unset(Yii::app()->session['MATKA']);

		  if(!empty($_POST['ilman']) and count($_POST['ilman']) > 0)
		  {
		    foreach($_POST['ilman'] as $val){
			if($val == 'Lounastauko')
			Yii::app()->session['Lounastauko'] = 10;

			if($val == 'MATKA')
			Yii::app()->session['MATKA'] = 2;
		    }
		  }
		}

		function sprint($val){
	   	    if($val > 0)
		   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
		}

		$dataProvider=new CActiveDataProvider('Toteutuneet');
		$this->render('kk',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionHyvaksy($id){

		$mob=Mobile::model()->findbypk($id);
		$tot=Toteutuneet::model()->findbypk($id);
		if($_POST['hyvaksy'] == 'kylla')
		{
		if(isset($mob->id)){
		$mob->hyvaksytty=$_POST['kuka'];
		$mob->save();
		}

		if(isset($tot->id)){
		Mobile::model()->updatebypk($tot->kid,array('hyvaksytty'=>$_POST['kuka']));
		$tot->hyvaksytty=$_POST['kuka'];
		$tot->save();
		}
		}

		if($_POST['hyvaksy'] == 'ei')
		{
		if(isset($mob->id)){
		$mob->hyvaksytty="";
		$mob->save();
		}

		if(isset($tot->id)){
		Mobile::model()->updatebypk($tot->kid,array('hyvaksytty'=>''));
		$tot->hyvaksytty="";
		$tot->save();
		}
		}
	}

	public function actionAl($str){

		$this->renderPartial('al',array(
			'str'=>$str,
		));
	}

	public function actionYhteensapvm($pvm,$tid)
	{

	   function sprint($val){
	       if($val > 0)
	   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	   }

		$this->renderPartial('yhteensapvm',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
		));
	}

	public function actionLuetutPvmTid($pvm,$tid,$from)
	{
		$this->renderPartial('luetutpvmtid',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
		));
	}

	public function actionTotPvmTid($pvm,$tid,$from)
	{

		$this->renderPartial('totpvmtid',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
		));
	}

	public function actionDeletebyajax()
	{
		Toteutuneet::model()->deletebypk($_POST['id']);

	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	   function sprint($val){
	       if($val > 0)
	   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	   }

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
		$model=new Toteutuneet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Toteutuneet']))
		{

			$k = Kohteet::model()->findbypk($_POST['Toteutuneet']['kohde_kannasta']);
			$model->attributes=$_POST['Toteutuneet'];
			$model->aloitan = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['aloitan']));
			$model->loppui = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['loppui']));
			$model->kohdenID=$k->id;			
			$model->kohde_kannasta=$k->osoite;

			if($model->save()){
			   $did = date("Ymd",strtotime($model->aloitan));
			   echo $did."_".$model->tid;


			// <-- Kirjoitetaan historia luettut tietokantaan
			$this->renderPartial('//mobile/historia',array(
			'id'=>$model->kid,
			'tilanne'=>"Toteuma",
			'uusikohde'=>$model->kohde_kannasta,
			'uusialoitus'=>$model->aloitan,
			'uusilopetus'=>$model->loppui,
			));
			// Kirjoitetaan historia luettut tietokantaan -->

			   exit;
			}

		}

		$this->renderPartial('create',array(
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

		if(isset($_POST['Toteutuneet']))
		{

			$k = Kohteet::model()->findbypk($_POST['Toteutuneet']['kohde_kannasta']);
			$model->attributes=$_POST['Toteutuneet'];
			$model->aloitan = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['aloitan']));
			$model->loppui = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['loppui']));
			$model->kohdenID=$k->id;			
			$model->kohde_kannasta=$k->osoite;


			if($model->save()){
			   $did = date("Ymd",strtotime($model->aloitan));
			   echo $did."_".$model->tid;

			// <-- Kirjoitetaan historia luettut tietokantaan
			$this->renderPartial('//mobile/historia',array(
			'id'=>$model->kid,
			'tilanne'=>"Toteuma",
			'uusikohde'=>$model->kohde_kannasta,
			'uusialoitus'=>$model->aloitan,
			'uusilopetus'=>$model->loppui,
			));
			// Kirjoitetaan historia luettut tietokantaan -->

			   exit;
			}
		}

		$this->renderPartial('update',array(
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

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		if(Yii::app()->request->getPost('tekija') == 'kaikki')
		unset(Yii::app()->session['tekija']);
		if(Yii::app()->request->getPost('tekija') and Yii::app()->request->getPost('tekija') != 'kaikki'){
		Yii::app()->session['tekija'] = Yii::app()->request->getPost('tekija');
		}

		if(Yii::app()->session['tekija'])
		   $explTekija = explode("//",Yii::app()->session['tekija']);

		if(isset($_POST['tekija']))
		{
		unset(Yii::app()->session['Lounastauko']);
		unset(Yii::app()->session['MATKA']);
		}

		if(isset($_POST['ilman']))
		{
		  foreach($_POST['ilman'] as $val){
			if($val == 'Lounastauko')
			Yii::app()->session['Lounastauko'] = 10;

			if($val == 'MATKA')
			Yii::app()->session['MATKA'] = 2;
		  }
		}

		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));

		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));

/*
       		$criteria = new CDbCriteria();

        	//$criteria->condition = " aloitan !='' and loppui !='' ";

        	$criteria->order = "DATE(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'),'%Y-%m-%d'))";
        	$criteria->group = "DATE(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'),'%Y-%m-%d'))";


		if(isset($explTekija[0]))
	        $criteria->addCondition (" tid = '".$explTekija[0]."'");

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");


		//$model = Mobile::model()->findAll($criteria);
*/
		if(isset($_POST['tulosta']))
		{

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta',array(),true));
	          $html2pdf->Output();


		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('index');
		}
		
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Toteutuneet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Toteutuneet']))
			$model->attributes=$_GET['Toteutuneet'];

		$this->render('admin',array(

			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Toteutuneet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Toteutuneet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Toteutuneet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='toteutuneet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



	protected function ilta($al,$lop){

		$totalIlta = 0;

	    if($al[0] == $lop[0])
	    {

	  	if(strtotime($al[0]." ".$al[1]) > strtotime($al[0]." 18:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 23:00"))
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 18:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 18:00"))
		{
	   	  $strAl0 = strtotime($al[0]." 18:00");
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 18:00")

		and strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 23:00"))
		{
	   	  $strAl0 = strtotime($al[0]." 18:00");
	   	  $strLop0 = strtotime($lop[0]." 23:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 18:00")
		and strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 23:00"))
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." 23:00");

	 	  $str = ($strLop0-$strAl0);

	      	  $totalIlta += $str;
		}

	    }

		return $totalIlta;
	}



	protected function tyoIlta($tid,$pvm)
	{

		$totalIlta 	= 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND status = '3'
		";


		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."' ");

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND status = '3'
		";


		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);

		}


		return $totalIlta;

	}


}

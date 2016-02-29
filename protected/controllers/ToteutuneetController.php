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

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
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
		$model = Toteutuneet::model()->findbypk($_POST['id']);
		Toteutuneet::model()->deletebypk($_POST['id']);

		$m = Mobile::model()->findbypk($model->kid);
		Mobile::model()->updatebypk($m->id, array('tietoja'=>$m->tietoja."\n".Yii::app()->user->nimi." (".date("d.m.Y H:i")."):\nMuutokset poistettu"));
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

		$tekija = '';
		if(isset($explTekija[1]))
		$tekija = $explTekija[1];

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

		$from = '';
		$to = '';
		if(isset(Yii::app()->session['from']))
		$from = Yii::app()->session['from'];

		if(isset(Yii::app()->session['to']))
		$to = Yii::app()->session['to'];

		if(isset($_POST['tulosta']))
		{

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta',array('from'=>$from,'to'=>$to,'tekija'=>$tekija),true));
	          $html2pdf->Output();


		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('index',array('from'=>$from,'to'=>$to));
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


	protected function tyoIlta($tid,$pvm,$tila)
	{

		$total 	= 0;
		$mobile = Yii::app()->createController('Mobile');

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND (status = '3' OR status = '2')
		";


	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."' ");

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    if($tila == 'ilta')
		    $total += $mobile[0]->ilta($al,$lop);
		    elseif($tila == 'yo')
		    $total += $mobile[0]->yo($al,$lop);
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND (status = '3' OR status = '2')
		";

	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    if($tila == 'ilta')
		    $total += $mobile[0]->ilta($al,$lop);
		    elseif($tila == 'yo')
		    $total += $mobile[0]->yo($al,$lop);

		}


		return $total;

	}




	protected function totLuYhteensa($criteria,$tid,$week,$year,$tila){


        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND tid='".$tid."'
			AND YEARWEEK(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')) = '".$year.$week."'
		";

		if($tila == 'luetut')
	        $criteria->addCondition (" admin!='1' ");

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");

		return $criteria;
	}


	protected function yhtLuWeek($tid,$week,$year){


       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,$tid,$week,$year,'luetut');
		$l = Mobile::model()->find($cr1);

		$lu = $l->l_tunnit;

	return $lu;

	}

	protected function yhtTOtWeek($tid,$week,$year){

       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,$tid,$week,$year,'toteutuneet');
		$cr1->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
		$tt = Mobile::model()->find($cr1);

       		$cr2 = new CDbCriteria();
		$this->totLuYhteensa($cr2,$tid,$week,$year,'toteutuneet');
		$tt2 = Toteutuneet::model()->find($cr2);
		
		$tot = $tt->l_tunnit+$tt2->l_tunnit;

	return $tot;

	}



	protected function viikkonLoppu($date,$tid,$yhtMatkaWeek,$yhtIltaWeek,$viikkoBreak){

	    if(date('N', strtotime($date)) == 7)
	    {
  	    echo '<tr>';
  		echo '<td style="background: #669999;color: white" class="text-center viikkoRivi small myBgColors"><b>'.Yii::t('main', 'Viikko').' '.date("W",strtotime($date)).'</b></td>';

		 $vktyoaika = '';
		 $ts = Tyosuhdet::model()->find(" tid = '".$tid."' ");
		 if(isset($ts->id) and !empty($ts['vktyoaika']))
		  $vktyoaika = $ts['vktyoaika'];

		  echo '<td style="background: #669999;color: white; text-align:center" class="viikkoRivi small myBgColors" id="vk_'.date("W",strtotime($date)).'_'.$tid.'">';
		  $kokoViikko = '';
		  $vko = '';
		  $vko = date("W",strtotime($date));
		  $year = date("Y",strtotime($date));
		  $kokoViikko = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$tid,'viikko'=>$vko,'year'=>$year),true);

		  $cl = '';
		  if(	(int)str_replace(":","",$kokoViikko) > (int)str_replace(":","",$vktyoaika)
			and (int)str_replace(":","",$kokoViikko) > 0
			and (int)str_replace(":","",$vktyoaika) > 0
		  )
		  $cl = 'class="btn btn-xs btn-danger"';

		  echo '<span '.$cl.'>'.$kokoViikko. '<br>('.$vktyoaika.')</span>';

		  echo '</td>';
		
		  $totalLu = 0;
		  $totalLu = $this->yhtLuWeek($tid,$vko,$year);
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($totalLu).'<br>('.$this->num($totalLu).')</td>';
		  $totalTot = '';
		  $totalTot = $this->yhtTOtWeek($tid,$vko,$year);
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($totalTot).'<br>('.$this->num($totalTot).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors"></td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtMatkaWeek).'<br>('.$this->num($yhtMatkaWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtIltaWeek).'<br>('.$this->num($yhtIltaWeek).')</td>';

	    echo '</tr>';
	    $viikkoBreak = true;
	    }

	}


	protected function sairausMerkki($val)
	{
	     $spl = '';
	  if($val == '1')
	     $spl = '<span style="color:red" class="small"> (SPL)</span>';
	  elseif($val == '2')
	     $spl = '<span style="color:red" class="small"> (SL)</span>';
	  elseif($val == '3')
	     $spl = '<span style="color:red" class="small"> (LS)</span>';
	
	  return $spl;
	}



}

<?php

class AsiakkaatController extends Controller
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
				'actions'=>array('login'),
				'users'=>array('*'),
			),
			array('allow', 
				'actions'=>array('asiakas_tila', 'ulos'),
                		'expression'=>"Yii::app()->controller->isAsiakas()",
			),
			array('allow',
				'actions'=>array('admin', 'delete', 'create', 'update', 'index','view', 'checkLastAsiakasID', 'showshift'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function isAsiakas() 
	{
		if(isset(Yii::app()->user->asiakas))
		{
		$m = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
	        if($m->id == Yii::app()->user->asiakas)
	            return true;
		} else {
	            return false;
		}
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
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();
        }

	public function actionUlos()
	{
		$dm = Yii::app()->user->domain;
		Yii::app()->user->logout();
		   $this->redirect(array('login','domain'=>$dm));
	}

	public function actionLogin($domain)
	{
		Yii::app()->theme = 'customer';
		$dm=Domainit::model()->find(" domain='".$domain."' ");
		if(!isset($dm->id))
		exit;
		else
		Yii::app()->user->setState('domain', $dm->domain);

		if(isset($_POST['sahkoposti']) and isset($_POST['salasana']))
		{
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				sahkoposti='".$_POST['sahkoposti']."' 
				AND salasana='".md5($_POST['salasana'])."'
				AND salasana!=''
			";
			$model=Asiakkaat::model()->find($criteria);
			if(isset($model->id))
			{

			    	if(isset($dm->paketti))
			    	Yii::app()->user->setState('adminPaketti', $dm->paketti);

				Yii::app()->user->setState('asiakas', $model->id);
				$this->redirect(array('asiakas_tila','id'=>$model->id));
			}
		}


		$this->render('login', array('dm'=>$dm));
	}

	public function actionAsiakas_tila($id)
	{

	        if($id == Yii::app()->user->asiakas)
		{
			Yii::app()->theme = 'customer';
			$model=$this->loadModel($id);

		if(isset($_POST['Asiakkaat']))
		{
			$model->attributes=$_POST['Asiakkaat'];

			if($model->salasana != md5($_POST['Asiakkaat']['salasana']))
			$model->salasana = md5($_POST['Asiakkaat']['salasana']);

			if($model->save())
				$this->redirect(array('asiakas_tila','id'=>$model->id));
		}


			$this->render('update', array('model'=>$model));

		} else {
	        	return false;
		}


	}

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	public function actionShowshift($id)
	{

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') ASC ";
/*
        	$criteria->condition = "DATE(paivays) BETWEEN 
			asiakas_id='".$id."'
		";
*/
		$from = date("Y-m-d");
		$to = date("Y-m-d", strtotime("+1 month"));

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}


        	$criteria->addCondition ("
			kohde IN 
			(SELECT id FROM sivex_kohdet 
			   WHERE asiakas_id='".$id."'
			)
		AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		");


		$model=Tyovuoroot::model()->findAll($criteria);

		if(isset($_POST['tulosta'])){

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('showshift', 
			array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
			'id'=>$id,
			),true));
	          $html2pdf->Output();

		} else {

		$this->render('showshift',array(
			'model'=>$model,
			'from'=>$from,
			'to'=>$to,
			'id'=>$id,
		));

		}
	}

	public function actionCheckLastAsiakasID()
	{
		$check = 0;
		$model=Asiakkaat::model()->find(" asiakasnumero='".$_POST['checkLastAsiakasID']."' ");
		if(isset($model->id))
		$check = 1;

		echo $check;
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

	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_1_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=new Asiakkaat;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Asiakkaat']))
		{
			$model->attributes=$_POST['Asiakkaat'];

			if($model->salasana != md5($_POST['Asiakkaat']['salasana']))
			$model->salasana = md5($_POST['Asiakkaat']['salasana']);

			if($model->save())
			{
				if(empty($model->asiakasnumero))
				$a = Asiakkaat::model()->updatebypk($model->id, array('asiakasnumero'=>$model->id));

				$this->redirect(array('update','id'=>$model->id));
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

	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Asiakkaat']))
		{
			$model->attributes=$_POST['Asiakkaat'];

			if($model->salasana != md5($_POST['Asiakkaat']['salasana']))
			$model->salasana = md5($_POST['Asiakkaat']['salasana']);

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

	// <-- Oikeudet
	   $checkOikeus = "asiakkaat_3_".Yii::app()->user->adminStatus;
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
	   $checkOikeus = "asiakkaat_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";

		if(isset($_POST['osoite']) and !empty($_POST['osoite']))
	        $criteria->addCondition (" osoite LIKE '%".$_POST['osoite']."%' ");

		if(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] != 'kaikki')
	        $criteria->addCondition (" aktiivinen ='".(int)$_POST['aktiivinen']."' ");
		elseif(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] == 'kaikki')
	        $criteria->addCondition (" (aktiivinen=1 OR aktiivinen=0) ");
		else
	        $criteria->addCondition (" aktiivinen=1 ");

		if(isset($_POST['yrityksen_nimi']) and !empty(trim($_POST['yrityksen_nimi'])))
	        $criteria->addCondition (" yrityksen_nimi LIKE '%".$_POST['yrityksen_nimi']."%' ");

		if(isset($_POST['yhteyshenkilo']) and !empty(trim($_POST['yhteyshenkilo'])))
	        $criteria->addCondition (" yhteyshenkilo LIKE '%".$_POST['yhteyshenkilo']."%' ");

		if(isset($_POST['puhelin']) and !empty(trim($_POST['puhelin'])))
	        $criteria->addCondition (" puhelin LIKE '%".$_POST['puhelin']."%' ");

		if(isset($_POST['sahkoposti']) and !empty(trim($_POST['sahkoposti'])))
	        $criteria->addCondition (" sahkoposti LIKE '%".$_POST['sahkoposti']."%' ");

		$dataProvider=new CActiveDataProvider('Asiakkaat', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Asiakkaat('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Asiakkaat']))
			$model->attributes=$_GET['Asiakkaat'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Asiakkaat the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Asiakkaat::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Asiakkaat $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='asiakkaat-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


    	protected function asiakasMuutosTheme($as)
	{ 
		$return = '';

		    $a = Asiakkaat::model()->findbypk($as);
		    if(isset($a->yrityksen_nimi) and !empty($a->yrityksen_nimi))
		    $return = $a->yrityksen_nimi;
		    elseif(isset($a->yhteyshenkilo) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
		    $return = $a->yhteyshenkilo;
		    else
		    $return = $as;

		    if(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'henkilo')
		    $return = '<b class="text-warning">Yhteyshenkilö</b><br>'.$return;
		    elseif(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'yritys')
		    $return = '<b class="text-success">Yritys</b><br>'.$return;

            	return $return;
	}

	protected function tas($tasnro)
	{
		if(isset(Yii::app()->user->adminPaketti))
		$tas = explode(",",Yii::app()->user->adminPaketti);
		if(isset(Yii::app()->user->adminID) and in_array($tasnro,$tas))
		return true;
		else
		return false;
	}

	protected function tarjouksetCRM($model)
	{

		$criteria=new CDbCriteria;
		$criteria->order = " DATE(time) DESC ";
		$criteria->condition = " asiakas_id='".$model->id."' ";
		$tar = CrmTarjoukset::model()->findAll($criteria);
		$bod = '';

		if(isset($tar[0])){

		$bod .= '<table class="table table-bordered">
		 <tr>
		  <th>'.Yii::t('main', 'Päiväys').'</th>
		  <th>'.Yii::t('main', 'Tiedostot').'</th>
		  <th>'.Yii::t('main', 'Tila').'</th>
		 </tr>';
	

		foreach($tar as $data)
		{
	
			$f = '';
			if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".docx"))
		 	$f .= '<a href="../../tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$data->liite.'.docx">'.$data->liite.'.docx</a>';
			$f .= '<br>';
			if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
			$f .= '<a href="../../tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$data->liite.'.pdf">'.$data->liite.'.pdf</a>';


			$s = '';
			if($data->status == 0 and
	  		(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
			)
			{
				$s .= '<button class="btn btn-primary btn-block laheta" for="'.$data->id.'">'.Yii::t('main', 'odotta lähetystä').'</button>';
			} elseif($data->status == 1){
				$s .= '<button class="btn btn-warning btn-block">'.Yii::t('main', 'Lähetetty').'</button>';
			} elseif($data->status == 2){
				$s .= '<button class="btn btn-success btn-block">'.Yii::t('main', 'Hyväksytty').'</button>';
			} elseif($data->status == 3){
				$s .= '<button class="btn btn-danger btn-block">'.Yii::t('main', 'Hylätty').'</button>';
			}

	  	$bod .= '
		<tr>
			<td>'.date("d.m.Y", strtotime($data->time)).'</td>
			<td>'.$f.'</td>
			<td>'.$s.'</td>
		</tr>';
	  	}


		$bod .= '</table>';
		}
	
		return $bod;
	}


	protected function laskutuksetCRM($model)
	{

	   $lasku = Yii::app()->createController('Lasku');
	   

		$criteria=new CDbCriteria;
		$criteria->order = " DATE(paivays) DESC ";
		$criteria->condition = " as_nro='".$model->asiakasnumero."' ";
		$tar = Lasku::model()->findAll($criteria);
		$bod = '';

		if(isset($tar[0])){

		$bod .= '<table class="table table-bordered">
		 <tr>
		  <th>'.Yii::t('main', 'Päiväys').'</th>
		  <th>'.Yii::t('main', 'Tiedosto').'</th>
		  <th>'.Yii::t('main', 'Tilanne').'</th>
		  <th>'.Yii::t('main', 'Yhteensä').'</th>
		 </tr>';
	
		foreach($tar as $data)
		{
	  	$bod .= '
		<tr>
			<td>'.date("d.m.Y", strtotime($data->paivays)).'</td>
			<td>'.CHtml::link('PDF', array('//lasku/lasku_pdf', 'id'=>$data->id), array('target'=>'_blank')).'</td>
			<td>'.$lasku[0]->tilanneCheck($data).'</td>
			<td>'.number_format($data->yhteensa_total, 2, ",", " ").'</td>
		</tr>';
	  	}
		$bod .= '</table>';
		}
	
		return $bod;
	}


	protected function tyovuorotCRM($model)
	{

		$criteria=new CDbCriteria;
		$criteria->order = " DATE(pvm) DESC ";
		$criteria->condition = " 
			kohde IN
			(
				SELECT id FROM sivex_kohdet
				WHERE id IN(SELECT id FROM asiakkaat WHERE id='".$model->id."')
			) 
		";
		$tar = Tyovuoroot::model()->findAll($criteria);
		$bod = '';

		if(isset($tar[0])){

		$bod .= '<table class="table table-bordered">
		 <tr>
		  <th>'.Yii::t('main', 'Päiväys').'</th>
		  <th>'.Yii::t('main', 'Osoite').'</th>
		  <th>'.Yii::t('main', 'Aloitus').'</th>
		 </tr>';
	
		foreach($tar as $data)
		{
		$k = Kohteet::model()->findbypk($data->kohde);
	  	$bod .= '
		<tr>
			<td>'.date("d.m.Y", strtotime($data->pvm)).'</td>
			<td>'.$k->osoite.'</td>
			<td>'.$data->alku.'</td>
		</tr>';
	  	}
		$bod .= '</table>';
		}
	
		return $bod;
	}

}

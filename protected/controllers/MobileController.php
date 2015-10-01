<?php


class MobileController extends Controller
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
				'actions'=>array('admin', 'delete', 'create', 'update', 'index', 'index_a', 'view', 'updatetime', 'showkohteet', 'yhteenveto', 'kyhteenveto', 'yhteenveto_m', 'historia', 'poistaKohde', 'total_suunniteltu', 'total_toteutu', 'total_luettu', 'kesto', 'index_ajax', 'raportit', 'palkkataulukko', 'tidfromtomatkat'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

	public function actionRaportit()
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		function allSess(){


			if(Yii::app()->request->getPost('kohteet') == 'kaikki')
			unset(Yii::app()->session['kohteet']);
			if(Yii::app()->request->getPost('kohteet') and Yii::app()->request->getPost('kohteet') != 'kaikki'){
			Yii::app()->session['kohteet'] = Yii::app()->request->getPost('kohteet');
			}

			if(Yii::app()->request->getPost('from'))
			Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));
	
			if(Yii::app()->request->getPost('to'))
			Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));

			if(isset($_POST['ilman']))
			{
			  foreach($_POST['ilman'] as $val){
				if($val == 'Lounastauko')
				Yii::app()->session['Lounastauko'] = 10;
	
				if($val == 'MATKA')
				Yii::app()->session['MATKA'] = 2;
			  }
			}

		}


		function allCrit($criteria){


			if(Yii::app()->request->getPost('tekija') != 'kaikki')
	        	$criteria->addCondition (" tid = '".Yii::app()->request->getPost('tekija')."'");

			if(isset(Yii::app()->session['kohteet']) and Yii::app()->session['kohteet'] != 'kaikki')
	        	$criteria->addCondition (" kohde_kannasta = '".Yii::app()->session['kohteet']."'");

			if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        	$criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

			if(Yii::app()->session['Lounastauko'])
			$criteria->addCondition (" status != '10' ");
		
			if(Yii::app()->session['MATKA'])
			$criteria->addCondition (" status != '2' ");
		}



		if(Yii::app()->request->getPost('method'))
		{

		  unset(Yii::app()->session['Lounastauko']);
		  unset(Yii::app()->session['MATKA']);

		// <-- Luetut
		  if(Yii::app()->request->getPost('method') == 'luetut')
		  {

			allSess();

		       	$criteria = new CDbCriteria();
			$criteria->select = " aloitan,loppui,tekijan_nimi,kohde_kannasta ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
			$criteria->condition = " aloitan!='' and loppui!='' ";

			allCrit($criteria);

			$model = Mobile::model()->findAll($criteria); 
	
		        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('raportit_pdf_l', array('model' => $model),true));
		        $html2pdf->Output();

		  }
		//  Luetut -->

		// <-- Toteutuneet
		  if(Yii::app()->request->getPost('method') == 'toteutuneet')
		  {

			allSess();

			/* lu */
		       	$criteria = new CDbCriteria();
			$criteria->select = " aloitan,loppui,tekijan_nimi,kohde_kannasta ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
			$criteria->condition = " aloitan!='' and loppui!='' AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";

			allCrit($criteria);

			$lu = Mobile::model()->findAll($criteria); 


			/* tot */
		       	$criteria = new CDbCriteria();
			$criteria->select = " aloitan,loppui,tekijan_nimi,kohde_kannasta ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
			$criteria->condition = " aloitan!='' and loppui!='' AND id NOT IN (SELECT kid FROM sivexkuitti) ";

			allCrit($criteria);

			$tot = Toteutuneet::model()->findAll($criteria); 
			$model = array_merge($lu, $tot);

		        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('raportit_pdf_t', array('model' => $model),true));
		        $html2pdf->Output();

		  }
		//  Toteutuneet -->


		} else {

			$this->render('raportit');

		}

	}

	public function actionTotal_suunniteltu($id,$kohde_tid)
	{

		$this->renderPartial('suunniteltu',array(
			'id'=>$id,
			'kohde_tid'=>$kohde_tid,
		));

	}

	public function actionTotal_luettu($tid)
	{

		$this->renderPartial('total_luettu',array(
			'tid'=>$tid,
		));

	}

	public function actionTotal_toteutu($tid)
	{

		$this->renderPartial('total_toteutu',array(
			'tid'=>$tid,
		));

	}

	public function actionIndex_ajax()
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}


		$model = Mobile::model()->find("id!='' order by id DESC");
		if(isset($_POST['setRivi']))
		$this->renderPartial('_view', array('data' => $model));
		else
		echo $model->id;

	}

	public function actionKesto($id)
	{
	
	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		$k = '';
		$model = $this->loadModel($id);
		$k = strtotime($model->loppui)-strtotime($model->aloitan);
		echo sprint($k);
	}

	public function actionHistoria($id,$tilanne,$uusikohde,$uusialoitus,$uusilopetus)
	{

		$this->renderPartial('historia',array(
			'id'=>$id,
			'tilanne'=>$tilanne,
			'uusikohde'=>$uusikohde,
			'uusialoitus'=>$uusialoitus,
			'uusilopetus'=>$uusilopetus,
		));
	}

	public function actionUpdatetime()
	{

		$model = $this->loadModel($_POST['id']);

 		$newdate = date("d.m.Y H:i:s",strtotime($_POST['value']));
		$model->$_POST['request']=$newdate;
		$model->status=$_POST['status'];

		if($model->save()){

			// <-- Kirjoitetaan historia luettut tietokantaan
			$this->renderPartial('//mobile/historia',array(
			'id'=>$model->id,
			'tilanne'=>"Luetut ".$_POST['request'],
			'uusikohde'=>$model->kohde_kannasta,
			'uusialoitus'=>$model->aloitan,
			'uusilopetus'=>$model->loppui,
			));
			// Kirjoitetaan historia luettut tietokantaan -->
			echo $_POST['request']."//".date("H:i",strtotime($model->aloitan))."//".date("H:i",strtotime($model->loppui));
		}

	}

	public function actionShowkohteet()
	{
		$as=new Kohteet;
		echo   CHtml::activeDropDownList($as, 'id',
		CHtml::listData(Kohteet::model()->findAll(array("order"=>"osoite")), 'id', 'osoite'),   
		    array('empty'=>'Muokka', "class"=>"kohdenvaihto btn btn-default") 
		);

		?>
		<input type="hidden" id="sainkohdenID" value="<?php echo $_POST['thisID']; ?>">
		<script type="text/javascript">
		$(document).ready(function(){

		  $("#Kohteet_id").on('change',function(){

			var kohdenID = $("#sainkohdenID").val().split("_");
			var thisText = $(this).find("option:selected").text();
			var thisVal = $(this).val();

   			var Mobile = {fromMob: "true",kohdenID: thisVal,kohde_kannasta: thisText};

		        $.ajax({
		           url: "update?id="+kohdenID[1],
		           type: "POST",
		           data: Mobile,
		           success: function(html){
				$("#vaihto_kohttisID_"+kohdenID[1]).addClass("text-success").text(thisText);
				//alert(thisText)
		           }
		        });
		
		  });

		});
		</script>
		<?php
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
		$model=new Mobile;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Mobile']))
		{
			$model->attributes=$_POST['Mobile'];
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
	//print_r($_POST);
	//exit;

		$model=$this->loadModel($id);
		$vanha_kohde_kannasta = $model->kohde_kannasta;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['fromMob'])){
			$_POST['Mobile']=$_POST;
		}

		if(isset($_POST['Mobile']))
		{
			$model->attributes=$_POST['Mobile'];

			if(!empty($model->tietoja)) 
			  $tietoja = $model->tietoja."\n"; 
			else 
			  $tietoja = "<perus>".$vanha_kohde_kannasta."//".$model->aloitan."//".$model->loppui."</perus>";

			if(isset($_POST['Mobile']['kohde_kannasta']))
			$model->tietoja=$tietoja.Yii::app()->user->nimi." (".date("d.m.Y H:i")."):\nTilanne-Kohteen muutos, vanha-".$vanha_kohde_kannasta.", uusi-".$_POST['Mobile']['kohde_kannasta'];

			$model->save();
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionPoistaKohde()
	{
		$this->loadModel($_POST['id'])->delete();
		Toteutuneet::model()->deleteAll(" kid='".$_POST['id']."' ");
	}


	public function actionIndex()
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}


		if(Yii::app()->request->getPost('etsi_tekijan_nimi') == 'kaikki')
		unset(Yii::app()->session['etsi_tekijan_nimi']);
		if(Yii::app()->request->getPost('etsi_tekijan_nimi') and Yii::app()->request->getPost('etsi_tekijan_nimi') != 'kaikki'){
		Yii::app()->session['etsi_tekijan_nimi'] = Yii::app()->request->getPost('etsi_tekijan_nimi');
		}

		if(Yii::app()->request->getPost('etsi_kohteet') == 'kaikki')
		unset(Yii::app()->session['etsi_kohteet']);
		if(Yii::app()->request->getPost('etsi_kohteet') and Yii::app()->request->getPost('etsi_kohteet') != 'kaikki'){
		Yii::app()->session['etsi_kohteet'] = Yii::app()->request->getPost('etsi_kohteet');
		}

		if(isset($_POST['etsi_pvm']) and empty($_POST['etsi_pvm']))
		unset(Yii::app()->session['etsi_pvm']);

		if(Yii::app()->request->getPost('etsi_pvm')){
		Yii::app()->session['etsi_pvm'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('etsi_pvm')));
		}

       		$criteria = new CDbCriteria();
/*
$criteria->order =
"    	  case 
            when loppui='' then 1
            when aloitan  then 2
	  else 100 
    	  end  DESC
";
time <= date_sub(NOW(), interval 3 hour) AND status IN (1,2,10) AND loppui='' DESC, 
*/
        	$criteria->order = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') < DATE_ADD(NOW(), interval 4 hour) AND status IN (1,2,10) AND loppui='' DESC, 
		time and status IN (1,2,10) AND loppui='' DESC, 
		time DESC ";

		if(Yii::app()->session['etsi_tekijan_nimi'])
	        $criteria->addCondition (" tekijan_nimi = '".Yii::app()->session['etsi_tekijan_nimi']."' ");
		if(Yii::app()->session['etsi_kohteet'])
	        $criteria->addCondition ("kohde_kannasta = '".Yii::app()->session['etsi_kohteet']."'");
		if(Yii::app()->session['etsi_pvm'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".Yii::app()->session['etsi_pvm']."' ");

		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;

		if(isset($_POST['index_ajax']))
		$this->renderPartial('index_a', array('dataProvider' => $dataProvider));
		else
		$this->render('index', array('dataProvider' => $dataProvider));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Mobile('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Mobile']))
			$model->attributes=$_GET['Mobile'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Mobile the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Mobile::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Mobile $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mobile-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



	protected function ilta($al,$lop){

		$totalIlta = 0;

	    if($al[0] == $lop[0])
	    {

	  	if(strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 18:00")
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


	protected function yo($al,$lop){

		$totalYo = 0;


	 	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 06:00") 
		and strtotime($lop[0]." ".$lop[1]) > strtotime($lop[0]." 06:00"))
		{

		   if($al[0] == $lop[0])
		   {


		   $strAl = strtotime($al[0]." ".$al[1]);
		   $strLop = strtotime($lop[0]." 06:00");
		   }

		   $str = ($strLop-$strAl);
		   $totalYo += $str;
	  	} 

	 	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 23:00") 
		and $lop[0] != $al[0])
		{
		   $str = 3600;
		   $totalYo += $str;
	  	} 

	 	if(strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 23:00") 
		and strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 00:00"))
		{

		   $strAl = strtotime($al[0]." ".$al[1]);
		   $strLop = strtotime($al[0]." 00:00");

		   $str = ($strLop-$strAl);
		   $totalYo += $str;

		}

	 	if(strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 00:00") 
		and $lop[0] != $al[0])
		{

		   $strAl = strtotime($lop[0]." 00:00");
		   $strLop = strtotime($lop[0]." ".$lop[1]);

		   $str = ($strLop-$strAl);
		   $totalYo += $str;
	  	}   


		if($totalYo > 0)
		return $totalYo;
	}

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}


	public function actionTidfromtomatkat($from,$to,$tid)
	{
		$this->renderPartial('palkkataulukko', array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$tid
		));
	}


	public function actionPalkkataulukko()
	{


		//unset(Yii::app()->session['Tekija']);
		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));

		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));
		

       		$criteria = new CDbCriteria();
        	$criteria->order = " tekijan_nimi "; //SUBSTR(LTRIM(tekijan_nimi), LOCATE(' ',LTRIM(tekijan_nimi)))
        	$criteria->condition = " aktiivinen=1 "; 

		if(Yii::app()->request->getPost('Tekija')){
		  if(count(Yii::app()->request->getPost('Tekija')) > 1)
		    $ids = implode(",",Yii::app()->request->getPost('Tekija'));
		  else
		    $ids = Yii::app()->request->getPost('Tekija')[0];

	        $criteria->addCondition ('id IN ('.$ids.') ');
		}


		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			'pagination'=>false
		));

		  $model = Tyontekijat::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_palkkataulukko', array('model' => $model),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('palkkataulukko', array('model' => $model));
		}
	}

	public function actionYhteenveto()
	{


		//unset(Yii::app()->session['Tekija']);
		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

		if(isset($_POST['yhtvetoform']))
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
		

       		$criteria = new CDbCriteria();
        	$criteria->select = "

		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as l_tunnit,

		t.*";

        	//$criteria->condition = " l_loppu = '' and l_alku = '' ";

        	$criteria->order = "tekijan_nimi"; //"SUBSTR(LTRIM(tekijan_nimi), LOCATE(' ',LTRIM(tekijan_nimi)))"
        	$criteria->group = 'tid';

		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

	        $criteria->addCondition ('tid IN ('.$ids.') ');
		}

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			'pagination'=>false
		));

		  $model = Mobile::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_yhteenveto', array('model' => $model),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('yhteenveto', array('model' => $model));
		}
	}


	public function actionYhteenveto_m()
	{


		//unset(Yii::app()->session['Tekija']);
		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

		if(isset($_POST['yhtvetoform']))
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
		

       		$criteria = new CDbCriteria();
        	$criteria->select = "

		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as l_tunnit,

		t.*";

        	//$criteria->condition = " l_loppu = '' and l_alku = '' ";

        	$criteria->order = "tekijan_nimi"; //"SUBSTR(LTRIM(tekijan_nimi), LOCATE(' ',LTRIM(tekijan_nimi)))"
        	$criteria->group = 'tid';
	        $criteria->condition = " status = '2' ";

		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

	        $criteria->addCondition ('tid IN ('.$ids.') ');
		}


		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");


		  $model = Mobile::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_yhteenveto_m', array('model' => $model),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('yhteenveto_m', array('model' => $model));
		}
	}



	public function actionKyhteenveto()
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}


		if(Yii::app()->request->getPost('kohteet') == 'kaikki')
		unset(Yii::app()->session['kohteet']);

		if(Yii::app()->request->getPost('kohteet') and Yii::app()->request->getPost('kohteet') != 'kaikki')
		Yii::app()->session['kohteet'] = Yii::app()->request->getPost('kohteet');

		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));

		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));
		
		if(Yii::app()->request->getPost('mitkatKohteet'))
		Yii::app()->session['mitkatKohteet'] = Yii::app()->request->getPost('mitkatKohteet');

       		$criteria = new CDbCriteria();
/*
        	$criteria->select = "

		COUNT(*) as kpl,
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as l_tunnit,


		t.*";
*/
		if(isset(Yii::app()->session['mitkatKohteet']))
			$mitkatKohteet = Yii::app()->request->getPost('mitkatKohteet');
		else
			$mitkatKohteet = "kohdenID";

		$criteria->select = "kohdenID,kohde_kannasta";

		if($mitkatKohteet == 'kohdenID')
        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND $mitkatKohteet !='' 
		";
        	$criteria->order = $mitkatKohteet;
        	$criteria->group = $mitkatKohteet;

		if(Yii::app()->session['kohteet'])
	        $criteria->addCondition (" kohde_kannasta like '%".Yii::app()->session['kohteet']."%' ");



		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			'pagination'=>false
		));

		//$dataProvider->pagination->pageSize = 50;

		if(Yii::app()->request->getPost('tulosta'))
		{
		  $model = Mobile::model()->findAll($criteria);
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_kyhteenveto', array('model' => $model),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('kyhteenveto', array('dataProvider' => $dataProvider));
		}
	}



}

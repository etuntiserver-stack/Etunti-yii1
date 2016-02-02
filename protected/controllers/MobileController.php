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
				'actions'=>array('admin', 'delete', 'create', 'update', 'index', 'index_a', 'view', 'updatetime', 'showkohteet', 'yhteenveto', 'kyhteenveto', 'yhteenveto_m', 'historia', 'poistaKohde', 'total_suunniteltu', 'total_toteutu', 'total_luettu', 'kesto', 'index_ajax', 'raportit', 'uusirivi', 'palkkataulukko', 'tidfromtomatkat', 'tidfromtoSL', 'tidfromtoSPL', 'tyobykohde', 'asiakas_hyvaksyminen', 'kohdebytekija' ,'kyhteenveto_tuntemattomat', 'laskutettu'),
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

	public function actionRaportit()
	{

function num($val){
    if($val > 0)
	return  number_format((float)$val/3600, 2, '.', '');
}

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
			$criteria->select = " aloitan,loppui,tekijan_nimi,kohde_kannasta,viesti ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " 
				aloitan!='' and loppui!='' 
				AND admin!='1'
			";

			allCrit($criteria);

			$model = Mobile::model()->findAll($criteria); 
	
		        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Toteutuneet'),true));
		        $html2pdf->Output();

		  }
		//  Luetut -->

		// <-- Toteutuneet
		  if(Yii::app()->request->getPost('method') == 'toteutuneet')
		  {

			allSess();

			/* lu */
		       	$criteria = new CDbCriteria();
			$criteria->select = " aloitan,loppui,tekijan_nimi,kohde_kannasta,viesti ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " aloitan!='' and loppui!='' AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";

			allCrit($criteria);

			$lu = Mobile::model()->findAll($criteria); 


			/* tot */
		       	$criteria = new CDbCriteria();
			$criteria->select = " aloitan,loppui,tekijan_nimi,kohde_kannasta ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " aloitan!='' and loppui!='' AND id NOT IN (SELECT kid FROM sivexkuitti) ";

			allCrit($criteria);

			$tot = Toteutuneet::model()->findAll($criteria); 
			$model = array_merge($lu, $tot);

		        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Luetut'),true));
		        $html2pdf->Output();

		  }
		//  Toteutuneet -->


		} else {

			$this->render('raportit');

		}

	}


	public function actionTotal_suunniteltu($id,$kohde_tid,$from,$to)
	{

		$this->renderPartial('suunniteltu',array(
			'id'=>$id,
			'kohde_tid'=>$kohde_tid,
			'from'=>$from,
			'to'=>$to,
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

		  $model->loppui = date("d.m.Y H:i",strtotime($model->loppui));
		  $model->aloitan = date("d.m.Y H:i",strtotime($model->aloitan));

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
				//console.log(html);
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


	public function actionUusirivi()
	{
		$model=new Mobile;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Mobile']))
		{
			$k = Kohteet::model()->findbypk($_POST['Mobile']['kohdenID']);
			$model->attributes=$_POST['Mobile'];
			$model->time = date("Y-m-d H:i:s",strtotime($_POST['Mobile']['aloitan']));
			$model->kohde_kannasta=$k->osoite;
			$model->admin=1;

			if($model->save()){
			   $did = date("Ymd",strtotime($model->aloitan));
			   echo $did."_".$model->tid;
			   exit;
			}

		}

		$this->renderPartial('_uusirivi',array(
			'model'=>$model,
		));
	
	}

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

			if(!$model->save())
			{
			   var_dump($model->getErrors());
			   exit;
			}

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




	public function actionLaskutettu()
	{

		if(isset($_POST['ajax']) and isset($_POST['id']))
		{
 			if(isset($_POST['tot']) and $_POST['tot'] == '1')
			  Toteutuneet::model()->updatebypk($_POST['id'], array('laskutettu'=>$_POST['las']));
			else
			  Mobile::model()->updatebypk($_POST['id'], array('laskutettu'=>$_POST['las']));

			  echo $_POST['id']." ".$_POST['las'];
			  exit;
		}


       		$criteria = new CDbCriteria();
        	$criteria->order = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < DATE_ADD(NOW(), interval 4 hour) AND status IN (1,2,10) AND loppui='' DESC, 
		time and status IN (1,2,10) AND loppui='' DESC, 
		time DESC ";

	        $criteria->condition = " admin!=1 AND status=3 ";

		if(isset($_POST['tekijaPaaSivulla']) and !empty($_POST['tekijaPaaSivulla']))
	        $criteria->addCondition (" tid = '".$_POST['tekijaPaaSivulla']."' ");

		if(isset($_POST['etsi_kohteet']) and !empty($_POST['etsi_kohteet']))
	        $criteria->addCondition (" kohde_kannasta LIKE '%".$_POST['etsi_kohteet']."%' ");

		if(isset($_POST['fromP']) and isset($_POST['toP']) and !empty($_POST['fromP']) and !empty($_POST['toP']))
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$_POST['fromP']."' AND '".$_POST['toP']."' ");

		if(isset($_POST['laskutettu']) and $_POST['laskutettu'] == '1')
	        $criteria->addCondition (" laskutettu = '1' ");

		if(isset($_POST['laskutettu']) and $_POST['laskutettu'] == '0')
	        $criteria->addCondition (" laskutettu = '0' ");


		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;

		$this->render('laskutettu', array('dataProvider' => $dataProvider));
	}



	public function actionIndex()
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}


		if(Yii::app()->request->getPost('tekijaPaaSivulla') == 'kaikki')
		unset(Yii::app()->session['tekijaPaaSivulla']);
		if(Yii::app()->request->getPost('tekijaPaaSivulla') and Yii::app()->request->getPost('tekijaPaaSivulla') != 'kaikki'){
		Yii::app()->session['tekijaPaaSivulla'] = Yii::app()->request->getPost('tekijaPaaSivulla');
		}

		if(isset($_POST['mob_hae']) and Yii::app()->request->getPost('etsi_kohteet') == '')
		unset(Yii::app()->session['etsi_kohteet']);
		if(isset($_POST['mob_hae']) and Yii::app()->request->getPost('etsi_kohteet')){
		Yii::app()->session['etsi_kohteet'] = Yii::app()->request->getPost('etsi_kohteet');
		}

		if(isset($_POST['fromP']) and empty($_POST['fromP']))
		unset(Yii::app()->session['fromP']);

		if(isset($_POST['toP']) and empty($_POST['toP']))
		unset(Yii::app()->session['toP']);

		if(isset($_POST['tunni_status']) and $_POST['tunni_status'] == 'kaikki')
		unset(Yii::app()->session['tunni_status']);


		if(Yii::app()->request->getPost('fromP'))
		Yii::app()->session['fromP'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('fromP')));

		if(Yii::app()->request->getPost('toP'))
		Yii::app()->session['toP'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('toP')));

		if(Yii::app()->request->getPost('tunni_status') and Yii::app()->request->getPost('tunni_status') != 'kaikki')
		Yii::app()->session['tunni_status'] = Yii::app()->request->getPost('tunni_status');

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
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < DATE_ADD(NOW(), interval 4 hour) AND status IN (1,2,10) AND loppui='' DESC, 
		time and status IN (1,2,10) AND loppui='' DESC, 
		time DESC ";

	        $criteria->condition = " admin!=1 ";

		if(Yii::app()->session['tekijaPaaSivulla'])
	        $criteria->addCondition (" tid = '".Yii::app()->session['tekijaPaaSivulla']."' ");
		if(Yii::app()->session['etsi_kohteet'])
	        $criteria->addCondition (" kohde_kannasta LIKE '%".Yii::app()->session['etsi_kohteet']."%' ");
		if(Yii::app()->session['fromP'] and Yii::app()->session['toP'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['fromP']."' AND '".Yii::app()->session['toP']."' ");
		if(Yii::app()->session['tunni_status'])
	        $criteria->addCondition (" status = '".Yii::app()->session['tunni_status']."' ");

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


	protected function yo($al,$lop){

		$totalYo = 0;


	  	if(strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 06:00"))
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 06:00")
		and strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 23:00"))
		{
	   	  $strAl0 = strtotime($al[0]." 23:00");
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 06:00"))
		{
	   	  $strAl0 = strtotime($al[0]." 23:00");
	   	  $strLop0 = strtotime($lop[0]." 06:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 06:00"))
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." 06:00");

	 	  $str = ($strLop0-$strAl0);

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
	/*
		$this->renderPartial('palkkataulukko', array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$tid
		));
	*/
	}

	protected function TidfromtoSL($from,$to,$tid)
	{

		$result = '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND sairaus='2'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
		";


		$lu = Mobile::model()->find($criteria);


       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND sairaus='2'
		";


		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->l_tunnit))
		$result = $lu->l_tunnit;

		if(isset($tot->l_tunnit))
		$result = $result+$tot->l_tunnit;


		return $result;
	}


	protected function TidfromtoLS($from,$to,$tid)
	{

		$result = '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'

			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND sairaus='3'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)

		";


		$lu = Mobile::model()->find($criteria);


       		$criteria = new CDbCriteria();
        	$criteria->select = "

			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 

			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'

			AND sairaus='3'
		";


		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->l_tunnit))
		$result = $lu->l_tunnit;

		if(isset($tot->l_tunnit))
		$result = $result+$tot->l_tunnit;


		return $result;
	}



	protected function TidfromtoSPL($from,$to,$tid)
	{

		$result = '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "COUNT(*) as count";

	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND sairaus='1'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
		";

		$lu = Mobile::model()->find($criteria);

       		$criteria = new CDbCriteria();
        	$criteria->select = "COUNT(*) as count";

	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND sairaus='1'
		";

		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->count))
		$result = $lu->count;

		if(isset($tot->count))
		$result = $result+$tot->count;

		return $result;
	}

	public function actionPalkkataulukko()
	{


		//unset(Yii::app()->session['Tekija']);
		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}
		

       		$criteria = new CDbCriteria();
		$criteria->select = " id,tekijan_nimi ";
        	$criteria->order = " tekijan_nimi "; //SUBSTR(LTRIM(tekijan_nimi), LOCATE(' ',LTRIM(tekijan_nimi)))
        	$criteria->condition = " aktiivinen=1 "; 

		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

	        $criteria->addCondition ('id IN ('.$ids.') ');
		}

/*
		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			'pagination'=>false
		));
*/
		$model = Tyontekijat::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_palkkataulukko', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('palkkataulukko', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ));
		}
	}

	protected function TP($tid,$from,$to){

       		$criteria = new CDbCriteria();
        	$criteria->select = "id";
        	$criteria->group = "DATE(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d'))";

	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
		";

		$model = Mobile::model()->findAll($criteria);
		return count($model);

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

/*
		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));

		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));
*/		

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}


       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit,tid
		";

        	$criteria->order = "tekijan_nimi"; //"SUBSTR(LTRIM(tekijan_nimi), LOCATE(' ',LTRIM(tekijan_nimi)))"
        	$criteria->group = 'tid';
        	$criteria->condition = " 
			aloitan !='' and loppui !='' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' 
		";

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



		/*
		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			'pagination'=>false
		));
		*/
		$model = Mobile::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_yhteenveto', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ),true));

	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('yhteenveto', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ));
		}
	}


	protected function luMatka($criteria,$tid,$from,$to){

        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = "  
			status = '2' and tid = '".$tid."' and aloitan !='' and loppui !='' 
		";

		if(!empty($from) and !empty($to))
		{
	        $criteria->addCondition (" 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		");
		}

	        $criteria->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");

	return $criteria;
	}

	protected function totMatka($criteria,$tid,$from,$to){

        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = "  
			status = '2' and tid = '".$tid."' and aloitan !='' and loppui !='' 
		";

		if(!empty($from) and !empty($to))
		{
	        $criteria->addCondition (" 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		");
		}

	        $criteria->addCondition (" kid IN (SELECT id FROM sivexkuitti) ");

	return $criteria;
	}

	public function actionYhteenveto_m()
	{


		//unset(Yii::app()->session['Tekija']);
		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}
		

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit,
		t.*
		";



        	//$criteria->condition = " l_loppu = '' and l_alku = '' ";

        	$criteria->order = "tekijan_nimi"; //"SUBSTR(LTRIM(tekijan_nimi), LOCATE(' ',LTRIM(tekijan_nimi)))"
        	$criteria->group = 'tid';
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND status = '2' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
		";

		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

	        $criteria->addCondition ('tid IN ('.$ids.') ');
		}


		$model = Mobile::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_yhteenveto_m', array(
			'model' => $model,
			'from' => $from,
			'to' => $to,	
		  ),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('yhteenveto_m', array(
			'model' => $model,
			'from' => $from,
			'to' => $to,
		  ));
		}
	}

	protected function yhtSUUNN($from,$to){

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";

        	$criteria->condition = " 
			loppu!='' and alku!='' 
			AND tyoajanmerkinta NOT LIKE '%Ei lasketa%'
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";

		$model = Tyovuoroot::model()->find($criteria);
		return $model->l_tunnit;
	}


	protected function totKpl($criteria,$kohdenID,$from,$to){


        	$criteria->select = " COUNT(*) as count	";

        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";
		if($kohdenID != 'kaikki')
	        $criteria->addCondition (" kohdenID = '".$kohdenID."' ");

		$criteria->group = "kohdenID"; 


		return $criteria;
	}


	protected function totLu($criteria,$kohdenID,$from,$to){


        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND kohdenID ='".$kohdenID."' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";

		return $criteria;
	}


	protected function totLuYhteensa($criteria,$status,$from,$to){


        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='$status'
			AND kohdenID !='' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";


		return $criteria;
	}


	protected function yhtLU($from,$to){


       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,3,$from,$to);
		$l = Mobile::model()->find($cr1);

		$lu = $l->l_tunnit;

	return $lu;

	}

	protected function yhtTOT($from,$to){

       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,3,$from,$to);
		$cr1->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
		$tt = Mobile::model()->find($cr1);

       		$cr2 = new CDbCriteria();
		$this->totLuYhteensa($cr2,3,$from,$to);
		$tt2 = Toteutuneet::model()->find($cr2);
		
		$tot = $tt->l_tunnit+$tt2->l_tunnit;

	return $tot;

	}



	protected function yhtLUmatka($from,$to){


       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,2,$from,$to);
		$l = Mobile::model()->find($cr1);

		$lu = $l->l_tunnit;

	return $lu;

	}

	protected function yhtTOTmatka($from,$to){

       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,2,$from,$to);
		$cr1->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
		$tt = Mobile::model()->find($cr1);

       		$cr2 = new CDbCriteria();
		$this->totLuYhteensa($cr2,2,$from,$to);
		$tt2 = Toteutuneet::model()->find($cr2);
		
		$tot = $tt->l_tunnit+$tt2->l_tunnit;

	return $tot;

	}

	public function actionTyobykohde($kohdenID,$from,$to)
	{

		$lu = array();
		$ids = array();

		$fromTo = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ";

       		$criteria = new CDbCriteria();
        	$criteria->select = "id,tekijan_nimi,aloitan,loppui,asiakas_hyvaksy,osoite,sairaus";
        	$criteria->order = "kohde_kannasta";
        	//$criteria->group = "kohde_kannasta";
        	$criteria->condition = "
			id NOT IN (select kid from sivexkuitti_repaired) 
			AND status='3'
			AND kohdenID='".$kohdenID."'
			AND $fromTo
		";

		$model = Mobile::model()->findAll($criteria);

		foreach($model as $d){
			$kesto = 0;

		  $d->loppui = date("d.m.Y H:i",strtotime($d->loppui));
		  $d->aloitan = date("d.m.Y H:i",strtotime($d->aloitan));

			$kesto = strtotime($d->loppui)-strtotime($d->aloitan);
			$lu[] = $d->tekijan_nimi."//".date("d.m",strtotime($d->aloitan))."//".$kesto."//mobile_".$d->id."//".$d->asiakas_hyvaksy."//".date("H:i",strtotime($d->aloitan))."//".date("H:i",strtotime($d->loppui))."//////".$d->sairaus;
		}


       		$criteria = new CDbCriteria();
        	$criteria->select = "id,tekijan_nimi,aloitan,loppui,asiakas_hyvaksy,osoite,tietoja,sairaus";
        	$criteria->order = "kohde_kannasta";
        	//$criteria->group = "kohde_kannasta";
        	$criteria->condition = "
			status='3'
			AND kohdenID='".$kohdenID."'
			AND $fromTo
		";

		$model = Toteutuneet::model()->findAll($criteria);
		foreach($model as $d){
			$kesto = 0;

		  $d->loppui = date("d.m.Y H:i",strtotime($d->loppui));
		  $d->aloitan = date("d.m.Y H:i",strtotime($d->aloitan));
			$kesto = strtotime($d->loppui)-strtotime($d->aloitan);
			$lu[] = $d->tekijan_nimi."//".date("d.m",strtotime($d->aloitan))."//".$kesto."//toteutu_".$d->id."//".$d->asiakas_hyvaksy."//".date("H:i",strtotime($d->aloitan))."//".date("H:i",strtotime($d->loppui))."//".$d->osoite."//".$d->tietoja."//".$d->sairaus;
		}

		//if(count($lu) > 0)
		//ksort($lu);



		foreach($lu as $k=>$v)
		{
			$explV = explode("//",$v);

			$asiakas_hyvaksy = '';
			if(isset($explV[4]) and !empty($explV[4])){
			  $exp = explode("_", $explV[4]);
			    if(isset($exp[0]) and $exp[0] == 0)
				$asiakas_hyvaksy = '<b class="fa fa-share pull-right text-warning"></b>';
			    if(isset($exp[0]) and $exp[0] == 1)
				$asiakas_hyvaksy = '<b class="glyphicon glyphicon-ok pull-right text-success"></b>';
			    if(isset($exp[0]) and $exp[0] == 2)
				$asiakas_hyvaksy = '<b class="glyphicon glyphicon-warning-sign pull-right text-danger"></b>';
			}

			if(isset($explV[0]) and isset($explV[1]) and isset($explV[2]))
			{

			$kertaosoite = '';
			if(isset($explV[7]) and !empty($explV[7]))
			$kertaosoite = ' ('.trim($explV[7]).') ';

			$tietoja = '';
			if(isset($explV[7]) and !empty($explV[8]))
			$tietoja = trim($explV[8]).'<hr>';

			$spl = $this->sairausMerkki($explV[9]);

			echo 
			'<div class="row">
			   <div class="col-sm-6 text-right">'.$explV[0].$kertaosoite.$spl.', '.$explV[1].'</div>
			   <div class="col-sm-6"> '.$explV[5].'-'.$explV[6].' kesto: <b> '.$this->sprint($explV[2]).'</b> '.$asiakas_hyvaksy.'</div>
			'.$tietoja.'
			</div>';
			}
			if(isset($explV[3]))
			$ids[] = $explV[3];
		}

		echo '<br>';
		echo '<div class="pull-right">';
		echo '<form action="asiakas_hyvaksyminen" method="POST">';
		echo '<input type="hidden" name="fromPosti" value="'.$from.'">';
		echo '<input type="hidden" name="toPosti" value="'.$to.'">';
		echo '<input type="hidden" name="ids" value="'.implode(",",$ids).'">';
		echo '<input type="hidden" name="kohdenID" value="'.$kohdenID.'">';
		echo '<input type="submit" class="btn btn-sm btn-success" value="'.Yii::t('main','lähetä asiakkaalle hyväksymiseksi').'">';
		echo '</form>';
		echo '</div>';


	}



	public function actionKyhteenveto_tuntemattomat()
	{

		if(Yii::app()->request->getPost('kohteet') == 'kaikki')
		unset(Yii::app()->session['kohteet']);

		if(Yii::app()->request->getPost('kohteet') and Yii::app()->request->getPost('kohteet') != 'kaikki')
		Yii::app()->session['kohteet'] = Yii::app()->request->getPost('kohteet');

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}



       		$criteria = new CDbCriteria();
        	$criteria->select = "
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,kohde_kannasta,tekijan_nimi,aloitan,loppui
		";
        	$criteria->order = "kohde_kannasta";
        	$criteria->condition = "
			aloitan!='' AND loppui!=''
			AND kohdenID=''
			AND status='3'
			AND id NOT IN (select kid from sivexkuitti_repaired) 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' 			
		";


		$model = Mobile::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_kyhteenveto', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('kyhteenveto_tuntemattomat', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ));
		}
	}


	public function actionKyhteenveto()
	{



		if(Yii::app()->request->getPost('kohteet') == 'kaikki')
		unset(Yii::app()->session['kohteet']);

		if(Yii::app()->request->getPost('kohteet') and Yii::app()->request->getPost('kohteet') != 'kaikki')
		Yii::app()->session['kohteet'] = Yii::app()->request->getPost('kohteet');
		
		if(Yii::app()->request->getPost('mitkatKohteet'))
		Yii::app()->session['mitkatKohteet'] = Yii::app()->request->getPost('mitkatKohteet');

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}


       		$criteria = new CDbCriteria();
        	$criteria->select = "kohdenID,kohde_kannasta";
        	$criteria->order = "kohde_kannasta";
        	$criteria->group = "kohdenID";
        	$criteria->condition = "
			id NOT IN (select kid from sivexkuitti_repaired) 
			AND status='3'
			AND kohdenID!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' 
		";


		$model = Mobile::model()->findAll($criteria);
		$lu = array();
		foreach($model as $d){
			$lu[$d->kohde_kannasta] = $d->kohdenID;
		}



       		$criteria = new CDbCriteria();
        	$criteria->select = "kohdenID,kohde_kannasta";
        	$criteria->order = "kohde_kannasta";
        	$criteria->group = "kohdenID";

        	$criteria->condition = "
			id NOT IN (select kid from sivexkuitti_repaired) 
			AND status='3'
			AND kohdenID!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' 
		";


		$model = Toteutuneet::model()->findAll($criteria);
		foreach($model as $d){
			$lu[$d->kohde_kannasta] = $d->kohdenID;
		}

		if(count($lu) >0)
		ksort($lu);
	

		if(Yii::app()->request->getPost('tulosta'))
		{

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_kyhteenveto', array(
			'lu' => $lu,
			'from' => $from,
			'to' => $to
		  ),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('kyhteenveto', array(
			'lu' => $lu,
			'from' => $from,
			'to' => $to
		  ));
		}
	}

	public function actionAsiakas_hyvaksyminen()
	{

		if(Yii::app()->request->getPost('laheta'))
		{

		  $length = 50;
		  $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);


	  	$model=new AsiakasHyvaksynta;
	  	$model->attributes=$_POST;
	  	$model->kirjen_body=$_POST['kirjen_body'];
	  	$model->code=$code;

	  	if($model->save())
		{



	$ids = explode(",",$_POST['ids']);
	foreach($ids as $val)
	{
	    $explVal = explode("_", $val);
	    if(isset($explVal[1]))
	    {

		if($explVal[0] == 'mobile') 
		{
		   Mobile::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'0_'.date("d.m.Y")));
		   //echo $explVal[1].'<br>';
		}

		if($explVal[0] == 'toteutu')
		{
		   Toteutuneet::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'0_'.date("d.m.Y")));
		   //echo $explVal[1].'<br>';
		}

	    }

	}


		  $message = json_decode($_POST['kirjen_body']);
		  $message .= '
			<br>
			<center>
			<a href="http://etunti.fi/index.php/site/hyvaksy?id='.$model->id.'&code='.$model->code.'&domain='.Yii::app()->user->domain.'">'.Yii::t('main','Hyväksy').'</a> &nbsp;&nbsp;&nbsp;
			<a href="http://etunti.fi/index.php/site/hylkaa?id='.$model->id.'&code='.$model->code.'&domain='.Yii::app()->user->domain.'">'.Yii::t('main','Hylkää').'</a>
			</center>

		  ';		 

	          $mail = new YiiMailer();
		  //$mail->clearLayout();//if layout is already set in config
		  $mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		  $mail->setTo($_POST['sahkoposti']);
		  $mail->setSubject($_POST['otsikko']);
		  $mail->setBody($message);
	
		     if($mail->send())
		     		$this->redirect(array('kyhteenveto'));
		     else
				echo Yii::t('main','Sähköpostissa on vika');

		}
		if(!$model->save()){
		   var_dump($model->getErrors());
		}


		} else {
		  $this->render('asiakas_hyvaksyminen');
		}

	}


	public function toteutu($tid,$sivu,$from,$to)
	{

		$total_l 	= 0;
		$total_t 	= 0;
		$totalIlta 	= 0;
		$totalYo 	= 0;
		$totalSu	= 0;
		$total	 	= 0;
		$al		= '';
		$lop		= '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
		";

		if($sivu == 'palkkataulukko')
	        	$criteria->addCondition (" status = '3' ");


		if($sivu == 'yhteenveto')
		{
			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");
		}


	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		    $totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    if(date('N', strtotime($al[0])) == 7)
		    $totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
		";

		if($sivu == 'palkkataulukko')
	        	$criteria->addCondition (" status = '3' ");


		if($sivu == 'yhteenveto')
		{
			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");
		}

	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		    $totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    if(date('N', strtotime($al[0])) == 7)
		    $totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}

		$kaikki = array($total_l,$totalIlta,$totalYo,$totalSu);

		return $kaikki;

	}



	public function matkaIlta($tid,$from,$to)
	{


		$totalIlta 	= 0;


       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND status = '2'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";



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
			AND status = '2'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";


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




	public function actionKohdebytekija($tid,$from,$to)
	{


       		$criteria = new CDbCriteria();
        	$criteria->select = "COUNT(*) as count,
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit,
			kohde_kannasta
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!=''
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";
        	$criteria->group = "kohde_kannasta";

			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");

		$lu = Mobile::model()->findAll($criteria);

		    $return = array();
		    $ks = array();
		    $c = array();
		    $sum = 0;

		foreach($lu as $t)
		{
		    $kesto = '';
		    $kesto = $t->l_tunnit;
		    $sum += $t->l_tunnit;

		    if(!isset($ks[$t->kohde_kannasta])) { $ks[$t->kohde_kannasta] = 0; }
		    $ks[$t->kohde_kannasta] += $t->l_tunnit;

		    if(!isset($c[$t->kohde_kannasta])) { $c[$t->kohde_kannasta] = 0; }
		    $c[$t->kohde_kannasta] += 1;

		    $return[$t->kohde_kannasta] = array($t->kohde_kannasta, $t->count, null);
		}


       		$criteria = new CDbCriteria();
        	$criteria->select = "COUNT(*) as count, 
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as t_tunnit,
			kohde_kannasta
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";
        	$criteria->group = "kohde_kannasta";

			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		    $return2 = array();

		foreach($tot as $t)
		{
		    $kesto = '';
		    $kesto = $t->t_tunnit;
		    $sum += $t->t_tunnit;

		    if(!isset($ks[$t->kohde_kannasta])) { $ks[$t->kohde_kannasta] = 0; }
		    $ks[$t->kohde_kannasta] += $t->t_tunnit;

		    if(!isset($c[$t->kohde_kannasta])) { $c[$t->kohde_kannasta] = 0; }
		    $c[$t->kohde_kannasta] += 1;

		    $return[$t->kohde_kannasta] = array($t->kohde_kannasta, $t->count, 'muokattu');
		}

		$model = $return;
		//ksort($return);
		//array_sum($ks);

		foreach($model as $k=>$result)
		{

		    $muokattu = '';
		    if($result[2] == 'muokattu')
		    $muokattu = 'text-danger';

			echo 
			'<div class="row">
			   <div class="col-sm-6 text-right '.$muokattu.'">'.$result[0].'</div>
			   <div class="col-sm-6">kesto: <b> '.$this->sprint($ks[$k]).' ('.$this->num($ks[$k]).')</b>, kerta: '.$c[$k].'</div>
			</div>';

		}

		echo '<h3 class="pull-right">'.Yii::t('main','Yhteensä').' '.$this->sprint($sum).' ('.$this->num($sum).')</h3>';

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



	public function pyhapaivat($tid,$from,$to,$m)
	{

		$pvmSTR = '';

		$asetukset = Asetukset::model()->findbypk(1);
		if($m == "pyhat")
		$pvms = explode("\n",$asetukset->pyhapaivat);
		elseif($m == "el")
		$pvms = explode("\n",$asetukset->erikoislauantai);

		$pget = array(0);
		if(isset($pvms[0]))
		{
		  foreach($pvms as $p)
		  {
		    if(date("Y-m-d",strtotime($p)) >= $from and date("Y-m-d",strtotime($p)) <= $to)
		    {
		      $prepair = date("Y-m-d",strtotime($p));
		      $pget[$prepair] = $prepair;
		    }
		  }
		}
		if(isset($pget[0]))
		{
		unset($pget[0]);
		$pvmSTR = "DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')='".implode("' OR DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')='",$pget)."'";
		}

		if(!empty($pvmSTR))
		$pvmSTR = " AND ($pvmSTR) ";

		$return 	= 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND (status='2' OR status='3')
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			$pvmSTR
		";


		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $return += $l->l_tunnit;
		}

		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND (status='2' OR status='3')
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			$pvmSTR
		";


		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $return += $l->l_tunnit;
		}


		return $return;

	}

}

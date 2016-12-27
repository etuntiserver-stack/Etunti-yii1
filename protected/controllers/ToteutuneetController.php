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
				'actions'=>array('admin','delete','create','update','index', 'view','luetutpvmtid', 'totpvmtid','al', 'yhteensapvm', 'deletebyajax', 'kk','hyvaksy', 'poista_luetut_toteutuneet', 'vuosiloma_hyvaksy', 'hyvaksy_pvm_tid'),
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
	    //if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}


	public function actionHyvaksy_pvm_tid()
	{

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			tid='".$_POST['json'][0]['tid']."'
			AND pvm='".date("Y-m-d", strtotime($_POST['json'][0]['pvm']))."'
		";
		$m = HyvaksyttamatPvmTunnit::model()->find($criteria);

		if(!isset($m->id))
			$model = new HyvaksyttamatPvmTunnit;
		else
			$model = $m;

		$model->pvm = date("Y-m-d", strtotime($_POST['json'][0]['pvm']));
		$model->tid = $_POST['json'][0]['tid'];
		if(isset(Yii::app()->user->adminID))
			$model->admin = Yii::app()->user->adminID;
		unset($_POST['json'][0]['pvm'],$_POST['json'][0]['tid']);
		$model->json_arvot = json_encode($_POST['json'][0]);

		if(!$model->save())
		{
			var_dump($model->getErrors());
		} else {

		}


		// <-- Netvisor lahetys
		$asetukset=Asetukset::model()->findbypk(1);
		if($asetukset->netvisor_kaytto == 1)
		{

			$update = false;
			$lastArr = json_decode($model->netvisor_ok_list, true);
			foreach($_POST['json'][0] as $key=>$value)
			{
				if($value > 0)
				{
					$return = $this->netvisorWorkday($key,$value,$model);
					if(isset($return['statusOK'])){
						$update = true;
						$lastArr[$key] = $value;
					}

					if(isset($return['statusError'])){
						echo json_encode($return['statusError']);
						exit;
					}
				}
			}

			if($update == true)
			{
				HyvaksyttamatPvmTunnit::model()->updateByPk($model->id, array('netvisor_ok_list'=>json_encode($lastArr)));
				echo json_encode('netvisorOK');
			}
		}
		//     Netvisor lahetys -->

		
	}


	protected function netvisorWorkday($nimike,$sekuntti,$model)
	{
		//$tyontekija = Tyontekijat::model()->findByPk($model->tid);
		$tunti = $sekuntti/3600;
		$return = array();
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	if(isset($n[0]))
	{

		$url		= $n[0].'/workday.nv';

		$host 		= $n[1];

		$sender 	= $n[2];
		$customerId	= $n[3];
		$partnerId	= $n[4];
		$timestamp	= $n[5];
		$language	= $n[6];
		$organisationIdentifier	= $n[7];
		$transactionIdentifier	= $n[8];
		$userKey 	= $n[9];
		$partnerKey	= $n[10];



	$getMAC = md5(
		$url.'&'.
		$sender.'&'.
		$customerId.'&'.
		$timestamp.'&'.
		$language.'&'.
		$organisationIdentifier.'&'.
		$transactionIdentifier.'&'.
		$userKey.'&'.
		$partnerKey
	 	);
	
	$auth_data = 
	    "Host: $host\r\n".  
	    "X-Netvisor-Authentication-Sender: $sender\r\n".  
	    "X-Netvisor-Authentication-CustomerId: $customerId\r\n".  
	    "X-Netvisor-Authentication-PartnerId: $partnerId\r\n".  
	    "X-Netvisor-Authentication-Timestamp: $timestamp\r\n".
	    "X-Netvisor-Interface-Language: $language\r\n".
	    "X-Netvisor-Organisation-ID: $organisationIdentifier\r\n".  
	    "X-Netvisor-Authentication-TransactionId: $transactionIdentifier\r\n".
	    "X-Netvisor-Authentication-MAC: $getMAC\r\n"
	; 
	
		$netvisor_ok_list = array();
		if( is_array(json_decode($model->netvisor_ok_list, true)) )
		$netvisor_ok_list = json_decode($model->netvisor_ok_list, true);


		if(isset($netvisor_ok_list[$nimike]))
		$method = 'replace';
		else
		$method = 'increment';

		
		//echo $method."\n";

		/*
		if(!isset($netvisor_ok_list[$nimike])){
			$return = array('statusOK'=>$nimike);
		}
		*/


// <-- XML
$xml = '
<root>
  <workday>
    <date format="ansi" method="'.$method.'">'.date("Y-m-d").'</date>
    <employeeidentifier type="number" defaultdimensionhandlingtype="usedefault">'.$model->tid.'</employeeidentifier>
    <workdayhour>
      <hours>'.$tunti.'</hours>
      <collectorratio type="number">1</collectorratio>
      <acceptancestatus>confirmed</acceptancestatus>
      <description>'.$nimike.'</description>
    </workdayhour>
  </workday>
</root>';
//  XML -->
	

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
		if(!isset($netvisor_ok_list[$nimike])){
			$return = array('statusOK'=>$nimike);
		}

	  } else {
			$return = array('statusError'=>$result);
	  }




	} // if isset $n[0]

		return $return;

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


	public function actionPoista_luetut_toteutuneet()
	{
		if(isset($_POST['id']))
		{
			Mobile::model()->deletebypk($_POST['id']);
			Toteutuneet::model()->deleteAll(" kid='".$_POST['id']."' ");
			echo json_encode('poistettu ID '.$_POST['id']);
		}

		exit;
	}


	public function actionVuosiloma_hyvaksy()
	{
	
		$pvm 	= date("Y-m-d", strtotime($_POST['pvm']));
		$tid 	= $_POST['tid'];
		$tila 	= $_POST['tila'];

		//echo $pvm.' '.$tid.' '.$tila;

	       	$criteria = new CDbCriteria();
		$criteria->condition = " 
			DATE(pvm)='".$pvm."' AND tid='".(int)$tid."' 
			AND status LIKE '%".$tila."//%' 
		";
		$vl = Vuosilomat::model()->find($criteria);

		if(isset($vl->id) and $_POST['hyvaksy'] == 'kylla')
		{
			Vuosilomat::model()->updatebypk($vl->id,array('hyvaksytty'=>1));
			echo 'kylla';
		}

		if(isset($vl->id) and $_POST['hyvaksy'] == 'ei')
		{
			Vuosilomat::model()->updatebypk($vl->id,array('hyvaksytty'=>0));
			echo 'ei';
		}

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

	public function actionTotpvmtid($pvm,$tid)
	{
		$mobile = Yii::app()->createController('Mobile');
		$arr = $this->TotPvmTid($pvm,$tid,$mobile);
		echo json_encode($arr);
	}

	public function TotPvmTid($pvm,$tid,$mobile)
	{
	
		$did = date("Ymd",strtotime($pvm));
		//echo '<div id="'.$did.'_'.$tid.'">';
		$laatikot = '<div class="small">';
	
		$muutos = false;
		$tun = 0;
		$get = array();
	
	       	$criteria = new CDbCriteria();
		$criteria->condition = " 
			tid = '".$tid."' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
			AND aloitan!='' AND loppui!=''
		 ";
		if(Yii::app()->session['Lounastauko'])
		$criteria->addCondition (" status != '10' ");
		if(Yii::app()->session['MATKA'])
		$criteria->addCondition (" status != '2' ");
	
		$tv = Toteutuneet::model()->findAll($criteria); 
		foreach($tv as $tvVal){
	
		   if($tvVal->id){
		   $muutos = true;

	   	$get[strtotime($tvVal->aloitan)+strtotime($tvVal->loppui)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->kid."//".$tvVal->asiakas_hyvaksy."//".$tvVal->tietoja."//".$tvVal->sairaus;
	
		  $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
		  $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));
	
		   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui))
		   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
		   }
		}
	
	
	       	$criteria = new CDbCriteria();
		$criteria->condition = " 
			tid = '".$tid."' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) 
			AND aloitan!='' AND loppui!=''
		";
		if(Yii::app()->session['Lounastauko'])
		$criteria->addCondition (" status != '10' ");
		if(Yii::app()->session['MATKA'])
		$criteria->addCondition (" status != '2' ");
	
		$mob = Mobile::model()->findAll($criteria); 
		foreach($mob as $tvVal){
	
		   if($tvVal->id){
		   $muutos = false;

		   $get[strtotime($tvVal->aloitan)+strtotime($tvVal->loppui)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->id."//".$tvVal->asiakas_hyvaksy."////".$tvVal->sairaus;
	
	
		  $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
		  $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));
	
		   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui))
		   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
		   }
		}
	
	
			   ksort($get);
			   foreach($get as $v){
			      $laatikot .= $this->renderPartial('al',array('str'=>$v), true);
			   }
	
	
		// <-- check Vuosilomat
	       	$criteria = new CDbCriteria();
		$criteria->condition = " 
			tid = '".$tid."' 
			AND DATE(pvm) = '".date("Y-m-d",strtotime($pvm))."'
			AND (status LIKE '%VL//%' OR status LIKE '%VKL//%')
		";
		$vuosilomat = Vuosilomat::model()->find($criteria);
		if(isset($vuosilomat->id))
		{
			$checked = '';
			if($vuosilomat->hyvaksytty == 1) $checked = 'checked';

			$exVl = explode("//", $vuosilomat->status);
		   	$laatikot .= '
			   <div class="fullRivi form-inline">
				<span class="form-group">
					<input type="checkbox" class="vuosilomaHyvaksynta" tila="'.$exVl[0].'" pvm="'.date("Y-m-d",strtotime($pvm)).'" tid="'.$tid.'" '.$checked.' did="'.date("Ymd",strtotime($pvm)).'_'.$tid.'" week="'.date("W",strtotime($pvm)).'" data-toggle="tooltip" data-placement="bottom" title="'.Yii::t('main', 'Hyväksy').'">&nbsp; 
				</span><span class="form-group">
					<i class="form-group link" style="color:'.$exVl[1].'">'.$exVl[2].'</i>
				</span>
			   </div>';

		}
		//     check Vuosilomat -->


		
		$laatikot .= '&nbsp;&nbsp;<b class="link glyphicon glyphicon-plus uusirivi" for="'.$did.'_'.$tid.'"></b>';
		$laatikot .= '</div>';
	
		//$mobile = Yii::app()->createController('Mobile');

		// <-- Tyotunnit	
		$tyotunnit = $mobile[0]->TidfromtoStatus($pvm,$pvm,$tid,3);
		//     Tyotunnit -->

		// <-- Lounaat	
		$lounaat = $mobile[0]->TidfromtoStatus($pvm,$pvm,$tid,10);
		//     Lounaat -->

		// <-- Matkat	
		$matkat = $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>date("Y-m-d",strtotime($pvm)),
		'to'=>date("Y-m-d",strtotime($pvm)),
		'tid'=>$tid
		),true);
		// <-- Matkat

		// <-- Ilta, Yo, Sunnuntai
		$yhtIlta= 0;
		$yhtYo 	= 0;
		$yhtSu 	= 0;
	
		$return 	= $this->IltaYoSu($tid,$pvm);
	
		if(isset($return[0])){
		    $tyoIlta 	= $return[0];
		}
		if(isset($return[1])){
		    $tyoYo 	= $return[1];
		}
		if(isset($return[2])){
		    $tyoSu 	= $return[2];
		}
		//     Ilta, Yo, Sunnuntai -->

		// <-- SPL, SL, LS
		$spl 	= $mobile[0]->TidfromtoSairaus($pvm,$pvm,$tid,'SPL'); // Palkaton
		$sl 	= $mobile[0]->TidfromtoSairaus($pvm,$pvm,$tid,'SL'); // Palkallinen
		$ls 	= $mobile[0]->TidfromtoSairaus($pvm,$pvm,$tid,'LS'); // Lapsen sairaus
		//     SPL, SL, LS -->


		$arr = array(
			'laatikot'=>$laatikot,
			'toteutuneetTunnit'=>(int)$tun,
			'ilta'=>(int)$tyoIlta,
			'yo'=>(int)$tyoYo,
			'su'=>(int)$tyoSu,
			'spl'=>(int)$spl,
			'sl'=>(int)$sl,
			'ls'=>(int)$ls,
			'tyotunnit'=>$tyotunnit,
			'matkat'=>$matkat,
			'lounaat'=>$lounaat,
			'week'=>date("W", strtotime($pvm)),
		);
	        return $arr;


	}

	public function actionDeletebyajax()
	{
		$model = Toteutuneet::model()->findbypk($_POST['id']);

 		if(isset($model->id)) $kohde_kannasta = $model->kohde_kannasta; else $kohde_kannasta = '';
 		if(isset($model->id)) $aloitan = $model->aloitan; else $aloitan = '';
 		if(isset($model->id)) $loppui = $model->loppui; else $loppui = '';
 		if(isset($model->id)) $tekijan_nimi = $model->tekijan_nimi; else $tekijan_nimi = '';

		Toteutuneet::model()->deletebypk($_POST['id']);

		$mob = Mobile::model()->findbypk($model->kid);

		// <-- Kirjoitetaan historia luettut tietokantaan
		$this->renderPartial('//mobile/historia',array(
		'id'=>$model->kid,
		'tilanne'=>"Toteutuneet",
		'kohde_kannasta'=>array('vanha'=>$kohde_kannasta, 'uusi'=>$mob->kohde_kannasta),
		'aloitan'=>array('vanha'=>$aloitan, 'uusi'=>$mob->aloitan),
		'loppui'=>array('vanha'=>$loppui, 'uusi'=>$mob->loppui),
		'tekijan_nimi'=>array('vanha'=>$tekijan_nimi, 'uusi'=>$mob->tekijan_nimi),
		));
		// Kirjoitetaan historia luettut tietokantaan -->
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

			$mob =  Mobile::model()->findByPk($_POST['Toteutuneet']['kid']);

 			if(isset($mob->id)) $kohde_kannasta = $mob->kohde_kannasta; else $kohde_kannasta = '';
	 		if(isset($mob->id)) $aloitan = $mob->aloitan; else $aloitan = '';
	 		if(isset($mob->id)) $loppui = $mob->loppui; else $loppui = '';
	 		if(isset($mob->id)) $tekijan_nimi = $mob->tekijan_nimi; else $tekijan_nimi = '';

			$k = Kohteet::model()->findbypk($_POST['Toteutuneet']['kohde_kannasta']);
			$model->attributes=$_POST['Toteutuneet'];
			$model->aloitan = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['aloitan']));
			$model->loppui = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['loppui']));
			$model->kohdenID=$k->id;			
			$model->kohde_kannasta=$k->osoite;

			if($model->save()){


			   	Mobile::model()->updateByPk($model->kid, array('sairaus'=>$model->sairaus));

			   	$did = date("Ymd",strtotime($model->aloitan));
			   	echo $did."_".$model->tid;

				// <-- Kirjoitetaan historia luettut tietokantaan
				$this->renderPartial('//mobile/historia',array(
				'id'=>$model->kid,
				'tilanne'=>"Toteutuneet",
				'kohde_kannasta'=>array('vanha'=>$kohde_kannasta, 'uusi'=>$model->kohde_kannasta),
				'aloitan'=>array('vanha'=>$aloitan, 'uusi'=>$model->aloitan),
				'loppui'=>array('vanha'=>$loppui, 'uusi'=>$model->loppui),
				'tekijan_nimi'=>array('vanha'=>$tekijan_nimi, 'uusi'=>$model->tekijan_nimi),
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

 			$kohde_kannasta	= $model->kohde_kannasta;
	 		$aloitan 	= $model->aloitan;
	 		$loppui	 	= $model->loppui;
	 		$tekijan_nimi	= $model->tekijan_nimi;

			$k = Kohteet::model()->findbypk($_POST['Toteutuneet']['kohde_kannasta']);
			$model->attributes=$_POST['Toteutuneet'];
			$model->aloitan = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['aloitan']));
			$model->loppui = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['loppui']));
			$model->kohdenID=$k->id;			
			$model->kohde_kannasta=$k->osoite;


			if($model->save()){

			   Mobile::model()->updateByPk($model->kid, array('sairaus'=>$model->sairaus));

			   $did = date("Ymd",strtotime($model->aloitan));
			   echo $did."_".$model->tid;

				// <-- Kirjoitetaan historia luettut tietokantaan
				$this->renderPartial('//mobile/historia',array(
				'id'=>$model->kid,
				'tilanne'=>"Toteutuneet",
				'kohde_kannasta'=>array('vanha'=>$kohde_kannasta, 'uusi'=>$model->kohde_kannasta),
				'aloitan'=>array('vanha'=>$aloitan, 'uusi'=>$model->aloitan),
				'loppui'=>array('vanha'=>$loppui, 'uusi'=>$model->loppui),
				'tekijan_nimi'=>array('vanha'=>$tekijan_nimi, 'uusi'=>$model->tekijan_nimi),
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
	          $html2pdf->WriteHTML($this->renderPartial('index',array('from'=>$from,'to'=>$to,'tekija'=>$tekija,'tulosta'=>true),true));
	          $html2pdf->Output();

		  //$this->renderPartial('tulosta',array('from'=>$from,'to'=>$to, 'tekija'=>$tekija));//
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


	protected function IltaYoSu($tid,$pvm)
	{

		$pvm = date("Y-m-d", strtotime($pvm));

		$ilta 	= 0;
		$yo 	= 0;
		$su 	= 0;

		$mobile = Yii::app()->createController('Mobile');

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui,id
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

		    $ilta += $mobile[0]->ilta($al,$lop);
		    $yo += $mobile[0]->yo($al,$lop);
		    if(date('N', strtotime($al[0])) == 7)
		    $su += $l->l_tunnit;

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

		    $ilta += $mobile[0]->ilta($al,$lop);
		    $yo += $mobile[0]->yo($al,$lop);
		    if(date('N', strtotime($al[0])) == 7)
		    $su += $l->l_tunnit;
		}

		$total = array($ilta,$yo,$su);
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



	protected function viikkonLoppu($date,$tid,$yhtMatkaWeek,$yhtIltaWeek,$viikkoBreak,$yhtYoWeek,$yhtTotpvmtid){
/*
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
		  //$totalTot = '';
		  //$totalTot = $this->yhtTOtWeek($tid,$vko,$year);
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtTotpvmtid).'<br>('.$this->num($yhtTotpvmtid).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors"></td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtMatkaWeek).'<br>('.$this->num($yhtMatkaWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtIltaWeek).'<br>('.$this->num($yhtIltaWeek).')</td>';
		  echo '<td style="background: #669999;color: white; text-align:center" class="small myBgColors">'.$this->sprint($yhtYoWeek).'<br>('.$this->num($yhtYoWeek).')</td>';
	    echo '</tr>';
	    $viikkoBreak = true;
	    }
*/

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


	public function pyhapaivat($tid,$date,$m)
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
		    if(date("Y-m-d",strtotime($p)) == date("Y-m-d",strtotime($date)))
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
			AND DAYOFWEEK(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d'))!=1
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
			AND DAYOFWEEK(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d'))!=1
			$pvmSTR
		";


		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $return += $l->l_tunnit;
		}


		return $return;

	}



	public function toteutuneetByPvm($tid,$pvm,$status)
	{
		$pvm = date("Y-m-d", strtotime($pvm));

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";
        	$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!='' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')='".$pvm."'
			$status
			AND sairaus!=1
			AND id NOT IN(select kid from sivexkuitti_repaired)
		";
		$luetut = Mobile::model()->find($criteria);

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";
        	$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!='' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')='".$pvm."'
			$status
			AND sairaus!=1
		";
		$toteutuneet = Toteutuneet::model()->find($criteria);


		return $luetut->l_tunnit+$toteutuneet->l_tunnit;

	}



}

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
				'actions'=>array('admin','delete','create','update','index', 'view','luetutpvmtid', 'totpvmtid','al', 'yhteensapvm', 'deletebyajax', 'kk','hyvaksy', 'poista_luetut_toteutuneet', 'hyvaksy_pvm_tid', 'korvaus_ylitunnit_ennakko', 'siirra_toteutuun'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}




	public function isEtuntiAdmin() {

		if(!isset(Yii::app()->user->adminID))
		{
			//die('login error');
		  	echo '<script type="text/javascript">
				window.location.href=location.protocol + "//" + location.host + "/index.php/site/index";
			</script>';
			exit;
		}

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



	public function actionSiirra_toteutuun($id)
	{
		$tv = Tyovuoroot::model()->findByPk($id);
		if(isset($tv->id))
		{

			$tt = Tyontekijat::model()->findByPk($tv->tid);
			$k = Kohteet::model()->findByPk($tv->kohde);
			(isset($k->id))? $osoite = $k->osoite:$osoite = '';

				$mobiili = new Mobile;
				//$mobiili->tuoteID = $tv->tuoteID;
				$mobiili->tid = $tv->tid;
				$mobiili->tv_id = $tv->id;
				$mobiili->kohdenID = $tv->kohde;
				$mobiili->kohde_kannasta = $osoite;
				$mobiili->tekijan_nimi = $this->etuSukunimi($tv->tid);
				$mobiili->admin = 1;
				$mobiili->aloitan = date("d.m.Y H:i:s", strtotime($tv->pvm." ".$tv->alku));
				$mobiili->loppui = date("d.m.Y H:i:s", strtotime($tv->pvm." ".$tv->loppu));
				if($tv->status != 2 and $tv->status != 3 and $tv->status != 10)
					$mobiili->status = 3;
				else
					$mobiili->status = $tv->status;

				if($mobiili->save())
				{
					$did = $tv->id.'_'.date("Ymd", strtotime($tv->pvm)).'_'.$tv->tid;
					echo json_encode(array('OK'=>$mobiili, 'did'=>$did));
				} else {
					echo json_encode(array('OK'=>getErrors($mobiili)));
				}
				exit;
		}
			echo json_encode('Error: ei löyty');
	}

	public function actionKorvaus_ylitunnit_ennakko()
	{
		$taulu 	= $_POST['taulu'];
		$pvm 	= $_POST['pvm'];
		$tid 	= $_POST['tid'];


		$return = $this->renderPartial('korvaus_ylitunnit_ennakko', 
			array('taulu'=>$taulu, 'pvm'=>$pvm, 'tid'=>$tid)
		, true);
		echo json_encode($return);
	}

	public function actionHyvaksy_pvm_tid()
	{
		/*
		echo '<pre>';
		print_r($_POST['json']);
		echo '</pre>';
		exit;
		*/

		$returnPayroll = '';

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
		if($asetukset->netvisor_kaytto == 1 and empty($model->netvisor_ok_list))
		{

			if(isset($asetukset->netvisor_mita_lahetetaan) and empty($asetukset->netvisor_mita_lahetetaan))
			{
				echo json_encode(array('ERROR'=>'Valitse asetuksessa mitä lähetetään'));
				exit;
			}

			$mitaLahetetaan = json_decode($asetukset->netvisor_mita_lahetetaan, true);
			//echo json_encode($mitaLahetetaan);
			//exit;

			$update = false;
			$lastArr = array();
			foreach($_POST['json'][0] as $key=>$value)
			{


				if($value > 0 and in_array($key,$mitaLahetetaan) )
				{
					$return = array();
					//$lastArr[] = array($key=>$value);

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
				echo json_encode(array('netvisorOK'=>date("d.m.Y", strtotime($model->pvm)). ' - Tiedot on lähetetty netvisoriin '.$returnPayroll));
				exit;
			}

		} elseif($asetukset->netvisor_kaytto == 1 and !empty($model->netvisor_ok_list)) {
				echo json_encode('Tiedot ovat jo lähetetty '.$returnPayroll);
				exit;
		}
		//     Netvisor lahetys -->

		
		echo json_encode(array('TallennettuMuttaEiLahetetty'=>date("d.m.Y", strtotime($model->pvm)).' - Tallennettu tietokantaan, mutta ei lähetetty netvisoriin.'));

	}


	protected function netvisorWorkday($nimike,$sekuntti,$model)
	{
		//$tyontekija = Tyontekijat::model()->findByPk($model->tid);
		$asetukset = Asetukset::model()->findByPk(1);

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

		if(empty($asetukset->netvisor_acceptancestatus))
			$acceptancestatus = 'confirmed';
		else
			$acceptancestatus = $asetukset->netvisor_acceptancestatus;

		$collectorratio = 1;
		if($nimike == 'tyoilta') $collectorratio =  2;
		if($nimike == 'matka') $collectorratio =  1;
		if($nimike == 'tyoyo') $collectorratio =  3;
		if($nimike == 'tyosu') $collectorratio =  5;
		if($nimike == 'sl') $collectorratio =  8;
		if($nimike == 'ls') $collectorratio =  9;
		if($nimike == 'py') $collectorratio =  7;
		if($nimike == 'el') $collectorratio =  4;



// <-- XML
$xml = '
<root>
  <workday>
    <date format="ansi" method="'.$method.'">'.date("Y-m-d", strtotime($model->pvm)).'</date>
    <employeeidentifier type="number" defaultdimensionhandlingtype="usedefault">'.$model->tid.'</employeeidentifier>
    <workdayhour>
      <hours>'.$tunti.'</hours>
      <collectorratio type="number">'.$collectorratio.'</collectorratio>
      <acceptancestatus>'.$acceptancestatus.'</acceptancestatus>
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
			$return = array('statusError'=>$model->pvm.'<br> '.json_encode($result).', nimike: '.$nimike.', collectorratio: '.$collectorratio);
	  }


	   // $return = array('statusError'=>$nimike.' '.$collectorratio.' '.$sekuntti); // tarkistamiseksi


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

			$m_d = Mobile::model()->findbypk($_POST['id']);

			if(isset($m_d->id))
			{
				// <-- LOG
				$model_log 	= 'Mobile';
				$name_log 	= 'Tunnit';
				$status_log 	= 'Delete';
	
					$old_values = json_encode($m_d->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			}

			Mobile::model()->updateByPk($_POST['id'], array('deleted' => 1, 'hyvaksytty' => ''));
		       	$criteria = new CDbCriteria();
			$criteria->condition = " 
				kid='".$_POST['id']."'
			";
			Toteutuneet::model()->updateAll(array('deleted' => 1,'hyvaksytty' => ''), $criteria);
			echo json_encode('poistettu ID '.$_POST['id']);
		}

		exit;
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
			AND deleted=0
		 ";
		if(Yii::app()->session['Lounastauko'])
		$criteria->addCondition (" status != '10' ");
		if(Yii::app()->session['MATKA'])
		$criteria->addCondition (" status != '2' ");
	
		$tv = Toteutuneet::model()->findAll($criteria); 
		foreach($tv as $tvVal){
	
		   if($tvVal->id){
		   $muutos = true;

	   	$get[strtotime($tvVal->aloitan).'_'.$tvVal->id] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->kid."//".$tvVal->asiakas_hyvaksy."//".$tvVal->tietoja."//".$tvVal->sairaus."//".$tvVal->status."//".$tvVal->tuoteID;
	
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
			AND deleted=0
		";
		if(Yii::app()->session['Lounastauko'])
		$criteria->addCondition (" status != '10' ");
		if(Yii::app()->session['MATKA'])
		$criteria->addCondition (" status != '2' ");
	
		$mob = Mobile::model()->findAll($criteria); 
		foreach($mob as $tvVal){
	
		   if($tvVal->id){
		   $muutos = false;

		   $get[strtotime($tvVal->aloitan).'_'.$tvVal->id] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->id."//".$tvVal->asiakas_hyvaksy."////".$tvVal->sairaus."//".$tvVal->status."//".$tvVal->tuoteID;
	
	
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
		$spl 	= $mobile[0]->TidfromtoSairausTP($pvm,$pvm,$tid,'SPL'); // Palkaton
		$sl 	= $mobile[0]->TidfromtoSairausTP($pvm,$pvm,$tid,'SL'); // Palkallinen
		$ls 	= $mobile[0]->TidfromtoSairausTP($pvm,$pvm,$tid,'LS'); // Lapsen sairaus
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


				// <-- LOG
				if( isset($model->id))
				{
				$m_m = Mobile::model()->findbypk($model->kid);
				if(isset($m_m->id))
				{
				$model_log 	= 'Toteutuneet';
				$name_log 	= 'Tuntien hyväksyntä';
				$status_log 	= 'Delete';

					$old_values = json_encode($model->attributes);
					$new_values = json_encode($m_m->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				}
				//     LOG -->

		Toteutuneet::model()->deletebypk($_POST['id']);

		$mob = Mobile::model()->findbypk($model->kid);

		// <-- Kirjoitetaan historia luettut tietokantaan
		/*
		$this->renderPartial('//mobile/historia',array(
		'id'=>$model->kid,
		'tilanne'=>"Toteutuneet",
		'kohde_kannasta'=>array('vanha'=>$kohde_kannasta, 'uusi'=>$mob->kohde_kannasta),
		'aloitan'=>array('vanha'=>$aloitan, 'uusi'=>$mob->aloitan),
		'loppui'=>array('vanha'=>$loppui, 'uusi'=>$mob->loppui),
		'tekijan_nimi'=>array('vanha'=>$tekijan_nimi, 'uusi'=>$mob->tekijan_nimi),
		));
		*/
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

			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt);
			} else {
				$model->tyo_erittelyt = '';
			}

			if(isset($k->id))
			{
				$model->kohdenID=$k->id;			
				$model->kohde_kannasta=$k->osoite;
			}

			if($model->save()){


				// <-- LOG
			   	$m_m = Mobile::model()->findByPk($model->kid);
				if( isset($model->id) and isset($m_m->id) )
				{
				$model_log 	= 'Toteutuneet';
				$name_log 	= 'Tuntien hyväksyntä';
				$status_log 	= 'Create';

					$old_values = json_encode($m_m->attributes);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

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

			$vanha_attr = $model->attributes;
			$model->attributes=$_POST['Toteutuneet'];
			$model->aloitan = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['aloitan']));
			$model->loppui = date("d.m.Y H:i:s",strtotime($_POST['Toteutuneet']['loppui']));

			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt);
			} else {
				$model->tyo_erittelyt = '';
			}

			if(isset($k->id))
			{
				$model->kohdenID=$k->id;			
				$model->kohde_kannasta=$k->osoite;
			}


			if($model->save()){

			   Mobile::model()->updateByPk($model->kid, array('sairaus'=>$model->sairaus));


				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Toteutuneet';
				$name_log 	= 'Tuntien hyväksyntä';
				$status_log 	= 'Update';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->


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
		// <-- Check days count
		if(Yii::app()->request->getPost('from') and Yii::app()->request->getPost('to'))
		{
			$site = Yii::app()->createController('Site');
			$daysreturn = $site[0]->daysBetween(Yii::app()->request->getPost('from'), Yii::app()->request->getPost('to'));
			if((int)$daysreturn > 100)
			{
				Yii::app()->user->setFlash('danger', "Haku aikaväli on liian pitkä.");
				$this->redirect(array("index"));
			}
		}
		//     Check days count -->


		function sprint($val){
		    if($val > 0)
			return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
		}


		if(isset($_GET['deleteKorvaus']))
		{
			Korvaukset::model()->findByPk($_GET['id'])->delete();
			$this->redirect('index');
		}
		if(isset($_GET['deleteLisatyotunnit']))
		{
			Lisatyotunnit::model()->findByPk($_GET['id'])->delete();
			$this->redirect('index');
		}
		if(isset($_GET['deleteEnnakko']))
		{
			Ennakko::model()->findByPk($_GET['id'])->delete();
			$this->redirect('index');
		}


		if(Yii::app()->request->getPost('tekija')){
		Yii::app()->session['tekija'] = Yii::app()->request->getPost('tekija');
		}


		if(isset($_POST['tekija']))
		{
			unset(Yii::app()->session['Lounastauko']);
			unset(Yii::app()->session['MATKA']);
		}

		if(isset($_POST['tekija']) and $_POST['tekija'] == 'kaikki')
		{
			unset(Yii::app()->session['tekija']);
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
		if(isset(Yii::app()->session['tekija']))
		{
			$tekija = $this->etuSukunimi(Yii::app()->session['tekija']);
		}


		$from = '';
		$to = '';
		if(isset(Yii::app()->session['from']))
		$from = Yii::app()->session['from'];


		if(isset(Yii::app()->session['to']))
		$to = Yii::app()->session['to'];

		if( isset($_GET['from']) and isset($_GET['to']) and isset($_GET['tid']) ){
			$from = date("Y-m-d", strtotime($_GET['from']));
			$to = date("Y-m-d", strtotime($_GET['to']));
			Yii::app()->session['from'] = $from;
			Yii::app()->session['to'] = $to;
			Yii::app()->session['tekija'] = $_GET['tid'];
		}

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
			AND sairaus!=1
			AND deleted=0
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
			AND sairaus!=1
			AND deleted=0
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

	protected function IltaYoSuTyovuorosta($tid,$pvm)
	{

		$pvm = date("Y-m-d", strtotime($pvm));
		$mobile = Yii::app()->createController('Mobile');
		$ilta 	= 0;
		$yo 	= 0;
		$su 	= 0;

       		$criteria = new CDbCriteria();
        	$criteria->condition = "  
			tid = '".$tid."'
			AND (status = '3' OR status = '2')
			AND peruutettu=0
		";

	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."' ");

		$lu = Tyovuoroot::model()->findAll($criteria);
		foreach($lu as $l)
		{

		    $loppu = date("d.m.Y H:i",strtotime($l->pvm.' '.$l->loppu));
		    $alku = date("d.m.Y H:i",strtotime($l->pvm.' '.$l->alku));

		    $l->l_tunnit = (strtotime($loppu)-strtotime($alku));
		    $al = explode(" ",$alku);
		    $lop = explode(" ",$loppu);

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
			AND deleted=0
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

		$asetukset = AsetuksetForAll::model()->findbypk(1);
		if($m == "pyhat")
		$pvms = explode("\n",$asetukset->viralliset_pyhapaivat);
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
			AND deleted=0
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
			AND deleted=0
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
			AND deleted=0
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
			AND deleted=0
		";
		$toteutuneet = Toteutuneet::model()->find($criteria);


		return $luetut->l_tunnit+$toteutuneet->l_tunnit;

	}


	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}


	protected function korvauksetPvmTid($pvm,$tid)
	{
		$bod = '';
	  	$criteria = new CDbCriteria();
		$criteria->order = " pvm DESC ";
		$criteria->condition = " 
			tid='".$tid."'
			AND pvm='".$pvm."'
		";
	
		$m = Korvaukset::model()->findAll($criteria);

		$korvArr = $this->korvauksetArray();

		foreach($m as $v)
		{
			$bod .= '<p>';
			if(isset($korvArr[$v->syy]))
			$v->getAttributeLabel('syy').': '.$korvArr[$v->syy].'<br>';

			$bod .= $v->getAttributeLabel('korvaus').': '.$v->korvaus.'<br>';
	
			$bod .=  CHtml::link("Poista", '#', array(
			  	'submit'=>array('index', "deleteKorvaus"=>true, "id"=>$v->id), 
			  	'confirm' => 'Oletko varmaa?')
			);

			$bod .= '</p>';
			$bod .=  '<br>';
		}
	
		return $bod;
	}


	protected function korvauksetArray()
	{
        	$l = array(
			7=>Yii::t('main', 'Ateriakorvauksen määrä'),
			1=>Yii::t('main', 'Kilometrikorvaus'),
			2=>Yii::t('main', 'Kokopäiväraha'),
			3=>Yii::t('main', 'Puolipäiväraha'),
			4=>Yii::t('main', 'Lomaraha'),
			5=>Yii::t('main', 'Palkkaennakko'),
			6=>Yii::t('main', 'Muu vähennys esim. lasku'),
			100=>Yii::t('main', 'Muut kustannukset'),
		);

		return $l;
	}

	protected function lisatyotunnitPvmTid($pvm,$tid)
	{
		$bod = '';
	  	$criteria = new CDbCriteria();
		$criteria->order = " pvm DESC ";
		$criteria->condition = " 
			tid='".$tid."'
			AND pvm='".$pvm."'
		";
	
		$m = Lisatyotunnit::model()->findAll($criteria);
		foreach($m as $v)
		{
			$bod .= '<p>'.
			$v->getAttributeLabel('syy').': '.$v->syy.'<br>'.
			$v->getAttributeLabel('prosentti').': '.$v->prosentti.'<br>'.
			$v->getAttributeLabel('tunnimaara').': '.$v->tunnimaara.'<br>';
	
			$bod .=  CHtml::link("Poista", '#', array(
			  	'submit'=>array('index', "deleteLisatyotunnit"=>true, "id"=>$v->id), 
			  	'confirm' => 'Oletko varmaa?')
			);

			$bod .= '</p>';
			$bod .=  '<br>';
		}
	
		return $bod;
	}

	protected function ennakkoPvmTid($pvm,$tid)
	{
		$bod = '';
	  	$criteria = new CDbCriteria();
		$criteria->order = " pvm DESC ";
		$criteria->condition = " 

			tid='".$tid."'
			AND pvm='".$pvm."'
		";
	
		$m = Ennakko::model()->findAll($criteria);
		foreach($m as $v)
		{
			$bod .= '<p>'.
			$v->getAttributeLabel('syy').': '.$v->syy.'<br>'.
			$v->getAttributeLabel('ennakko').': '.$v->ennakko.'<br>';
	
			$bod .=  CHtml::link("Poista", '#', array(
			  	'submit'=>array('index', "deleteEnnakko"=>true, "id"=>$v->id), 
			  	'confirm' => 'Oletko varmaa?')
			);

			$bod .= '</p>';
			$bod .=  '<br>';
		}
	
		return $bod;
	}

	protected function vuosilomaChecker($tid, $pvm)
	{
		$vl = '';
	       	$criteria = new CDbCriteria();
		$criteria->condition = " 
			tid = '".$tid."' 
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."'
			AND status=11
			AND tyoajanlaatu!=''
		";
		$tv = Tyovuoroot::model()->findAll($criteria);
		foreach($tv as $item){
			$arr = explode("/", $item->tyoajanlaatu);
			if(isset($arr[1])){ $vl .= '<span style="color:'.$arr[1].'">'.$arr[0].'<br>'; }
		}

		return $vl;

	}

	protected function pyhat($date){

		$dateMonth = '';
		$pyh = array();

		$dateMonth = date("d.m.Y",strtotime($date));
		$asetukset = AsetuksetForAll::model()->findbypk(1);
		$pyh = explode("\n",$asetukset->viralliset_pyhapaivat);

		if(date("N",strtotime($date)) == 7)
		{
			return 'su';
		}

		if(strstr($asetukset->viralliset_pyhapaivat, $dateMonth))
		{
			return 'pyhapaiva';
		}

		if(strstr($asetukset->erikoislauantai, $dateMonth))
		{
			return 'erikoislauantai';
		}

		return false;

 	}

	public function eiLasketaSubStr($val)
	{

		$return = false;
		if (strpos($val, 'Ei lasketa') !== false or strpos($val, 'Varallaolo') !== false) {
		    $return = true;
		}
		return $return;
	}
}

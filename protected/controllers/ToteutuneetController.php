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
				'actions'=>array('admin','delete','create','update','index', 'view','luetutpvmtid', 'totpvmtid','al', 'yhteensapvm', 'deletebyajax', 'kk','hyvaksy', 'poista_luetut_toteutuneet', 'hyvaksy_pvm_tid', 'korvaus_ylitunnit_ennakko', 'siirra_toteutuun', 'lahetanetvisoriin'),
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

		if(!isset(Yii::app()->user->adminID))
		{
			//die('login error');
			echo '<script type="text/javascript">
			window.location.href=location.protocol + "//" + location.host + "/index.php/site/index";
			</script>';
			exit;
		}

		// <-- Oikeudet
		$checkOikeus = "tuntienhallinta_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		$site[0]->checkOikeus($checkOikeus);
		//  Oikeudet -->

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

	public function actionSiirra_toteutuun($this_id)
	{
		$tyovuoroot = Yii::app()->createController('Tyovuoroot');
		$get_id 	= $tyovuoroot[0]->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		$pvm 		= $get_id['pvm'];
		$tid 		= $get_id['tid'];

		if( $toistuva ){
			$u		= Yii::app()->user->nimi;
			$d		= date("d.m.Y");
			$poisto_syy	= ['text' => 'ByHyvaksyntaSiirto', 'user'=>$u, 'date'=>$d];
			$tyovuoroot[0]->toistuvaDeletePvm($model->id, $pvm, $tid, $poisto_syy);

			$tv_new = new Tyovuoroot;
			$cleared_attr = $tyovuoroot[0]->compareToistuvaAttributes($tv_new->attributes, $model->attributes);
			$tv_new->attributes = $cleared_attr;
			$tv_new->pvm = date("d.m.Y",strtotime($pvm));
			$tv_new->tid = $tid;
			$tv_new->tyopaari = '';
			if($tv_new->save()){
				$tv = $tv_new;
			} else {
				echo json_encode($tv_new->getErrors());
				exit;
			}
		} else {
			$tv = $model;
		}

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

				if($mobiili->save()){
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

	public function actionLahetanetvisoriin()
	{			
		$response = $this->netvisorWorkdayPerID($_POST['json']);
		echo json_encode($response);
		exit;
	}

	protected function netvisorWorkdayPerID($json)
	{
		$return 	= [];
		$asetukset 	= Asetukset::model()->findByPk(1);

		$postData	= $json[0];
		$pvm 		= date("Y-m-d", strtotime($postData['pvm']));
		$tid 		= $postData['tid'];
		unset($postData['pvm'], $postData['tid']);
		
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			tid='".$tid."'
			AND pvm='".date("Y-m-d", strtotime($pvm))."'
		";
		$m = HyvaksyttamatPvmTunnit::model()->find($criteria);
		
		// <-- Henkari
		$henkkari 	= '';
		$tyontekija = Tyontekijat::model()->findByPk($tid);
		if(isset($tyontekija->id))
			$henkkari = $tyontekija->tekijan_henkilotunnus;

		$site 	= Yii::app()->createController('Site');
		$mobile = Yii::app()->createController('Mobile');
		$n 		= $site[0]->netvisorYhteys();

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

			if(isset($m->id))
				$method = 'replace';
			else
				$method = 'increment';

			if(empty($asetukset->netvisor_acceptancestatus))
				$acceptancestatus = 'confirmed';
			else
				$acceptancestatus = $asetukset->netvisor_acceptancestatus;


			$collectorratio 				= [];
			$collectorratio['tyotunnit'] 	= 1;
			$collectorratio['tyoilta'] 		= 2;
			$collectorratio['matka'] 		= 14;
			$collectorratio['tyoyo'] 		= 3;
			$collectorratio['tyosu'] 		= 5;
			$collectorratio['sl'] 			= 8;
			$collectorratio['spl'] 			= 10;
			$collectorratio['ls'] 			= 9;
			$collectorratio['py'] 			= 7;
			$collectorratio['el'] 			= 4;
			$collectorratio['vl'] 			= 11;
			$collectorratio['ap'] 			= 12;
			$collectorratio['pv'] 			= 13;

			if(isset($asetukset->netvisor_mita_lahetetaan) and empty($asetukset->netvisor_mita_lahetetaan))
				return ['ERROR' => 'Valitse asetuksessa mitä lähetetään'];

			$mitaLahetetaan = json_decode($asetukset->netvisor_mita_lahetetaan, true);

			function yleisXML($mobile, $pvm, $tid, $acceptancestatus, $collectorratio, $status, $num, $description)
			{
				$body = '';
					$data   				= $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, [$tid], [$status], 3, true, $num, false, null, null, true);
					foreach($data as $t_id => $arr)
					{
						if($t_id == $tid)
						{
							foreach($arr as $kohdenID => $sum)
							{
								if($sum > 0)
								$body .= '
								<workdayhour>
									<hours>'.($sum/3600).'</hours>
									<collectorratio type="number">'.$collectorratio.'</collectorratio>
									<acceptancestatus>'.$acceptancestatus.'</acceptancestatus>
									<description>'.$description.', Kohde id#: '.$kohdenID.'</description>
								</workdayhour>'; 
							}
						}
					}
				return $body;
			}

			// <-- XML
			$xml = '
			<root>
				<workday>
				<date format="ansi" method="'.$method.'">'.date("Y-m-d", strtotime($pvm)).'</date>
				<employeeidentifier type="personalidentificationnumber" defaultdimensionhandlingtype="usedefault">'.$henkkari.'</employeeidentifier>';

				if(in_array('tyotunnit', $mitaLahetetaan))
					$xml .= yleisXML($mobile, $pvm, $tid, $acceptancestatus, $collectorratio['tyotunnit'], 3, 0, 'Työtunnit');

				if(in_array('tyoilta', $mitaLahetetaan))
					$xml .= yleisXML($mobile, $pvm, $tid, $acceptancestatus, $collectorratio['tyoilta'], 3, 1, 'Työtunnit ilta');

				if(in_array('tyoyo', $mitaLahetetaan))
					$xml .= yleisXML($mobile, $pvm, $tid, $acceptancestatus, $collectorratio['tyoyo'], 3, 2, 'Työtunnit yö');
					
				if(in_array('tyosu', $mitaLahetetaan))
					$xml .= yleisXML($mobile, $pvm, $tid, $acceptancestatus, $collectorratio['tyosu'], 3, 3, 'Työtunnit sunnuntai');
			
				// <-- sl, spl, ls, vl, ap, pv
				foreach($postData as $nimike => $hours)
				{
					if($hours > 0 and in_array($nimike,$mitaLahetetaan))
					{
						if($nimike == 'matka') $hours = $hours/3600;
						$xml .= '
						<workdayhour>
							<hours>'.$hours.'</hours>
							<collectorratio type="number">'.$collectorratio[$nimike].'</collectorratio>
							<acceptancestatus>'.$acceptancestatus.'</acceptancestatus>
							<description>'.$nimike.'</description>
						</workdayhour>';
					}
				}
				
				$xml .= '
				</workday>
			</root>';
			//  XML -->

			//echo $xml;
			//exit;
			
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

			$context 	= stream_context_create($optsPOST);
			$response 	= @file_get_contents($url, false, $context);
			
			if(!$response)
				return ['ERROR' => 'Lähetys ei onnistunut'];
				
			$result 	= new SimpleXMLElement($response);
			$array 		= json_decode(json_encode($result), true);

			if($array['ResponseStatus']['Status'] == 'OK')
			{
				if(!isset($m->id))
					$model = new HyvaksyttamatPvmTunnit;
				else
					$model = $m;

				$model->pvm 				= date("Y-m-d", strtotime($_POST['json'][0]['pvm']));
				$model->tid 				= $_POST['json'][0]['tid'];
				$model->admin 				= Yii::app()->user->adminID;
				$model->xml 				= json_encode($xml);
				$model->netvisor_ok_list	= json_encode($result);

				if(!$model->save())
					return ['ERROR' => var_dump($model->getErrors())];
				else
					return ['OK' => 'Tiedot on lähetetty netvisoriin'];
				
			} else {
				if(isset($array['ResponseStatus']['Status'][1]))
					return ['ERROR' => $array['ResponseStatus']['Status'][1]];
			}

		} // if isset $n[0]

		return ['ERROR' => 'Lähetys ei onnistunut'];

	}

	public function actionHyvaksy_pvm_tid()
	{
		/*
		echo '<pre>';
		print_r($_POST['json']);
		echo '</pre>';
		exit;
		*/

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
				if($value > 0 and in_array($key,$mitaLahetetaan) ){
					$return = array();
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
				echo json_encode(array('netvisorOK'=>date("d.m.Y", strtotime($model->pvm)). ' - Tiedot on lähetetty netvisoriin'));
				exit;
			}

		} elseif($asetukset->netvisor_kaytto == 1 and !empty($model->netvisor_ok_list)) {
				echo json_encode('Tiedot ovat jo lähetetty');
				exit;
		}
		//     Netvisor lahetys -->

		
		echo json_encode(array('TallennettuMuttaEiLahetetty'=>date("d.m.Y", strtotime($model->pvm)).' - Tallennettu tietokantaan, mutta ei lähetetty netvisoriin.'));

	}


	protected function netvisorWorkday($nimike,$sekuntti,$model)
	{
		$henkkari = '';
		$tyontekija = Tyontekijat::model()->findByPk($model->tid);
		if(isset($tyontekija->id)){ $henkkari = $tyontekija->tekijan_henkilotunnus; }
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
			if($nimike == 'sl') { $collectorratio =  8; $tunti = 1; }
			if($nimike == 'spl') { $collectorratio =  10; $tunti = 1; }
			if($nimike == 'ls') $collectorratio =  9;
			if($nimike == 'py') $collectorratio =  7;
			if($nimike == 'el') $collectorratio =  4;
			if($nimike == 'vl') { $collectorratio =  11; $tunti = 1; }
			if($nimike == 'ap') { $collectorratio =  12; $tunti = 1; }
			if($nimike == 'pv') { $collectorratio =  13; $tunti = 1; }


			// <-- XML
			$xml = '
			<root>
			<workday>
			<date format="ansi" method="'.$method.'">'.date("Y-m-d", strtotime($model->pvm)).'</date>
			<employeeidentifier type="personalidentificationnumber" defaultdimensionhandlingtype="usedefault">'.$henkkari.'</employeeidentifier>
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

	public function actionHyvaksy($id, $tot_lu){

		if($tot_lu == 'lu'){
			$mob=Mobile::model()->findbypk($id);
		}
		if($tot_lu == 'tot'){
			//$mob=Mobile::model()->findbypk($id);
			$tot=Toteutuneet::model()->findbypk($id);
			if(isset($tot->kid)){
				$mob=Mobile::model()->findbypk($tot->kid);
			}
		}
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

	protected function LuetutPvmTidBetween($from,$to,$tid)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));


	       	$criteria = new CDbCriteria();
		$criteria->order = "DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC";
		$criteria->condition = " 
			tid = '".$tid."' 
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) BETWEEN '".$from."' AND '".$to."'
			AND admin!='1'
			AND aloitan!='' AND loppui!=''
		";

		if(Yii::app()->session['Lounastauko'])
			$criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
			$criteria->addCondition (" status != '2' ");

		$tv = Mobile::model()->findAll($criteria); 
		$tun = [];
		$get = [];
		foreach($tv as $tvVal)
		{
			$tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
			$tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));
			$tun[date("d.m.Y",strtotime($tvVal->aloitan))][] = strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
	
			$strlen = strlen($tvVal->kohde_kannasta);
			if($strlen > 18)
				$tvVal->kohde_kannasta = substr($tvVal->kohde_kannasta,0,18).'..';
			else
				$tvVal->kohde_kannasta = $tvVal->kohde_kannasta;
	
			if($tvVal->aloitan > 0 and $tvVal->loppui > 0)
				$al = date("H:i",strtotime($tvVal->aloitan)).'-'.date("H:i",strtotime($tvVal->loppui));
			else
				$al = '';

			$did = date("Ymd",strtotime($tvVal->aloitan));
			$get[date("d.m.Y",strtotime($tvVal->aloitan))][] = '
			<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="fullRivi">
				&nbsp;<span class="" id="tv_'.$tvVal->id.'">'.$al.'<br>'.$tvVal->kohde_kannasta.'</span><br>
			</div>';
		}
	
		return json_encode(array('laatikkot'=>$get, 'tunnit'=>$tun));
	}

	public function actionTotpvmtid($pvm,$tid,$ilman_lounastaukot,$ilman_matkat)
	{
		// <-- For toteuma.js
		if( $ilman_lounastaukot == "true" ) $ilman_lounastaukot = true;
		if( $ilman_lounastaukot == "false" ) $ilman_lounastaukot = false;
		if( $ilman_matkat == "true" ) $ilman_matkat = true;
		if( $ilman_matkat == "false" ) $ilman_matkat = false;

		$pvm			= date("Y-m-d", strtotime($pvm));
		$mobile = Yii::app()->createController('Mobile');

		$tyotunnit_all 		= $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, $tid, array(3), /*hyvaksynta*/ 2, false, 0, true, null, null);

		$hyv_arr = array(3,2,10);
		if($ilman_matkat)
			unset($hyv_arr[1]);
		if($ilman_lounastaukot)
			unset($hyv_arr[2]);
		$hyv_tyotunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, $tid, $hyv_arr, 3, false, 0, true, null, null);

		if(!$ilman_lounastaukot)
		$lounaat_all 	= $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, $tid, array(10), 2, false, 0, true, null, null);

		if(!$ilman_matkat)
		$matkatunnit_all = $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, $tid, array(2), 2, false, 0, true, null, null);

		if($ilman_matkat)
		$iltatunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, $tid, array(3), /*hyvaksytyt*/ 2, false, 1, true, null, null);
		else
		$iltatunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, $tid, array(2,3), /*hyvaksytyt*/ 2, false, 1, true, null, null);

		$yotunnit_all 		= $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, $tid, array(3), /*hyvaksytyt*/ 2, false, 2, true, null, null);
		$sutunnit_all 		= $mobile[0]->TidfromtoMobiiliAll($pvm, $pvm, $tid, array(3), /*hyvaksytyt*/ 2, false, 3, true, null, null);

		$asetukset	= Asetukset::model()->findbypk(1);
		$laatikot 	= $this->TotPvmTidBetween($asetukset, $pvm,$pvm,$tid,$ilman_lounastaukot,$ilman_matkat);
		echo json_encode(array(
			'laatikot' 	=> $laatikot,
			'tyotunnit' 	=> (isset($tyotunnit_all[$pvm][$tid]))? $tyotunnit_all[$pvm][$tid] : 0,
			'hyv_tyotunnit'	=> (isset($hyv_tyotunnit_all[$pvm][$tid]))? $hyv_tyotunnit_all[$pvm][$tid] : 0,
			'lounaat' 	=> (isset($lounaat_all[$pvm][$tid]))? $lounaat_all[$pvm][$tid] : 0,
			'matkatunnit' 	=> (isset($matkatunnit_all[$pvm][$tid]))? $matkatunnit_all[$pvm][$tid] : 0,
			'iltatunnit' 	=> (isset($iltatunnit_all[$pvm][$tid]))? $iltatunnit_all[$pvm][$tid] : 0,
			'yotunnit' 	=> (isset($yotunnit_all[$pvm][$tid]))? $yotunnit_all[$pvm][$tid] : 0,
			'sutunnit' 	=> (isset($sutunnit_all[$pvm][$tid]))? $sutunnit_all[$pvm][$tid] : 0,
		));
		exit;
	}

	protected function TotPvmTidBetween($asetukset, $from,$to,$tid,$ilman_lounastaukot=false,$ilman_matkat=false)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$get = [];
		$muutos = false;
		$tun = 0;

		if(isset($_GET['ilman']))
		{
		  foreach($_GET['ilman'] as $val){
			if($val == 'Lounastauko')
			$ilman_lounastaukot = true;

			if($val == 'MATKA')
			$ilman_matkat = true;
		  }
		}

		$criteria = new CDbCriteria();
		$criteria->order = "TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'))";
		$criteria->condition = " 
			tid = '".$tid."' 
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) BETWEEN '".$from."' AND '".$to."'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) 
			AND aloitan!='' AND loppui!=''
			AND deleted=0
		";
		if($ilman_lounastaukot)
		$criteria->addCondition (" status != '10' ");
		if($ilman_matkat)
		$criteria->addCondition (" status != '2' ");
	
		$mob = Mobile::model()->findAll($criteria); 
		foreach($mob as $tvVal){
			if($tvVal->id){
				$muutos = false;
				$did = date("Ymd",strtotime($tvVal->aloitan));
				$get[date("Y-m-d H:i",strtotime($tvVal->aloitan))][] = $tvVal->attributes;
			}
	
		}

		$criteria = new CDbCriteria();
		$criteria->order = "TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'))";
		$criteria->condition = " 
			tid = '".$tid."' 
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) BETWEEN '".$from."' AND '".$to."'
			AND aloitan!='' AND loppui!=''
			AND deleted=0
		 ";
		if($ilman_lounastaukot)
		$criteria->addCondition (" status != '10' ");
		if($ilman_matkat)
		$criteria->addCondition (" status != '2' ");
	
		$tv = Toteutuneet::model()->findAll($criteria); 
		foreach($tv as $tvVal){
			if($tvVal->id){
				$muutos = true;
				$did = date("Ymd",strtotime($tvVal->aloitan));
				$get[date("Y-m-d H:i",strtotime($tvVal->aloitan))][] = $tvVal->attributes;
		   	}
		}

		ksort($get);
		$laatikot = [];
		foreach($get as $k=>$v){
		      $laatikot[date("d.m.Y",strtotime($k))][] = $this->renderPartial('al', ['attributes' => $v, 'asetukset' => $asetukset], true);
		}
	        return $laatikot;

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
		$from = date("Y-m-d", strtotime("first day of last month"));
		$to = date("Y-m-d", strtotime("last day of last month"));

		// <-- Check days count
		if(isset($_GET['from']) and isset($_GET['to']))
		{
			$site = Yii::app()->createController('Site');
			$daysreturn = $site[0]->daysBetween($_GET['from'], $_GET['to']);
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

		if(isset($_POST['hyvaksyminen']) and isset($_POST['tid']))
		{
			if( $_POST['hyvaksyminen'] == 'alkaen' ){
				$criteria = new CDbCriteria();
			        $criteria->condition = " 
					DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d') < CURDATE()
					AND tid='".$_POST['tid']."'
					AND hyvaksytty NOT LIKE '%//%'
				";
				$mob_hyvaksy_alkaen = Mobile::model()->findAll($criteria);
				if( count($mob_hyvaksy_alkaen) > 0 ){
					Mobile::model()->updateAll(array('hyvaksytty' => Yii::app()->user->username.'//'.date("d.m.Y")), $criteria);
				}
				$tot_hyvaksy_alkaen = Toteutuneet::model()->findAll($criteria);
				if( count($tot_hyvaksy_alkaen) > 0 ){
					Toteutuneet::model()->updateAll(array('hyvaksytty' => Yii::app()->user->username.'//'.date("d.m.Y")), $criteria);
				}
			}
			if( $_POST['hyvaksyminen'] == 'kaikki' ){
				$criteria = new CDbCriteria();
			        $criteria->condition = " 
					DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d') < CURDATE()
					AND hyvaksytty NOT LIKE '%//%'
					AND tid='".$_POST['tid']."'
				";
				$mob_hyvaksy_all = Mobile::model()->findAll($criteria);
				if( count($mob_hyvaksy_all) > 0 ){
					Mobile::model()->updateAll(array('hyvaksytty' => Yii::app()->user->username.'//'.date("d.m.Y")), $criteria);
				}
				$tot_hyvaksy_all = Toteutuneet::model()->findAll($criteria);
				if( count($tot_hyvaksy_all) > 0 ){
					Toteutuneet::model()->updateAll(array('hyvaksytty' => Yii::app()->user->username.'//'.date("d.m.Y")), $criteria);
				}
			}
			echo 'ok';
			exit;
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

		if(isset($_GET['from']))
		   $from = date("Y-m-d",strtotime($_GET['from']));
		if(isset($_GET['to']))
		   $to = date("Y-m-d",strtotime($_GET['to']));

		$tekija = '';
		if(isset($_GET['tekija']))
		{
			$tekija = $this->etuSukunimi($_GET['tekija']);
		}
/*
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
*/
		  $this->render('index',array('from'=>$from,'to'=>$to));
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
			AND hyvaksytty!=''
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
			AND hyvaksytty!=''
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

	protected function vuosilomaCheckerBetween($from, $to, $tid)
	{

		$set = [];
		$tyovuoroot 	= Yii::app()->createController('Tyovuoroot');
		$haku_criteria	= [];
		$haku_criteria[] = "status=11 AND tyoajanlaatu!=''";
		$with		= ['data'];
		$from 		= date("Y-m-d", strtotime($from));
		$to 		= date("Y-m-d", strtotime($to));
		$dataAll 	= $tyovuoroot[0]->FromToSuunnitellutAll($from, $to, [$tid], $haku_criteria, $with);
		foreach($dataAll as $arr){
			$item = $arr['data'];
			$expl = explode("/", $item->tyoajanlaatu);
			if(isset($expl[1])){ $set[date("d.m.Y", strtotime($arr['this_pvm']))][] = '<h3 style="color:'.$expl[1].'">'.$expl[0].'</h3>'; }
		}

		return $set;

	}

	protected function hyvaksyttamatTunnitBetween($from, $to, $tid){
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$set = [];
	       	$criteria = new CDbCriteria();
		$criteria->condition = " 
			tid='".$tid."' 
			AND DATE(STR_TO_DATE(pvm, '%Y-%m-%d')) BETWEEN '$from' AND '$to'
			AND netvisor_ok_list!=''
		";
		$model = HyvaksyttamatPvmTunnit::model()->findAll($criteria);
		foreach($model as $item){
			$set[date("d.m.Y", strtotime($item->pvm))] = 1;
		}
		return $set;
	}
/*
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
*/

	public function eiLasketaSubStr($val)
	{

		$return = false;
		if (strpos($val, 'Ei lasketa') !== false or strpos($val, 'Varallaolo') !== false) {
		    $return = true;
		}
		return $return;
	}
}

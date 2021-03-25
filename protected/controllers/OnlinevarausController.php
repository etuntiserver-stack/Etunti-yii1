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
				'actions'=>array('index','view', 'check', 'aika', 'osoite', 'maksu', 'valmis', 'palvelu_ajax', 'palvelu_save_ajax', 'lisat_ajax', 'ajaat_ajax', 'aika_ajax', 'onkokohde', 'luouusi', 'checkout', 'maksettu', 'rekisteriseloste', 'tidtietoja', 'get_lomake_ajax', 'kupongi_checker', 'otakaytoon', 'index_temp'),
                		'users'=>array("*"),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'kaikki', 'kaikkieDico', 'varaukset_raportti'),
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
		   {
			return true;
		   } else {
			$this->redirect(array('/site/otakaytoon', 'tila' => 'onlinevaraus'));
		   }		

		} else {
			$this->redirect(array('/site/otakaytoon', 'tila' => 'onlinevaraus'));
		}
	}

        public function init()
        {
                Yii::app()->theme = 'onlinevaraus';
		if(isset($_GET['domain']))
		{
			Yii::app()->user->setState('domain', $_GET['domain']);

			if( isset($_SESSION['onlinevaraus']) ){ unset($_SESSION['onlinevaraus']); }
			$domainit = Domainit::model()->find(" domain='".$_GET['domain']."' ");
			if(isset($domainit->paketti))
			{
				$tas = array();
				$tas = explode(",",$domainit->paketti);
				if(!in_array('4', $tas))
				{
				    if(isset(Yii::app()->user->adminID))
				    {
				    	$this->redirect(array('/site/otakaytoon', 'tila' => 'onlinevaraus'));
				    } else {
			            	$this->redirect('https://etunti.fi');
				    }
				}
			}
		}

			if(isset($_GET['aid']))
			{
				Yii::app()->user->setState('aid', $_GET['aid']);

			}
			if(isset($_GET['alennuskoodi']))
			{
				Yii::app()->user->setState('alennuskoodi', $_GET['alennuskoodi']);
			} else {
				Yii::app()->user->setState('alennuskoodi',null);
			}
			//$this->redirect(array('index'));


		if( !isset(Yii::app()->user->domain) )
		{
		/*
			$domainit = Domainit::model()->findAll(" yritys!='' AND paketti LIKE '%4%' AND domain!='defdb' AND domain!='demo' AND domain!='testi' ");
			$this->renderPartial('index_temp',array(
				'domainit'=>$domainit
			));
		*/
			die('Yritystunnus ei löydy.');
			exit;
		}

		parent::init();
        }

	public function actionKupongi_checker($kupongi)
	{

		$result = $this->Kupongi_checker($kupongi);
		echo $result;
	}

	protected function Kupongi_checker($kupongi)
	{

		$result = '';

	       		$criteria = new CDbCriteria();
	       		$criteria->condition = " 
				DATE(voimassa) > CURDATE()
				AND kupongin_id='".$kupongi."'
				AND status=0
			";
			$kup = Kupongit::model()->find($criteria);
			if(isset($kup->id))
			{
				$_SESSION['onlinevaraus']['kupongi'] = $kup->id;
				$result = $kup->id;
			}

		echo $result;
		exit;
	}

	public function actionGet_lomake_ajax($id)
	{

		$model = Kohteet::model()->findbypk($id);
		if(isset($model->id)){

			$modelAsiakas = Asiakkaat::model()->findbypk($model->asiakas_id);

			$m = array();
			if(isset($model->id))
			{
				$_SESSION['onlinevaraus']['kohde_id']	= $model->id;
				$m['kohde_id']		= $model->id;
				$m['osoite']		= $model->osoite;
				$m['postinumero']	= $model->pnumero;
				$m['kaupunki']		= $model->kaupunki;
				$m['lisatietoja']	= $model->tietoja;
			}

			if(isset($modelAsiakas->id))
			{
				$_SESSION['onlinevaraus']['asiakas_id']	= $modelAsiakas->id;
				$m['asiakas_id']	= $modelAsiakas->id;
				$m['tyyppi'] 		= $modelAsiakas->tyyppi;
				//$m['yrityksen_nimi'] 	= $modelAsiakas->yrityksen_nimi;
				//$m['y_tunnus'] 		= $modelAsiakas->y_tunnus;
				$m['yhteyshenkilo']	= $modelAsiakas->yhteyshenkilo;
				$m['puhelin']		= $modelAsiakas->puhelin;
			}

		
			echo json_encode($m);
			exit;

			//$this->renderPartial('get_lomake_ajax', array('model'=>$model, 'modelAsiakas'=>$modelAsiakas));

		} else {
			echo json_encode('Get_lomake_ajax: error');
		}
	}


	public function actionTidtietoja()
	{


		$tt = Tyontekijat::model()->findbypk($_POST['tid']);
		if(isset($tt->id))
		{

		$tietoja = '
		<div class="panel panel-success">
		  <div class="panel-heading"><b>'.$this->etuSukunimi($tt->id).'</b></div>
		  <div class="panel-body">

		 <div class="row col-sm-4">
		 ';
			$filename = Yii::app()->request->baseUrl."/img/tekijat/".Yii::app()->user->domain."/".$_POST['tid'].".jpg";
			if (file_exists(Yii::app()->basePath."/../img/tekijat/".Yii::app()->user->domain."/".$_POST['tid'].".jpg")){
			   $tietoja .= '<img src="'.$filename.'" class="img-thumbnail">';
			} else {
			   $tietoja .= '<img src="'.Yii::app()->request->baseUrl.'/img/tekijat/noname.jpg" class="img-thumbnail">';
			}
		$tietoja .= '
		  </div><div class="col-sm-8">
		    <div class="small">
			'.$tt->tietoja_onlinevarauksen.'
		    </div>
		 </div>

		 </div>
		</div>
		';
				echo json_encode($tietoja);
		}
	}

	public function actionRekisteriseloste()
	{

	   if( isset(Yii::app()->user->domain) ){
		$rt = Asetukset::model()->findbypk(1);
		$rekisteriseloste = json_decode($rt->rekisteriseloste);
		if(!is_array($rekisteriseloste)){
			die('Rekisteriteloste puutuu.');
		}
		$this->render('rekisteriseloste',array(
			'rekisteriseloste'=>$rekisteriseloste
		));
	   }
	}

	public function actionKaikki()
	{

                Yii::app()->theme = 'etunti';

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_GET['from']) and isset($_GET['to'])){
		$from 	= date("Y-m-d",strtotime($_GET['from']));
		$to 	= date("Y-m-d",strtotime($_GET['to']));
		}

		if(isset($_GET['tyoryhma_tyyppi']) and $_GET['tyoryhma_tyyppi'] == 'kohde' and !empty($_GET['tyoryhma'])){
		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->addCondition (" kohde_id IN(SELECT id FROM sivex_kohdet WHERE tyoryhma IN ($ids)) ");
		}
		//    Tyoryhmat -->
		}

		if(isset($_GET['tyoryhma_tyyppi']) and $_GET['tyoryhma_tyyppi'] == 'tyontekija' and !empty($_GET['tyoryhma'])){
		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
	        	$criteria->addCondition (" id IN(SELECT onlinevaraus_id FROM sivex_tvuoro WHERE tid IN ($ids)) ");
		}
		//    Tyoryhmat -->
		}


	        $criteria->addCondition (" 
			tila!=3
			AND DATE(time) BETWEEN '".$from."' AND '".$to."' 
		");

		if(isset($_GET['tila']) and !empty($_GET['tila']))
	        	$criteria->addCondition (" tila='".$_GET['tila']."' ");

		if(isset($_POST['tulosta']))
		{

			$model = Onlinevaraus::model()->findAll($criteria);


	          	$html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('varaukset_raportti', array(
				'model' => $model,
				'from' => $from,
				'to' => $to
			),true));
		        $html2pdf->Output();

			/*
			$this->render('varaukset_raportti', array(
				'model' => $model,
				'from' => $from,
				'to' => $to
			));
			*/

		} else {

			$dataProvider=new CActiveDataProvider('Onlinevaraus', array(
				'criteria'=>$criteria,
				//'pagination'=>false
			));

			$dataProvider->pagination->pageSize = 50;
			$this->render('kaikki', array(
				'dataProvider' => $dataProvider,
				'from' => $from,
				'to' => $to
			));
		}
	}


	public function actionKaikkieDico()
	{

                Yii::app()->theme = 'etunti';

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_GET['from']) and isset($_GET['to'])){
		$from 	= date("Y-m-d",strtotime($_GET['from']));
		$to 	= date("Y-m-d",strtotime($_GET['to']));
		}

	        $criteria->addCondition (" 
			tila=3
			AND DATE(time) BETWEEN '".$from."' AND '".$to."' 
		");

		if(isset($_GET['tulosta']))
		{

			$model = Onlinevaraus::model()->findAll($criteria);

	          	$html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
			$html2pdf->setDefaultFont('Arial');
		        $html2pdf->WriteHTML($this->renderPartial('varaukset_raportti', array(
				'model' => $model,
				'from' => $from,
				'to' => $to
			),true));
		        $html2pdf->Output();

			/*
			$this->render('varaukset_raportti', array(
				'model' => $model,
				'from' => $from,
				'to' => $to
			));
			*/

		} else {

			$dataProvider=new CActiveDataProvider('Onlinevaraus', array(
				'criteria'=>$criteria,
				//'pagination'=>false
			));

			$dataProvider->pagination->pageSize = 50;
			$this->render('kaikkiedico', array(
				'dataProvider' => $dataProvider,
				'from' => $from,
				'to' => $to
			));
		}
	}

	public function actionCheckout()
	{
		$this->renderPartial('checkout');
	}

	public function actionMaksettu()
	{
		$this->render('maksettu');
	}

	public function actionValmis()
	{
		$this->render('valmis');
	}

	public function actionOnkokohde()
	{
		if(isset($_POST['sahkoposti']))
		{

			$_SESSION['onlinevaraus']['sahkoposti'] = $_POST['sahkoposti'];
			$body = '';

			$criteria=new CDbCriteria;
			$criteria->condition = " sahkoposti='".$_POST['sahkoposti']."' ";
			$a = Asiakkaat::model()->findAll($criteria);
			if(isset($a[0]))
			{

			  $body .= '<label>'.Yii::t('main', 'Valitse osoite').'</label>
			  <select id="valitseOsoite" class="form-control input-lg">';

			  foreach($a as $asiakasData)
			  {
			  	$k = Kohteet::model()->findAll(" asiakas_id='".$asiakasData->id."' ");

			  	foreach($k as $data)
			  	{
			     		$body .= '<option value="'.$data->id.'">'.$data->osoite.'</option>';
			  	}
			  }

			  $body .= '</select>';

			  echo json_encode($body);
			} else {
			  echo json_encode('ei');
			}
		}

	}


	public function actionLuouusi()
	{

			if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
			{
				$ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
			} else {

				$ov = new Onlinevaraus;
			}

			if( isset($_POST['yhteyshenkilo']) )
			{
				if(isset($_SESSION['onlinevaraus']['kohde_id']))
				$ov->kohde_id 		= $_SESSION['onlinevaraus']['kohde_id'];

				if(isset($_SESSION['onlinevaraus']['asiakas_id']))
				$ov->asiakas_id		= $_SESSION['onlinevaraus']['asiakas_id'];

				$ov->yhteyshenkilo 	= $_POST['yhteyshenkilo'];
				$ov->puhelin 		= $_POST['puhelin'];
				$ov->osoite 		= $_POST['osoite'];
				$ov->postinumero 	= $_POST['postinumero'];
				$ov->kaupunki 		= $_POST['kaupunki'];
				$ov->lisatietoja 	= $_POST['lisatietoja'];
				$ov->sahkoposti 	= $_POST['sahkoposti'];
				$ov->lisatietoja 	= $_POST['lisatietoja'];

				$ov->tyyppi 		= $_POST['tyyppi'];
				//$ov->yrityksen_nimi 	= $_POST['yrityksen_nimi'];
				//$ov->y_tunnus 		= $_POST['y_tunnus'];


				if($ov->save())
				{
					$_SESSION['onlinevaraus']['onlinevarausID'] = $ov->id;

					if(isset($_SESSION['onlinevaraus']['modelTV']))
					{
				        	$tv = Tyovuoroot::model()->updatebypk($_SESSION['onlinevaraus']['modelTV'], 
						array(
							'onlinevaraus_id' => $ov->id,
							'kohde' => $ov->kohde_id,
							'status' => 3
						));
					}

					if(isset(Yii::app()->user->aid))
					{
						echo json_encode('nytRedirectValmis');
						exit;
					}

					echo json_encode('nytRedirectMaksulle');
					exit;

				} else {
					var_dump($ov->errors);
				}

			}
	
			echo json_encode(Yii::t('main', 'errorLuoUusiOnlinevaraus'));
			exit;
	}


	public function actionAika_ajax()
	{
		if(!isset(Yii::app()->user->domain))
		die('Error: domain');

		if(isset($_POST['kalenteri_year_month'])){
			$_SESSION['onlinevaraus']['kalenteri_year_month'] = $_POST['kalenteri_year_month'];
		}

		$this->renderPartial('aika_ajax');
	}


	public function actionAjaat_ajax()
	{

		if(!isset(Yii::app()->user->domain))
		die('Error: domain');

		if(isset($_POST['pvm']))
		$_SESSION['onlinevaraus']['valittuPVM'] = $_POST['pvm'];

		$data = 0;
		$this->renderPartial('ajaat_ajax',array(
			'data'=>$data,
		));

	}

	public function actionLisat_ajax()
	{
	   if(isset($_POST['lisapalvelut']))
	   {

		    if($_POST['checked'] == 1)
		    {

			$_SESSION['onlinevaraus']['lisapalvelut'][$_POST['fordata']] = $_POST['lisapalvelut'];
			echo 'save';
		    }
		    if($_POST['checked'] == 0)
		    {

			if($_SESSION['onlinevaraus']['lisapalvelut'][$_POST['fordata']] == $_POST['lisapalvelut'])
			unset($_SESSION['onlinevaraus']['lisapalvelut'][$_POST['fordata']]);
			echo 'deleted';
		    }

	   }
	}


	public function actionPalvelu_ajax()
	{

	   if(isset($_POST['id']))
	   {

		// <-- Data
		$data = TuotteetPalvelut::model()->findByPk($_POST['id']);
		if(isset($data->id)){
			if(!isset($_SESSION['onlinevaraus'])) { $_SESSION['onlinevaraus'] = array(); }
			$_SESSION['onlinevaraus']['paapalvelu'] = $data->id;
		}

		$this->renderPartial('palvelu_ajax',array(
			'data'=>$data,
		));
	   }
	}

	public function actionPalvelu_save_ajax()
	{

		if(isset($_POST['tyo_toimialue']))
			$_SESSION['onlinevaraus']['tyo_toimialue'] = $_POST['tyo_toimialue'];

		if(isset($_POST['otsikko']))
			$_SESSION['onlinevaraus']['paa_otsikko'] = $_POST['otsikko'];
		if(isset($_POST['nimike']))
			$_SESSION['onlinevaraus']['paa_nimike'] = $_POST['nimike'];
		if(isset($_POST['hinta']))
			$_SESSION['onlinevaraus']['paa_hinta'] = $_POST['hinta'];
		if(isset($_POST['kesto']))
			$_SESSION['onlinevaraus']['paa_kesto'] = $_POST['kesto'];



	   if(isset($_POST['toinen_valiko']))
	   {

		if(isset($_SESSION['onlinevaraus']['paapalvelu']))
		{
			$model = TuotteetPalvelut::model()->findByPk($_SESSION['onlinevaraus']['paapalvelu']);

			$this->renderPartial('palvelu_save_ajax',array(
				'model'=>$model,
				'sivu'=>'index',
			));

		} else {
			echo json_encode('paapalvelu puutuu');
			exit;
		}

	   }

	   if(isset($_POST['tid']) and isset($_POST['pvm'])) {

		if(isset($_SESSION['onlinevaraus']['modelTV']))
		{

			$tv = Tyovuoroot::model()->findbypk($_SESSION['onlinevaraus']['modelTV']);
			if(isset($tv->id))
			{
				$tv->attributes=$_POST;
				$tv->save();

			} else {

				$modelTV = new Tyovuoroot;
				$modelTV->attributes=$_POST;
				$modelTV->save();
				$_SESSION['onlinevaraus']['modelTV'] = $modelTV->id;

			}

		} else {

			$modelTV = new Tyovuoroot;
			$modelTV->attributes=$_POST;
			$modelTV->save();
			$_SESSION['onlinevaraus']['modelTV'] = $modelTV->id;

		}

		$this->renderPartial('palvelu_save_ajax',array(
			'sivu'=>'aika',
		));

	   }

	   if(isset($_POST['kohde'])) {

		$k = Kohteet::model()->findbypk($_POST['kohde']);
		if(isset($k->id))
		{
		    $_SESSION['onlinevaraus']['modelKohde'] = $k->id;
		    Tyovuoroot::model()->updatebypk($_SESSION['onlinevaraus']['modelTV'], array('kohde'=>$k->id));
		}

		$this->renderPartial('palvelu_save_ajax',array(
			'sivu'=>'osoite',
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

                Yii::app()->theme = 'etunti';
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

		if(!isset(Yii::app()->user->domain))
		die('Error: domain');
		$asetukset = Asetukset::model()->findbypk(1);

		if(isset($_SESSION['onlinevaraus']) and !is_array($_SESSION['onlinevaraus'])) 
		{ 
			unset($_SESSION['onlinevaraus']);
			$this->redirect('index');
		}
		if(isset($_POST['kalenteriin'])){
			$_SESSION['onlinevaraus']['palvelut_summary'] = true;
			exit;
		}
		if(isset($_POST['poistaTamaTiedosto'])){
			unlink($_POST['poistaTamaTiedosto']);
			exit;
		}
		if(isset($_POST['getMyPictures']))
		{

			$i = 0;
		  	$kuvat = '';

		    	if(isset($_SESSION['onlinevaraus']['kuvat']))
		    	{
				foreach(array_reverse(glob('tiedostot/onlinevaraus_temp/'.Yii::app()->user->domain.'/'.$_SESSION['onlinevaraus']['kuvat'].'_*.*')) as $file) {
				$i++;
				$explNimi = explode("/",$file);
			 	$kuvat .= '
					<div class="form-inline" id="t_'.$_SESSION['onlinevaraus']['kuvat'].$i.'">
				  		<div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" for="t_'.$_SESSION['onlinevaraus']['kuvat'].$i.'">X</div>
				  		&nbsp;&nbsp;&nbsp;<a href="../../'.$file.'">'.end($explNimi).'</a>
					</div>
				';
			 	}
		    	}
			echo json_encode($kuvat);
			exit;
		}

		if(isset($_POST['kuvanLisaaminen']))
		{
			function rand_string( $length ) {
				$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				return substr(str_shuffle($chars),0,$length);
			}
			if(!isset($_SESSION['onlinevaraus']['kuvat']))
				$_SESSION['onlinevaraus']['kuvat'] = rand_string(8);

			if (!file_exists(Yii::app()->basePath."/../tiedostot/onlinevaraus_temp/".Yii::app()->user->domain)) {
			  	mkdir(Yii::app()->basePath."/../tiedostot/onlinevaraus_temp/".Yii::app()->user->domain, 0777, true);
			}

			$uploaddir = Yii::app()->basePath.'/../tiedostot/onlinevaraus_temp/'.Yii::app()->user->domain.'/';
			$uploadfile = $uploaddir . basename($_SESSION['onlinevaraus']['kuvat'].'_'.$_FILES['file']['name']);
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
				//echo "";
			}
			exit;
		}

		if($asetukset->onlinevaraus_alku == 0){
			Asetukset::model()->updatebypk(1, array('onlinevaraus_alku'=>8));
		}

		if($asetukset->onlinevaraus_loppu == 0){
			Asetukset::model()->updatebypk(1, array('onlinevaraus_loppu'=>18));
		}

		// <-- Clear
		if(isset($_GET['keskeyta']))
		{
			if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
			Onlinevaraus::model()->deletebypk($_SESSION['onlinevaraus']['onlinevarausID']);

			if(isset($_SESSION['onlinevaraus']['modelTV'])){

				$tv = Tyovuoroot::model()->findByPk($_SESSION['onlinevaraus']['modelTV']);
				// <-- LOG
				$model_log 	= 'Tyovuoroot';
				$name_log 	= 'Työvuorot';
				$status_log = 'Auto Delete';
				$old_values = (isset($tv->attributes))? json_encode($tv->attributes) : '';
				$new_values = null;
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

			Tyovuoroot::model()->deletebypk($_SESSION['onlinevaraus']['modelTV']);
			}

			unset($_SESSION['onlinevaraus']);
			$this->redirect('index');
		}
		//     Clear -->

		$this->render('index', array(
			'asetukset' => $asetukset
		));
	}

	public function actionAika()
	{
/*
		if(!isset(Yii::app()->user->domain))
		die('Error: domain');

		$this->render('aika');
*/
	}

	public function actionOsoite()
	{
/*
		if(!isset(Yii::app()->user->domain))
		die('Error: domain');

		$asetukset = Asetukset::model()->findbypk(1);

		if(isset($_POST['poistaTamaTiedosto'])){
			unlink($_POST['poistaTamaTiedosto']);
			exit;
		}
		if(isset($_POST['getMyPictures']))
		{

			$i = 0;
		  	$kuvat = '';

		    	if(isset($_SESSION['onlinevaraus']['kuvat']))
		    	{
				foreach(array_reverse(glob('tiedostot/onlinevaraus_temp/'.Yii::app()->user->domain.'/'.$_SESSION['onlinevaraus']['kuvat'].'_*.*')) as $file) {
				$i++;
				$explNimi = explode("/",$file);
			 	$kuvat .= '
					<div class="form-inline" id="t_'.$_SESSION['onlinevaraus']['kuvat'].$i.'">
				  		<div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" for="t_'.$_SESSION['onlinevaraus']['kuvat'].$i.'">X</div>
				  		&nbsp;&nbsp;&nbsp;<a href="../../'.$file.'">'.end($explNimi).'</a>
					</div>
				';
			 	}
		    	}
			echo json_encode($kuvat);
			exit;
		}

		if(isset($_POST['kuvanLisaaminen']))
		{
			function rand_string( $length ) {
				$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				return substr(str_shuffle($chars),0,$length);
			}
			if(!isset($_SESSION['onlinevaraus']['kuvat']))
				$_SESSION['onlinevaraus']['kuvat'] = rand_string(8);

			if (!file_exists(Yii::app()->basePath."/../tiedostot/onlinevaraus_temp/".Yii::app()->user->domain)) {
			  	mkdir(Yii::app()->basePath."/../tiedostot/onlinevaraus_temp/".Yii::app()->user->domain, 0777, true);
			}

			$uploaddir = Yii::app()->basePath.'/../tiedostot/onlinevaraus_temp/'.Yii::app()->user->domain.'/';
			$uploadfile = $uploaddir . basename($_SESSION['onlinevaraus']['kuvat'].'_'.$_FILES['file']['name']);
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
				//echo "";
			}
			exit;
		}

		$this->render('osoite', array(
			'asetukset' => $asetukset
		));
*/
	}

	public function actionMaksu($json)
	{

		if(!isset(Yii::app()->user->domain))
		die('Error: domain');

		$this->renderPartial('maksu', array(
			'json' => $json
		));

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


protected function build_calendar($month, $year, $dateArray, $pvmRaja, $numOfWeek, $getTyovuorot) {

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

     $calendar = "";
     $calendar .= "<table class='table table-bordered'>";
     $calendar .= "<tr>";

     // Create the calendar headers

     foreach($daysOfWeek as $day) {
          $calendar .= "<td class='header'>$day</td>";
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

          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);         
          $date = "$year-$month-$currentDayRel";

          if ($dayOfWeek == 7) {

               $dayOfWeek = 0;
               $calendar .= "</tr><tr>";

          }
	  $fromTyovuorot = (isset($getTyovuorot['returnData'][$date]))? $getTyovuorot['returnData'][$date] : [];
	  $on = $this->pmvCalNew($date, $fromTyovuorot, $getTyovuorot['tids'])[0];
	  if($numOfWeek == 5 and ( date("N",strtotime($date)) == 7 or date("N",strtotime($date)) == 6 ))
	  $on = 'kiinni';

	  $pyhat = $this->pyhatCheck($date);
	  if($pyhat == 'pyhat')
	  {
	     $tooltip = "data-toggle='tooltip' title='+100%'";
	  } elseif($pyhat == 'lauantai'){
	     $tooltip = "data-toggle='tooltip' title='+50%'";
	  } else {
	     $tooltip = "";
 	  }

	  $tila = '';
	  if($date > date("Y-m-d", strtotime("+$pvmRaja day")) and $on == 'vapaa' and isset($_SESSION['onlinevaraus']['valittuPVM']) and date("Y-m-d", strtotime($_SESSION['onlinevaraus']['valittuPVM'])) != date("Y-m-d", strtotime($date)))
		 $tila .= '<td class="day link vapaa cal" pvm="'.$date.'"><div class="toolt" '.$tooltip.'>'.$currentDay.'</div></td>';
	  elseif($date > date("Y-m-d", strtotime("+$pvmRaja day")) and $on == 'vapaa' and !isset($_SESSION['onlinevaraus']['valittuPVM']))
		 $tila .= '<td class="day link vapaa cal" pvm="'.$date.'"><div class="toolt" '.$tooltip.'>'.$currentDay.'</div></td>';
	  elseif($date > date("Y-m-d", strtotime("+$pvmRaja day")) and $on == 'vapaa' and isset($_SESSION['onlinevaraus']['valittuPVM']) and date("Y-m-d", strtotime($_SESSION['onlinevaraus']['valittuPVM'])) == date("Y-m-d", strtotime($date)))
		 $tila .= '<td class="day link orangeColor cal" pvm="'.$date.'"><div class="toolt" '.$tooltip.'>'.$currentDay.'</div></td>';
	  elseif($date < date("Y-m-d"))
		 $tila .= '<td class="day kiinni" >'.$currentDay.'</td>';
	  else
		$tila .= '<td class="day kiinni">'.$currentDay.'</td>';




          $calendar .= $tila;

          // Increment counters
 
          $currentDay++;
          $dayOfWeek++;

     }
     
     

     // Complete the row of the last week in month, if necessary

     if ($dayOfWeek != $numOfWeek) { 
     
          $remainingDays = $numOfWeek - $dayOfWeek;
          $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>"; 

     }
     
     $calendar .= "</tr>";
     $calendar .= "</table>";

     return $calendar;

}


	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function getTyovuorot2months(){

		$criteria=new CDbCriteria;
		$criteria->condition = "
			aktiivinen=1
			AND online_varauksen_valmina=1 
		";
		if(!empty($tyo_toimialue))
		{
			$criteria->addCondition ("
				tyo_toimialue LIKE '%".$tyo_toimialue."%'
			");
		}
		if(!empty($sopiiva_tuotteet))
		{
			$criteria->addCondition ("
				onlinevaraus_tuotteet LIKE '%\"".$sopiiva_tuotteet."\"%'
			");
		}
		$tyontekijat = Tyontekijat::model()->findAll($criteria);
		$tids = [];
		foreach($tyontekijat as $item)
			$tids[$item->id] = $item->id;

		$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
		$from		= date('Y-m-d', strtotime("first day of this month"));
		$to		= date('Y-m-d', strtotime($from. " last day of next month"));
		$dataAll = $tyovuorot[0]->FromToSuunnitellutAll($from, $to, $tids, [], ['data']);
		$returnData = [];
		foreach($dataAll as $arr){
			$data = $arr['data'];
			$returnData[date('Y-m-d', strtotime($arr['this_pvm']))][$arr['this_tid']][] = ['alku' => $data->alku, 'loppu' => $data->loppu];
		}
		return ['returnData' => $returnData, 'tids' => $tids];
	}

	protected function pmvCalNew($date, $fromTyovuorot, $tids)
	{
		if( !isset($_SESSION['onlinevaraus']['sumTunti']) ){
			echo json_encode('sumTunti Error');
			exit;
		}

		$tekija 		= [];
		$on 			= 'kiinni';

		$asetukset 		= Asetukset::model()->findbypk(1);
		$aikavali 		= $asetukset->onlinevaraus_aikavali*3600;
		$onlinevaraus_alku	= sprintf('%02d', $asetukset->onlinevaraus_alku);
		$onlinevaraus_loppu	= sprintf('%02d', $asetukset->onlinevaraus_loppu);

		$alkuAstetuksesta 	= strtotime($onlinevaraus_alku.":00");
		$loppuAstetuksesta 	= strtotime($onlinevaraus_loppu.":00");

   		$sumTunti 		= (float)$_SESSION['onlinevaraus']['sumTunti'];
		$sumTuntiMin 		= $sumTunti*60;
		$sumTuntiSec 		= $sumTunti*3600;
		$start 			= $onlinevaraus_alku.":00";
		$stop 			= date("H:i",strtotime($start." +".$sumTuntiMin." minutes"));
		$countStop 		= strtotime($onlinevaraus_loppu.":00");

		// <-- Täysin vapaana
		foreach($tids as $tid)
		{
			if(!isset($fromTyovuorot[$tid])){
			   	$on = 'vapaa';
				$tekija = $this->loopForAjaat($tid, $start, $stop, $date, $sumTuntiMin, $countStop, $tekija);
			}
		}
		// Täysin vapaana -->

		foreach($fromTyovuorot as $tid => $ajaat_arr)
		{
			foreach($ajaat_arr as $al_lop_arr){
				if(isset($last_loppu) and strtotime($al_lop_arr['alku']) > $last_loppu and (strtotime($al_lop_arr['alku'])-$last_loppu-($aikavali*2)) >= $sumTuntiSec){
					$alku 	= $last_loppu+$aikavali;
					$loppu 	= $alku+$sumTuntiSec;
					$on = 'vapaa';

	  				$tekija = $this->loopForAjaat(
					$tid,
					date("H:i",$alku), 
					date("H:i",$loppu), 
					$date,
					$sumTuntiMin,
					((strtotime($al_lop_arr['alku'])-$aikavali) > strtotime("18:00"))? strtotime("18:00") : strtotime($al_lop_arr['alku'])-$aikavali, // countStop
					$tekija
					);

				}

				$last_alku 	= strtotime($al_lop_arr['alku']);
				$last_loppu 	= strtotime($al_lop_arr['loppu']);
			}

			// <-- REIKÄ ennen EKA alku
			$first = array_shift($ajaat_arr);
			if(isset($first['alku']) and strtotime($first['alku']) > strtotime($start) and (strtotime($first['alku'])-strtotime($start)-$aikavali) >= $sumTuntiSec){
				$alku 	= strtotime($start);
				$loppu 	= $alku+$sumTuntiSec;
				$on = 'vapaa';

  				$tekija = $this->loopForAjaat(
				$tid,
				date("H:i",$alku), 
				date("H:i",$loppu), 
				$date,
				$sumTuntiMin,
				strtotime($first['alku'])-$aikavali, // countStop
				$tekija
				);
			}
			// <-- REIKÄ viimeisen jälkeen
			if(isset($last_loppu) and $countStop > $last_loppu and ($countStop-$last_loppu-$aikavali) >= $sumTuntiSec){
				$alku 	= $last_loppu+$aikavali;
				$loppu 	= $alku+$sumTuntiSec;
				$on = 'vapaa';

  				$tekija = $this->loopForAjaat(
				$tid,
				date("H:i",$alku), 
				date("H:i",$loppu), 
				$date,
				$sumTuntiMin,
				$countStop,
				$tekija
				);
			}
		}
		ksort($tekija);
		$return_tulos = [];
		foreach($tekija as $tulos)
			foreach($tulos as $t1)
				$return_tulos[] = $t1;
/*
		echo '<pre>';
		print_r($return);
		echo '</pre>';
		exit;
*/
		$return = array($on, $return_tulos);
		return $return;
	}

	protected function loopForAjaat($tid, $start, $stop, $date, $sumTuntiMin, $countStop, $tekija)
	{

		   for ($i = 1; $i <= 24; $i++) 
		   {
		   	$int = 0;
		   	if(!isset($sta[$tid]) and !isset($sto[$tid]))
		   	{
				$sta = array();
				$sto = array();
				$sta[$tid] = $start;
				$sto[$tid] = $stop;
		   	}


			$tekija[$tid][] = array($tid, $date, $sta[$tid], $sto[$tid]);

		   	$int += $sumTuntiMin;
		   	$sta[$tid] = date("H:i",strtotime($sta[$tid]." +$int minutes"));
		   	$sto[$tid] = date("H:i",strtotime($sto[$tid]." +$int minutes"));
		
		   	if(strtotime($sta[$tid]." +$int minutes") > $countStop)
		   	break;

		   }
		   return $tekija;

	}

/*
	protected function pmvCal($date)
	{
		if( !isset($_SESSION['onlinevaraus']['sumTunti']) ){
			echo json_encode('sumTunti Error');
			exit;
		}

		$tyo_toimialue = '';
		if(isset($_SESSION['onlinevaraus']['tyo_toimialue']) and !empty($_SESSION['onlinevaraus']['tyo_toimialue']))
		$tyo_toimialue 	= $_SESSION['onlinevaraus']['tyo_toimialue'];

		$sopiiva_tuotteet = '';
		if(isset($_SESSION['onlinevaraus']['paapalvelu']) and !empty($_SESSION['onlinevaraus']['paapalvelu']))
		$sopiiva_tuotteet = $_SESSION['onlinevaraus']['paapalvelu'];


		$tekija 	= array();
		$on 		= 'kiinni';

		$asetukset = Asetukset::model()->findbypk(1);
		$onlinevaraus_alku	= sprintf('%02d', $asetukset->onlinevaraus_alku);
		$onlinevaraus_loppu	= sprintf('%02d', $asetukset->onlinevaraus_loppu);

		$alkuAstetuksesta = strtotime($onlinevaraus_alku.":00");
		$loppuAstetuksesta = strtotime($onlinevaraus_loppu.":00");

		// <-- Täysin vapaana
   		$sumTunti = (float)$_SESSION['onlinevaraus']['sumTunti'];
		$sumTuntiMin = $sumTunti*60;
		$sumTuntiSec = $sumTunti*3600;
		$start = $onlinevaraus_alku.":00";
		$stop = date("H:i",strtotime($start." +".$sumTuntiMin." minutes"));
		$countStop = strtotime($onlinevaraus_loppu.":00");

		$criteria=new CDbCriteria;
		$criteria->condition = "
			aktiivinen=1
			AND online_varauksen_valmina=1 
			AND id NOT IN ( SELECT tid FROM sivex_tvuoro WHERE pvm='".date("d.m.Y", strtotime($date))."' )
			AND id NOT IN ( SELECT tid FROM vuosilomat WHERE pvm='".date("Y-n-j", strtotime($date))."' )
		";
		if(!empty($tyo_toimialue))
		{
			$criteria->addCondition ("
				tyo_toimialue LIKE '%".$tyo_toimialue."%'
			");
		}

		if(!empty($sopiiva_tuotteet))
		{
			$criteria->addCondition ("
				onlinevaraus_tuotteet LIKE '%\"".$_SESSION['onlinevaraus']['paapalvelu']."\"%'
			");
		}

		$tyontekijat = Tyontekijat::model()->findAll($criteria);
		foreach($tyontekijat as $t)
		{
		   	$on = 'vapaa';
			$tekija = $this->loopForAjaat($t->id, $start, $stop, $date, $sumTuntiMin, $countStop, $tekija);
		}
		// Täysin vapaana -->


		// <-- Reika vuoron välillä
		$criteria=new CDbCriteria;
		$criteria->group = " tid  ";
		$criteria->condition = "
			pvm='".date("d.m.Y", strtotime($date))."'
			AND tid IN ( SELECT id FROM sivex_ttekijat WHERE aktiivinen=1 AND online_varauksen_valmina=1 )
			AND tid NOT IN ( SELECT tid FROM vuosilomat WHERE pvm='".date("Y-n-j", strtotime($date))."' )
		";

		if(!empty($tyo_toimialue))
		{
			$criteria->addCondition ("
				tid IN ( SELECT id FROM sivex_ttekijat WHERE tyo_toimialue LIKE '%".$tyo_toimialue."%' )
			");
		}

		if(!empty($sopiiva_tuotteet))
		{
			$criteria->addCondition ("
				tid IN ( SELECT id FROM sivex_ttekijat WHERE onlinevaraus_tuotteet LIKE '%\"".$sopiiva_tuotteet."\"%' )
			");
		}

		$aikavali = $asetukset->onlinevaraus_aikavali*3600;
		$tv = Tyovuoroot::model()->findAll($criteria);
		$i = 0;
		foreach($tv as $t)
		{
		$i++;
			$alku 	= 0;
			$loppu 	= 0;
			$countStop = strtotime($onlinevaraus_loppu.":00");

			// <-- Ensimmainen tyovuoro
			$criteria=new CDbCriteria;
			$criteria->order = " UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(pvm, loppu), '%d.%m.%Y %H:%i')) ASC ";
			$criteria->condition = "
				pvm='".$t->pvm."'
				AND tid='".$t->tid."'
			";
			$tv_first = Tyovuoroot::model()->find($criteria);
			//     Ensimmainen tyovuoro -->

			// <-- Reika valilla
			$criteria=new CDbCriteria;
			$criteria->order = " UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(pvm, loppu), '%d.%m.%Y %H:%i')) ASC ";
			$criteria->condition = "
				pvm='".$t->pvm."'
				AND tid='".$t->tid."'
			";
			$tv_all = Tyovuoroot::model()->findAll($criteria);
			foreach($tv_all as $item){
				if( 
					isset($edellinen_loppu) 
					and (strtotime($item->alku)-strtotime($edellinen_loppu)) > $sumTuntiSec+($aikavali*2)
				){
					$on = 'vapaa';
					$alku 	= strtotime($edellinen_loppu)+$aikavali;
					$loppu 	= $alku+$sumTuntiSec;
					$countStop = strtotime($item->alku)-$aikavali;

			   		$tekija = $this->loopForAjaat(
						$t->tid, 
						date("H:i",$alku), 
						date("H:i",$loppu), 
						$date,
						$sumTuntiMin,
						$countStop,
						$tekija
						);
				}
				$edellinen_alku = $item->alku;
				$edellinen_loppu = $item->loppu;
			}
			//     Reika valilla -->

			// <-- Viimeinen tyovuoro
			$criteria=new CDbCriteria;
			$criteria->order = " UNIX_TIMESTAMP(STR_TO_DATE(CONCAT(pvm, loppu), '%d.%m.%Y %H:%i')) DESC ";
			$criteria->condition = "
				pvm='".$t->pvm."'
				AND tid='".$t->tid."'
			";
			$tv_last = Tyovuoroot::model()->find($criteria);
			//     Viimeinen tyovuoro -->

			// <-- Ensimmainen ja sen ennen reikoja
			if(
				isset($tv_first->id)
				and ((strtotime($tv_first->alku)-$aikavali-$sumTuntiSec) - $alkuAstetuksesta) >= 0
			){
	   		   $on 		= 'vapaa';
			   $alku 	= $alkuAstetuksesta;
			   $loppu 	= $alku+$sumTuntiSec;
			   $countStop 	= strtotime($tv_first->alku)-$aikavali;

			   $tekija = $this->loopForAjaat(
					$t->tid, 
					date("H:i",$alku), 
					date("H:i",$loppu), 
					$date,
					$sumTuntiMin,
					$countStop,
					$tekija
					);
			}
			//     Ensimmainen ja sen ennen reikoja -->

			// <-- Viimeinen ja sen ennen reikoja
			if(
				isset($tv_last->id)
				and (strtotime($onlinevaraus_loppu.":00") - (strtotime($tv_last->loppu)+$aikavali+$sumTuntiSec)) >= 0
			){
	   		   $on 		= 'vapaa';
			   $alku 	= strtotime($tv_last->loppu)+$aikavali;
			   $loppu 	= $alku+$sumTuntiSec;
			   $countStop 	= strtotime($onlinevaraus_loppu.":00");

			   $tekija = $this->loopForAjaat(
					$t->tid, 
					date("H:i",$alku), 
					date("H:i",$loppu), 
					$date,
					$sumTuntiMin,
					$countStop,
					$tekija
					);

			}
			//     Viimeinen ja sen ennen reikoja -->
	

		}
		// Reika vuoron välillä -->

		ksort($tekija);
		$return = array($on,$tekija);
		return $return;
	}
*/

	protected function pyhatCheck($date){

	$dateMonth = '';
	$pyh = array();

	$dateMonth = date("d.m.Y",strtotime($date));
	$asetukset = AsetuksetForAll::model()->findbypk(1);
	$pyh = explode("\n",$asetukset->viralliset_pyhapaivat);

	if(
	   date("N",strtotime($date)) == 7
	   or strstr($asetukset->viralliset_pyhapaivat, $dateMonth)
	)
	return 'pyhat';
	elseif(date("N",strtotime($date)) == 6)
	return 'lauantai';

 	}


	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

}

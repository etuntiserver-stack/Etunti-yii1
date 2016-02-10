<?php

class LaskuController extends Controller
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

	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','etsikohde','etsiasiakas', 'etsisaaja','luoKohteista', 'luoAsiakaasta', 'tr_rivit', 'tr_rivitkk','lasku_pdf', 'finvoice','tr_rivit_tyhja','valitsetuote', 'hyvityslasku'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','etsikohde','etsiasiakas', 'etsisaaja','luoKohteista', 'luoAsiakaasta', 'tr_rivit','tr_rivitkk','lasku_pdf', 'finvoice','tr_rivit_tyhja','valitsetuote', 'hyvityslasku'),
                		'message'=>Yii::t('main', 'Tämä TASO ei kuuluu teille'),
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

		if(isset(Yii::app()->user->adminID) and in_array('3',$tas))
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

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}

	public function actionTr_rivit_tyhja()
	{
		$this->renderPartial('tr_rivit_tyhja');
	}


	public function actionHyvityslasku($id)
	{
		$lasku = $this->loadModel($id);
		$model=new Lasku;
		$model->attributes=$lasku->attributes;
		$model->hyvityslasku=$lasku->id;
		$model->laskun_nimetys="Hyvityslasku";
		$model->yhteensa_total='-'.$lasku->yhteensa_total;
		if($model->save()){

		$laskunRivit=LaskunRivit::model()->findAll("lid='".$lasku->id."'");
		foreach($laskunRivit as $rivit)
		{
		$lm=new LaskunRivit;
		$lm->attributes=$rivit->attributes;
		$lm->lid=$model->id;
		$lm->save();
		}

		$this->redirect(array('update','id'=>$model->id));

		} else {
		var_dump($model->getErrors());
		}


	}

	public function actionFinvoice($id)
	{

		$lasku=$this->loadModel($id);
		$laskunRivit=LaskunRivit::model()->findAll("lid='".$id."'");
		$asetukset=Asetukset::model()->find("id=1");
		$firmanTiedot=FirmanTiedot::model()->find("id=1");


		$this->render('finvoice', 

			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,

			));

	}

	public function actionLasku_pdf($id)
	{

		$lasku=$this->loadModel($id);
		$laskunRivit=LaskunRivit::model()->findAll("lid='".$id."'");
		$asetukset=Asetukset::model()->find("id=1");
		$firmanTiedot=FirmanTiedot::model()->find("id=1");


	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('lasku_pdf', 
			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			),true));
	          $html2pdf->Output();

/*
		$this->render('lasku_pdf', 
			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			));
*/
	}

/*
	public function actionTr_rivitkk($id)
	{

		$num 		= $_POST['num'];
		$kpl 		= $_POST['kpl'];
		$from 		= $_POST['from'];
		$to 		= $_POST['to'];
		$hinta 		= $_POST['hinta'];
		$yksikko 	= $_POST['yksikko'];

		$this->renderPartial('tr_rivitkk',array(
			'from'=>$from,
			'to'=>$to,
			'num'=>$num,
			'kohde'=>$id,
			'kpl'=>$kpl,
			'hinta'=>$hinta,
			'yksikko'=>$yksikko,
		));

	}
*/

	public function actionTr_rivit($id)
	{

		$num 		= $_POST['num'];
		$kpl 		= $_POST['kpl'];
		$from 		= $_POST['from'];
		$to 		= $_POST['to'];
		$hinta 		= $_POST['hinta'];
		$yksikko 	= $_POST['yksikko'];
		$onkokohde 	= $_POST['onkokohde'];

		$this->renderPartial('tr_rivit',array(
			'from'=>$from,
			'to'=>$to,
			'num'=>$num,
			'kohde'=>$id,
			'kpl'=>$kpl,
			'hinta'=>$hinta,
			'yksikko'=>$yksikko,
			'onkokohde'=>$onkokohde,
		));
	}

	public function actionValitsetuote()
	{
		$tuote = LaskutusTuotteet::model()->findbypk($_POST['tuoteID']);
		echo $tuote->tuotenimi."//".$tuote->hinta_alv_0."//".$tuote->alv."//".$tuote->yksikko."//".$tuote->hinta_alv_sis;

	}

	public function actionluoAsiakaasta($id)
	{
		echo 1;
	}

	public function actionLuoKohteista($id)
	{

	function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}



       		$criteria = new CDbCriteria();
		$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as t_tunnit ";
		$criteria->condition = " 
		kohdenID = '".$id."'
		and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
		BETWEEN 
		'".date("Y-m-d",strtotime($_POST['from']))."' AND '".date("Y-m-d",strtotime($_POST['to']))."'
		AND status='3'
		";
		$tot = Toteutuneet::model()->find($criteria); 
	
	
	       	$criteria = new CDbCriteria();
		$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit ";
		$criteria->condition = "
		kohdenID = '".$id."'
		and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
		BETWEEN 
		'".date("Y-m-d",strtotime($_POST['from']))."' AND '".date("Y-m-d",strtotime($_POST['to']))."'
		AND status='3'
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";	
		$lu = Mobile::model()->find($criteria); 
	
		echo num($lu->l_tunnit+$tot->t_tunnit);

	}

	public function actionEtsikohde($id, $tuntiTaiKk)
	{
	?>
	<script type="text/javascript">
 	  $(document).ready(function(){
		$('.selectpicker').selectpicker({
		      //style: 'btn btn-default',
		      //size: 4
		});
	  });
	</script>
	<?php
       		$criteria = new CDbCriteria();
       		$criteria->condition = " asiakasnumero='".$id."' ";
		$as = Asiakkaat::model()->find($criteria);

		if($tuntiTaiKk == 1)
		$yksikko = 'h';
		if($tuntiTaiKk == 2)
		$yksikko = 'kk';

		$body = '<b class="glyphicon glyphicon-home"></b><br>
		<select id="kohteet" class="selectpicker '.$yksikko.'" multiple title="Valitse kohteet">';
		$thisTrue = false;
		$onkoKohdeMaaritetty = false;


       		$criteria = new CDbCriteria();
       		$criteria->condition = " 
			asiakas_id='".$as->id."' 
			AND hinta_tyyppi='".$tuntiTaiKk."' AND hinta!=''
		";
		$k = Kohteet::model()->findAll($criteria);
		foreach($k as $a)
		{
		$thisTrue = true;
		$onkoKohdeMaaritetty = true;
		$body .= '<option value="'.$a->id.'//'.$a->hinta.'//'.$yksikko.'//onkohde">'.$a->osoite.' ( '.$a->hinta.'&euro;/'.$yksikko.' )</option>';
		}


       		$criteria = new CDbCriteria();
       		$criteria->condition = " 
			asiakas_id='".$as->id."' 
			AND hinta_tyyppi!='".$tuntiTaiKk."' AND hinta=''
		";
		$k = Kohteet::model()->findAll($criteria);
		foreach($k as $a)
		{
		$thisTrue = true;
		$onkoKohdeMaaritetty = true;
		$body .= '<option value="'.$a->id.'//'.$as->hinta.'//'.$yksikko.'//onkohde">'.$a->osoite.' ( '.$as->hinta.'&euro;/'.$yksikko.' )</option>';
		}




		// jos kohde ei ole maariteltu, kokeilemme asiakasta ota tietoja
		if($thisTrue == false and isset($as->id) and $tuntiTaiKk == $as->hinta_tyyppi)
		{
		$thisTrue = true;
		$onkoKohdeMaaritetty = false;
		$body .= '<option value="'.$as->id.'//'.$as->hinta.'//'.$yksikko.'//eikohde">'.$as->osoite.' ( '.$as->hinta.'&euro;/'.$yksikko.' )</option>';
		}
		$body .= '</select>';


		echo $body.'***'.$thisTrue;
	}


	public function actionEtsisaaja($id)
	{
		$a = Asetukset::model()->findbypk($id);
		echo $a->iban;
	}

	public function actionEtsiasiakas($id)
	{

		$a = Asiakkaat::model()->find(" asiakasnumero='".$id."' ");
		$k = Kohteet::model()->findAll(" asiakas_id='".$id."' ");

		$tyyppi = '';
		if(!empty($a->yrityksen_nimi))
		$tyyppi = "yritys**".$a->yrityksen_nimi."**".$a->y_tunnus;

		if(empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
		$tyyppi = "henkilo**".$a->yhteyshenkilo;

		$kodeOn = 0;
		if(isset($k[0]))
		$kodeOn = 1;

		$erapaiva = '';
		if(!empty($a->maksuehto))
		$erapaiva = date("Y-m-d",strtotime("+$a->maksuehto day"));

		echo $a->laskutus_kanava."//".$a->maksuehto."//".$tyyppi."//".$a->osoite."//".$a->postinumero."//".$a->kaupunki."//".$a->yhteyshenkilo."//".$a->puhelin."//".$kodeOn."//".$erapaiva."//".$a->valittajan_tunnus."//".$a->verkkolaskuosoite."//".$a->muistutuslasku_auto."//".$a->kirjeenluokka;
	}


	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	protected function yksikkot($row){
		$body = '';
		if($row)
		$body .= '<option value="'.$row.'">'.$row.' kpl</option>';
	
		$body .= '
			<option value="kpl">kpl</option>
			<option value="h">h</option>
			<option value="min">min</option>
			<option value="kk">kk</option>
			<option value="kg">kg</option>
		';
		return $body;
	}

	protected function alv($row){
		$body = '';
		if($row)
		$body .= '<option value="'.$row.'">'.$row.'</option>';
		$body .= '<option value="24">24</option>';
		for ($i = 0; $i <= 100 ; $i++) {
		    $body .= '<option value='.$i.'>'.$i.'</option>';
		}
		return $body;
	}

	public function actionCreate()
	{


		$model=new Lasku;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Lasku']))
		{

			//$vm = Lasku::model()->find(array('order'=>'id DESC'));

			$model->attributes=$_POST['Lasku'];
			$model->tilanne=0;
			$model->laskun_nimetys="Lasku";
			if($model->save()){


			// Viite
			function Viitenumero($string) {
			  $string = strval($string);
			  $paino = array(7, 3, 1);
			  $summa = 0;

			  for($i=strlen($string)-1, $j=0; $i>=0; $i--,$j++){
			    $summa += (int) $string[$i] * (int) $paino[$j%3];
			  }
			  $tarkiste = (10-($summa%10))%10;
			  return $string.$tarkiste;
			}


			$viite = Viitenumero($model->as_nro."00".$model->id);
			Lasku::model()->updatebypk($model->id, array('viitenumero'=>$viite, 'laskunumero'=>$model->id));

			foreach($_POST['tkoodi'] as $key=>$val)
			{
				$lr = new LaskunRivit;
				$lr->lid	=$model->id;
				$lr->rivi	=$key;
				$lr->tkoodi	=$_POST['tkoodi'][$key];
				//$lr->nimike	=$_POST['nimike'][$key];
				$lr->kpl	=$_POST['kpl'][$key];
				$lr->yksikko	=$_POST['yksikko'][$key];
				$lr->hinta	=$_POST['hinta'][$key];
				$lr->alv	=$_POST['alv'][$key];
				$lr->hinta_alv	=$_POST['hinta_alv'][$key];
				$lr->ale	=$_POST['ale'][$key];
				$lr->veroton	=$_POST['veroton'][$key];
				$lr->yhteensa_alv=$_POST['yhteensa_alv'][$key];
				$lr->save();
			}


		    // Lasku historia
		    $historia = new LaskuHistoria;
		    $historia->lid = $model->id;
		    $historia->status = "Lasku luotu";
		    $historia->palvelu = "local";
		    $historia->yht_euro = $model->yhteensa_total;
		    $historia->save();


				$this->redirect(array('update','id'=>$model->id));
				//$this->redirect(array('admin'));
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
		$model=$this->loadModel($id);
		$laskunRivit=LaskunRivit::model()->findAll("lid='".$id."'");
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_GET['tilanne']) and $_GET['tilanne'] == '1')
		{
			Lasku::model()->updatebypk($id, array('tilanne'=>1));
			$this->redirect(array('update','id'=>$model->id));
		}

		if(isset($_POST['Lasku']))
		{
			$model->attributes=$_POST['Lasku'];
			if($model->save()){

			LaskunRivit::model()->deleteAll("lid='".$id."'");


		if(isset($_POST['tkoodi']))
		{
			foreach($_POST['tkoodi'] as $key=>$val)
			{
				$lr = new LaskunRivit;
				$lr->lid	=$model->id;
				$lr->rivi	=$key;
				$lr->tkoodi	=$_POST['tkoodi'][$key];
				//$lr->nimike	=$_POST['nimike'][$key];
				$lr->kpl	=$_POST['kpl'][$key];
				$lr->yksikko	=$_POST['yksikko'][$key];
				$lr->hinta	=$_POST['hinta'][$key];
				$lr->alv	=$_POST['alv'][$key];
				$lr->hinta_alv	=$_POST['hinta_alv'][$key];
				$lr->ale	=$_POST['ale'][$key];
				$lr->veroton	=$_POST['veroton'][$key];
				$lr->yhteensa_alv=$_POST['yhteensa_alv'][$key];
				$lr->save();
			}
		}



				$this->redirect(array('update','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'laskunRivit'=>$laskunRivit,
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
		LaskunRivit::model()->deleteAll(" lid='".$id."' ");

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";

		if(isset($_POST['laskunumero']) and !empty(trim($_POST['laskunumero'])))
	        $criteria->addCondition (" laskunumero LIKE '%".$_POST['laskunumero']."%' ");

		if(isset($_POST['viitenumero']) and !empty(trim($_POST['viitenumero'])))
	        $criteria->addCondition (" viitenumero LIKE '%".$_POST['viitenumero']."%' ");

	
		$dataProvider=new CActiveDataProvider('Lasku', array(
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


	$asetukset=Asetukset::model()->findbypk(1);
	
	// <-- Trust
	if($asetukset->palvelu_tyyppi == 2)
	{
	$cid = $asetukset['trust_cid'];
	$api = $asetukset['trust_api'];
	$trust_url = $asetukset['trust_url'];

	$ch = curl_init();
	$data = array('cid'=>$cid, 'apicode'=>$api);
	curl_setopt($ch, CURLOPT_URL, $trust_url.'/API/statusupdates.php');
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	
    	$rss = curl_exec($ch);
    	curl_close($ch);

	if($xml = simplexml_load_string($rss, 'SimpleXMLElement', LIBXML_NOCDATA))
	{

	 if($xml->commonerror != 'No statusupdates')
	 {

		libxml_use_internal_errors(true);
		$sxe = simplexml_load_string($rss);
		if ($sxe) 
		{
/*
echo '<textarea class="form-control" rows="10">';
print_r($rss);
echo '</textarea>';
*/
	  	foreach ($sxe->status as $r) {
/*
echo '<textarea class="form-control" rows="10">';
print_r(json_encode($r));
echo '</textarea>';
exit;
*/

		$str = '';
	    	$str = 'statustime:'.trim($r->statustime).'//jobid:'.trim($r->jobid).'//billnum:'.trim($r->billnum).'//statusref:'.trim($r->statusref).'//statustext:'.trim($r->statustext).'//statuscode:'.trim($r->statuscode).'//statusid:'.trim($r->statusid).'//paydate:'.trim($r->paydate).'//amount:'.trim($r->amount).'//statustype:'.trim($r->statustype);
	

		$l = Lasku::model()->find(" trust_jobid='".trim($r->jobid)."' ");
		if(isset($l['id']))
		{
		    $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($r->statustime)));
	     	    Lasku::model()->updatebypk($l['id'], array('laskunumero'=>$r->billnum,'tilanne'=>$r->statuscode,'response_finvoice'=>$str,'tapahtumapvm'=>$tapahtumapvm));

		    $paydate = '';
		    if(isset($r->paydate) and !empty($r->paydate))
		    $paydate = $r->paydate;

		    $amount = 0;
		    $yhteensa_total = '';
		    if(isset($r->amount) and !empty($r->amount))
		    $amount = $r->amount;


		    // Lasku historia 
		    $historia = new LaskuHistoria;
		    $historia->time = $tapahtumapvm;
		    $historia->lid = $l['id'];
		    $historia->status = json_encode($r);
		    $historia->trust_statuscode = $r->statuscode;
		    $historia->palvelu = "trust";
		    $historia->paydate = $paydate;


	    	    $criteria = new CDbCriteria();
	    	    $criteria->order = "id DESC";
	    	    $criteria->condition = " lid='".$l['id']."' ";
		    $lh = LaskuHistoria::model()->find($criteria);
		    if(isset($lh->id))
		    {
			$amount = str_replace(",",".",$amount);
			$lh->yht_euro = str_replace(",",".",$lh->yht_euro);

		    	$historia->yht_euro = (float)$lh->yht_euro-(float)$amount;
		    } else {
		    	$historia->yht_euro = $l['yhteensa_total'];
		    }

		    $historia->amount = $amount;
		    $historia->save();


		}


		}

		}




	 }
	}
	}
	// Trust -->



		$model=new Lasku('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Lasku']))
			$model->attributes=$_GET['Lasku'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Lasku the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Lasku::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Lasku $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lasku-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    	protected function asianro($data,$row)
	{ 
		    $job_id = '';

		if($data->postita_jobid != '')
		    $job_id = 'Postita:<br>'.$data->postita_jobid;

		if($data->trust_jobid != '')
		    $job_id = 'Trust:<br>'.$data->trust_jobid;

            	return $job_id;
	}

    	protected function tilanneCheck($data,$row)
	{ 

       		$criteria = new CDbCriteria();
       		$criteria->select = " palvelu,id,status ";
       		$criteria->order = " id DESC ";
       		$criteria->condition = " lid='".$data->id."' ";
		$l = LaskuHistoria::model()->findAll($criteria);


		// <-- Trust
		$trust = false;
		$trustStr = '';
		$xml = array();
		$bd = '';
		$bd .= '<div class="pull-right btn btn-info btn-xs" data-toggle="collapse" data-target="#haku_'.$data->id.'">'.Yii::t('main', 'historia').' <b class="caret"></b></div>';
		$bd .= '<div class="collapse" id="haku_'.$data->id.'">';
		foreach($l as $d)
		{
		  if(isset($d->palvelu) and $d->palvelu == 'trust')
		  {

		    $json = json_decode($d->status, true);

		    if(isset($json['statustext']) and !empty($json['statustext']) and $json['statustext'] != 1){
		      if($trust == false)
		      echo '<b>'.date("d.m.Y",strtotime($json['statustime'])).'</b><br> <span id="first_'.$data->id.'">'.$json['statustext'].'</span>';
		      if($trust == true)
		      $bd .= '<div class="well"><b>'.date("d.m.Y",strtotime($json['statustime'])).'</b><br> '.$json['statustext'].'</div>';

		      $trust = true;
		    }

		  }
		}
		if($trust == true)
		{
		$bd .= '</div>';
		echo $bd;
		}
		//  Trust -->



		// <-- Postita
		$postita = false;
		$postitaStr = '';

		if(isset($l->palvelu) and $l->palvelu == 'postita')
		{

		$json = json_decode($l->status, true);
		$json = str_replace("{","",$json);
		$json = str_replace("}","",$json);
		$json = explode(", ",$json);
		$json = str_replace('"','',$json);

		  if(isset($json['0']) and $json['0'] == 'status: CO'){
		   $postitaStr = 'Vastaanotettu ja lähetetty';
		   $postita = true;
		  }

		}
		//  Postita -->



		    $tilanne = '';

		if($data->tilanne == 0 and $data->response_finvoice == '')
		    $tilanne = 'Luotu';
		elseif($data->tilanne == 1 and $trust == false)
		    $tilanne = 'Hyväksytty';
		elseif($data->tilanne == 2 and $postita == false and $trust == false)
		    $tilanne = 'Lähetetty';
		elseif($data->tilanne == 3)
		    $tilanne = 'Maksettu';
		elseif($postita == true)
		    $tilanne = $postitaStr;

            	return $tilanne;
	}


    	protected function avoinnaCheck($data,$row)
	{ 

       		$criteria = new CDbCriteria();
       		$criteria->select = " yht_euro ";
       		$criteria->order = " id DESC ";
       		$criteria->condition = " lid='".$data->id."' AND yht_euro!='' ";
		$l = LaskuHistoria::model()->find($criteria);

		$yht_euro = $data->yhteensa_total;
		if(isset($l->yht_euro))
		$yht_euro = $l->yht_euro;

            	return $yht_euro;
	}




}

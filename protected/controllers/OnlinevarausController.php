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
				'actions'=>array('index','view', 'check', 'aika', 'osoite', 'maksu', 'palvelu_ajax', 'palvelu_save_ajax', 'lisat_ajax', 'ajaat_ajax', 'aika_ajax', 'onkokohde', 'luouusi', 'checkout', 'maksettu', 'rekisteriseloste', 'tidtietoja', 'get_lomake_ajax'),
                		'users'=>array("*"),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'kaikki'),
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
	       	  return true;
		else
	       	   return false;		

		} else {
	            return false;
		}
	}

        public function init()
        {

                Yii::app()->theme = 'classic';
		parent::init();
		if(isset($_GET['domain']))
		{
		Yii::app()->user->setState('domain', $_GET['domain']);
		$this->redirect(array('index'));
		}
        }



	public function actionGet_lomake_ajax($id)
	{

		$model = Kohteet::model()->findbypk($id);
		$this->renderPartial('get_lomake_ajax', array('model'=>$model));
	}


	public function actionTidtietoja()
	{


		$tt = Tyontekijat::model()->findbypk($_POST['tid']);
		if(isset($tt->id))
		{

		$tietoja = '
		<div class="panel panel-success">
		  <div class="panel-heading"><b>'.$tt->tekijan_nimi.'</b></div>
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

		$rt = Asetukset::model()->findbypk(1);
		$rekisteriseloste = json_decode($rt->rekisteriseloste);
		$this->render('rekisteriseloste',array(
			'rekisteriseloste'=>$rekisteriseloste
		));

	}

	public function actionKaikki()
	{

                Yii::app()->theme = 'etunti';

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}

	        $criteria->addCondition (" DATE(time) BETWEEN '".$from."' AND '".$to."' ");


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


	public function actionCheckout()
	{
		$this->renderPartial('checkout');
	}

	public function actionMaksettu()
	{
		$this->render('maksettu');
	}

	public function actionOnkokohde()
	{
		if(isset($_POST['sahkoposti']))
		{

			$_SESSION['onlinevaraus']['sahkoposti'] = $_POST['sahkoposti'];

			$criteria=new CDbCriteria;
			$criteria->condition = " email='".$_POST['sahkoposti']."' ";
			$k = Kohteet::model()->findAll($criteria);
			if(isset($k[0]))
			{
			  $body = '<label>'.Yii::t('main', 'Valitse osoite').'</label>
			  <select id="valitseOsoite" class="form-control input-lg">
			  <option>'.Yii::t('main', 'Valitse osoite').'</option>';
			  foreach($k as $data)
			  {
			     $body .= '<option value="'.$data->id.'">'.$data->osoite.'</option>';
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

		$k = Kohteet::model()->find(" email='".$_POST['sahkoposti']."' ");

		if(isset($_POST) and !isset($k->id))
		{

		$asiakkaat = new Asiakkaat;
		$asiakkaat->attributes=$_POST;
		$asiakkaat->tyyppi = 'henkilo';
		$asiakkaat->aktiivinen = 1;

		  if($asiakkaat->save())
		  {

			$kohteet = new Kohteet;
			$kohteet->asiakas_id = $asiakkaat->id;
			$kohteet->etu_suku_nimet = $asiakkaat->yhteyshenkilo;
			$kohteet->osoite = $asiakkaat->osoite;
			$kohteet->pnumero = $asiakkaat->postinumero;
			$kohteet->kaupunki = $asiakkaat->kaupunki;
			$kohteet->puh_nro = $asiakkaat->puhelin;
			$kohteet->email = $asiakkaat->sahkoposti;
			$kohteet->muut = "Onlinevaraus ".date("d.m.Y");
			$kohteet->tietoja = $_POST['lisatietoja'];
		  	$kohteet->save();

		  }

		} 

			$bd = 'onOlemassa';
			if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
			{
				$ov = Onlinevaraus::model()->findbypk($_SESSION['onlinevaraus']['onlinevarausID']);
			} else {

				$ov = new Onlinevaraus;
			}

			if(isset($_POST['asiakas_id']) and !empty($_POST['asiakas_id']))
			$asiakas_id = $_POST['asiakas_id'];
			elseif(isset($_POST['asiakas_id']) and empty($_POST['asiakas_id']) and isset($asiakkaat->id))
			$asiakas_id = $asiakkaat->id;


				$ov->asiakas_id = $asiakas_id;
				$ov->yhteyshenkilo = $_POST['yhteyshenkilo'];
				$ov->puhelin = $_POST['puhelin'];
				$ov->osoite = $_POST['osoite'];
				$ov->postinumero = $_POST['postinumero'];
				$ov->kaupunki = $_POST['kaupunki'];
				$ov->lisatietoja = $_POST['lisatietoja'];
				$ov->sahkoposti = $_POST['sahkoposti'];

				if($ov->save())
				{
					$_SESSION['onlinevaraus']['onlinevarausID'] = $ov->id;

					if(isset($_SESSION['onlinevaraus']['modelTV']))
					{
				        $tv = Tyovuoroot::model()->updatebypk($_SESSION['onlinevaraus']['modelTV'], 
						array(
							'onlinevaraus_id' => $ov->id
						));
					}

					$bd = 'nytRedirectMaksulle';
				} else {
					var_dump($ov->errors);
				}


				echo json_encode($bd);
		

	}


	public function actionAika_ajax()
	{
		$this->renderPartial('aika_ajax');
	}


	public function actionAjaat_ajax()
	{
		if(isset($_POST['pvm']))
		$_SESSION['onlinevaraus']['valittuPVM'] = $_POST['pvm'];

		$data = 0;
		$this->renderPartial('ajaat_ajax',array(
			'data'=>$data,
		));

	}

	public function actionLisat_ajax()
	{
	   if(isset($_POST['id']))
	   {
		$model = OnlinevarausTuotteet::model()->findbypk($_POST['id']);
		if(isset($model->id))
		{
		    if($_POST['checked'] == 1)
		    {
			$_SESSION['onlinevaraus']['lisapalvelut'][$model->id] = $model->id;
			echo 'save';
		    }
		    if($_POST['checked'] == 0)
		    {
			unset($_SESSION['onlinevaraus']['lisapalvelut'][$model->id]);
			echo 'deleted';
		    }


		}
	   }
	}

	public function actionPalvelu_ajax()
	{

	   if(isset($_POST['clear']) and $_POST['clear'] == 'all')
	   {
		unset($_SESSION['onlinevaraus']);

		if(isset($_SESSION['onlinevaraus']['onlinevarausID']))
		$this->loadModel($_SESSION['onlinevaraus']['onlinevarausID'])->delete();

		echo 'cleared';
		exit;
	   }

	   if(isset($_POST['word']))
	   {
		$word = trim($_POST['word']);
		$criteria=new CDbCriteria;
		$criteria->order = " SUBSTRING_INDEX(nelio,'-',1) ";
		$criteria->condition = " nimike='".$word."' ";
		$data = OnlinevarausTuotteet::model()->findAll($criteria);

		$this->renderPartial('palvelu_ajax',array(
			'data'=>$data,
		));
	   }
	}

	public function actionPalvelu_save_ajax()
	{
	   if(isset($_POST['palvelu']) and isset($_POST['nelio']))
	   {
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			palvelu=0 AND nimike='".$_POST['palvelu']."' AND nelio='".$_POST['nelio']."' 
		";
		$model = OnlinevarausTuotteet::model()->find($criteria);
		if(isset($model->id))
		{
			$_SESSION['onlinevaraus']['paapalvelu'] = $model->id;
		}

		$this->renderPartial('palvelu_save_ajax',array(
			'model'=>$model,
			'sivu'=>'index',
		));

	   } elseif(isset($_POST['tid']) and isset($_POST['pvm'])) {

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

	   } elseif(isset($_POST['kohde'])) {


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
		if(isset($_GET['keskeyta']))
		{
			if(isset($_SESSION['onlinevaraus']['modelTV']))
			{
			$tv = Tyovuoroot::model()->findbypk($_SESSION['onlinevaraus']['modelTV']);
			if(isset($tv->id))
			Tyovuoroot::model()->deletebypk($tv->id);
			}

			unset($_SESSION['onlinevaraus']);
			$this->redirect('index');
		}
	
		$this->render('index');
	}

	public function actionAika()
	{
		$this->render('aika');
	}

	public function actionOsoite()
	{
		$this->render('osoite');
	}

	public function actionMaksu()
	{
		$this->render('maksu');
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


protected function build_calendar($month,$year,$dateArray) {


$months=array(
	'01'=>'Tammikuu',
	'02'=>'Helmikuu',
	'03'=>'Maaliskuu',
	'04'=>'Huhtikuu',
	'05'=>'Toukokuu',
	'06'=>'Kesäkuu',
	'07'=>'Heinäkuu',
	'08'=>'Elokuu',
	'09'=>'Syyskuu',
	10=>'Lokakuu',
	11=>'Marraskuu',
	12=>'Joulukuu'
	);


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
     $calendar .= "<h4>".$months[$month]." $year</h4>";
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

          // Seventh column (Saturday) reached. Start a new row.

          if ($dayOfWeek == 7) {

               $dayOfWeek = 0;
               $calendar .= "</tr><tr>";

          }
          
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";
	  $on = $this->pmvCal($date)[0];


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
	  if($date > date("Y-m-d") and $on == 'vapaa' and isset($_SESSION['onlinevaraus']['valittuPVM']) and $_SESSION['onlinevaraus']['valittuPVM'] != date("Y-m-d", strtotime($date)))
		 $tila .= '<td class="day link vapaa cal" pvm="'.$date.'"><div class="toolt" '.$tooltip.'>'.$currentDay.'</div></td>';
	  elseif($date > date("Y-m-d") and $on == 'vapaa' and !isset($_SESSION['onlinevaraus']['valittuPVM']))
		 $tila .= '<td class="day link vapaa cal" pvm="'.$date.'"><div class="toolt" '.$tooltip.'>'.$currentDay.'</div></td>';
	  elseif($date > date("Y-m-d") and $on == 'vapaa' and isset($_SESSION['onlinevaraus']['valittuPVM']) and $_SESSION['onlinevaraus']['valittuPVM'] == date("Y-m-d", strtotime($date)))
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

     if ($dayOfWeek != 7) { 
     
          $remainingDays = 7 - $dayOfWeek;
          $calendar .= "<td colspan='$remainingDays'>&nbsp;</td>"; 

     }
     
     $calendar .= "</tr>";


     $calendar .= "</table>";

     return $calendar;

}



	protected function pmvCal($date)
	{

		$lp = array();
		$ajaanReika = array();
		$on = 'vapaa';
		$aamuOn = 'kiinni';
		$vuorot = '';
		$criteria=new CDbCriteria;
		$criteria->condition = "online_varauksen_valmina=1 ";
		$tyontekijat = Tyontekijat::model()->findAll($criteria);

		$ti = 0;
		foreach($tyontekijat as $t)
		{
		$ti++;
		$on = 'vapaa';
		$criteria=new CDbCriteria;
		$criteria->order = " alku ASC";
		$criteria->condition = " 

			pvm='".date("d.m.Y", strtotime($date))."' 
			AND tid='".$t->id."'
			AND SUBSTRING_INDEX(alku,':',1) <= '18'
		";
		$tyovuorot = Tyovuoroot::model()->findAll($criteria);

		$a = 0;
		$l = 0;
		$l2 = 0;

		$sumTunti = ((float)$_SESSION['onlinevaraus']['sumTunti']*3600)+7199;
		$sumAamuIlta = ((float)$_SESSION['onlinevaraus']['sumTunti']*3600)+3599;
		$realSumMin = (float)$_SESSION['onlinevaraus']['sumTunti']*60;

		    foreach($tyovuorot as $tv)
		    {

			$on = 'vapaa';
			$aamuOn = 'kiinni';

			if($a == 0 and strtotime($tv->alku)-strtotime("08:00") >= $sumAamuIlta)
			{
			  $on = 'vapaa';
			  $aamuOn = 'vapaa';
			  $ajaanReika["08:00//".date("H:i",strtotime("08:00 +".$realSumMin." minutes")).'//'.$t->id] = $t->id;
			}

			if($l > 0 and (strtotime($tv->alku)-$l) <= $sumTunti and $aamuOn == 'kiinni'){
			  $on = 'kiinni';
			} elseif($l > 0 and (strtotime($tv->alku)-$l) >= $sumTunti){
			  $on = 'vapaa';
			  $l2zapas = date("H:i",strtotime($l2." +1 hour"));
			  $ajaanReika[$l2zapas."//".date("H:i",strtotime($l2zapas." +".$realSumMin." minutes")).'//'.$t->id] = $t->id;
			} elseif(strtotime("18:00")-strtotime($tv->loppu) <= $sumTunti and $aamuOn == 'kiinni'){
			  $on = 'kiinni';
			}
			  $a = strtotime($tv->alku);
			  $l = strtotime($tv->loppu);
			  $l2 = $tv->loppu;
			  $l2zapas = date("H:i",strtotime($l2." +1 hour"));

			  //$vuorot .= $tv->alku.' '.$tv->loppu.' '.$on.'<br>';
		    }

			// loppuilta
			if($l > 0 and strtotime("18:00")-$l >= $sumAamuIlta)
			{
			  $on = 'vapaa';
			  $ajaanReika[$l2zapas."//".date("H:i",strtotime($l2zapas." +".$realSumMin." minutes"))."//".$t->id] = $t->id;
			}


			if($on == 'vapaa')
			$lp[$t->id] = 'vapaa';


		}

	  	if(in_array('vapaa', $lp, true))
		$on = 'vapaa';

		$return = array($on,$ajaanReika);
		return $return;
	}


	protected function pyhatCheck($date){

	$dateMonth = '';
	$pyh = array();

	$dateMonth = date("d.m.Y",strtotime($date));
	$asetukset = Asetukset::model()->findbypk(1);
	$pyh = explode("\n",$asetukset->pyhapaivat);

	if(
	   date("N",strtotime($date)) == 7
	   or strstr($asetukset->pyhapaivat, $dateMonth)
	)
	return 'pyhat';
	elseif(date("N",strtotime($date)) == 6)
	return 'lauantai';

 	}

}

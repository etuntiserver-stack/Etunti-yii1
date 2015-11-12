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
				'actions'=>array('admin','delete','create','update','index','view','etsikohde','etsiasiakas', 'etsisaaja','luoKohteista','tr_rivit','tr_rivitkk','lasku_pdf', 'finvoice','tr_rivit_tyhja','valitsetuote'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','etsikohde','etsiasiakas', 'etsisaaja','luoKohteista','tr_rivit','tr_rivitkk','lasku_pdf', 'finvoice','tr_rivit_tyhja','valitsetuote'),
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

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}

	public function actionTr_rivit_tyhja()
	{
		$this->renderPartial('tr_rivit_tyhja');
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

	public function actionTr_rivitkk()
	{

		$hintaForTunti 	= $_POST['hintaForTunti'];
		$palvelu 	= $_POST['palvelu'];

		$this->renderPartial('tr_rivitkk',array(
			'palvelu'=>$palvelu,
			'hintaForTunti'=>$hintaForTunti,
		));
	}

	public function actionTr_rivit($num,$id)
	{

		$kpl 		= $_POST['kpl'];
		$lt 		= $_POST['lt'];
		$from 		= $_POST['from'];
		$to 		= $_POST['to'];

		$this->renderPartial('tr_rivit',array(
			'from'=>$from,
			'to'=>$to,
			'num'=>$num,
			'kohde'=>$id,
			'kpl'=>$kpl,
			'lt'=>$lt,
		));
	}

	public function actionValitsetuote()
	{
		$tuote = LaskutusTuotteet::model()->findbypk($_POST['tuoteID']);
		echo $tuote->tuotenimi."//".$tuote->hinta_alv_0."//".$tuote->alv."//".$tuote->yksikko."//".$tuote->hinta_alv_sis;

	}

	public function actionLuoKohteista($id)
	{

	function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}

       		$criteria = new CDbCriteria();
		$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as t_tunnit ";
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
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as l_tunnit ";
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

	public function actionEtsikohde($id)
	{
	?>
	<script type="text/javascript">
 	  $(document).ready(function(){
		$('.selectpicker').selectpicker({
		      style: 'btn-default',
		      //size: 4
		});
		/*
		$('.selectpicker').on('change', function(){
			$(".tyyppi").show('slow');
			$("#kohteistaRivit").val($(this).val());
		});
		*/
	  });
	</script>
	<?php
		$k = Kohteet::model()->findAll(" asiakas_id='".$id."' ");
		$body = '<b class="glyphicon glyphicon-home"></b><br>
		<select id="kohteet" class="selectpicker input-sm" multiple title="Valitse kohteet">';
		foreach($k as $a)
		$body .= '<option value="'.$a->id.'">'.$a->osoite.'</option>';
		$body .= '</select>';
		echo $body;
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

		echo $a->laskutus_kanava."//".$a->maksuehto."//".$tyyppi."//".$a->osoite."//".$a->postinumero."//".$a->kaupunki."//".$a->yhteyshenkilo."//".$a->puhelin."//".$kodeOn."//".$erapaiva."//".$a->valittajan_tunnus."//".$a->verkkolaskuosoite;
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
		$body .= '<option value="'.$row.'">'.$row.' %</option>';
		$body .= '<option value="24">24 %</option>';
		for ($i = 0; $i <= 24 ; $i++) {
		    $body .= '<option value='.$i.'>'.$i.' %</option>';
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

			$model->attributes=$_POST['Lasku'];
			$model->tilanne=0;

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
			Lasku::model()->updatebypk($model->id, array('viitenumero'=>$viite));

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
		$dataProvider=new CActiveDataProvider('Lasku');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
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
		    $tilanne = '';

		if($data->tilanne == 0)
		    $tilanne = 'Luotu';
		if($data->tilanne == 1)
		    $tilanne = 'Hyväksytty';
		if($data->tilanne == 2)
		    $tilanne = 'Lähetetty';

            	return $tilanne;
	}
}

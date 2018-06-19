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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('otakaytoon'),
                		'users'=>array("*"),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','etsikohde', 'etsikohde_by_yksikko', 'etsiasiakas', 'etsisaaja','luoKohteista', 'luoAsiakaasta', 'tr_rivit', 'tr_rivitkk','lasku_pdf', 'finvoice', 'postita', 'tr_rivit_tyhja','valitsetuote', 'hyvityslasku', 'postita_pdf', 'get_historia', 'kohteen_tieto', 'osoite_haku', 'indexnv', 'updatenv', 'laheta_valitsemmat', 'tr_rivit_jarjestelmavalvojat', 'tr_rivit_edico_tilaus', 'edico_tilaus_get_asiakas', 'auto', 'luolaskut', 'autolahetys'),
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
		   {
			return true;
		   } else {
			$this->redirect(array('/site/otakaytoon', 'tila' => 'lasku'));
		   }		

		} else {
			$this->redirect(array('/site/otakaytoon', 'tila' => 'lasku'));
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

		$asetukset = Asetukset::model()->findByPk(1);
		if(isset($asetukset->palvelu_tyyppi) and $asetukset->palvelu_tyyppi == 0)
		{
			$this->redirect(array('/site/otakaytoon', 'tila' => 'lasku'));
		}

                parent::init();
        }

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}

	public function actionEdico_tilaus_get_asiakas($edico_tilaus_id)
	{
		$return = array();
		$ov = Onlinevaraus::model()->findByPk($edico_tilaus_id);
		if(isset($ov->id))
		{
			$as = Asiakkaat::model()->findByPk($ov->asiakas_id);
			if(isset($as->id))
			{
				$return['asiakas_id'] = $as->asiakasnumero;
			}
		}

		echo json_encode($return);
		exit;
	}

	public function actionLuolaskut($from, $to, $asiakas_id=null, $asiakkaat_all=null, $laheta=null, $alvsis=null)
	{
		$asetukset = Asetukset::model()->findByPk(1);

       		$criteria = new CDbCriteria();
	        //$criteria->order = " id DESC ";
	        $criteria->condition = " 
		  aktiivinen='1'
		  AND id IN 
		    ( SELECT asiakas_id FROM sivex_kohdet 
		      WHERE id IN 
			( SELECT kohdenID FROM sivexkuitti 
			  WHERE 
			  DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			  BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			  AND status='3' 
			  AND hyvaksytty!=''
			  AND sairaus!=1
			  AND tv_id IS NOT NULL AND tv_id > 0
			  AND tv_id IN (
				SELECT id FROM sivex_tvuoro WHERE (tuoteID > 0 OR lisa_tuotteet!='') AND laskutettu='0'
			  )
			)
		    )
		";
		if( $asiakas_id !== null ){
	        $criteria->addCondition ("  id='".$asiakas_id."' ");
		}

		$lista = Asiakkaat::model()->findAll($criteria);
		$this->render('luolaskut', array(
			'asetukset' => $asetukset,
			'lista' => $lista,
			'from' => $from,
			'to' => $to,
			'asiakas_id' => $asiakas_id,
			'laheta' => $laheta,
			'alvsis' => $alvsis
		));
	}

	protected function hyvaksyttyListaByAsiakas($id, $from, $to){

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND status='3'
			AND sairaus!=1
			AND hyvaksytty!=''
			AND tv_id IS NOT NULL AND tv_id > 0
			AND tv_id IN (
				SELECT id FROM sivex_tvuoro WHERE (tuoteID > 0 OR lisa_tuotteet!='') AND laskutettu='0'
			)
			AND kohdenID IN (
				SELECT id FROM sivex_kohdet WHERE asiakas_id='".$id."'
			)
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
		";
		$lu = Mobile::model()->findAll($criteria);

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND status='3'
			AND sairaus!=1
			AND hyvaksytty!=''
			AND tv_id IS NOT NULL AND tv_id > 0
			AND tv_id IN (
				SELECT id FROM sivex_tvuoro WHERE tuoteID > 0 AND laskutettu='0'
			)
			AND kohdenID IN (
				SELECT id FROM sivex_kohdet WHERE asiakas_id='".$id."'
			)
		";
		$tot = Toteutuneet::model()->findAll($criteria);
		$lista = $lu;
		if( is_array($tot) and count($tot) > 0 ){ $lista = array_merge($lu, $tot); }

		return $lista;
	}

	public function actionAuto()
	{

		$from = date("Y-m-d", strtotime("first day of last month"));
		$to = date("Y-m-d");
		if( isset($_GET['from']) and !empty($_GET['from']) and isset($_GET['to']) and !empty($_GET['to']) ){
		        $from = date("Y-m-d", strtotime($_GET['from']));
			$to = date("Y-m-d", strtotime($_GET['to'])); 
		}

       		$criteria = new CDbCriteria();
       		$criteria->condition = "
			valmistettu_automaattiseesti='1'
			AND DATE(time) 
			BETWEEN '".$from."' AND '".$to."'
		";

		$dataProvider=new CActiveDataProvider('Lasku', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 200;
		$this->render('auto', array(
				'dataProvider' => $dataProvider, 
				'from'=>$from, 
				'to'=>$to
		));
	}

	protected function asiakasmuutos($asiakas)
	{
		if($asiakas->tyyppi == 'yritys')
		return $asiakas->yrityksen_nimi;
		if($asiakas->tyyppi == 'henkilo')
		return $asiakas->yhteyshenkilo;
	}

	public function actionTr_rivit_jarjestelmavalvojat()
	{
		$this->renderPartial('tr_rivit_jarjestelmavalvojat');
	}

	public function actionTr_rivit_edico_tilaus()
	{
		$this->renderPartial('tr_rivit_edico_tilaus');
	}

	public function actionTr_rivit_tyhja()
	{
		$this->renderPartial('tr_rivit_tyhja');
	}


	public function actionOsoite_haku()
	{

				$result = '';

		if(isset($_POST['word']))
		{
       			$criteria = new CDbCriteria();
       			$criteria->condition = " osoite LIKE '%".$_POST['word']."%' AND aktiivinen=1 ";
			$k=Kohteet::model()->findAll($criteria);
			if(isset($k[0]))
			{

				$result .= '<br><select class="form-control" id="loytyiOsoitteet">';
				$result .= '<option>'.Yii::t('main', 'Valitse asiakkaita kohteista').'</option>';
				foreach($k as $data)
				{
					$a=Asiakkaat::model()->findbypk($data->asiakas_id);
					$result .= '<option value="'.$a->asiakasnumero.'">'.$data->osoite.'</option>';
				}
				$result .= '</select>';

			}	
		}

				echo json_encode($result);

	}


	public function actionKohteen_tieto($id)
	{

		if(isset($_POST['from']) and isset($_POST['to']))
		{
			$from 	= $_POST['from'];
			$to 	= $_POST['to'];
		}

		if( $_POST['jakso'] == 'kk' )
		{
			$from = date("Y-m-d",strtotime($_POST['kuukausi'].' first day of this month'));
			$to = date("Y-m-d",strtotime($from.' last day of this month'));
		}

		$crit = $this->criteriaKohdeLasku($id, $from, $to);
		$luetut = $crit['lu'];
		$toteutuneet = $crit['tot'];

       		$criteria = new CDbCriteria();
		$criteria->condition = $toteutuneet;
		$tot = Toteutuneet::model()->findAll($criteria); 
	
	       	$criteria = new CDbCriteria();
		$criteria->condition = $luetut;	
		$lu = Mobile::model()->findAll($criteria); 

		$hyvaksytyt = array_merge($lu, $tot);



		$return = '';
		$hinnoittelu = '';
		$k=Kohteet::model()->findbypk($id);
		if(isset($k->id) and $k->hinnoittelu != '')
		{
			$hinnoittelu = '<h3>Hinnoittelu: '.$k->hinnoittelu.'</h3><br>';
			$return .= '<h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> '.Yii::t('main', 'Tietoja: '). ' </h2>';

			$return .= '<div class="panel heading-border"><div class="panel-body">';
			$return .= '<h1>'.$k->osoite.'</h1>';
			$return .= $hinnoittelu;
		}

		if(isset($k->hinnasto_id)){
		   $h = Hinnastot::model()->findByPk($k->hinnasto_id);
		}
		if(isset($h->id))
		{
			$return .= '<h2>'.Yii::t('main', 'Hinnasto: '). ' ' .$h->hinnaston_otsikko.'</h2>';
			$hr = HinnastotRivi::model()->findAll(" hinnastot_id='".$h->id."' ");
			$return .= '<table class="table table-bordered">';
			$return .= '<tr>';
			$return .= '<th>TUOTE</th>';
			$return .= '<th>HINTA TUOTTEISTA JA PALVELUISTA</th>';
			$return .= '<th>HINNASTON HINTA</th>';
			$return .= '<th>HINNASTON ALV%</th>';
			$return .= '<th>YHTEENSÄ</th>';
			$return .= '<th>YKSIKKÖ</th>';
			$return .= '</tr>';
			foreach($hr as $item)
			{
			  $tuote = TuotteetPalvelut::model()->findbypk($item->tuote_palvelu_id);
			  if(isset($tuote->id))
			  {
				$return .= '<tr>';
				$return .= '<td>'.$tuote->nimike.'</td>';
				$return .= '<td>'.$tuote->hinta_alv_0.'</td>';
				$return .= '<td>'.$item->hinnasto_hinta.'</td>';
				$return .= '<td>'.$item->hinnasto_alv.'</td>';
				$return .= '<td>'.$item->hinnasto_yht.'</td>';
				$return .= '<td>'.$item->hinnasto_yksikko.'</td>';
				$return .= '</tr>';
			  }
			}
			$return .= '</table>';

			if(count($hyvaksytyt) > 0)
			{
			   $return .= '<h2>'.Yii::t('main', 'Hyväksytyt tunnit').'</h2>';
			   $return .= '<table class="table table-bordered">';
				$return .= '<tr>';
				$return .= '<th>Tuote</td>';
				$return .= '<th>Päivämäärä</td>';
				$return .= '<td>Aloitus</td>';
				$return .= '<td>Lopetus</td>';
				$return .= '<td>Kesto</td>';
				$return .= '</tr>';
			   foreach($hyvaksytyt as $item)
			   {
			  	$tuote = TuotteetPalvelut::model()->findbypk($item->tuoteID);
				if(isset($tuote->nimike)) { $tuote = $tuote->nimike; } else { $tuote = ''; }
				$return .= '<tr>';
				$return .= '<td>'.$tuote.'</td>';
				$return .= '<td>'.date("d.m.Y", strtotime($item->aloitan)).'</td>';
				$return .= '<td>'.date("H:i", strtotime($item->aloitan)).'</td>';
				$return .= '<td>'.date("H:i", strtotime($item->loppui)).'</td>';
				$return .= '<td>'.$this->sprint((strtotime($item->loppui)-strtotime($item->aloitan))).'</td>';
				$return .= '</tr>';
			   }
			   $return .= '</table>';
			}


			$return .= '</div></div>';
		}

		echo json_encode(array('return' => $return));
	}

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	public function actionHyvityslasku($id)
	{

		$lasku = $this->loadModel($id);

		$asetukset=Asetukset::model()->findbypk(1);

		// <-- Jos netvisor niin laskunumero on seurava
		if($asetukset->palvelu_tyyppi == 4)
		{
       			$criteria = new CDbCriteria();
	       		$criteria->order = " laskunumero!='' DESC,id DESC ";
			$vm = Lasku::model()->find($criteria);
			if(isset($vm->id) and empty($model->laskunumero))
			$lasku->laskunumero = $vm->laskunumero+1;
		}
		//     Jos netvisor niin laskunumero on seurava -->


		$model=new Lasku;
		$model->attributes=$lasku->attributes;
		$model->hyvityslasku=$lasku->id;
		$model->laskun_nimetys="Hyvityslasku";
		$model->yhteensa_total='-'.$lasku->yhteensa_total;
		$model->netvisorkey='';
		if($model->save()){

		$laskunRivit=LaskunRivit::model()->findAll("lid='".$lasku->id."'");
		foreach($laskunRivit as $rivit)
		{
		$lm=new LaskunRivit;
		$lm->attributes=$rivit->attributes;
		$lm->lid=$model->id;
		//$lm->hinta='-'.$rivit->hinta;
		$lm->kpl='-'.$rivit->kpl;
		$lm->hinta_alv='-'.$rivit->hinta_alv;
		$lm->veroton='-'.$rivit->veroton;
		$lm->yhteensa_alv='-'.$rivit->yhteensa_alv;
		$lm->save();
		}


		// Lasku historia
		$historia = new LaskuHistoria;
		$historia->lid = $model->id;
		$historia->status = 'HYVITYSLASKU';
		$historia->palvelu = "local";
		$historia->yht_euro = $model->yhteensa_total;
		$historia->save();

		$this->redirect(array('update','id'=>$model->id));

		} else {
		var_dump($model->getErrors());
		}


	}

	public function actionPostita()
	{
       		$criteria = new CDbCriteria();
       		$criteria->condition = " postita_jobid!='' ";
		$lasku = Lasku::model()->findAll($criteria);
		$asetukset=Asetukset::model()->find("id=1");
		$firmanTiedot=FirmanTiedot::model()->find("id=1");


		$this->render('postita', 

			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'yritys'=>$firmanTiedot,

			));

	}

	public function actionFinvoice($id)
	{

		$lasku=$this->loadModel($id);
		$laskunRivit=LaskunRivit::model()->findAll("lid='".$id."'");
		$asetukset=Asetukset::model()->find("id=1");
		$firmanTiedot=FirmanTiedot::model()->find("id=1");


		$this->renderPartial('finvoice', 

			array(
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			));

	}

	protected function finvoiceAuto($id)
	{

		$lasku=$this->loadModel($id);
		$laskunRivit=LaskunRivit::model()->findAll("lid='".$id."'");
		$asetukset=Asetukset::model()->find("id=1");
		$firmanTiedot=FirmanTiedot::model()->find("id=1");


		$this->renderPartial('finvoice', 
			array(
			'id'=>$id,
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			'finvoiceTrust' => true,
			'autolaskutus' => true
			));
	}

	public function Lasku_pdf($id)
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
		//$content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);
		return $html2pdf->Output();

/*
		$file = $id.'_my_temp_pdf.pdf';
		$path = Yii::app()->request->baseUrl."temp/lasku_pdf/".Yii::app()->user->domain;

  		if (!file_exists($path))
		  	mkdir($path, 0777, true);

		file_put_contents($path.'/'.$file, $content_PDF);

		return $path.'/'.$file;
*/
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

	public function actionTr_rivit()
	{

		$num 		= $_POST['num'];
		$kpl 		= $_POST['kpl'];
		$from 		= $_POST['from'];
		$to 		= $_POST['to'];
		$hinta 		= $_POST['hinta'];
		$alv 		= $_POST['alv'];
		$yksikko 	= $_POST['yksikko'];
		$kohde_id 	= $_POST['kohde_id'];
		$free_text 	= $_POST['free_text'];

		$this->renderPartial('tr_rivit',array(
			'from'=>$from,
			'to'=>$to,
			'num'=>$num,
			'kpl'=>$kpl,
			'hinta'=>$hinta,
			'alv'=>$alv,
			'yksikko'=>$yksikko,
			'kohde_id'=>$kohde_id,
			'free_text'=>$free_text,
		));
	}

	public function actionValitsetuote()
	{
		$tuote = TuotteetPalvelut::model()->findbypk($_POST['tuoteID']);
		$asiakas = Asiakkaat::model()->find(" asiakasnumero='".$_POST['asiakas_nro']."' ");
		if(isset($tuote->id)) $tuoteID = $tuote->id; else $tuoteID = '';
		{
			$arr = array(
				'id' => $tuote->id,
				'tuotenimi' => $tuote->nimike,
				'hinta_alv_0' => $tuote->hinta_alv_0,
				'alv' => $tuote->alv,
				'yksikko' => $tuote->yksikko,
				'hinta_alv_sis' => $tuote->hinta_alv_sis,
			);
			$arr['hinnaston_otsikko'] = Yii::t('main', 'Hinnastoa ei määritetty');
			$arr['hinnasto_rivi_id'] = 0;

			if(isset($asiakas->id) and $asiakas->hinnasto_id != 0)
			{
				$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tuote->id."' AND hinnastot_id='".$asiakas->hinnasto_id."' ");
				if(isset($hinnasto->id))
				{
					$arr['hinta_alv_0'] = $hinnasto->hinnasto_hinta;
					$arr['alv'] = $hinnasto->hinnasto_alv;
					$arr['yksikko'] = $hinnasto->hinnasto_yksikko;
					$arr['hinta_alv_sis'] = $hinnasto->hinnasto_yht;

					$hn = Hinnastot::model()->findByPk($hinnasto->hinnastot_id);
					$arr['hinnaston_otsikko'] = Yii::t('main', 'Hinnasto: '). ' ' .$hn->hinnaston_otsikko;
					$arr['hinnasto_rivi_id'] = $hinnasto->id;
				}
			}
			echo json_encode($arr);

		}
		exit;

	}

	public function actionluoAsiakaasta($id)
	{
		echo 1;
	}

	public function actionLuoKohteista($id, $for)
	{

		if(isset($_POST['from']) and isset($_POST['to']))
		{
			$from 	= $_POST['from'];
			$to 	= $_POST['to'];
		}

		if( $_POST['jakso'] == 'kk' )
		{
			$from = date("Y-m-d",strtotime($_POST['kuukausi'].' first day of this month'));
			$to = date("Y-m-d",strtotime($from.' last day of this month'));
		}

		$crit = $this->criteriaKohdeLasku($id, $from, $to);
		$luetut= $crit['lu'];
		$toteutuneet = $crit['tot'];

       		$criteria = new CDbCriteria();
		$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as t_tunnit, COUNT(*) as count ";
		$criteria->condition = $toteutuneet;
		$tot = Toteutuneet::model()->find($criteria); 
	
	
	       	$criteria = new CDbCriteria();
		$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, COUNT(*) as count ";
		$criteria->condition = $luetut;	
		$lu = Mobile::model()->find($criteria); 



	
/*
		$kk_kpl = 0;
		$date1 = new DateTime(date("Y-m-d", strtotime($_POST['from'])));
		$date2 = new DateTime(date("Y-m-d", strtotime($_POST['to'])));
		$interval = date_diff($date1, $date2);
		$kk_kpl =  $interval->m + ($interval->y * 12);
*/

		$tunnit = 0;
		$rivi_kpl = 0;

		if(isset($lu->l_tunnit) or isset($tot->t_tunnit))
		{
			$tunnit = $this->num($lu->l_tunnit+$tot->t_tunnit);
			$rivi_kpl = $lu->count+$tot->count;
		}

		$return = array(
			'from' => $from,
			'to' => $to,
			'tunnit' => $tunnit,
			'rivi_kpl' =>$rivi_kpl,
			'fromto' => date("d.m.Y", strtotime($from)).' - '.date("d.m.Y", strtotime($to)),
			'kohde_id' => 0,
			'asiakas_id' => 0,
			'free_text' => ''
		);
		if( $for == 'kohde'){ $return['kohde_id'] = $id; }
		if( $for == 'asiakas'){ $return['asiakas_id'] = $id; }

		// <-- Palvelu Muoto 1 / Asiakas
		if( $for == 'asiakas' and isset($_POST['tuotteet_palvelut_muoto']) and $_POST['tuotteet_palvelut_muoto'] == 1){
			$asiakas = Asiakkaat::model()->findByPk($id);
			if( isset($asiakas->id) and $_POST['jakso'] == 'kk' and $asiakas->hinta_tyyppi == 2 )
			{
			$return['hinta'] 	= $asiakas->hinta;
			$return['alv'] 		= $asiakas->alv;
			$return['kpl'] 		= 1;
			$return['yksikko'] 	= 'kk';
			$return['free_text'] 	= $return['fromto'].' '.$asiakas->osoite.', '.$asiakas->kaupunki.' '.$asiakas->postinumero;
			}
			echo json_encode($return);
			exit;
		}
		//     Palvelu Muoto 1 / Asiakas -->

		// <-- Hinnastot
		$kohteet = Kohteet::model()->findByPk($id);


		$return['hinnasto_rivi_id'] 	= 0;
		$return['hinta'] 	= 0;
		$return['alv'] 		= 0;
		$return['kpl'] 		= 0;
		$return['yksikko'] 	= 'kpl';

		$r = $this->hinnastoHintaat($_POST['tuotePalvelu'], $_POST['asiakasnumero'], $kohteet, $tunnit, $rivi_kpl);
		$return['kpl'] 		= $r['kpl'];
		$return['hinta'] 	= $r['hinta'];
		$return['alv'] 		= $r['alv'];
		$return['yksikko']	= $r['yksikko'];

		// <-- Asiakkaan muoto
		if( isset($kohteet->id) and isset($_POST['tuotteet_palvelut_muoto']) and $_POST['tuotteet_palvelut_muoto'] == 1){
	
				$return['hinta'] 	= $kohteet->hinta;
				$return['alv'] 		= $kohteet->alv;

				if($kohteet->hinta_tyyppi == '1')
				{
					$return['kpl'] = $tunnit;
					$return['yksikko'] = 'h';
				}
				if($kohteet->hinta_tyyppi == '2')
				{
					$return['kpl'] = 1;
					$return['yksikko'] = 'kk';
				}
				if($kohteet->hinta_tyyppi == '3')
				{
					$return['kpl'] = $rivi_kpl;
					$return['yksikko'] = 'kpl';
				}
		}
		// Asiakkaan muoto -->

		if(isset($kohteet->id))
		{
			$return['osoite'] = $kohteet->osoite;
			$return['free_text'] 	= $return['fromto'].' '.$kohteet->osoite.', '.$kohteet->kaupunki.' '.$kohteet->pnumero;
		}

		echo json_encode($return);

	}

	protected function hinnastoHintaat($id, $asiakasnumero, $kohteet, $tunnit, $rivi_kpl)
	{
		$return = [];
		// <-- 1. TuotteetPalvelut
		$tp = TuotteetPalvelut::model()->findbypk($id);
		if(isset($tp->id))
		{
			$return['tp_nimike'] = $tp->nimike;
			$return['tp_id'] = $tp->id;

			if($tp->yksikko == 'h')
			{
				$return['kpl'] = $tunnit;
			}
			if($tp->yksikko == 'kpl')
			{
				$return['kpl'] = $rivi_kpl;
			}
			if($tp->yksikko == 'kk')
			{
				$return['kpl'] = 1;
			}

			$return['hinta'] 	= $tp->hinta_alv_0;
			$return['alv'] 		= $tp->alv;
			$return['yksikko']	= $tp->yksikko;
		}
		//     TuotteetPalvelut -->

		// <-- 2. Asiakas
       		$criteria = new CDbCriteria();
       		$criteria->condition = " asiakasnumero='".$asiakasnumero."' ";
		$asiakas = Asiakkaat::model()->find($criteria);
		if(isset($tp->id) and isset($asiakas->id) and $asiakas->hinnasto_id != 0)
		{
			$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$asiakas->hinnasto_id."' ");
			if(isset($hinnasto->id))
			{

				if($tp->yksikko == 'h')
				{
					$return['kpl'] = $tunnit;
				}
				if($tp->yksikko == 'kpl')
				{
					$return['kpl'] = $rivi_kpl;
				}
				if($tp->yksikko == 'kk')
				{
					$return['kpl'] = 1;
				}

				$return['hinnasto_rivi_id'] 	= $hinnasto->id;
				$return['hinta'] 	= $hinnasto->hinnasto_hinta;
				$return['alv'] 		= $hinnasto->hinnasto_alv;
				$return['yksikko']	= $hinnasto->hinnasto_yksikko;
			}
		}
		//     Asiakas -->

		// <-- 3. Kohteet
		if(isset($tp->id) and isset($kohteet->id) and $kohteet->hinnasto_id != 0)
		{
			$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$kohteet->hinnasto_id."' ");
			if(isset($hinnasto->id))
			{

				if($tp->yksikko == 'h')
				{
					$return['kpl'] = $tunnit;
				}
				if($tp->yksikko == 'kpl')
				{
					$return['kpl'] = $rivi_kpl;
				}
				if($tp->yksikko == 'kk')
				{
					$return['kpl'] = 1;
				}

				$return['hinnasto_rivi_id'] 	= $hinnasto->id;
				$return['hinta'] 	= $hinnasto->hinnasto_hinta;
				$return['alv'] 		= $hinnasto->hinnasto_alv;
				$return['yksikko']	= $hinnasto->hinnasto_yksikko;
			}
		}
		//     Kohteet -->

		return $return; 
	}

	protected function criteriaKohdeLasku($id, $from, $to)
	{

		$tot = " 
		kohdenID=$id
		and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
		BETWEEN 
		'".date("Y-m-d",strtotime($from))."' AND '".date("Y-m-d",strtotime($to))."'
		AND status='3'
		AND sairaus!=1
		AND laskutetaan=1
		";

		$lu = "
		kohdenID=$id
		and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
		BETWEEN 
		'".date("Y-m-d",strtotime($from))."' AND '".date("Y-m-d",strtotime($to))."'
		AND status='3'
		AND sairaus!=1
		AND laskutetaan=1
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";	

		return array('lu' => $lu, 'tot' => $tot );
	}

	public function actionEtsikohde($asiakasnumero)
	{

		$is_true = false;
       		$criteria = new CDbCriteria();
       		$criteria->condition = " asiakasnumero='".$asiakasnumero."' ";
		$asiakas = Asiakkaat::model()->find($criteria);

		$kohteet = '';
		$kohteet .= '<br><select class="selectpicker kohteet etsikohde_alasvetovaliko" multiple title="Valitse kohteet">';

		// <-- Kohteet
       		$criteria = new CDbCriteria();
       		$criteria->condition = " 
			asiakas_id='".$asiakas->id."' 
			AND aktiivinen=1
		";
		$k = Kohteet::model()->findAll($criteria);
		foreach($k as $item)
		{
			$is_true = true;
			$kohteet .= '<option value="'.$item->id.'" for="kohde">Kohde: '.$item->osoite.'</option>';
		}
		//     Kohteet -->

		$return = array(
			'kohteet'=>$kohteet,
			'is_true' => $is_true,
			'asiakas_id' => $asiakas->id
		);

		echo json_encode($return);
	}

	public function actionEtsikohde_by_yksikko($asiakasnumero, $hinta_tyyppi)
	{

		$is_true = false;
       		$criteria = new CDbCriteria();
       		$criteria->condition = " asiakasnumero='".$asiakasnumero."' ";
		$asiakas = Asiakkaat::model()->find($criteria);

		$kohteet = '';
		$kohteet .= '<br><select class="selectpicker kohteet etsikohde_alasvetovaliko" multiple title="Valitse kohteet">';

		// <-- Asiakas
		if( $hinta_tyyppi == 2 )
		{
       		$criteria = new CDbCriteria();
       		$criteria->condition = " 
			id='".$asiakas->id."' 
			AND aktiivinen=1
			AND hinta_tyyppi='".$hinta_tyyppi."'
		";
		$a = Asiakkaat::model()->find($criteria);
		if( isset($a->id))
		{
			$is_true = true;
			$kohteet .= '<option value="'.$a->id.'" for="asiakas">Asiakas: '.$a->osoite.'</option>';
		}
		}
		//     Asiakas -->

		// <-- Kohteet
       		$criteria = new CDbCriteria();
       		$criteria->condition = " 
			asiakas_id='".$asiakas->id."' 
			AND aktiivinen=1
			AND hinta_tyyppi='".$hinta_tyyppi."'
		";
		$k = Kohteet::model()->findAll($criteria);
		foreach($k as $item)
		{
			$is_true = true;
			$kohteet .= '<option value="'.$item->id.'" for="kohde">Kohde: '.$item->osoite.'</option>';
		}
		//     Kohteet -->

		$return = array(
			'kohteet'=>$kohteet,
			'is_true' => $is_true,
			'asiakas_id' => $asiakas->id
		);

		echo json_encode($return);
	}
	public function actionEtsisaaja($id)
	{
		$a = Asetukset::model()->findbypk($id);
		echo $a->iban;
	}

	public function actionEtsiasiakas($id)
	{

		$a = Asiakkaat::model()->findByPk($id);
		$k = Kohteet::model()->findAll(" asiakas_id='".$id."' AND aktiivinen=1 ");

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
		$erapaiva = date("d.m.Y",strtotime("+$a->maksuehto day"));

		$sahkoposti = '';
		if(!empty($a->sahkopostilaskuosoite))
			$sahkoposti = $a->sahkopostilaskuosoite;
		else
			$sahkoposti = $a->sahkoposti;

		echo json_encode($a->laskutus_kanava."//".$a->maksuehto."//".$tyyppi."//".$a->osoite."//".$a->postinumero."//".$a->kaupunki."//".$a->yhteyshenkilo."//".$a->puhelin."//".$kodeOn."//".$erapaiva."//".$a->valittajan_tunnus."//".$a->verkkolaskuosoite."//".$a->muistutuslasku_auto."//".$a->kirjeenluokka."//".$sahkoposti."//".$a->viivastyskorko);
	}


	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function yksikkot($row){
		$body = '';
		if($row)
		$body .= '<option value="'.$row.'">'.$row.' kpl</option>';
	

		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='laskutus_yksikko' ",array('order' => "select_type"));

		if(isset($l[0]))
		{
		    foreach($l as $k=>$v)
		    {
			$body .= '<option value="'.$v->value.'">'.$v->value.'</option>';
		    }
		}

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

	protected function Viite($string)
	{

		$string = strval($string);
		$paino = array(7, 3, 1);
		$summa = 0;

		  for($i=strlen($string)-1, $j=0; $i>=0; $i--,$j++){
		    $summa += (int) $string[$i] * (int) $paino[$j%3];
		  }
		$tarkiste = (10-($summa%10))%10;
		return $string.$tarkiste;

	}

	public function actionCreate()
	{

	// <-- Oikeudet
	   $checkOikeus = "lasku_1_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=new Lasku;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Lasku']))
		{

			//$vm = Lasku::model()->find(array('order'=>'id DESC'));

			$model->attributes=$_POST['Lasku'];
			$model->tilanne=0;
			$model->tapahtumapvm=date("Y-m-d H:i:s");
			$model->paivays=date("Y-m-d", strtotime($_POST['Lasku']['paivays']));
			$model->erapaiva=date("Y-m-d", strtotime($_POST['Lasku']['erapaiva']));
			$model->laskun_nimetys="Lasku";
			if($model->save()){


				// <-- digisten_tunnit_id
				if(isset($_POST['Lasku']['digisten_tunnit_id']) and $_POST['Lasku']['digisten_tunnit_id'] != '')
				DigistenTunnitKk::model()->updateByPk($_POST['Lasku']['digisten_tunnit_id'], array('laskutettu' => 1, 'lasku_id' => $model->id));
				//     digisten_tunnit_id -->

				// <-- edico_tilaus_id
				if(isset($_POST['Lasku']['edico_tilaus_id']) and $_POST['Lasku']['edico_tilaus_id'] != '')
				Onlinevaraus::model()->updateByPk($_POST['Lasku']['edico_tilaus_id'], array('laskutettu' => 1, 'lasku_id' => $model->id));
				//     edico_tilaus_id -->

				// Viite
				$viite = $this->Viite($model->as_nro."00".$model->id);
				Lasku::model()->updatebypk($model->id, array('viitenumero'=>$viite));


				// <-- LOG
			   	$l_m = Lasku::model()->findByPk($model->id);
				if( isset($l_m->id) )
				{
				$model_log 	= 'Lasku';
				$name_log 	= 'Lasku';
				$status_log 	= 'Create';

					$old_values = null;
					$new_values = json_encode($l_m->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->



			$as = Asiakkaat::model()->find(" asiakasnumero='".$model->as_nro."'  ");


			foreach($_POST['tkoodi'] as $key=>$val)
			{
				$lr = new LaskunRivit;
				$lr->lid	=$model->id;
				$lr->rivi	=$key;
				$lr->tkoodi	=$_POST['tkoodi'][$key];
				$lr->free_text	=$_POST['free_text'][$key];
				$lr->kpl	=$_POST['kpl'][$key];
				$lr->yksikko	=$_POST['yksikko'][$key];
				$lr->hinta	=$_POST['hinta'][$key];
				$lr->alv	=$_POST['alv'][$key];
				$lr->hinta_alv	=$_POST['hinta_alv'][$key];
				$lr->ale	=$_POST['ale'][$key];

				if(isset($_POST['tuoteID'][$key]))
					$lr->tuoteID = $_POST['tuoteID'][$key];
				if(isset($_POST['hinnasto_rivi_id'][$key]))
					$lr->hinnasto_rivi_id = $_POST['hinnasto_rivi_id'][$key];

				/*
				if(	isset($as->id) 
					and (int)$as->vinkki_tunnit > 0 
					and $_POST['ale'][$key] > 0
					and $_POST['yksikko'][$key] == 'h'
				)
				{
					$vinkki_tunnit = '';
					$vinkki_tunnit = (int)$as->vinkki_tunnit-$_POST['kpl'][$key];
					Asiakkaat::model()->updatebypk($as->id, array('vinkki_tunnit'=>$vinkki_tunnit));
				}
				*/

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

	// <-- Oikeudet
	   $checkOikeus = "lasku_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->


		$model=$this->loadModel($id);
		$laskunRivit=LaskunRivit::model()->findAll("lid='".$id."'", array('order'=>'id'));
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_GET['tilanne']) and $_GET['tilanne'] == '1')
		{
			Lasku::model()->updatebypk($id, array('tilanne'=>1));
			$this->redirect(array('update','id'=>$model->id));
		}

		if(isset($_POST['Lasku']))
		{
		/*
		echo '<pre>';
		print_r($_POST['tkoodi']);
		echo '</pre>';
		exit;
		*/
			$vanha_attr 	= $model->attributes;
			$model->attributes=$_POST['Lasku'];
			$model->paivays=date("Y-m-d", strtotime($_POST['Lasku']['paivays']));
			$model->erapaiva=date("Y-m-d", strtotime($_POST['Lasku']['erapaiva']));
			if($model->save()){


				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Lasku';
				$name_log 	= 'Lasku';
				$status_log 	= 'Update';

					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->


			LaskunRivit::model()->deleteAll("lid='".$id."'");


			if(isset($_POST['tkoodi']))
			{
			  foreach($_POST['tkoodi'] as $key=>$val)
			  {
				$lr = new LaskunRivit;
				$lr->lid	=$model->id;
				$lr->rivi	=$key;
				$lr->tkoodi	=$_POST['tkoodi'][$key];
				$lr->free_text	=$_POST['free_text'][$key];
				$lr->kpl	=$_POST['kpl'][$key];
				$lr->yksikko	=$_POST['yksikko'][$key];
				$lr->hinta	=$_POST['hinta'][$key];
				$lr->alv	=$_POST['alv'][$key];
				$lr->hinta_alv	=$_POST['hinta_alv'][$key];
				$lr->ale	=$_POST['ale'][$key];

				if(isset($_POST['tuoteID']))
					$lr->tuoteID = $_POST['tuoteID'][$key];
				if(isset($_POST['hinnasto_rivi_id']))
					$lr->hinnasto_rivi_id = $_POST['hinnasto_rivi_id'][$key];

				$lr->veroton	=$_POST['veroton'][$key];
				$lr->yhteensa_alv=$_POST['yhteensa_alv'][$key];
				$lr->save();
			  }
			}

		    		// Lasku historia
				$historia = new LaskuHistoria;
				$historia->lid = $model->id;
				$historia->status = "Lasku on muokattu";
				$historia->palvelu = "local";
				$historia->yht_euro = $model->yhteensa_total;
				$historia->save();

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

	// <-- Oikeudet
	   $checkOikeus = "lasku_3_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->


		$l_d = Lasku::model()->findbypk($id);

			if(isset($l_d->id))
			{
				// <-- LOG
				$model_log 	= 'Lasku';
				$name_log 	= 'Lasku';
				$status_log 	= 'Delete';
	
					$old_values = json_encode($l_d->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			}

		$this->loadModel($id)->delete();
		LaskunRivit::model()->deleteAll(" lid='".$id."' ");
		LaskuHistoria::model()->deleteAll("lid='".$id."'");

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

	// <-- Oikeudet
	   $checkOikeus = "lasku_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$lahettamattomat = false;
		$asetukset=Asetukset::model()->findbypk(1);

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= date("Y-m-d",strtotime($_POST['from']));
		$to 	= date("Y-m-d",strtotime($_POST['to']));
		}


		// <!-- Lasku updater
		$info 	= '';
		$info 	.= $this->LaskuUpdater($from,$to);
		// Lasku updater -->

       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";


		if(Yii::app()->request->getPost('asiakasLaskulle'))
       		$criteria->addCondition ( " as_nro='".Yii::app()->request->getPost('asiakasLaskulle')."' " );

        	$criteria->addCondition ("DATE(paivays) BETWEEN 
			'".$from."' AND '".$to."' 
		");

		if(isset($_POST['laskunumero']) and !empty(trim($_POST['laskunumero'])))
	        $criteria->addCondition (" laskunumero LIKE '%".$_POST['laskunumero']."%' ");

		if(isset($_POST['viitenumero']) and !empty(trim($_POST['viitenumero'])))
	        $criteria->addCondition (" viitenumero LIKE '%".$_POST['viitenumero']."%' ");

		if(isset($_POST['laskuosoite']) and !empty(trim($_POST['laskuosoite'])))
	        $criteria->addCondition (" osoite LIKE '%".$_POST['laskuosoite']."%' ");

		// <-- Luotu
		if( isset($_POST['tilaLaskulle']) and !empty($_POST['tilaLaskulle']) and $_POST['tilaLaskulle'] == 0 )
		{
			$criteria->addCondition (" tilanne=0 ");
		}
		//  Luotu -->


		// <-- Lahetamattomat hyväksyttyt
		if( (isset($_POST['tilaLaskulle']) and !empty($_POST['tilaLaskulle']) and $_POST['tilaLaskulle'] == 1) or (isset($_GET['lahettamattomat'])) )
		{
		$criteria->addCondition (" 
			tilanne=1 AND postita_jobid='' AND trust_jobid='' AND netvisorkey=0 
		");
		$lahettamattomat = true;
		}
		//     Lahetamattomat hyväksyttyt -->


		// POSTITA Lahetetty
		if(isset($_POST['tilaLaskulle']) and !empty($_POST['tilaLaskulle']) and $_POST['tilaLaskulle'] == 2 and $asetukset->palvelu_tyyppi == 1)
		{
		$criteria->addCondition ("
		id in (SELECT lid FROM 
			(SELECT lid FROM lasku_historia 
			   WHERE id IN (SELECT MAX(id) FROM lasku_historia GROUP BY lid)
			   AND postita_statuscode='SE'
			) as lid)
		");
		}

		// POSTITA Maksettu
		if(isset($_POST['tilaLaskulle']) and !empty($_POST['tilaLaskulle']) and $_POST['tilaLaskulle'] == 3 and $asetukset->palvelu_tyyppi == 1)
		{
		$criteria->addCondition ("
		id in (SELECT lid FROM 
			(SELECT lid FROM lasku_historia 
			   WHERE id IN (SELECT MAX(id) FROM lasku_historia GROUP BY lid)
			   AND status='MAKSETTU'
			) as lid)
		");
		}


		// Trust Lahetetty
		if(isset($_POST['tilaLaskulle']) and !empty($_POST['tilaLaskulle']) and $_POST['tilaLaskulle'] == 2 and $asetukset->palvelu_tyyppi == 2)
		{
		$criteria->addCondition ("
		id in (SELECT lid FROM 

			(SELECT lid FROM lasku_historia 
			   WHERE id IN (SELECT MAX(id) FROM lasku_historia GROUP BY lid)
			   AND trust_statuscode='98' AND palvelu='trust'
			) as lid)
		");
		}

		// Trust Maksettu
		if(isset($_POST['tilaLaskulle']) and !empty($_POST['tilaLaskulle']) and $_POST['tilaLaskulle'] == 3 and $asetukset->palvelu_tyyppi == 2)
		{
		$criteria->addCondition ("
		id in (SELECT lid FROM 
			(SELECT lid FROM lasku_historia 
			   WHERE id IN (SELECT MAX(id) FROM lasku_historia GROUP BY lid)
			   AND trust_statuscode='101' AND palvelu='trust'
			) as lid)
		");
		}

		// LOCAL Lahetetty
		if(isset($_POST['tilaLaskulle']) and !empty($_POST['tilaLaskulle']) and $_POST['tilaLaskulle'] == 2 and $asetukset->palvelu_tyyppi == 3)
		{
		$criteria->addCondition ("
		id in (SELECT lid FROM 
			(SELECT lid FROM lasku_historia 
			   WHERE id IN (SELECT MAX(id) FROM lasku_historia GROUP BY lid)
			   AND status='LÄHETETTY'
			) as lid)
		");
		}



		$dataProvider=new CActiveDataProvider('Lasku', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 200;
		$this->render('index', array(
				'dataProvider' => $dataProvider, 
				'from'=>$from, 
				'to'=>$to, 
				'asetukset' => $asetukset,
				'info' => $info,
				'lahettamattomat' => $lahettamattomat
		));
	}


	public function actionPostita_pdf($id)
	{
		$this->renderPartial('postita_pdf',array('id'=>$id));
	}


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


    	public function tilanneCheck($data)
	{ 

       		$criteria = new CDbCriteria();
       		$criteria->order = " id DESC ";
       		$criteria->condition = " lid='".$data->id."' ";
		$l = LaskuHistoria::model()->find($criteria);

		// <-- Trust
		$trust = false;
		$trustStr = '';
		if(isset($l->palvelu) and $l->palvelu == 'trust')
		{

		  $json = json_decode($l->status, true);
		    if(isset($json['statustext']) and !empty($json['statustext']))
		    {
		      	$trustStr = date("d.m.Y",strtotime($json['statustime'])).' '.$json['statustext'];
			$trust = true;
		    } elseif(!isset($json['statustext']) and isset($json['reference'])) {

			$trustStr = 'Vastaanotettu<br>';
			$trust = true;

		    } else {
		      	$trustStr = print_r($json);
			$trust = true;
		    }

		}
		//  Trust -->


		// <-- Postita
		$postita = false;
		$postitaStr = '';


		if(isset($l->palvelu) and $l->palvelu == 'postita')
		{

		  if($l->postita_statuscode == 'NE'){
		   $postitaStr = 'Lasku on vielä vahvistettava';
		   $postita = true;
		  } elseif($l->postita_statuscode == 'CO'){
		   $postitaStr = 'Odottaa lähetystä';
		   $postita = true;
		  } elseif($l->postita_statuscode == 'SE'){
		   $postitaStr = 'Lasku lähetetty';
		   $postita = true;
		  } elseif($l->postita_statuscode == 'CA'){
		   $postitaStr = 'Lasku peruutettu';
		   $postita = true;
		  } elseif($l->postita_statuscode == 'MAKSUMUISTUTUS'){
		   $postitaStr = 'Maksumuistutus lähetetty';
		   $postita = true;
		  } elseif($l->postita_statuscode == 'POISTETTU'){
		   $postitaStr = 'Lasku poistettu POSTITA.FI:sta';
		   $postita = true;
		  }

		}
		//  Postita -->



		// <-- Local
		$local = false;
		$localStr = '';
		if(isset($l->palvelu) and $l->palvelu == 'local')
		{

		  if($l->status == 'LÄHETETTY'){
		   $localStr = 'Lasku lähetetty';
		   $local = true;
		  } elseif($l->status == 'MAKSUMUISTUTUS'){
		   $localStr = 'Maksumuistutus lähetetty';
		   $local = true;
		  } elseif($l->status == 'MAKSETTU'){
		   $localStr = 'Lasku maksettu';
		   $local = true;
		  } elseif($l->status == 'Lasku luotu'){
		   $localStr = 'Lasku luotu';
		   $local = true;
		  } elseif($l->status == 'HYVÄKSYTTY'){
		   $localStr = 'Lasku hyväksytty';
		   $local = true;
		  } elseif($l->status == 'Lähetetty sähköpostilla'){
		   $localStr = 'Lähetetty sähköpostilla';
		   $local = true;
		  } elseif($l->status == 'Lasku mitätöity'){
		   $localStr = 'Lasku mitätöity';
		   $local = true;
		  }

		}
		//  Local -->
		


		// <-- Netvisor
		$netvisor = false;
		$netvisorStr = '';
		if(isset($l->palvelu) and $l->palvelu == 'netvisor')
		{
		   $netvisorStr = $l->status;
		   $netvisor = true;
		}
		//  Netvisor -->
  
		$tilanne = ''; 

		if($trust == true)
		    $tilanne = $trustStr; 
		elseif($postita == true)
		    $tilanne = $postitaStr;
		elseif($local == true)
		    $tilanne = $localStr;
		elseif($netvisor == true)
		    $tilanne = $netvisorStr;

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

	public function actionGet_historia()
	{

	$bod = '
	<div class="modal-dialog">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title">'.Yii::t('main', 'Historia').' '.$_POST['id'].'</h2>
	
		</div>
		<div class="modal-body">
		<div class="dialogTable clearfix modal-osio">';

       		$criteria = new CDbCriteria();
       		$criteria->select = " time,palvelu,postita_statuscode,status ";
       		$criteria->order = " id ASC ";
       		$criteria->condition = " lid='".$_POST['id']."' ";
		$lh = LaskuHistoria::model()->findAll($criteria);
		$la = Lasku::model()->findbypk($_POST['id']);

		$str = '';


		foreach($lh as $l)
		{

		  // <-- Trust
		  if(isset($l->palvelu) and $l->palvelu == 'trust')
		  {

		    $json = json_decode($l->status, true);
		    if(isset($json['statustext']) and !empty($json['statustext']))
		    {
		      	$str .= '<b>'.date("d.m.Y H:i",strtotime($json['statustime'])).'</b> '.$json['statustext'].'<br>';

		    } elseif(!isset($json['statustext']) and isset($json['accepted'])) {

			$str .= '<b>'.date("d.m.Y H:i",strtotime($la->time)).'</b> Vastaanotettu<br>';
			$trust = true;

		    } else {
		      	$str .= print_r($json);
			$trust = true;
		    }

		  }
		  //  Trust -->


		// <-- Postita
		if(isset($l->palvelu) and $l->palvelu == 'postita')
		{

		  if($l->postita_statuscode == 'NE'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku on vielä vahvistettava<br>';
		  } elseif($l->postita_statuscode == 'CO'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Odottaa lähetystä<br>';
		  } elseif($l->postita_statuscode == 'SE'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku lähetetty<br>';
		  } elseif($l->postita_statuscode == 'CA'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku peruutettu<br>';
		  } elseif($l->postita_statuscode == 'MAKSUMUISTUTUS'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Maksumuistutus lähetetty<br>';
		  } elseif($l->postita_statuscode == 'POISTETTU'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku poistettu POSTITA.FI:sta<br>';
		  }

		}
		//  Postita -->



		// <-- Local
		if(isset($l->palvelu) and $l->palvelu == 'local')
		{

		  if($l->status == 'LÄHETETTY'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku lähetetty<br>';
		  } elseif($l->status == 'MAKSUMUISTUTUS'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Maksumuistutus lähetetty<br>';
		  } elseif($l->status == 'MAKSETTU'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku maksettu<br>';
		  } elseif($l->status == 'Lasku luotu'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku luotu<br>';
		  } elseif($l->status == 'HYVÄKSYTTY'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku hyväksytty<br>';
		  } elseif($l->status == 'Lähetetty sähköpostilla'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lähetetty sähköpostilla<br>';
		  } elseif($l->status == 'Lasku mitätöity'){
		   $str .= '<b>'.date("d.m.Y H:i",strtotime($l->time)).'</b> Lasku mitätöity<br>';
		  } 

		}
		//  Local -->

		}


		$bod .= $str;

	$bod .= '
		</div>
		</div>
	   </div>
	</div>';

	echo json_encode($bod);
	}




	protected function netvisorLasku($tila, $model)
	{



		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	if(isset($n[0]))
	{
		if( $tila == 'add' )
		$url		= $n[0].'/salesinvoice.nv?method=add';
		if( $tila == 'edit' and !empty($model->netvisorkey))
		$url		= $n[0].'/salesinvoice.nv?id='.$model->netvisorkey.'&method=edit';

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
	

	$name = 'Ei tietoja';
	if(!empty($model->yritys))
	$name = $model->yritys;
	elseif(empty($model->yritys) and !empty($model->nimi))
	$name = $model->nimi;

	$InvoicingCustomerIdentifier = '';
	$asiakas = Asiakkaat::model()->find(" asiakasnumero='".$model->as_nro."' ");
	if(isset($asiakas->id) and $asiakas->netvisorkey != 0)
	{
		$InvoicingCustomerIdentifier = $asiakas->netvisorkey;
	} else {
		die('ERROR: Tämä asiakas ei saanut netvisorkey viellä');
	}


$xml = '
<root>
  <SalesInvoice>
    <SalesInvoiceNumber>'.$model->laskunumero.'</SalesInvoiceNumber>
    <SalesInvoiceDate format="ansi">'.date("Y-m-d", strtotime($model->paivays)).'</SalesInvoiceDate>
    <SalesInvoiceDeliveryDate format="ansi">'.date("Y-m-d", strtotime($model->paivays)).'</SalesInvoiceDeliveryDate>
    <SalesInvoiceReferenceNumber>'.$model->viitenumero.'</SalesInvoiceReferenceNumber>
    <SalesInvoiceAmount>'.$model->yhteensa_total.'</SalesInvoiceAmount>
    <SellerIdentifier type="netvisor">32</SellerIdentifier> 
    <SalesInvoiceStatus type="netvisor">unsent</SalesInvoiceStatus>
    <InvoicingCustomerIdentifier type="netvisor">'.$InvoicingCustomerIdentifier.'</InvoicingCustomerIdentifier>
    <InvoicingCustomerName>'.$name.'</InvoicingCustomerName>
    <InvoicingCustomerNameExtension></InvoicingCustomerNameExtension>
    <InvoicingCustomerAddressLine>'.$model->osoite.'</InvoicingCustomerAddressLine>
    <InvoicingCustomerPostNumber>'.$model->postinumero.'</InvoicingCustomerPostNumber>
    <InvoicingCustomerTown>'.$model->toimipaikka.'</InvoicingCustomerTown>
    <InvoicingCustomerCountryCode type="ISO-3166">FI</InvoicingCustomerCountryCode>
    <DeliveryAddressName>'.$name.'</DeliveryAddressName>
    <DeliveryAddressNameExtension>Lasku</DeliveryAddressNameExtension>
    <DeliveryAddressLine>'.$model->osoite.'</DeliveryAddressLine>
    <DeliveryAddressPostNumber>'.$model->postinumero.'</DeliveryAddressPostNumber>
    <DeliveryAddressTown>'.$model->toimipaikka.'</DeliveryAddressTown>
    <DeliveryAddressCountryCode type="ISO-3166">FI</DeliveryAddressCountryCode>
    <PaymentTermNetDays>'.$model->maksuehto.'</PaymentTermNetDays>';

$laskunRivit=LaskunRivit::model()->findAll("lid='".$model->id."'");

if(count($laskunRivit) > 0)
$xml .= '<InvoiceLines>';

foreach($laskunRivit as $rivit)
{

	$ProductIdentifier = '';
	$tuotteet = TuotteetPalvelut::model()->findByPk($rivit->tuoteID);
	if(isset($tuotteet->id) and $tuotteet->netvisorkey != 0){
		$ProductIdentifier = $tuotteet->netvisorkey;
	} elseif( $this->netvisorProductDefault() != 0 and !isset($tuotteet->id) or (isset($tuotteet->id) and $tuotteet->netvisorkey == 0) ){
		$ProductIdentifier = $this->netvisorProductDefault();
	} else {
		die('ERROR: ProductIdentifier');
	}

//             <SalesInvoiceProductLineFreeText>'.$rivit->free_text.'</SalesInvoiceProductLineFreeText>
/*
             <AccountingAccountSuggestion>3000</AccountingAccountSuggestion> 
             <Dimension>
                <DimensionName>Liiketoimintayksikkö laskentakohteena</DimensionName>
                <DimensionItem>Yleishallinto</DimensionItem>
             </Dimension>
             <Dimension>
                <DimensionName>Severan "työ" laskentakohteena</DimensionName>
                <DimensionItem>Makkaran paisto</DimensionItem>
             </Dimension>
*/
	$Comment = '';
	if(!empty($rivit->free_text)){
	$Comment = '
	<InvoiceLine>
		<SalesInvoiceCommentLine>
			<Comment>'.$rivit->free_text.'</Comment>
		    </SalesInvoiceCommentLine>
	</InvoiceLine>';
	}

$xml .= '
       <InvoiceLine>
         <SalesInvoiceProductLine>
             <ProductIdentifier type="netvisor">'.$ProductIdentifier.'</ProductIdentifier>
             <ProductName>'.$rivit->tkoodi.'</ProductName>
             <ProductUnitPrice type="net">'.$rivit->hinta.'</ProductUnitPrice>
             <ProductVatPercentage vatcode="KOMY">'.$rivit->alv.'</ProductVatPercentage>
             <SalesInvoiceProductLineQuantity>'.$rivit->kpl.'</SalesInvoiceProductLineQuantity>
             <SalesInvoiceProductLineDiscountPercentage>'.$rivit->ale.'</SalesInvoiceProductLineDiscountPercentage>
         </SalesInvoiceProductLine>
       </InvoiceLine>
            '.$Comment.'
       ';
}

if(count($laskunRivit) > 0)
$xml .= '</InvoiceLines>';

$xml .= '
  </SalesInvoice>
</root>';
	
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
		if( $tila == 'add' )
		$return=$result->Replies->InsertedDataIdentifier;
		if( $tila == 'edit' )
		$return=$result;

	  } else {

		echo '<pre>';
		print_r( $response );
		echo '</pre>';
		exit;

	  }


	} // if isset $n[0]

		return $return;

	}


	protected function netvisorGetsalesinvoice($netvisorkey)
	{

		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	  if(isset($n[0]))
	  {
		$url		= $n[0].'/getsalesinvoice.nv?netvisorkey='.$netvisorkey;
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
		    "X-Netvisor-Authentication-MAC: $getMAC\r\n"; 
		
	
		$optsGET = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Accept: text/plain\r\n" .
		              "Content-Type: application/x-www-form-urlencoded\r\n".
			      $auth_data,
		    'content'=> ''
		  )
		);
	
		$context = stream_context_create($optsGET);
		
		$response = file_get_contents($url, false, $context);
		$return = new SimpleXMLElement($response);
	   }

		return $return;
	}


	protected function netvisorList($lastmodifiedstart, $lastmodifiedend)
	{

		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	  if(isset($n[0]))
	  {
		$url		= $n[0].'/salesinvoicelist.nv?lastmodifiedstart='.$lastmodifiedstart.'&lastmodifiedend='.$lastmodifiedend;
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
		    "X-Netvisor-Authentication-MAC: $getMAC\r\n"; 
		
	
		$optsGET = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Accept: text/plain\r\n" .
		              "Content-Type: application/x-www-form-urlencoded\r\n".
			      $auth_data,
		    'content'=> ''
		  )
		);
	
		$context = stream_context_create($optsGET);
		
		$response = file_get_contents($url, false, $context);
		$return = new SimpleXMLElement($response);

	   }

		return $return;
	}

	public function actionIndexnv()
	{
		$result = $this->netvisorList(null,null);
		$this->render('indexnv', array('result'=>$result));
	}


	
	public function actionUpdatenv($id)
	{

/*

		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	  if(isset($n[0]))
	  {

		if(isset($_POST['Sales_Invoice_Number']))
		$url		= $n[0].'/salesinvoice.nv?id='.$id.'&method=edit';
		else
		$url		= $n[0].'/getsalesinvoice.nv?netvisorkey='.$id;

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
		    "X-Netvisor-Authentication-MAC: $getMAC\r\n"; 
		

		// update -->
		if(isset($_POST['Sales_Invoice_Number']))
		{

		header("Content-Type: text/html; charset=utf-8");



$xml = '
<root>
  <SalesInvoice>
    <SalesInvoiceDate format="ansi">'.date("Y-m-d", strtotime($_POST['Sales_Invoice_Date'])).'</SalesInvoiceDate>
    <SalesInvoiceDeliveryDate format="ansi">'.date("Y-m-d", strtotime($_POST['Sales_Invoice_Delivery_Date'])).'</SalesInvoiceDeliveryDate>
    <SalesInvoiceReferenceNumber>'.$_POST['Sales_Invoice_Reference_Number'].'</SalesInvoiceReferenceNumber>
    <SalesInvoiceAmount>'.$_POST['Sales_Invoice_Amount'].'</SalesInvoiceAmount>
    <SellerIdentifier type="netvisor">32</SellerIdentifier> 
    <SalesInvoiceStatus type="netvisor">'.$_POST['Sales_Invoice_Status'].'</SalesInvoiceStatus>
    <InvoicingCustomerIdentifier type="netvisor">1</InvoicingCustomerIdentifier>
    <InvoicingCustomerName>'.$_POST['Invoicing_Customer_Name'].'</InvoicingCustomerName>
    <InvoicingCustomerNameExtension></InvoicingCustomerNameExtension>
    <InvoicingCustomerAddressLine>'.$_POST['Invoicing_Customer_Address_Line'].'</InvoicingCustomerAddressLine>
    <InvoicingCustomerPostNumber>'.$_POST['Invoicing_Customer_Postnumber'].'</InvoicingCustomerPostNumber>
    <InvoicingCustomerTown>'.$_POST['Invoicing_Customer_Town'].'</InvoicingCustomerTown>
    <InvoicingCustomerCountryCode type="ISO-3166">FI</InvoicingCustomerCountryCode>
    <DeliveryAddressName>'.$_POST['Delivery_Address_Name'].'</DeliveryAddressName>
    <DeliveryAddressNameExtension>Lasku</DeliveryAddressNameExtension>
    <DeliveryAddressLine>'.$_POST['Delivery_Address_Line'].'</DeliveryAddressLine>
    <DeliveryAddressPostNumber>'.$_POST['Delivery_Address_Postnumber'].'</DeliveryAddressPostNumber>
    <DeliveryAddressTown>'.$_POST['Delivery_Address_Town'].'</DeliveryAddressTown>
    <DeliveryAddressCountryCode type="ISO-3166">FI</DeliveryAddressCountryCode>
    <PaymentTermNetDays>'.$_POST['Payment_Term_Net_Days'].'</PaymentTermNetDays>
    <PaymentTermCashDiscountDays>'.$_POST['Payment_Term_Cash_Discount_Days'].'</PaymentTermCashDiscountDays>
';


if(count($_POST['InvoiceLine']['ProductName']) > 0)
$xml .= '<InvoiceLines>';

$forLaskuRivit = array();
foreach($_POST['InvoiceLine']['ProductName'] as $key=>$rivit)
{

$xml .= '
       <InvoiceLine>
          <SalesInvoiceProductLine>
             <ProductIdentifier type="netvisor">8</ProductIdentifier>
             <ProductName>'.$_POST['InvoiceLine']['ProductName'][$key].'</ProductName>
             <ProductUnitPrice type="net">'.$_POST['InvoiceLine']['ProductUnitPrice'][$key].'</ProductUnitPrice>
             <ProductVatPercentage vatcode="KOMY">'.$_POST['InvoiceLine']['ProductVatPercentage'][$key].'</ProductVatPercentage>
             <SalesInvoiceProductLineQuantity>'.$_POST['InvoiceLine']['SalesInvoiceProductLineQuantity'][$key].'</SalesInvoiceProductLineQuantity>
             <SalesInvoiceProductLineDiscountPercentage>'.$_POST['InvoiceLine']['SalesInvoiceProductLineDiscountPercentage'][$key].'</SalesInvoiceProductLineDiscountPercentage>
             <AccountingAccountSuggestion>3000</AccountingAccountSuggestion> 
             <Dimension>
                <DimensionName>Liiketoimintayksikkö laskentakohteena</DimensionName>
                <DimensionItem>Yleishallinto</DimensionItem>
             </Dimension>
             <Dimension>
                <DimensionName>Severan "työ" laskentakohteena</DimensionName>
                <DimensionItem>Makkaran paisto</DimensionItem>
             </Dimension>
           </SalesInvoiceProductLine>
       </InvoiceLine>';

	$forLaskuRivit[] = array(
	   $_POST['InvoiceLine']['ProductName'][$key],
	   $_POST['InvoiceLine']['ProductUnitPrice'][$key],
	   $_POST['InvoiceLine']['ProductVatPercentage'][$key],
	   $_POST['InvoiceLine']['SalesInvoiceProductLineQuantity'][$key],
	   $_POST['InvoiceLine']['SalesInvoiceProductLineDiscountPercentage'][$key]
	);
}

if(count($_POST['InvoiceLine']['ProductName']) > 0)
$xml .= '</InvoiceLines>';

$xml .= '
  </SalesInvoice>
</root>';



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

		$l = Lasku::model()->find(" netvisorkey='".$id."' ");
		if(isset($l->id))
		{

			Lasku::model()->updateByPk($l->id, array(
				'paivays'=>date("Y-m-d", strtotime($_POST['Sales_Invoice_Date'])),
				'toimituspaiva'=>date("Y-m-d", strtotime($_POST['Sales_Invoice_Delivery_Date'])),
				'viitenumero'=>$_POST['Sales_Invoice_Reference_Number'],
				'yhteensa_total'=>$_POST['Sales_Invoice_Amount'],
				'viitenumero'=>$_POST['Sales_Invoice_Reference_Number'],
				'response'=>$_POST['Sales_Invoice_Status'],
				'nimi'=>$_POST['Invoicing_Customer_Name'],
				'osoite'=>$_POST['Invoicing_Customer_Address_Line'],
				'postinumero'=>$_POST['Invoicing_Customer_Postnumber'],
				'toimipaikka'=>$_POST['Invoicing_Customer_Town']
			));

		    	LaskunRivit::model()->deleteAll("lid='".$l->id."'");


			if(count($forLaskuRivit) > 0)
			{
			   foreach($forLaskuRivit as $key=>$val)
			   {
				$lr = new LaskunRivit;
				$lr->lid	=$l->id;
				$lr->rivi	=$key;
				$lr->tkoodi	=$forLaskuRivit[$key][0]; // on nimetus
				$lr->kpl	=$forLaskuRivit[$key][3];
				//$lr->yksikko	=$_POST['yksikko'][$key];
				$lr->hinta	=$forLaskuRivit[$key][1];
				$lr->alv	=$forLaskuRivit[$key][2];
				//$lr->hinta_alv	=$_POST['hinta_alv'][$key];
				$lr->ale	=$forLaskuRivit[$key][4];
				//$lr->veroton	=$_POST['veroton'][$key];
				//$lr->yhteensa_alv=$_POST['yhteensa_alv'][$key];
				if(!$lr->save())
				print_r($lr->getErrors()).'<br>';
			   }
			}


		}

			$this->redirect(array('indexnv'));

			echo '<pre>';
			print_r($result);
			echo '</pre>';
			exit;

	  } else {

			echo '<pre>';
			print_r($result);
			echo '</pre>';
			exit;
	  }



		}
		// loppu update -->


	
		$optsGET = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Accept: text/plain\r\n" .
		              "Content-Type: application/x-www-form-urlencoded\r\n".
			      $auth_data,
		    'content'=> ''
		  )
		);
	
		$context = stream_context_create($optsGET);
		
		$response = file_get_contents($url, false, $context);
		$result = new SimpleXMLElement($response);
	
		$this->render('updatenv', array('id'=>$id, 'result'=>$result));

	  }
*/
	}



	public function LaskuUpdater($from,$to)
	{
		$return = '';
		$asetukset=Asetukset::model()->findbypk(1);

		// <-- Netvisor updater
		$netvisorUpdateCheck = false;
		if($asetukset->palvelu_tyyppi == 4 and $asetukset->netvisor_kaytto == 1)
		{

			$netvisorList = $this->netvisorList(date("Y-m-d",strtotime($from)),date("Y-m-d",strtotime($to.' +1 day')));
			if($netvisorList->ResponseStatus->Status == 'OK')
			{
				foreach($netvisorList->SalesInvoiceList->SalesInvoice as $list)
				{
	
					//echo '<pre>';
					//print_r( $list );
					//echo '</pre>';
	
					$getLaskun = $this->netvisorGetsalesinvoice($list->NetvisorKey);
	
					if($getLaskun->ResponseStatus->Status == 'OK')
					{
						//echo '<pre>';
						//print_r( $getLaskun );
						//echo '</pre><hr>';
	
		
				       		$criteria = new CDbCriteria();
					        $criteria->condition = " netvisorkey='".$list->NetvisorKey."' ";
						$l = Lasku::model()->find($criteria);
	
						if(isset($l->id))
						{
						//echo $l->id.'<br>';
				       		$criteria = new CDbCriteria();
					        $criteria->order = " id DESC ";
					        $criteria->condition = " lid='".$l->id."' ";
						$h = LaskuHistoria::model()->find($criteria);
						}
		
						if( isset($l->id) and isset($h->id) and $l->id == $h->lid
							and 
							(
							$h->status != $getLaskun->SalesInvoice->InvoiceStatus 
							or $h->yht_euro != str_replace(",",".",$list->OpenSum)
							)
						)
						{
		
							//echo '<pre>';
							//print_r( $list );
							//echo '</pre>';
		
				    			// Lasku historia 
							$historia = new LaskuHistoria;
							$historia->time = date("Y-m-d H:i:s", strtotime($list->Invoicedate));
							$historia->lid = $l->id;
							$historia->status = $getLaskun->SalesInvoice->InvoiceStatus;
							$historia->palvelu = "netvisor";
							$historia->yht_euro = str_replace(",",".",$list->OpenSum);
							$historia->save();

							$netvisorUpdateCheck = true;
						}
	
	
					}
	
				}
			}
	
		}
		//exit;
		if($netvisorUpdateCheck == true)
		$return .= '<p>Netvisor laskut on päivitetty.</p>';
		//     Netvisor updater -->



		if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
		{
	
	
	
	
		// <-- Postita
		if($asetukset->palvelu_tyyppi == 1 and !isset(Yii::app()->user->laskunTarkistus))
		{
		Yii::app()->user->setState('laskunTarkistus', true);
	
		$username = $asetukset->postita_username;
		$password = $asetukset->postita_password;
		$auth_string = $username . ":" . $password;
	
	
	
			$url = 'https://postita.fi/api/job_list';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	
			$send_response = curl_exec($ch);
			curl_close($ch);
	
			$resultJson = json_encode($send_response);
			$job_ids = array();
			$postita_statuscode = array();
			$send_response = json_decode($send_response, true);
			if(isset($send_response[0]))
			{
			     foreach($send_response as $k => $v ) {
			       if($v['id'])
			       {
			          $job_ids[] = $v['id'];
			          $postita_statuscode[$v['id']] = $v['status'];
			       }
			     }
			}
			/*
			echo '<pre>';
			print_r($postita_statuscode);
			echo '</pre>';
	
			*/
	
			$ids = implode(",",$job_ids);
			$criteria = new CDbCriteria();
		    	$criteria->order = " id DESC ";
		    	//$criteria->group = " lid ";
	
			if(isset($ids[0]))
			{
			$ids = implode(",",$job_ids);
		    	$criteria->condition = " 
				id IN (SELECT MAX(id) FROM lasku_historia GROUP BY lid )
				AND lid IN (SELECT id FROM laskut where postita_jobid IN ($ids) ) 
			";
			}
	
			$lh=LaskuHistoria::model()->findAll($criteria);	
		
			$ch = curl_init();
			foreach($lh as $h)
			{
	
			$l = Lasku::model()->findbypk($h->lid);
	
			if(
				isset($l->postita_jobid) 
				and isset($postita_statuscode[$l->postita_jobid]) 
				and $postita_statuscode[$l->postita_jobid] != $h->postita_statuscode
			)
			{
	
			$url = 'https://postita.fi/api/job_info/'.(int)$l->postita_jobid;
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERPWD, $auth_string);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			
			$send_response = curl_exec($ch);
			$resultJson = json_encode($send_response);
			$send_response = json_decode($send_response, true);
			/*
			echo '<pre>';
	
			print_r($send_response);
			echo '</pre>';
			*/
	
			  if(isset($send_response['status']))
			  {
			       $job_id = '';
			       $created = '';
			     foreach($send_response as $k => $v ) {
			       $prep[$k] = $k.":".$v;
			       if($k == 'id')
			       $job_id = $v;
			       if($k == 'created')
			       $created = $v;
			     }
			     $tapahtumapvm = date("Y-m-d H:i:s",strtotime(trim($created)));
			     Lasku::model()->updatebypk($l->id, array('tapahtumapvm'=>$tapahtumapvm));
			
					    // Lasku historia
			    $historia = new LaskuHistoria;
			    $historia->time = $tapahtumapvm;
			    $historia->lid = $l->id;
			    $historia->status = $resultJson;
			    $historia->postita_statuscode = $send_response['status'];
			    $historia->palvelu = "postita";
			    $historia->yht_euro = $l->yhteensa_total;
			    $historia->save();
			  }
	
	
	
			} // (isset($l->postita_jobid))
			} // foreach
			curl_close($ch);
	
	
		}
		// Postita -->
	


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
		     	    Lasku::model()->updatebypk($l['id'], array('laskunumero'=>$r->billnum,'tilanne'=>$r->statuscode,'response_finvoice'=>	$str,'tapahtumapvm'=>$tapahtumapvm));
	
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


		} else { // jos ei localhost
			$return .= ''; //<h1>Ei päivitetään laskun tietoja, koska olet localhostina</h1>
		}

		return $return;

	}


	protected function lahetaNetvisoriin($id)
	{
		$return = false;
		$tapahtumapvm = date("Y-m-d H:i:s");
		Lasku::model()->updatebypk($id, array('tapahtumapvm'=>$tapahtumapvm));

	     	$l = Lasku::model()->findbypk($id);
		//$InsertedDataIdentifier = $this->netvisorLasku("edit", $model);
		$InsertedDataIdentifier = $this->netvisorLasku("add", $l);
		if(!empty($InsertedDataIdentifier)){
			Lasku::model()->updateByPk($id, array('netvisorkey'=>$InsertedDataIdentifier));
			$return = true;
		}

		return $return;
	}

	public function actionLaheta_valitsemmat($id)
	{
		$bod = '';
		$asetukset = Asetukset::model()->findByPk(1);

		// <-- Netvisor
		if($asetukset->palvelu_tyyppi == 4)
		{
			$return = $this->lahetaNetvisoriin($id);
			if($return != false)
				$bod = "OK";
			else
				$bod = "Error";
		}
		//     Netvisor -->

		return $bod;
	}


	protected function netvisorProductDefault()
	{

       		$criteria = new CDbCriteria();
	        $criteria->condition = " tuotenimi='Oletus tuote' AND netvisorkey!=0 ";
		$chkLT = LaskutusTuotteet::model()->find($criteria);
		if(isset($chkLT->id))
		{
			return $chkLT->netvisorkey;
		}



		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	if(isset($n[0]))
	{

		$url		= $n[0].'/product.nv?method=add';
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
	

$xml = '
<root>
  <product>
    <productbaseinformation>
      <productcode>-</productcode>
      <productgroup>-</productgroup>
      <name>Tuote</name>
      <description></description>
      <unitprice type="net">0</unitprice>
      <unit>kpl</unit>
      <unitweight>1</unitweight>
      <purchaseprice>0</purchaseprice>
      <tariffheading></tariffheading>
      <comissionpercentage>0</comissionpercentage>
      <isactive>1</isactive>
      <issalesproduct>0</issalesproduct>
      <inventoryenabled>1</inventoryenabled>
    </productbaseinformation>
    <productbookkeepingdetails>
      <defaultvatpercentage>24</defaultvatpercentage>
    </productbookkeepingdetails>
  </product>
</root>';
	
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
	
	
	  if($result->ResponseStatus->Status == 'OK' and !isset($chkLT->id))
	  {

		$lt = new LaskutusTuotteet;
		$lt->tuotenimi='Oletus tuote';
		$lt->hinta_alv_0='0';
		$lt->alv='24';
		$lt->yksikko='kpl';
		$lt->ryhma='1';
		$lt->is_active='0';
		$lt->netvisorkey=(int)$result->Replies->InsertedDataIdentifier;
		if($lt->save())
			return $lt->netvisorkey;
		else
			var_dump($lt->getErrors());


	  } else {

		echo '<pre>';
		print_r( $response );
		echo '</pre>';
		exit;

	  }

	


	} // if isset $n[0]



	}


}

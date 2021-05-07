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
				'actions'=>array('admin','delete','create','update','index','view','etsikohde', 'etsikohde_by_yksikko', 'etsiasiakas', 'etsisaaja','luoKohteista', 'luoAsiakaasta', 'tr_rivit', 'tr_rivitkk','lasku_pdf', 'finvoice', 'postita', 'tr_rivit_tyhja','valitsetuote', 'hyvityslasku', 'postita_pdf', 'get_historia', 'kohteen_tieto', 'osoite_haku', 'indexnv', 'updatenv', 'laheta_procountor', 'laheta_valitsemmat', 'tr_rivit_jarjestelmavalvojat', 'tr_rivit_edico_tilaus', 'edico_tilaus_get_asiakas', 'auto', 'luolaskut', 'autolahetys', 'update_autolahetteet', 'delete_autolahetteet', 'perpvmkohde', 'l_asiakkaat', 'kklaskuperasiakas', 'tuotepalvelukohdelle', 'laskutetuksi'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('insert_lahete'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdminNoTas()",
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

	public function isEtuntiAdminNoTas() {
		if(!isset(Yii::app()->user->adminID)){
			echo json_encode(array('error'=>'Kirjautuminen vaaditaan!'));
			exit;
		}
		if(isset(Yii::app()->user->adminID)){
			$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	       		if(isset($m->id) and $m->id == Yii::app()->user->adminID){
				return true;
			}
		}
		return false;
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

	public function actionLuolaskut($from, $to, $yrityksen_nimi=null, $asiakas_id=null, $asiakkaat_all=null, $luo=null, $laheta=null, $alvsis=null, $paivays=null, $erapaiva=null, $tunnit=null, $decimal=null, $ajax=null)
	{
		$asetukset = Asetukset::model()->findByPk(1);
		if( $asetukset->netvisor_kaytto != 1 or $asetukset->palvelu_tyyppi != 4 ){
			die('Netvisor ei ole aktiivinen asetuksessa.');
		}
		$paivays = date("Y-m-d", strtotime($paivays));
       		$criteria = new CDbCriteria();
	        //$criteria->order = " id DESC ";

		$alvsis_tuote = 'nolla';
		if($alvsis != null and $alvsis == 1){
			$alvsis_tuote = 'sis';
		}

		if( $yrityksen_nimi !== null and !empty($yrityksen_nimi) ){
	        $criteria->addCondition ("  yrityksen_nimi='".$yrityksen_nimi."' OR etunimi='".$yrityksen_nimi."' OR sukunimi='".$yrityksen_nimi."'  ");
		}
		if( $asiakas_id !== null ){
	        $criteria->addCondition ("  id='".$asiakas_id."' ");
		}
		if(isset($_GET['filter_tyyppi']) and !empty($_GET['filter_tyyppi']) and $_GET['filter_tyyppi'] == 'henkilo'){
			$criteria->addCondition(" tyyppi='".$_GET['filter_tyyppi']."' "); 
		}
		if(isset($_GET['filter_tyyppi']) and !empty($_GET['filter_tyyppi']) and $_GET['filter_tyyppi'] == 'yritys'){
			$criteria->addCondition(" tyyppi='".$_GET['filter_tyyppi']."' "); 
		}
		if(isset($_GET['filter_tyyppi']) and !empty($_GET['filter_tyyppi']) and $_GET['filter_tyyppi'] == 'kaikki'){
			$criteria->addCondition(" tyyppi='henkilo' OR tyyppi='yritys' "); 
		}
		if(isset($_GET['filter_postitoimipaikka']) and !empty($_GET['filter_postitoimipaikka'])){
			$criteria->addCondition(" kaupunki='".$_GET['filter_postitoimipaikka']."' "); 
		}
		if(isset($_GET['filter_tyoryhma']) and !empty($_GET['filter_tyoryhma'])){
			$criteria->addCondition(" tyoryhma='".$_GET['filter_tyoryhma']."' "); 
		}
		if(isset($_GET['filter_asiakasryhma']) and !empty($_GET['filter_asiakasryhma'])){
			$criteria->addCondition(" ryhma LIKE '%\"".$_GET['filter_asiakasryhma']."\"%' "); 
		}

		$al = Autolahetteet::model()->findAll(" from_date='".$from."' AND to_date='".$to."' AND laskutettu=0 AND tab_array!=''");
		$autolahetteet_asids = [];
		foreach($al as $item)
			$autolahetteet_asids[$item->asiakas_id] = ['al_id' => $item->id, 'tab_array' => $item->tab_array];

		if( $tunnit == 'mob' ){
		   $hyv_lista_all = $this->hyvaksyttyListaByAsiakasMobiilistaaAll($from, $to, true, $criteria->condition);
		   $asiakkaat_ids = [];
		   $attr = [];
		   foreach($hyv_lista_all as $item){
			$nimi = $item->kohteet->asiakkaat->AsiakasWithExtraContacts;
			if (isset($item->kohteet->asiakkaat) and !array_key_exists($nimi, $attr)) $attr[$nimi] = $item->kohteet->asiakkaat->attributes;
			$asiakkaat_ids[$nimi][$item->id] = [
				'mob_tunnit' => (isset($item->attributes))? $item->attributes : '',
				'mob_tunnit_tyovuoroot' => (isset($item->tyovuoroot->attributes))? $item->tyovuoroot->attributes : '', 
				'kohteet' => (isset($item->kohteet->attributes))? $item->kohteet->attributes : '', 
			];
		   }
		   foreach($asiakkaat_ids as $k => $i)
			if (array_key_exists($k, $attr)) $asiakkaat_ids[$k] =
				array_merge(['asiakas' => $attr[$k]], $asiakkaat_ids[$k]);

		   ksort($asiakkaat_ids);
		}

		$tv_controller = Yii::app()->createController('Tyovuoroot');
		if( $tunnit == 'tv' ){
		   $hyv_lista_all = $this->hyvaksyttyListaByAsiakasTyovuoroistaAll($from, $to, $criteria->condition, $tv_controller);
		   $asiakkaat_ids = [];
		   $attr = [];
		   foreach($hyv_lista_all as $d){
			$item = $d['data'];
			$nimi = (isset($item->kohteet->asiakkaat->id))? $item->kohteet->asiakkaat->AsiakasWithExtraContacts : 'Asiakas nimi puutuu';
			if (isset($item->kohteet->asiakkaat) and !array_key_exists($nimi, $attr)) $attr[$nimi] = $item->kohteet->asiakkaat->attributes;
			$asiakkaat_ids[$nimi][$d['this_id']] = [
				'this_id' => $d['this_id'],
				'this_pvm' => $d['this_pvm'],
				'this_tid' => $d['this_tid'],
				'tv_kesto' => $d['tv_kesto'],
				'toistuva' => $d['toistuva'],
				'tyovuoroot' => $item->attributes, 
				'kohteet' => (isset($item->kohteet->attributes))? $item->kohteet->attributes : '', 
				'mobile' => (isset($item->mobile->attributes))? $item->mobile->attributes : '',
				'toteutuneet' => (isset($item->mobile->toteutuneet->attributes))? $item->mobile->toteutuneet->attributes : '',
				//'tyontekijan_nimi' => (isset($item->tt->id))? $item->tt->$tt_order_1.' '.$item->tt->$tt_order_2 : '', 
			];
		   }
		   foreach($asiakkaat_ids as $k => $i)
			if (array_key_exists($k, $attr)) $asiakkaat_ids[$k] =
				array_merge(['asiakas' => $attr[$k]], $asiakkaat_ids[$k]);

		   ksort($asiakkaat_ids);
		}

/*
echo '<pre>';
print_r($asiakkaat_ids);
echo '</pre>';
exit;
*/
		$this->render('luolaskut', array(
			'tv_controller' => $tv_controller,
			'autolahetteet_asids' => $autolahetteet_asids,
			'asiakkaat_ids' => $asiakkaat_ids,
			'asetukset' => $asetukset,
			//'lista' => $lista,
			'from' => $from,
			'to' => $to,
			'paivays' => $paivays,
			'erapaiva' => $erapaiva,
			'asiakas_id' => $asiakas_id,
			'laheta' => $laheta,
			'luo' => $luo,
			'alvsis' => $alvsis,
			'decimal' => $decimal,
			'tunnit' => $tunnit,
			'yrityksen_nimi' => $yrityksen_nimi,
			'ajax' => $ajax
		));
	}

	protected function hyvaksyttyListaByAsiakasMobiilistaaAll($from, $to, $tvid_checker=false, $asiakas_condition){

	    $lista = array();

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) ASC ";
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND status='3'
			AND hyvaksytty!=''
			AND kohdenID > 0
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND deleted=0
			AND laskutetaan=1
			AND laskutettu=0
		";
		if($tvid_checker){
			$criteria->addCondition("
			tv_id IS NOT NULL AND tv_id > 0
			AND tv_id IN (
				SELECT id FROM sivex_tvuoro WHERE tid!=0 AND (tuoteID > 0 OR lisa_tuotteet!='') AND laskutettu='0'
			)
			");
		}
		if(!empty($asiakas_condition)){
			$criteria->addCondition("
			kohdenID IN(SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat
					WHERE $asiakas_condition	
				)
			)
			");
		}
		$lu = Mobile::model()->findAll($criteria);

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) ASC ";
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND status='3'
			AND hyvaksytty!=''
			AND kohdenID > 0
			AND deleted=0
			AND laskutetaan=1
			AND laskutettu=0
		";
		if($tvid_checker){
			$criteria->addCondition("
			tv_id IS NOT NULL AND tv_id > 0
			AND tv_id IN (
				SELECT id FROM sivex_tvuoro WHERE tid!=0 AND (tuoteID > 0 OR lisa_tuotteet!='') AND laskutettu='0'
			)
			");
		}
		if(!empty($asiakas_condition)){
			$criteria->addCondition("
			kohdenID IN(SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat
					WHERE $asiakas_condition	
				)
			)
			");
		}
		$tot = Toteutuneet::model()->findAll($criteria);
		$lista = $lu;
		if( is_array($tot) and count($tot) > 0 ){ $lista = array_merge($lu, $tot); }

	    return $lista;
	}

	protected function hyvaksyttyListaByAsiakasTyovuoroistaAll($from, $to, $asiakas_condition, $tv_controller){

		$lista = [];
		$haku_criteria = ["(laskutettu=0 or laskutettu is NULL) AND tuoteID > 0 AND status=3 AND tid!=0 AND (peruutettu=0 or peruutettu is NULL)"];
		if(!empty($asiakas_condition)){
			$haku_criteria[] = "
			kohde IN(SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN(SELECT id FROM asiakkaat
					WHERE $asiakas_condition	
				)
			)
			";
		}
		$lista = $tv_controller[0]->FromToSuunnitellutAll($from, $to, [], $haku_criteria, ['data','tv_kesto']);

		/*
		echo '<pre>';
		print_r($lista);
		echo '</pre>';
		exit;
		*/

		return $lista;
	}

	protected function hyvaksyttyListaByAsiakas($id, $from, $to, $tunnit){

	    $lista = array();
	    if( $tunnit == 'mob' ){
       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') ASC ";
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND status='3'
			AND hyvaksytty!=''
			AND kohdenID IN (
				SELECT id FROM sivex_kohdet WHERE asiakas_id='".$id."'
			)
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND deleted=0
			AND laskutetaan=1
		";
		$lu = Mobile::model()->findAll($criteria);

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') ASC ";
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND status='3'
			AND hyvaksytty!=''
			AND kohdenID IN (
				SELECT id FROM sivex_kohdet WHERE asiakas_id='".$id."'
			)
			AND deleted=0
			AND laskutetaan=1
		";
		$tot = Toteutuneet::model()->findAll($criteria);
		$lista = $lu;
		if( is_array($tot) and count($tot) > 0 ){ $lista = array_merge($lu, $tot); }
	    }

	    // TV
	    if( $tunnit == 'tv' ){
       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') ASC ";
	        $criteria->condition = " 
			alku!='' AND loppu!='' AND kohde!=0
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND kohde IN (
				SELECT id FROM sivex_kohdet WHERE asiakas_id='".$id."'
			)
			AND tid!=0
			AND status='3'
			AND peruutettu=0
			AND laskutettu=0
		";
		$lista = Tyovuoroot::model()->findAll($criteria);
	    }
	    return $lista;
	}

	public function actionUpdate_autolahetteet($id)
	{
		$model=Autolahetteet::model()->findByPk($id);
		if(isset($_POST['Autolahetteet']))
		{
			$model->attributes=$_POST['Autolahetteet'];
			if($model->save()){
				$this->redirect(array('auto'));
			}
		}
		$this->render('update_autolahetteet', array(
				'model' => $model
		));
	}

	public function actionDelete_autolahetteet($id)
	{
		$model=Autolahetteet::model()->deleteByPk($id);
		$this->redirect(array('auto'));
	}

	public function actionAuto()
	{
	/*
		$from = date("Y-m-d", strtotime("first day of last month"));
		$to = date("Y-m-d");
		if( isset($_GET['from']) and !empty($_GET['from']) and isset($_GET['to']) and !empty($_GET['to']) ){
		        $from = date("Y-m-d", strtotime($_GET['from']));
			$to = date("Y-m-d", strtotime($_GET['to'])); 
		}
	*/
       		$criteria = new CDbCriteria();
       		$criteria->order = " id DESC ";
		/*
       		$criteria->condition = "

		";
		*/
		$dataProvider=new CActiveDataProvider('Autolahetteet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 200;
		$this->render('auto', array(
				'dataProvider' => $dataProvider
		));
	}

	protected function base64url_encode($input) {
	    return strtr(base64_encode($input), '+/', '-_');
	}

	protected function asiakasmuutos($asiakas)
	{
		return $asiakas->Fullname;
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
					if( isset($a->id) and isset($data->osoite)){
					$result .= '<option value="'.$a->asiakasnumero.'">'.$data->osoite.'</option>';
					}
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
		$asiakas=Asiakkaat::model()->find(" asiakasnumero='".$lasku->as_nro."' ");
		$erapaiva = date("Y-m-d", strtotime("+14 day"));
		if(!empty($asiakas->maksuehto))
			$erapaiva = date("Y-m-d",strtotime("+$asiakas->maksuehto day"));


		$model=new Lasku;
		$model->attributes=$lasku->attributes;

		$criteria = new CDbCriteria();
       		$criteria->select = " id, MAX(ABS(laskunumero)) as laskunumero ";
		$vm = Lasku::model()->find($criteria);
		if( isset($vm->id) and $asetukset->lasku_laskunumero == 1)
			$model->laskunumero = $vm->laskunumero+1;

		$model->hyvityslasku=$lasku->id;
		$model->laskun_nimetys="Hyvityslasku";
		$model->yhteensa_total='-'.$lasku->yhteensa_total;
		$model->netvisorkey='';
		$model->paivays=date("Y-m-d");
		$model->erapaiva=$erapaiva;
		$model->tilanne=0;
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

	protected function finvoiceAuto($id, $lahetys_tyyppi)
	{

		$lasku=$this->loadModel($id);
		$laskunRivit=LaskunRivit::model()->findAll("lid='".$id."'");
		$asetukset=Asetukset::model()->find("id=1");
		$firmanTiedot=FirmanTiedot::model()->find("id=1");


		$lah = $this->renderPartial('finvoice', 
			array(
			'id'=>$id,
			'lasku'=>$lasku,
			'asetukset'=>$asetukset,
			'laskunRivit'=>$laskunRivit,
			'yritys'=>$firmanTiedot,
			$lahetys_tyyppi => true,
			'autolaskutus' => true
			), true);

		return $lah;
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
			'rivi_lisays' => $_POST['rivi_lisays'] ?? ''
		));
	}

	public function actionValitsetuote()
	{
		$tuote = TuotteetPalvelut::model()->findbypk($_POST['tuoteID']);
		$asiakas = Asiakkaat::model()->find(" asiakasnumero='".$_POST['asiakas_nro']."' ");
		if(isset($tuote->id))
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
					if( isset($hn->id) ){
						$arr['hinnaston_otsikko'] = Yii::t('main', 'Hinnasto: '). ' ' .$hn->hinnaston_otsikko;
						$arr['hinnasto_rivi_id'] = $hinnasto->id;
					}
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

	public function actionPerpvmkohde($from=null, $to=null, $valinnat)
	{

		$from 	= date ("Y-m-d", strtotime($from));
		$to 	= date ("Y-m-d", strtotime($to));
		$return = [];
		if(!empty($valinnat)){
			$valinnat_arr 	= explode(",", $valinnat);
			$tt 		= Tyontekijat::model()->findAll();
			$tids 		= [];
			foreach ($tt as $data)
				$tids[] = $data->id;

			$mobile = Yii::app()->createController('Mobile');
			if(count($valinnat_arr) > 0)
			{
				foreach($valinnat_arr as $kohde)
					$hyv_tyotunnit_all[$kohde] = $mobile[0]->TidfromtoMobiiliAll($from, $to, $tids, [3], 3, false, 0, true, $kohde, null);
				foreach($hyv_tyotunnit_all as $kohde => $arr)
					foreach($arr as $pvm => $arr2)
						foreach($arr2 as $k => $v)
							$return[$pvm][$kohde] = $this->num($v);
				ksort($return);
			}
			/*
			echo '<pre>';
			print_r($hyv_tyotunnit_all);
			echo '</pre>';
			exit;
			*/
		}
		echo json_encode($return);
		exit;
	}

	public function actionLuoKohteista($id, $for, $rivi_kpl = 0) // $tunnit = 0,
	{
		$tunnit = 0;

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
		//if( $_POST['rivien_teko'] == 'perkohde' )
		//{
			$tt 		= Tyontekijat::model()->findAll();
			$tids 		= [];
			foreach ($tt as $data)
				$tids[] = $data->id;

			$mobile 		= Yii::app()->createController('Mobile');
			$hyv_tyotunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tids, [3], 3, false, 0, true, $id, null);

			foreach($hyv_tyotunnit_all as $pvm => $arr){
				foreach($arr as $kohde => $sec){
					if($sec > 0){
						$rivi_kpl++;
						$tunnit += $this->num($sec);
					}
				}
			}

		//}
		$return = array(
			'from' => $from,
			'to' => $to,
			'tunnit' => $tunnit,
			'rivi_kpl' => $rivi_kpl,
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
		$k = Kohteet::model()->findByPk($id);
		$return['hinnasto_rivi_id'] 	= 0;
		$return['hinta'] 	= 0;
		$return['alv'] 		= 0;
		$return['kpl'] 		= 0;
		$return['yksikko'] 	= 'kpl';

		$r = $this->hinnastoHintaat($_POST['tuotePalvelu'], $_POST['asiakasnumero'], $k, $tunnit, $rivi_kpl);
		$return['kpl'] 		= (isset($r['kpl']))? $r['kpl'] : '';
		$return['hinta'] 	= (isset($r['hinta']))? $r['hinta'] : '';
		$return['alv'] 		= (isset($r['alv']))? $r['alv'] : '';
		$return['yksikko']	= (isset($r['yksikko']))? $r['yksikko'] : '';

		// <-- Asiakkaan muoto
		if( isset($k->id) and isset($_POST['tuotteet_palvelut_muoto']) and $_POST['tuotteet_palvelut_muoto'] == 1){
	
				$return['hinta'] 	= $k->hinta;
				$return['alv'] 		= $k->alv;

				if($k->hinta_tyyppi == '1')
				{
					$return['kpl'] = $tunnit;
					$return['yksikko'] = 'h';
				}
				if($k->hinta_tyyppi == '2')
				{
					$return['kpl'] = 1;
					$return['yksikko'] = 'kk';
				}
				if($k->hinta_tyyppi == '3')
				{
					$return['kpl'] = $rivi_kpl;
					$return['yksikko'] = 'kpl';
				}
		}
		// Asiakkaan muoto -->

		if(isset($k->id))
		{
			$return['osoite'] 	= $k->osoite;
			$return['free_text'] 	= (($_POST['rivien_teko'] == 'perpvmkohde')? date("d.m.Y", strtotime($_POST['from'])):$return['fromto']).' '.$k->osoite.', '.$k->kaupunki.' '.$k->pnumero;
		}

		echo json_encode($return);

	}

	protected function hinnastoHintaat($id, $asiakas, $kohteet, $tunnit, $rivi_kpl)
	{
		$return = [];
		// <-- 1. TuotteetPalvelut
		$tp = TuotteetPalvelut::model()->findbypk($id);
		if(isset($tp->id))
		{
			$return['tp_nimike'] = $tp->nimike;
			$return['tp_id'] = $tp->id;
			$return['alvsis'] = $tp->alvsis;

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
			$return['hinta_sis'] 	= $tp->hinta_alv_sis;
			$return['alv'] 		= $tp->alv;
			$return['yksikko']	= $tp->yksikko;
		}
		//     TuotteetPalvelut -->

		// <-- 2. Asiakas
		if(isset($tp->id))
		{
			if(isset($asiakas->id) and $asiakas->hinnasto_id != 0)
				$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$asiakas->hinnasto_id."' ");
			if(isset($asiakas['id']) and $asiakas['hinnasto_id'] != 0)
				$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$asiakas['hinnasto_id']."' ");
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
		if(isset($tp->id))
		{
			if(isset($kohteet->id) and $kohteet->hinnasto_id != 0)
				$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$kohteet->hinnasto_id."' ");
			if(isset($kohteet['id']) and $kohteet['hinnasto_id'] != 0)
				$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$kohteet['hinnasto_id']."' ");
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

	public function actionTuotepalvelukohdelle($id, $tuote)
	{
		$k = Kohteet::model()->findByPk($id);
		$k->tuote = $tuote;
		$k->save();
		
		echo json_encode('ok');
		exit;
	}
	
	protected function getHintaFor($for, $model, $yksikko)
	{		
		$return 			= [];
		$return['hinta'] 	= 0;
		$return['alv'] 		= 0;
		
		if( $for == 'kohde')
			$return['nimike'] 	= '<b class="text-danger link tuote_puutu" data-toggle="tooltip" kohde_id="'.$model->id.'" title="Klikkamalla tänne saa määrittellä kohdelle Tuote/Palvelu">Tuote puutuu</b>';
		else
			$return['nimike'] 	= '';
		
		$return['yksikko'] 	= $yksikko;
		$return['tuote_id'] = 0;

		if( $for == 'kohde')
			$tuote_id = $model->tuote;
		if( $for == 'tyovuoro')
			$tuote_id = $model->tuoteID;

		$tp = TuotteetPalvelut::model()->findByPk($tuote_id);
		if(isset($tp->id))
		{
		
			$return['nimike'] 	= $tp->nimike;
			$return['tuote_id']	= $tp->id;

			// <-- Kohde
			if( $for == 'kohde')
			{
				if($model->hinnasto_id != 0 and $model->tuote != 0)
				{
					$hinnasto = HinnastotRivi::model()->find("tuote_palvelu_id='".$model->tuote."' AND hinnastot_id='".$model->hinnasto_id."' AND hinnasto_yksikko='".$yksikko."'");
					if(isset($hinnasto->id))
					{
						$return['hinta'] 	= $hinnasto->hinnasto_hinta;
						$return['alv'] 		= $hinnasto->hinnasto_alv;
						$return['yksikko'] 	= $hinnasto->hinnasto_yksikko;
						return $return;
					}
					
					return $return;
					
				} elseif($model->hinnasto_id == 0 and $model->tuote != 0) {
				
					$tp = TuotteetPalvelut::model()->find("id='".$model->tuote."' AND yksikko='".$yksikko."'");
					if(isset($tp->id))
					{
							$return['hinta'] 	= $tp->hinta_alv_0;
							$return['alv'] 		= $tp->alv;
							$return['nimike']	= $tp->nimike;
							$return['yksikko']	= $tp->yksikko;
							$return['tuote_id']	= $tp->id;
							return $return;
					}

					return $return;
					
				} elseif($model->hinnasto_id == 0 and $model->tuote == 0 and isset($model->asiakkaat->id) and $model->asiakkaat->hinnasto_id != 0 and $model->asiakkaat->tuote != 0) { 

					$hinnasto = HinnastotRivi::model()->find("tuote_palvelu_id='".$model->asiakkaat->tuote."' AND hinnastot_id='".$model->asiakkaat->hinnasto_id."' AND hinnasto_yksikko='".$yksikko."'");
					if(isset($hinnasto->id))
					{
						$return['hinta'] 	= $hinnasto->hinnasto_hinta;
						$return['alv'] 		= $hinnasto->hinnasto_alv;
						return $return;
					}
					
					return $return;
							
				} elseif($model->hinnasto_id == 0 and $model->tuote == 0 and isset($model->asiakkaat->id) and $model->asiakkaat->hinnasto_id == 0 and $model->asiakkaat->tuote != 0) {
				
					$tp = TuotteetPalvelut::model()->find("id='".$model->asiakkaat->tuote."' AND yksikko='".$yksikko."'");
					if(isset($tp->id))
					{
						$return['hinta'] 	= $tp->hinta_alv_0;
						$return['alv'] 		= $tp->alv;
						$return['nimike']	= $tp->nimike;
						$return['tuote_id']	= $tp->id;
						return $return;
					}
					
					return $return;
				}
			}
			// Kohde -->
			
			// <-- Työvuoro
			if( $for == 'tyovuoro' and $model->tuoteID > 0)
			{
				$hinnasto = HinnastotRivi::model()->find("tuote_palvelu_id='".$model->tuoteID."' AND hinnastot_id='".$model->kohteet->hinnasto_id."' AND hinnasto_yksikko='".$yksikko."'");
				if(isset($hinnasto->id))
				{
					$return['hinta'] 	= $hinnasto->hinnasto_hinta;
					$return['alv'] 		= $hinnasto->hinnasto_alv;
					return $return;
				}

				$hinnasto = HinnastotRivi::model()->find("tuote_palvelu_id='".$model->tuoteID."' AND hinnastot_id='".$model->kohteet->asiakkaat->hinnasto_id."' AND hinnasto_yksikko='".$yksikko."'");
				if(isset($hinnasto->id))
				{
					$return['hinta'] 	= $hinnasto->hinnasto_hinta;
					$return['alv'] 		= $hinnasto->hinnasto_alv;
					return $return;
				}
				
				$tp = TuotteetPalvelut::model()->find("id='".$model->tuoteID."' AND yksikko='".$yksikko."'");
				if(isset($tp->id))
				{
					$return['hinta'] 	= $tp->hinta_alv_0;
					$return['alv'] 		= $tp->alv;
					$return['nimike']	= $tp->nimike;
					$return['tuote_id']	= $tp->id;
					return $return;
				}
			}
			// Työvuoro -->
		
		}

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
		AND deleted=0
		";

		$lu = "
		kohdenID=$id
		and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
		BETWEEN 
		'".date("Y-m-d",strtotime($from))."' AND '".date("Y-m-d",strtotime($to))."'
		AND status='3'
		AND sairaus!=1
		AND laskutetaan=1
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) 
		";	

		return array('lu' => $lu, 'tot' => $tot );
	}

	public function actionEtsikohde($asiakasnumero)
	{

		$is_true = false;
       		$criteria = new CDbCriteria();
       		$criteria->condition = " asiakasnumero='".$asiakasnumero."' ";
		$asiakas = Asiakkaat::model()->find($criteria);

		$kohteet = '';
		$kohteet .= '<br><select class="selectpicker kohteet etsikohde_alasvetovaliko" multiple title="Valitse kohteet" style="z-index: 999999">';

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
		$kohteet .= '<br><select class="selectpicker kohteet etsikohde_alasvetovaliko" multiple title="Valitse kohteet" style="z-index: 999999">';

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
		if(isset($a->id) and $a->tyyppi == 'yritys')
		$tyyppi = "yritys**".$a->yrityksen_nimi."**".$a->y_tunnus;

		if(isset($a->id) and $a->tyyppi == 'henkilo')
		$tyyppi = "henkilo**".$a->Etusukunimi;

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

		echo json_encode($a->laskutus_kanava."//".$a->maksuehto."//".$tyyppi."//".$a->osoite."//".$a->postinumero."//".$a->kaupunki."//".$a->Etusukunimi."//".$a->puhelin."//".$kodeOn."//".$erapaiva."//".$a->valittajan_tunnus."//".$a->verkkolaskuosoite."//".$a->muistutuslasku_auto."//".$a->kirjeenluokka."//".$sahkoposti."//".$a->viivastyskorko."//".$a->netvisor_dimension_name."//".$a->netvisor_dimension_item."//".str_replace("\n", "<br>", $a->lisatietoja_laskutuksesta));
	}


	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function yksikkot($row){
		$body = '';
		if($row){ $body .= '<option value="'.$row.'">'.$row.'</option>'; }
	
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
		$body .= '<option value="14">14</option>';
		$body .= '<option value="10">10</option>';
		$body .= '<option value="0">0</option>';
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

			$model->attributes = $_POST['Lasku'];
			$model->tilanne = 0;
			$model->tapahtumapvm = date("Y-m-d H:i:s");
			$model->paivays = date("Y-m-d", strtotime($_POST['Lasku']['paivays']));
			$model->erapaiva = date("Y-m-d", strtotime($_POST['Lasku']['erapaiva']));
			$model->laskun_nimetys = "Lasku";
			// <-- Dimension
			if( isset($_POST['Lasku']['netvisor_dimension_name']) ){
			   $dimension = explode("//", $_POST['Lasku']['netvisor_dimension_name']);
			   if( isset($dimension[0]) and isset($dimension[1]) ){
				$model->netvisor_dimension_name = $dimension[0];
				$model->netvisor_dimension_item = $dimension[1];
			   }
			}
			//     Dimension -->
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
				$viite = $this->Viite($model->as_nro."00".date("md").$model->id);
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

				$lr->veroton	=$_POST['veroton'][$key];
				$lr->yhteensa_alv=$_POST['yhteensa_alv'][$key];
				if($lr->save()){

				}
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

		// Procountor update status.
		if (Asetukset::model()->findByPk(1)->palvelu_tyyppi == 5) {

			// If invoice has been sent to Procountor, updated status.
			if (!empty($model->procountor_id ?? '')) {
				$error_msg = '';
				if ($this->updateProcountorInvoice($model->id, $error_msg)) {
					// Yii::app()->user->setFlash('success', 'Uusimmat laskun tiedot haettiin Procountorista.');
					$model->refresh();
				} elseif (!empty($error_msg)) {
					Yii::app()->user->setFlash('warning', 'Laskun tietojen päivitys Procountorista epäonnistui.');
				}
			}
			// If invoice is unapproved, and not sent to Procountor, add notification.
			elseif ($model->tilanne == 0) {
				Yii::app()->user->setFlash('primary', 'Lasku ei ole vielä lähetetty Procountoriin. Lähetä lasku hyväksymällä se alalaidassa olevalla painikkeella.');
			}
		}

		if(isset($_GET['tilanne']) and $_GET['tilanne'] == '1')
		{
			Lasku::model()->updatebypk($id, array('tilanne'=>1));
			$this->redirect(array('update','id'=>$model->id));
		}

		if(isset($_POST['Lasku']))
		{
			$vanha_attr 	= $model->attributes;
			$model->attributes=$_POST['Lasku'];
			$model->paivays=date("Y-m-d", strtotime($_POST['Lasku']['paivays']));
			$model->erapaiva=date("Y-m-d", strtotime($_POST['Lasku']['erapaiva']));
			// <-- Dimension
			if( isset($_POST['Lasku']['netvisor_dimension_name']) ){
			   $dimension = explode("//", $_POST['Lasku']['netvisor_dimension_name']);
			   if( isset($dimension[0]) and isset($dimension[1]) ){
				$model->netvisor_dimension_name = $dimension[0];
				$model->netvisor_dimension_item = $dimension[1];
			   }
			}
			//     Dimension -->
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
				if(isset($_POST['tunnit_id']))
					$lr->mobile_id = $_POST['tunnit_id'][$key];
				if(isset($_POST['tiedot']))
					$lr->tiedot = $_POST['tiedot'][$key];
					
				$lr->veroton	=$_POST['veroton'][$key];
				$lr->yhteensa_alv=$_POST['yhteensa_alv'][$key];
				$lr->save();
			  }
			}

		    		// Lasku historia
				$historia = new LaskuHistoria;
				$historia->lid = $model->id;
				$historia->status = "Muokattu";
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

	public function actionKklaskuperasiakas($asiakas_id, $from, $to, $rakenne_muoto, $rivi_muoto)
	{
		$asiakas	= Asiakkaat::model()->findByPk($asiakas_id);
		$ajanjakso 	= date("d.m.Y", strtotime($from)).'-'.date("d.m.Y", strtotime($to));

		if($rakenne_muoto == 'mobiili') 	$which_ids = 'mobiili_id';
		if($rakenne_muoto == 'tuovuoro') 	$which_ids = 'tv_id';

		$kk						= date("m.Y", strtotime($from));
		$etunti_tunniste		= 'la_'.$kk.'_'.$asiakas_id;
		$laskut					= Lasku::model()->findAll("etunti_tunniste='".$etunti_tunniste."'");
		$laskurivitAll			= [];
		$laskutetut_tuotteet	= [];
		
		if(count($laskut) > 0)
		{
			foreach($laskut as $item)
			{
				$laskuRivit			= LaskunRivit::model()->findAll("lid='".$item->id."' AND tiedot IS NOT NULL");
				foreach($laskuRivit as $rivi)
				{
					$laskurivitAll[] = $rivi;
					if(strpos($rivi->tiedot, $which_ids) !== false)
						$laskutetut_tuotteet[$rivi->tuoteID][$which_ids]['maara'][] = $rivi->kpl;
				}
			}
		}
			
		function prepare($which, $laskurivitAll)
		{
			
			$return = [];
			$pre_laskutetut_tiedot 	= [];		

			foreach($laskurivitAll as $rivi)
			{
				$get_tiedot = json_decode($rivi->tiedot, true);
				if(isset($get_tiedot[$which]))
				{
					if($which == 'kuukausi')
						$pre_laskutetut_tiedot[$which][] = $get_tiedot['kohde_id'];
					else
						$pre_laskutetut_tiedot[$which][] = $get_tiedot[$which];
				}
			}

		
			if(isset($pre_laskutetut_tiedot[$which]))
			{
				foreach($pre_laskutetut_tiedot[$which] as $item)
				{
					if(is_array($item))
					{
						foreach($item as $k => $id)
							$return[$id] = $id;
					} else {
						$return[$item] = $item;
					}
				}
			}
			return $return;
		}
		
		$laskutetut_tiedot					= [];
		$laskutetut_tiedot['kuukausi'] 		= prepare('kuukausi', $laskurivitAll);
		$laskutetut_tiedot['mobiili_id'] 	= prepare('mobiili_id', $laskurivitAll);
		$laskutetut_tiedot['tv_id'] 		= prepare('tv_id', $laskurivitAll);
		$laskutetut_tiedot['tuote_id'] 		= prepare('tuote_id', $laskurivitAll);

		// <-- KK logikka
		$kk_hinta = [];

		$kohteet 		= Kohteet::model()->findAll("asiakas_id='".$asiakas_id."'");
		foreach($kohteet as $item)
		{	
			$return = $this->getHintaFor('kohde', $item, 'kk');
			if($return['hinta'] > 0)
			{
				$kk_hinta[$item->asiakas_id][$item->id] = [
						'tuote_id' 		=> $item->tuote,
						'tuote' 		=> $item->osoite,
						'hinta' 		=> $return['hinta'], 
						'alv' 			=> $return['alv'],
						'yksikko' 		=> $return['yksikko'],
						'nimike' 		=> $return['nimike'],
						'free_text' 	=> $ajanjakso,
						'tiedot'		=> ['kuukausi' => $kk, 'kohde_id' => $item->id]
				];
			}
		}

			
		// <-- Mobiili logikka
		if($rakenne_muoto == 'mobiili')
			$getall 		= $this->hyvaksyttyListaByAsiakasMobiilistaaAll($from, $to, false, "id=$asiakas_id");
			
		if($rakenne_muoto == 'tuovuoro')
		{
			$haku_criteria 	= [
				"(laskutettu=0 or laskutettu is NULL) AND tid!=0 AND (peruutettu=0 or peruutettu is NULL)
				AND kohde IN(SELECT id FROM sivex_kohdet WHERE 
					asiakas_id='$asiakas_id'	
				)
				"
			];
			$tv_controller 	= Yii::app()->createController('Tyovuoroot');
			$getall 		= $tv_controller[0]->FromToSuunnitellutAll($from, $to, [], $haku_criteria, ['data','tv_kesto']);
		}
			
		$l 		= [];
		foreach($getall as $item)
		{
			$pikkuviesti 	= '';

			if($rakenne_muoto == 'mobiili')
			{
				$aloitan	= $item->aloitan;
				$kesto		= strtotime($item->loppui)-strtotime($item->aloitan);
				$tyovuorot 	= (isset($item->tyovuoroot->id))? $item->tyovuoroot : null;
				$kohde_id	= ($item->kohdenID > 0)? $item->kohdenID : null;
				$tv_id 		= (isset($tyovuorot->id))? $tyovuorot->id : 0;
				$tv_pvm 	= (isset($tyovuorot->pvm))? $tyovuorot->pvm : null;
			}
			
			if($rakenne_muoto == 'tuovuoro')
			{
				$tv_id 		= (isset($item['this_id']))? $item['this_id'] : $item['data']->id;
				$tv_pvm 	= (isset($item['this_pvm']))? $item['this_pvm'] : $item['data']->pvm;
				$item 		= $item['data'];
				$kesto		= strtotime($item->loppu)-strtotime($item->alku);
				$tyovuorot 	= $item;
				$aloitan	= $tyovuorot['pvm'].' '.$tyovuorot->alku;
				$kohde_id	= $tyovuorot->kohde;
			}

			if(isset($item->kohteet->asiakkaat->id) and $item->kohteet->asiakkaat->id == $asiakas_id)
			{
				// <-- pikkuviesti
				if($rakenne_muoto == 'mobiili')
				{
					$pv = explode("\n", $item->viesti);
					if(isset($pv[0]) and !empty($pv[0]) and strpos($pv[0], 'xxx') === false){
						$pikkuviesti = $pv[0];
					} elseif(isset($pv[1]) and !empty($pv[1]) and strpos($pv[1], 'xxx') === false){
						$pikkuviesti = $pv[1];
					}
				}

				// <-- Työvuoroista Tuote/Palvelu mukaan logikka
				$tyovuoro_tuotteet = [];
				if($tyovuorot !== null and $kohde_id > 0)
				{
					if($tyovuorot->tuoteID > 0)
					{
						$return = $this->getHintaFor('tyovuoro', $tyovuorot, 'h');
						$tyovuoro_tuotteet['paa_tuote']['tuote_id'] = $tyovuorot->tuoteID;
						$tyovuoro_tuotteet['paa_tuote']['tv_id'] 	= $tv_id;
						$tyovuoro_tuotteet['paa_tuote']['tv_pvm'] 	= $tv_pvm;
						$tyovuoro_tuotteet['paa_tuote']['nimike'] 	= $return['nimike'];
						$tyovuoro_tuotteet['paa_tuote']['hinta'] 	= $return['hinta'];
						$tyovuoro_tuotteet['paa_tuote']['alv'] 		= $return['alv'];
						$tyovuoro_tuotteet['paa_tuote']['yksikko'] 	= $return['yksikko'];
					}

					if($tyovuorot->lisa_tuotteet != null)
					{
						$lisa_tuotteet = json_decode($tyovuorot->lisa_tuotteet, true);
						foreach($lisa_tuotteet['tuote'] as $key => $tuote_id)
						{
							$tuotteet = TuotteetPalvelut::model()->findByPk($tuote_id);
							if(isset($tuotteet->id))
							{
								$tyovuoro_tuotteet['lisa_tuotteet'][] = [
									'tv_pvm'	=> $tyovuorot['pvm'],
									'tuote_id' 	=> $tuote_id,
									'nimike' 	=> $tuotteet->nimike,
									'hinta' 	=> $tuotteet->hinta_alv_0,
									'alv' 		=> $tuotteet->alv,
									'yksikko' 	=> $tuotteet->yksikko,
									'maara' 	=> $lisa_tuotteet['maara'][$key]
								];
							}
						}
					}
				}

				$l[strtotime($aloitan)][] = [
					'tv_id'				=> $tv_id,
					'tv_pvm'			=> $tv_pvm,
					'attributes' 		=> $item,
					'maara' 			=> $kesto,
					'pikkuviesti' 		=> $pikkuviesti,
					'tyovuoro_tuotteet' => $tyovuoro_tuotteet,
					'hinta_laskenta'	=> $this->getHintaFor('kohde', $item->kohteet, 'h')
				];
			}
		}
				
		ksort($l);
		if(count($l) > 0)
			$mob_lista[$asiakas_id] = $l;
		
		$body 		= '<br>';
		$yht_summ 	= 0;
		$kk_arr 	= [];
		$yht_kk 	= 0;
		$mob_tv_arr	= [];
		$yht_tunnit = 0;
		$num_rivi	= 0;
		$tv_ids		= [];

		$body .= '<table class="well table table-striped" border="1">';
		$body .= '<tr>';
		$body .= '<th></th>';
		$body .= '<th>'.Yii::t('main', 'Tuote').'</th>';
		$body .= '<th>'.Yii::t('main', 'Määrä').'</th>';
		$body .= '<th>'.Yii::t('main', 'Yks.').'</th>';
		$body .= '<th>'.Yii::t('main', 'Alv').'</th>';
		$body .= '<th>'.Yii::t('main', 'Hinta').'</th>';
		$body .= '<th>'.Yii::t('main', 'Yhteensä').'</th>';
		$body .= '<th>'.Yii::t('main', 'Freetext').'</th>';
		$body .= '<th style="display:none">'.Yii::t('main', 'Tiedot').'</th>';
		$body .= '</tr>';

		// <-- KK
		if(isset($kk_hinta[$asiakas_id]))
		{
			foreach($kk_hinta[$asiakas_id] as $kohde_id => $arr)
			{
				$kohde_link = CHtml::link($arr['tuote'],
					['/kohteet/update', 'id' => $kohde_id],
					['class' => '', 'target' => '_blank']
				);
				
				$num_rivi++;
				$kk_arr[] 	= [
					'tuote' 	=> $arr['tuote'], 
					'maara' 	=> 1, 
					'hinta' 	=> $arr['hinta'], 
					'alv' 		=> $arr['alv'],
					'yksikko'	=> $arr['yksikko'],
					'nimike' 	=> $arr['nimike'],
					'free_text'	=> $arr['free_text'],
					'tiedot'	=> $arr['tiedot']
				];
				
				$yht_kk 			+= $arr['hinta'];
				$yht_summ			+= $arr['hinta'];

				$laskutettu = false;
				if(isset($laskutetut_tiedot['kuukausi'][$kohde_id]))
						$laskutettu = true;
					
				$body .= '<tr class="lasku_rivi" num_rivi="'.$num_rivi.'">';
				$body .= '
				<td align="center">
					'.(($laskutettu)? '<p class="text-info">laskutettu</p>' : '<input type="checkbox" class="laskutetaan" checked').'
				</td>';
				$body .= '<td class="tuote" tuote_id="'.$arr['tuote_id'].'" tv_id="0"><b>'.$arr['nimike'].'</b><br>'.$kohde_link.'</td>';
				$body .= '<td class="maara text-center">1</td>';
				$body .= '<td class="yksikko text-center">'.$arr['yksikko'].'</td>';
				$body .= '<td class="alv text-center">'.$arr['alv'].'</td>';
				$body .= '<td class="hinta text-center">'.$arr['hinta'].'</td>';
				$body .= '<td class="'.(($laskutettu)? '' : 'forsumm').' text-center">'.$arr['hinta'].'</td>';
				$body .= '<td class="free_text">'.$arr['free_text'].'</td>';
				$body .= '<td class="tiedot" style="display:none">'.json_encode($arr['tiedot']).'</td>';
				$body .= '</tr>';
			}
		}
		
		// <-- Mobiili ja TV
		if(isset($mob_lista[$asiakas_id]))
		{
			$group_arr = [];
			foreach($mob_lista[$asiakas_id] as $key => $arr)
			{			
				foreach($arr as $k => $v)
				{
					$item 		= $v['attributes'];
					$tv_id		= $v['tv_id'];
					$tv_pvm		= $v['tv_pvm'];

					if($rakenne_muoto == 'mobiili')
					{
						$pvm		= date("d.m.Y", strtotime($item->aloitan));
						$osoite		= $item->kohde_kannasta;
						$tyovuorot 	= (isset($item->tyovuoroot->id))? $item->tyovuoroot : null;
						$kohde_id	= ($item->kohdenID > 0)? $item->kohdenID : null;
						$tiedot		= ['mobiili_id' => $item->id, 'tv_id' => $tv_id];
					}
					
					if($rakenne_muoto == 'tuovuoro')
					{
						$pvm		= $tv_pvm;
						$tyovuorot 	= $item;
						$osoite		= $item->osoiteById;
						$kohde_id	= $item->kohde;
						$tiedot		= ['tv_id' => $tv_id];
					}

					if(isset($kk_hinta[$asiakas_id][$kohde_id])) continue;
					
					$tv_link	= '';
					$maara 		= $this->num($v['maara']);
					
					if($tyovuorot !== null)
						$tv_ids[$tv_id] = $tv_id;
					
					$kohde_link = CHtml::link($osoite,
						['/kohteet/update', 'id' => $kohde_id],
						['class' => '', 'target' => '_blank']
					);
						
					if(!isset($v['tyovuoro_tuotteet']['paa_tuote']))
					{
						$tuote_id = $v['hinta_laskenta']['tuote_id'];
						
						$group_arr[$kohde_id][$tuote_id][] = [
							'tuote_id'	=> $tuote_id,
							'nimike' 	=> '<b>'.$v['hinta_laskenta']['nimike'].':</b> '.$kohde_link,
							'alv' 		=> $v['hinta_laskenta']['alv'],
							'yksikko'	=> $v['hinta_laskenta']['yksikko'],
							'hinta' 	=> $v['hinta_laskenta']['hinta'],
							'maara'		=> $maara,
							'free_text'	=> $pvm,
							'tiedot'	=> $tiedot
						];
					}
					
					if(isset($v['tyovuoro_tuotteet']['paa_tuote']))
					{
						$tv_id 	= $v['tyovuoro_tuotteet']['paa_tuote']['tv_id'];
						$pvm 	= $v['tyovuoro_tuotteet']['paa_tuote']['tv_pvm'];

						$tv_link 	= CHtml::link('<span class="text-success">Työvuoro</span>',
							[
							  sprintf('/tyovuoroot/beta?mode=vko&year=%s&week=%s&tv_id=%s', date("Y", strtotime($pvm)), date("W", strtotime($pvm)), $tv_id)
							],
							[
							  'class' 			=> 'pull-right',
							  'target' 			=> '_blank',
							  //'data-toggle' 	=> 'tooltip',
							  //'data-placement' 	=> 'top',
							  //'title' 			=> 'Tämä kirjaus on tehty työvuorosta ID#: '.$tv_id
							]
						);
						
						$tuote_id = $v['tyovuoro_tuotteet']['paa_tuote']['tuote_id'];
						
						$group_arr[$kohde_id][$tuote_id][] = [
							'tuote_id'	=> $tuote_id,
							'nimike' 	=> '<b>'.$v['tyovuoro_tuotteet']['paa_tuote']['nimike'].':</b> '.$kohde_link.(($rivi_muoto == 'rivi_per_kirjaus')?$tv_link:''),
							'alv' 		=> $v['tyovuoro_tuotteet']['paa_tuote']['alv'],
							'yksikko' 	=> $v['tyovuoro_tuotteet']['paa_tuote']['yksikko'],
							'hinta' 	=> $v['tyovuoro_tuotteet']['paa_tuote']['hinta'],
							'maara'		=> $maara,
							'free_text'	=> $pvm,
							'tiedot'	=> $tiedot
						];
					}
					
					if(isset($v['tyovuoro_tuotteet']['lisa_tuotteet']))
					{
						foreach($v['tyovuoro_tuotteet']['lisa_tuotteet'] as $tuote)
						{
							$tuote_id = $tuote['tuote_id'];

							$group_arr[$kohde_id][$tuote_id][] = [
								'tuote_id'	=> $tuote_id,
								'nimike' 	=> '<b>'.$tuote['nimike'].(($rivi_muoto == 'rivi_per_kirjaus')?$tv_link:'').'</b>',
								'alv' 		=> $tuote['alv'],
								'yksikko' 	=> $tuote['yksikko'],
								'hinta' 	=> $tuote['hinta'],
								'maara'		=> $tuote['maara'],
								'free_text'	=> $pvm,
								'tiedot'	=> $tiedot
							];
						}
					}
				}
			}
			
			$pregroup = [];
			foreach($group_arr as $kohdenID => $parr)
			{
				foreach($parr as $tuote_id => $prearr)
				{
					foreach($prearr as $key => $arr)
					{
						$hinta 		= $arr['hinta'];
						$maara 		= $arr['maara'];
						$yht_tunnit += $maara;
						$yht_summ	+= $maara*$hinta;

						if($rivi_muoto == 'rivi_per_kirjaus')
						{
							$tiedot 			= $arr['tiedot'];
							$tiedot['tuote_id'] = $tuote_id;
							$laskutettu 		= false;
							
							if(isset($laskutetut_tiedot['tuote_id'][$tuote_id]) and isset($laskutetut_tiedot[$which_ids]) and isset($tiedot[$which_ids]))
							{
								$laskutettu = true;
								if(!in_array($tiedot[$which_ids], $laskutetut_tiedot[$which_ids]))
										$laskutettu = false;
							}

							$body .= '<tr class="lasku_rivi">';
							$body .= '
							<td align="center">
								'.(($laskutettu)? '<p class="text-info">laskutettu</p>' : '<input type="checkbox" class="laskutetaan" checked').'
							</td>';
							$body .= '<td class="tuote" tuote_id="'.$tuote_id.'">'.$arr['nimike'].'</td>';
							$body .= '<td class="maara text-center">'.$maara.'</td>';
							$body .= '<td class="yksikko text-center">'.$arr['yksikko'].'</td>';
							$body .= '<td class="alv text-center">'.$arr['alv'].'</td>';
							$body .= '<td class="hinta text-center">'.$hinta.'</td>';
							$body .= '<td class="'.(($laskutettu)? '' : 'forsumm').' text-center">'.($maara*$hinta).'</td>';
							$body .= '<td class="free_text">'.$arr['free_text'].'</td>';
							$body .= '<td class="tiedot" style="display:none">'.json_encode($tiedot).'</td>';
							$body .= '</tr>';
						}

						$pregroup[$tuote_id]['nimike'] 		= $arr['nimike'];
						$pregroup[$tuote_id]['alv'] 		= $arr['alv'];
						$pregroup[$tuote_id]['yksikko'] 	= $arr['yksikko'];
						$pregroup[$tuote_id]['hinta'] 		= $hinta;
						$pregroup[$tuote_id]['maara'][]		= $maara;
						$pregroup[$tuote_id]['tiedot'][]	= $arr['tiedot'];
					}
				}
			}

			if($rivi_muoto == 'rivi_per_kohde')
			{
				foreach($pregroup as $tuote_id => $arr)
				{
					$maara 					= array_sum($arr['maara']);
					$laskutettu_maara 		= isset($laskutetut_tuotteet[$tuote_id][$which_ids]['maara'])? array_sum($laskutetut_tuotteet[$tuote_id][$which_ids]['maara']): 0;
									
					$new_tiedot 			= [];
					$new_tiedot['tuote_id'] = $tuote_id;
					foreach($arr['tiedot'] as $key => $arr_tiedot)
					{
						if(isset($arr_tiedot['mobiili_id']) and $arr_tiedot['mobiili_id'] > 0)
							$new_tiedot['mobiili_id'][] = $arr_tiedot['mobiili_id'];
						if(isset($arr_tiedot['tv_id']) and $arr_tiedot['tv_id'] > 0)
							$new_tiedot['tv_id'][] = $arr_tiedot['tv_id'];
					}
					
					$laskutettu = false;					
					if(isset($laskutetut_tiedot['tuote_id'][$tuote_id]) and isset($laskutetut_tiedot[$which_ids]))
					{
						$laskutettu = true;
						foreach($new_tiedot[$which_ids] as $id)
						{
							if(!in_array($id, $laskutetut_tiedot[$which_ids]))
							{

								$maara -= $laskutettu_maara;

								if(isset($laskutetut_tuotteet[$tuote_id][$which_ids]['maara']))
								{
									$body .= '<tr class="lasku_rivi">';
									$body .= '<td align="center"><p class="text-info">laskutettu</p></td>';
									$body .= '<td class="tuote" tuote_id="'.$tuote_id.'">'.$arr['nimike'].'</td>';
									$body .= '<td class="maara text-center">'.$laskutettu_maara.'</td>';
									$body .= '<td class="yksikko text-center">'.$arr['yksikko'].'</td>';
									$body .= '<td class="alv text-center">'.$arr['alv'].'</td>';
									$body .= '<td class="hinta text-center">'.$arr['hinta'].'</td>';
									$body .= '<td class="'.(($laskutettu)? '' : 'forsumm').' text-center">'.($laskutettu_maara*$arr['hinta']).'</td>';
									$body .= '<td class="free_text">'.$ajanjakso.'</td>';
									$body .= '<td class="tiedot" style="display:none">'.json_encode($new_tiedot).'</td>';
									$body .= '</tr>';
								}
					
								$laskutettu = false;
								break;
							}
						}
					}

					$body .= '<tr class="lasku_rivi">';
					$body .= '
					<td align="center">
						'.(($laskutettu)? '<p class="text-info">laskutettu</p>' : '<input type="checkbox" class="laskutetaan" checked').'
					</td>';
					$body .= '<td class="tuote" tuote_id="'.$tuote_id.'">'.$arr['nimike'].'</td>';
					$body .= '<td class="maara text-center">'.$maara.'</td>';
					$body .= '<td class="yksikko text-center">'.$arr['yksikko'].'</td>';
					$body .= '<td class="alv text-center">'.$arr['alv'].'</td>';
					$body .= '<td class="hinta text-center">'.$arr['hinta'].'</td>';
					$body .= '<td class="'.(($laskutettu)? '' : 'forsumm').' text-center">'.($maara*$arr['hinta']).'</td>';
					$body .= '<td class="free_text">'.$ajanjakso.'</td>';
					$body .= '<td class="tiedot" style="display:none">'.json_encode($new_tiedot).'</td>';
					$body .= '</tr>';
				}
			}
		}

		$body .= '<tr>';
		$body .= '<th></th>';
		$body .= '<th></th>';
		$body .= '<th></th>';
		$body .= '<th></th>';
		$body .= '<th></th>';
		$body .= '<th></th>';
		$body .= '<th id="summ_result" class="text-center"></th>';
		$body .= '<th></th>';
		$body .= '<th style="display:none"></th>';
		$body .= '</tr>';
		$body .= '</table>';

		if(count($laskut) > 0)
		{
			$body .= '<h2 class="text-info">Tehdyt laskut</h2>';
			$body .= '<div class="form-inline">';
			foreach($laskut as $item)
			{
				$link = CHtml::link('<span class="text-white">Lasku:' . $item->id.'<br>' . $this->tilanneCheck($item).'</span>',
					['/lasku/update', 'id' => $item->id],
					['class' => 'btn btn-group btn-info'] // , 'target' => '_blank'
				);
				$body .= $link.' ';
			}
			$body .= '</div>';
		}
		/*
		$body .= '<br><div class="well"><h3>Laskutetut ID:t</h3>';
		if(isset($laskutetut_tiedot['mobiili_id']))
			$body .= '<b>Mobiili Id:</b> '.implode(", ", $laskutetut_tiedot['mobiili_id']);
		if(isset($laskutetut_tiedot['tv_id']))
			$body .= '<br><b>Työvuorojen Id:</b> '.implode(", ", $laskutetut_tiedot['tv_id']);
		if(isset($laskutetut_tiedot['tuote_id']))
			$body .= '<br><b>Tuotteiden Id:</b> '.implode(", ", $laskutetut_tiedot['tuote_id']);
		if(isset($laskutetut_tiedot['kuukausi']))
			$body .= '<br><b>Kuukausi kohde Id:</b> '.implode(", ", $laskutetut_tiedot['kuukausi']);
		$body .= '</div>';
		
		//$body .= 'check: '.json_encode($laskuTilantteet);
		
		$body .= '<h4>Erikoinen Tunniste: '.$etunti_tunniste.'</h4>';
		*/
		
		$body .= '
			<br>
			<p><span class="btn-block btn btn-info laskutetuksi" asiakas_id="'.$asiakas_id.'" from="'.$from.'" to="'.$to.'" etunti_tunniste="'.$etunti_tunniste.'" style="display:none" data-toggle="tooltip" data-placement="top" title="Hei, Tämän painamalla oikeasti luodaan heti uusi hyväksymätön laskun määritetty rivijen mukaiseesti. Luomiseen jälkeen lasku saa muokata, hyväksytä ja lähetä. Kaikki tilantteet on näkyvissä tällä sivulla.">LUO UUSI LASKU</span></p>
		';
		
		echo json_encode($body);
		//echo $body;
		exit;
	}

	public function actionLaskutetuksi($asiakas_id, $from, $to, $tilanne, $la_id=null)
	{
	
		$asiakas = Asiakkaat::model()->findByPk($asiakas_id);

		if($tilanne == 'remove' and $la_id > 0)
		{
		/*
			$model 	= LaskutetutAsiakkaat::model()->findByPk($la_id);
			if($model->delete())
			{
				echo json_encode(['ok' => true]);
				exit;
			}
			*/
		}
		
		if($tilanne == 'new' and $asiakas !== null)
		{
		
			// print_r(json_decode($_POST['la_asiakkaat_tr_rivit'], true));
			// exit;
						
			$paivays = date("Y-m-d", strtotime($_POST['laskun_paivays']));
			$erapaiva = '';
			if(!empty($asiakas->maksuehto))
				$erapaiva = date("d.m.Y",strtotime($paivays . " +$asiakas->maksuehto day"));
			
			$model 					= new Lasku;
			$model->etunti_tunniste = $_POST['etunti_tunniste'];
			$model->as_nro 			= $asiakas->asiakasnumero;
			$model->laskutus 		= $asiakas->laskutus_kanava;
			$model->maksuehto 		= $asiakas->maksuehto;
			$model->osoite 			= $asiakas->osoite;
			$model->postinumero 	= $asiakas->postinumero;
			$model->toimipaikka 	= $asiakas->kaupunki;
			$model->yhteensa_total	= ''; // Pakko RE Tallenna lomake
			$model->toimitusosoite 	= 0;
			$model->tyyppi 			= $asiakas->tyyppi;
			$model->yid 			= 1; // Miksi on aina yksi?
			$model->tilanne 		= 0;
			$model->tapahtumapvm 	= date("Y-m-d H:i:s");
			$model->paivays 		= $paivays;
			$model->erapaiva 		= $erapaiva;
			$model->laskun_nimetys 	= "Lasku";

			if($model->save()){

				// Viite
				$viite = $this->Viite($model->as_nro."00".date("md").$model->id);
				Lasku::model()->updatebypk($model->id, array('viitenumero'=>$viite));

				// <-- LOG
				$l_m = Lasku::model()->findByPk($model->id);
				if( isset($l_m->id) )
				{
					$model_log 		= 'Lasku';
					$name_log 		= 'Lasku';
					$status_log 	= 'Create';

					$old_values = null;
					$new_values = json_encode($l_m->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

				foreach(json_decode($_POST['la_asiakkaat_tr_rivit'], true) as $arr)
				{
					foreach($arr as $key => $val)
					{
						$lr 			= new LaskunRivit;
						$lr->lid		= $model->id;
						$lr->asiakas_id = $asiakas->id;
						$lr->tuoteID 	= $val['tuote_id'];
						$lr->rivi		= $key;
						$lr->tkoodi		= $val['tuote'];
						$lr->kpl		= $val['maara'];
						$lr->yksikko	= $val['yksikko'];
						$lr->hinta		= $val['hinta'];
						$lr->alv		= $val['alv'];
						$lr->hinta_alv	= ($val['hinta']*$val['alv'])/100;
						$lr->ale		= 0;
						$lr->veroton	= $val['hinta'];
						$lr->yhteensa_alv= $val['hinta']+$lr->hinta_alv;
						$lr->free_text	= $val['free_text'];
						$lr->tiedot		= $val['tiedot'];
						if(!$lr->save()){
							echo json_encode(['ERROR' => $lr->getErrors()]);
							exit;
						}
					}
				}

				// Lasku historia
				$historia = new LaskuHistoria;
				$historia->lid = $model->id;
				$historia->status = "Lasku luotu";
				$historia->palvelu = "local";
				$historia->yht_euro = $model->yhteensa_total;
				if(!$historia->save())
				{
					echo json_encode(['ERROR' => $historia->getErrors()]);
					exit;
				}
				
				echo json_encode(['lasku_id' => $model->id]);
				exit;
				
			} else {
				echo json_encode(['ERROR' => $model->getErrors()]);
				exit;
			}
		}
		
		echo json_encode(['ERROR' => true]);
		exit;
	}

	public function actionL_asiakkaat($kk=null, $rakenne_muoto=null)
	{
		$dataProvider 		= [];
		$la_AsIds 			= [];
		$from 				= '';
		$to 				= '';
		if($kk !== null)
		{
			$from 			= date("Y-m-d", strtotime($kk." first day of this month"));
			$to 			= date("Y-m-d", strtotime($kk." last day of this month"));

			// <-- Check laskutetut
			$kk				= date("m.Y", strtotime($from));
			$la 			= Lasku::model()->findAll("etunti_tunniste LIKE 'la_".$kk."_%'");
			foreach($la as $item)
				$la_AsIds[$item->etunti_tunniste][] = $item->id;
		
	       	$criteria = new CDbCriteria();
			// <-- Tyoryhmat
			$site = Yii::app()->createController('Site');
			$arr = $site[0]->TyoryhmatHelper();
			$ids = implode(",", $arr);
			if( count($arr) > 0 ){
				$criteria->condition = " tyoryhma IN ($ids) ";
			}
			//    Tyoryhmat -->
			
			if($rakenne_muoto == 'mobiili')
			{
				$criteria->addCondition(" 
					id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id IN(SELECT kohdenID FROM sivexkuitti WHERE 
						DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) 
						BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
						AND status='3'
						AND deleted=0
						AND laskutetaan=1
						AND hyvaksytty!=''
						AND laskutettu=0
					))
					OR id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id IN(SELECT kohdenID FROM sivexkuitti_repaired WHERE 
						DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) 
						BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
						AND status='3'
						AND deleted=0
						AND laskutetaan=1
						AND hyvaksytty!=''
						AND laskutettu=0
					))
					OR id IN(SELECT asiakas_id FROM sivex_kohdet WHERE 
						(tuote!=0 OR t.tuote!=0)
					)
				");
			}
			
			if($rakenne_muoto == 'tuovuoro')
			{

				$haku_criteria 	= ["(laskutettu=0 or laskutettu is NULL) AND tid!=0 AND (peruutettu=0 or peruutettu is NULL)"];
				$tv_controller 	= Yii::app()->createController('Tyovuoroot');
				$getall 		= $tv_controller[0]->FromToSuunnitellutAll(date("Y-m-d", strtotime($from)), date("Y-m-d", strtotime($to)), [], $haku_criteria, []);
				$kohde_ids = [];
				foreach($getall as $arr)
				{
					if($arr['kohde'] > 0)
						$kohde_ids[$arr['kohde']] = $arr['kohde'];
				}
				/*
				echo '<pre>';
				print_r($kohde_ids);
				echo '</pre>';
				exit;
				*/
				$impl = "id='".implode("' OR id='", $kohde_ids)."'";
				$criteria->addCondition(" 
					id IN(SELECT asiakas_id FROM sivex_kohdet WHERE
						($impl)
					)
				");
				
			}
			
			if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi']))
		        	$criteria->addCondition (" yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR CONCAT(etunimi , ' ' , sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%'");

			if(isset($_GET['tyoryhma']) and is_array($_GET['tyoryhma'])){
					$impl = 'tyoryhma='.implode(' OR tyoryhma=', $_GET['tyoryhma']);
					$criteria->addCondition ($impl);
			}
	
			$dataProvider=new CActiveDataProvider('Asiakkaat', array(
				'criteria'=>$criteria,
				//'pagination'=>true
			));
			$dataProvider->pagination->pageSize = 50;
		}
		
		/*
		echo '<pre>';
		print_r($kk_hinta);
		echo '</pre>';
		exit;
		*/
				
		$this->render('la_asiakkaat', array(
			'kk' 			=> $kk,
			'rakenne_muoto' => $rakenne_muoto,
			'from'			=> $from,
			'to'			=> $to,
			'la_AsIds'		=> $la_AsIds,
			'dataProvider' 	=> $dataProvider
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

	//echo '<pre>';
	//print_r($this->netvisorLaskentaKohteetLista());
	//echo '</pre>';
	//exit;
	
	// <-- Oikeudet
	   $checkOikeus = "lasku_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$lahettamattomat = false;
		$asetukset=Asetukset::model()->findbypk(1);

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_GET['from']) and isset($_GET['to'])){
		$from 	= date("Y-m-d",strtotime($_GET['from']));
		$to 	= date("Y-m-d",strtotime($_GET['to']));
		}


		// <!-- Lasku updater
		$info 	= '';
		$is_error = false;
		$info 	.= $this->LaskuUpdater($from, $to, $is_error);
		// Lasku updater -->

       		$criteria = new CDbCriteria();
	        $criteria->order = "  id DESC ";
        	$criteria->addCondition ("DATE(paivays) BETWEEN 
			'".$from."' AND '".$to."' 
		");

		if(isset($_GET['asiakasLaskulle']) and !empty(trim($_GET['asiakasLaskulle'])))
       			$criteria->addCondition ( " as_nro='".$_GET['asiakasLaskulle']."' " );
		if(isset($_GET['laskunumero']) and !empty(trim($_GET['laskunumero'])))
	        	$criteria->addCondition (" laskunumero LIKE '%".$_GET['laskunumero']."%' ");

		if(isset($_GET['viitenumero']) and !empty(trim($_GET['viitenumero'])))
	        	$criteria->addCondition (" viitenumero LIKE '%".$_GET['viitenumero']."%' ");

		if(isset($_GET['laskuosoite']) and !empty(trim($_GET['laskuosoite'])))
	        	$criteria->addCondition (" osoite LIKE '%".$_GET['laskuosoite']."%' ");

		// <-- Luotu
		if( isset($_GET['tilaLaskulle']) and !empty($_GET['tilaLaskulle']) and $_GET['tilaLaskulle'] == 0 )
			$criteria->addCondition (" tilanne=0 ");
		//  Luotu -->


		// <-- Lahetamattomat hyväksyttyt
		if( (isset($_GET['tilaLaskulle']) and !empty($_GET['tilaLaskulle']) and $_GET['tilaLaskulle'] == 1) or (isset($_GET['lahettamattomat'])) )
		{
		$criteria->addCondition (" 
			tilanne=1 AND postita_jobid='' AND trust_jobid='' AND netvisorkey=0 
		");
		$lahettamattomat = true;
		}
		//     Lahetamattomat hyväksyttyt -->


		// POSTITA Lahetetty
		if(isset($_GET['tilaLaskulle']) and !empty($_GET['tilaLaskulle']) and $_GET['tilaLaskulle'] == 2 and $asetukset->palvelu_tyyppi == 1)
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
		if(isset($_GET['tilaLaskulle']) and !empty($_GET['tilaLaskulle']) and $_GET['tilaLaskulle'] == 3 and $asetukset->palvelu_tyyppi == 1)
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
		if(isset($_GET['tilaLaskulle']) and !empty($_GET['tilaLaskulle']) and $_GET['tilaLaskulle'] == 2 and $asetukset->palvelu_tyyppi == 2)
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
		if(isset($_GET['tilaLaskulle']) and !empty($_GET['tilaLaskulle']) and $_GET['tilaLaskulle'] == 3 and $asetukset->palvelu_tyyppi == 2)
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
		if(isset($_GET['tilaLaskulle']) and !empty($_GET['tilaLaskulle']) and $_GET['tilaLaskulle'] == 2 and $asetukset->palvelu_tyyppi == 3)
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
				'is_error' => $is_error,
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
		if(!isset($data->id))
			return '';
			
		$criteria = new CDbCriteria();
		$criteria->order = " id DESC ";
		$criteria->condition = " lid='" . $data->id . "' ";
		$l = LaskuHistoria::model()->find($criteria);
		$tilanne = '';

		// <-- Trust
		$trust = false;
		$trustStr = '';
		if (isset($l->palvelu) and $l->palvelu == 'trust') {
			$json = json_decode($l->status, true);
			if (isset($json['statustext']) and !empty($json['statustext'])) {
				$trustStr = date("d.m.Y", strtotime($json['statustime'])) . ' ' . $json['statustext'];
				$trust = true;
			} elseif (!isset($json['statustext']) and isset($json['reference'])) {
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

		if (isset($l->palvelu) and $l->palvelu == 'postita') {
			if ($l->postita_statuscode == 'NE') {
				$postitaStr = 'Lasku on vielä vahvistettava';
				$postita = true;
			} elseif ($l->postita_statuscode == 'CO') {
				$postitaStr = 'Odottaa lähetystä';
				$postita = true;
			} elseif ($l->postita_statuscode == 'SE') {
				$postitaStr = 'Lasku lähetetty';
				$postita = true;
			} elseif ($l->postita_statuscode == 'CA') {
				$postitaStr = 'Lasku peruutettu';
				$postita = true;
			} elseif ($l->postita_statuscode == 'MAKSUMUISTUTUS') {
				$postitaStr = 'Maksumuistutus lähetetty';
				$postita = true;
			} elseif ($l->postita_statuscode == 'POISTETTU') {
				$postitaStr = 'Lasku poistettu POSTITA.FI:sta';
				$postita = true;
			}
		}
		//  Postita -->

		// Procountor - Copied from other entries @ 14.11.19.
		if (isset($l->palvelu) && $l->palvelu == 'procountor') {

			// Check for procountor status, received when updating invoices.
			if (isset($l->procountor_statuscode) && !empty($l->procountor_statuscode)) {
				switch($l->procountor_statuscode) {
					case 'EMPTY':                       $tilanne = 'Tyhjä'; break;
					case 'UNFINISHED':                  $tilanne = 'Kesken'; break;
					case 'NOT_SENT':                    $tilanne = 'Ei lähetetty'; break;
					case 'SENT':                        $tilanne = 'Lähetetty'; break;
					case 'RECEIVED':                    $tilanne = 'Vastaanotettu'; break;
					case 'PAID':                        $tilanne = 'Maksettu'; break;
					case 'PAYMENT_DENIED':              $tilanne = 'Maksu epäonnistunut'; break;
					case 'VERIFIED':                    $tilanne = 'Varmistettu'; break;
					case 'APPROVED':                    $tilanne = 'Hyväksytty'; break;
					case 'INVALIDATED':                 $tilanne = 'Mitätöity'; break;
					case 'PAYMENT_QUEUED':              $tilanne = 'Maksu jonossa'; break;
					case 'PARTLY_PAID':                 $tilanne = 'Osittain maksettu'; break;
					case 'PAYMENT_SENT_TO_BANK':        $tilanne = 'Maksu lähetetty pankille'; break;
					case 'MARKED_PAID':                 $tilanne = 'Merkitty maksetuksi'; break;
					case 'STARTED':                     $tilanne = 'Aloitettu'; break;
					case 'INVOICED':                    $tilanne = 'Laskutettu'; break;
					case 'OVERRIDDEN':                  $tilanne = 'Ohitettu'; break;
					case 'DELETED':                     $tilanne = 'Poistettu'; break;
					case 'UNSAVED':                     $tilanne = 'Tallentamatta'; break;
					case 'PAYMENT_TRANSACTION_REMOVED': $tilanne = 'Maksutapahtuma poistettu'; break;
					case 'MUU': default:                $tilanne = 'Muu tilanne'; break;
				}
			} else {
				switch ($l->status) {
					case 'LÄHETETTY':                   $tilanne = 'Lasku lähetetty'; break;
					case 'MAKSUMUISTUTUS':              $tilanne = 'Maksumuistutus lähetetty'; break;
					case 'MAKSETTU':                    $tilanne = 'Lasku maksettu'; break;
					case 'Lasku luotu':                 $tilanne = 'Lasku luotu'; break;
					case 'HYVÄKSYTTY':                  $tilanne = 'Lasku hyväksytty'; break;
					case 'Lähetetty sähköpostilla':     $tilanne = 'Lähetetty sähköpostilla'; break;
					case 'Lasku mitätöity':             $tilanne = 'Lasku mitätöity'; break;
					case 'POISTETTU':                   $tilanne = 'Lasku poistettu'; break;
					case 'MUU': default:                $tilanne = 'Muu tilanne'; break;
				}
			}
		}

		// <-- Local
		$local = false;
		$localStr = '';
		if (isset($l->palvelu) and $l->palvelu == 'local') {
			if ($l->status == 'LÄHETETTY') {
				$localStr = 'Lasku lähetetty';
				$local = true;
			} elseif ($l->status == 'MAKSUMUISTUTUS') {
				$localStr = 'Maksumuistutus lähetetty';
				$local = true;
			} elseif ($l->status == 'MAKSETTU') {
				$localStr = 'Lasku maksettu';
				$local = true;
			} elseif ($l->status == 'Lasku luotu') {
				$localStr = 'Lasku luotu';
				$local = true;
			} elseif ($l->status == 'HYVÄKSYTTY') {
				$localStr = 'Lasku hyväksytty';
				$local = true;
			} elseif ($l->status == 'Lähetetty sähköpostilla') {
				$localStr = 'Lähetetty sähköpostilla';
				$local = true;
			} elseif ($l->status == 'Lasku mitätöity') {
				$localStr = 'Lasku mitätöity';
				$local = true;
			} elseif ($l->status == 'Muokattu') {
				$localStr = 'Muokattu';
				$local = true;
			}
		}
		//  Local -->

		// <-- Netvisor
		$netvisor = false;
		$netvisorStr = '';
		if (isset($l->palvelu) and $l->palvelu == 'netvisor') {
			$netvisorStr = $l->status;
			$netvisor = true;
		}
		//  Netvisor -->

		if ($trust == true)
			$tilanne = $trustStr;
		elseif ($postita == true)
			$tilanne = $postitaStr;
		elseif ($local == true)
			$tilanne = $localStr;
		elseif ($netvisor == true)
			$tilanne = $netvisorStr;

		$invoice = Lasku::model()->findByPk($data->id);
		if ($invoice->tilanne == 0)
			$tilanne .= " <b>(hyväksymätön)</b>";

		return $tilanne;
	}


    	protected function avoinnaCheck($data,$row)
	{ 

		$criteria = new CDbCriteria();
		$criteria->select = " yht_euro ";
		$criteria->order = " id DESC ";
		$criteria->condition = " lid='".$data->id."' "; // AND yht_euro!=''
		$l = LaskuHistoria::model()->find($criteria);

		$yht_euro = $data->yhteensa_total;
		if(isset($l->yht_euro))
			$yht_euro = str_replace(",",".", $l->yht_euro);

		return (float)$yht_euro;
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
		$asetukset=Asetukset::model()->findbypk(1);
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
	} elseif(isset($asiakas->id) and $asiakas->netvisorkey == 0) {

		Yii::app()->user->setFlash('danger', "Asiakasnumero: <b>".$asiakas->asiakasnumero."</b> ei saanut netvisorkey viellä, päivittä sen tallentamalla asiakas lomake uudestaan.");
		$this->redirect(Yii::app()->request->urlReferrer);

		die('ERROR: Tämä asiakas ei saanut netvisorkey viellä');
	}
	$dimension = '';
	if( !empty($model->netvisor_dimension_name) and !empty($model->netvisor_dimension_item)){
	$dimension = '
	     <Dimension>
            	<DimensionName>'.$model->netvisor_dimension_name.'</DimensionName>
            	<DimensionItem>'.$model->netvisor_dimension_item.'</DimensionItem>
             </Dimension>';
	}

$xml = '
<root>
  <SalesInvoice>
    '.(($asetukset->lasku_laskunumero == 1)?'<SalesInvoiceNumber>'.$model->laskunumero.'</SalesInvoiceNumber>':'').'
    <SalesInvoiceDate format="ansi">'.date("Y-m-d", strtotime($model->paivays)).'</SalesInvoiceDate>
    <SalesInvoiceDueDate>'.date("Y-m-d", strtotime($model->erapaiva)).'</SalesInvoiceDueDate>
    <SalesInvoiceDeliveryDate format="ansi">'.date("Y-m-d", strtotime($model->paivays)).'</SalesInvoiceDeliveryDate>
    <SalesInvoiceReferenceNumber>'.$model->viitenumero.'</SalesInvoiceReferenceNumber>
    <SalesInvoiceAmount>'.$model->yhteensa_total.'</SalesInvoiceAmount>
    <!--<SellerIdentifier type="netvisor">32</SellerIdentifier>-->
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
    <DeliveryAddressCountryCode type="ISO-3166">FI</DeliveryAddressCountryCode>';
//<PaymentTermNetDays>'.$model->maksuehto.'</PaymentTermNetDays>

$laskunRivit=LaskunRivit::model()->findAll("lid='".$model->id."'");

if(count($laskunRivit) > 0){ $xml .= '<InvoiceLines>'; }

foreach($laskunRivit as $rivit)
{

	$ProductIdentifier 	= '';
	$myyntitili		= '3000';
	$tuotteet = TuotteetPalvelut::model()->findByPk($rivit->tuoteID);
	if(isset($tuotteet->id) and $tuotteet->netvisorkey != 0){
		$ProductIdentifier 	= $tuotteet->netvisorkey;
		$myyntitili		= $tuotteet->myyntitili;
	} elseif( $this->netvisorProductDefault() != 0 and !isset($tuotteet->id) or (isset($tuotteet->id) and $tuotteet->netvisorkey == 0) ){
		$ProductIdentifier = $this->netvisorProductDefault();
	} else {
		die('ERROR: ProductIdentifier');
	}

//      <SalesInvoiceProductLineFreeText>'.$rivit->free_text.'</SalesInvoiceProductLineFreeText>
//      <AccountingAccountSuggestion>3000</AccountingAccountSuggestion> 

	$comment = '';
	if(!empty($rivit->free_text)){
	$comment = '
	<InvoiceLine>
		<SalesInvoiceCommentLine>
			<Comment>'.$rivit->free_text.'</Comment>
		</SalesInvoiceCommentLine>
	</InvoiceLine>';
	}

if( $model->alv_muoto == 0 ){  $type = 'net'; }
if( $model->alv_muoto == 1 ){  $type = 'gross'; }
$hinta = $rivit->hinta;

// If price contains more than 2 decimal places, calculate price manually,
// because netvisor doesn't support more than 2 decimal places. (test)
if (strlen(substr(strrchr($hinta, "."), 1)) > 2) {
	$alv_modifier = (100 + $rivit->alv) / 100;
	if ($type == 'net') {
		$hinta = $hinta * $alv_modifier;
		$type = 'gross';
	} else {
		$hinta = $hinta / $alv_modifier;
		$type = 'net';
	}
}

$xml .= '
       <InvoiceLine>
         <SalesInvoiceProductLine>
             <ProductIdentifier type="netvisor">'.$ProductIdentifier.'</ProductIdentifier>
             <ProductName>'.$rivit->tkoodi.'</ProductName>
             <ProductUnitPrice type="'.$type.'">'.$hinta.'</ProductUnitPrice>
             <ProductVatPercentage vatcode="KOMY">'.$rivit->alv.'</ProductVatPercentage>
             <SalesInvoiceProductLineQuantity>'.$rivit->kpl.'</SalesInvoiceProductLineQuantity>
             <SalesInvoiceProductLineDiscountPercentage>'.$rivit->ale.'</SalesInvoiceProductLineDiscountPercentage>
	     <accountingAccountSuggestion>'.$myyntitili.'</accountingAccountSuggestion>
	     '.$dimension.'
         </SalesInvoiceProductLine>
       </InvoiceLine>
       '.$comment;
}

if(count($laskunRivit) > 0){ $xml .= '</InvoiceLines>'; }

$xml .= '
  </SalesInvoice>
</root>';
	
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
		if( $tila == 'add' ){
			$lasku = Lasku::model()->deletebypk($model->id);
	       		$criteria = new CDbCriteria();
	       		$criteria->condition = " lid='".$model->id."' ";
			LaskunRivit::model()->deleteAll($criteria);
		}
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

	protected function netvisorListByDay($from, $to)
	{

		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

	  	if(isset($n[0])){
		$url		= $n[0].'/salesinvoicelist.nv?BeginInvoiceDate='.$from.'&EndInvoiceDate='.$to;
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
		/*
			echo '<pre>';
			print_r($return); // $netvisorList->SalesInvoiceList
			echo '</pre>';
			exit;
		*/
	   	}

		return $return;
	}
	protected function netvisorLaskentaKohteetLista()
	{
		$return = '';
		$site = Yii::app()->createController('Site');
		$n = $site[0]->netvisorYhteys();

		if(isset($n[0]))
		{
			$url		= $n[0].'/dimensionlist.nv';
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

			$context 	= stream_context_create($optsGET);
			$response 	= file_get_contents($url, false, $context);
			if(empty($response))
			{
				Yii::app()->user->setFlash('danger', "Netvisor API yhteys ei toimii.");
				$this->redirect(array('index'));
			} else {
				$return = new SimpleXMLElement($response);
			}
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

	/**
	 * Get updated invoice status from Procountor.
	 *
	 * @param int $invoice_id
	 * Local invoice ID.
	 * @param string $error_msg
	 * Possible error message if result is false.
	 * @return bool
	 * True if status or open amount was updated; otherwise, false. If false,
	 * possible error message is stored in &$error_msg. However, false doesn't
	 * always mean an error happened; if status hasn't changed, returns false.
	 */
	public function updateProcountorInvoice($invoice_id, &$error_msg)
	{
		$asetukset = Asetukset::model()->findbypk(1);
		if ($asetukset->palvelu_tyyppi != 5) {
			$error_msg = 'Procountor ei ole käytössä.';
			return false;
		}

		// Check that authorization is valid.
		$pc = Yii::createComponent('Procountor');
		if (!$pc->isAuthorized()) {
			$error_msg = 'Procountor kirjautuminen on viallinen tai vanhentunut. Kirjaudu Procountoriin uudelleen asetuksista.';
			return false;
		}

		// Get local invoice data.
		$local_invoice = Lasku::model()->findByPk($invoice_id);
		if (!$local_invoice || empty($local_invoice->id ?? '')) {
			$error_msg = 'Laskun tietojen lataaminen epäonnistui.';
			return false;
		}

		// Get local invoice history.
		$criteria = new CDbCriteria();
		$criteria->condition = "lid={$local_invoice->id}";
		$criteria->order = "id DESC";
		$local_invoice_history = LaskuHistoria::model()->find($criteria);
		if (!$local_invoice_history || empty($local_invoice_history->id) || $local_invoice_history->lid != $local_invoice->id) {
			$error_msg = 'Laskuhistorian lataaminen epäonnistui.';
			return false;
		}

		// Get full remote invoice data.
		$remote_invoice_full = $pc->getInvoice($local_invoice->procountor_id);
		if (isset($remote_invoice_full['errors']) || !isset($remote_invoice_full['id'])) {
			$error_msg = 'Laskun tietojen hakeminen Procountorista epäonnistui.';
			return false;
		}

		// Calculate total price (yht_euro).
		$total_price = 0.0;
		$includes_vat = $remote_invoice_full['extraInfo']['unitPricesIncludeVat'] ?? true;
		foreach ($remote_invoice_full['invoiceRows'] ?? [] as $invoice_row) {
			$vat_multiplier = $includes_vat ? 1 : 1 + $invoice_row['vatPercent'] / 100;
			$total_price += $invoice_row['unitPrice'] * $invoice_row['quantity'] * $vat_multiplier;
		}

		// Do nothing if neither statuscode or price have updated.
		if ($local_invoice_history->procountor_statuscode == $remote_invoice_full['status'] && $local_invoice_history->yht_euro == $total_price)
			return false;

		// Updated status. In some cases, update invoice status, such as when
		// the invoice is marked sent/paid/invalidated in Procountor.
		switch ($remote_invoice_full['status']) {
			case 'UNFINISHED':
				// Set invoice status (tilanne) to 0 (unfinished).
				$local_invoice->tilanne = 0;
				$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
				$local_invoice->save();
				break;
			case 'APPROVED':
			case 'NOT_SENT':
				// Set invoice status (tilanne) to 1 (approved).
				$local_invoice->tilanne = 1;
				$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
				$local_invoice->save();
				break;
			case 'SENT':
				// Set invoice status (tilanne) to 2 (sent).
				$local_invoice->tilanne = 2;
				$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
				$local_invoice->save();
				break;
			case 'PAID':
				// Set invoice status (tilanne) to 3 (paid).
				$local_invoice->tilanne = 3;
				$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
				$local_invoice->save();
				break;
			case 'INVALIDATED':
				// Set invoice status (tilanne) to 999 (invalidated).
				$local_invoice->tilanne = 999;
				$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
				$local_invoice->save();
				break;
		}

		// Update history.
		$history_entry = new LaskuHistoria;
		$history_entry->time = date("Y-m-d H:i:s", time());
		$history_entry->lid = $local_invoice->id;
		$history_entry->status = $pc->translateProcountorStatus($remote_invoice_full['status']);
		$history_entry->procountor_statuscode = $remote_invoice_full['status'];
		$history_entry->palvelu = "procountor";
		$history_entry->yht_euro = number_format($total_price, 2);
		$history_entry->save();
		return true;
	}

	public function LaskuUpdater($from,$to, &$is_error = false)
	{
		$return = '';
		$asetukset=Asetukset::model()->findbypk(1);

		// Procountor - get invoice status on invoice search, and update if needed.
		if ($asetukset->palvelu_tyyppi == 5) {
			$pc = Yii::createComponent('Procountor');

			// Check that authorization is valid.
			if (!$pc->isAuthorized()) {
				$is_error = true;
				return '<p>Procountor kirjautuminen on viallinen tai vanhentunut. Kirjaudu Procountoriin uudelleen asetuksista.</p>';
			}

			$params = new ProcountorInvoiceSearchParameters();
			$procountor_updated = false;

			// Set search dates. Add one day to end date so that a day is not skipped.
			// (one day search: 12.12-12.12 -> adjust -> 12.12-13.12).
			$params->createdStartDate = $from;
			$params->createdEndDate = date('Y-m-d', strtotime("$to +1 day"));

			// Get search results and check for errors.
			$procountor_results = $pc->searchInvoices($params);
			if (isset($procountor_results['errors'])) {
				/*
				echo '<pre>';
				print_r($procountor_results);
				echo '<pre>';
				*/
				// Log request results and set a notification for the user.
				// Error disabled as per request @ 13.7.2020
				// $pc->logError(
				// 	'searchInvoices',
				// 	$procountor_results,
				// 	['Search dates' => "{$params->createdStartDate} - {$params->createdEndDate}"],
				// 	'Failed to get bank accounts from Procountor.'
				// );

				$is_error = true;
				// return '<p>Laskujen haku Procountorista epäonnistui. Viasta on ilmoitettu ylläpidolle.</p>';
				return '<p>Laskujen haku Procountorista epäonnistui. Tarkista kirjautuminen asetuksista. Jos vika jatkuu, ota yhteys ylläpitoon.</p>' .
						json_encode($procountor_results['errors']);
			}

			foreach($procountor_results['results'] ?? [] as $remote_invoice) {

				// Get local invoice data.
				$criteria = new CDbCriteria();
				$criteria->condition = "procountor_id={$remote_invoice['id']}";
				$local_invoice = Lasku::model()->find($criteria);
				if (!$local_invoice || empty($local_invoice->id))
					continue;

				// Get local invoice history.
				$criteria = new CDbCriteria();
				$criteria->condition = "lid={$local_invoice->id}";
				$criteria->order = "id DESC";
				$local_invoice_history = LaskuHistoria::model()->find($criteria);
				if (!$local_invoice_history || empty($local_invoice_history->id) || $local_invoice_history->lid != $local_invoice->id)
					continue;

				// Get full remote invoice data.
				$remote_invoice_full = $pc->getInvoice($remote_invoice['id']);
				if (isset($remote_invoice_full['errors']) || !isset($remote_invoice_full['id']))
					continue;

				// Calculate total price (yht_euro).
				$total_price = 0.0;
				$includes_vat = $remote_invoice_full['extraInfo']['unitPricesIncludeVat'] ?? true;
				foreach($remote_invoice_full['invoiceRows'] ?? [] as $invoice_row) {
					$vat_multiplier = $includes_vat ? 1 : 1 + $invoice_row['vatPercent'] / 100;
					$total_price += $invoice_row['unitPrice'] * $invoice_row['quantity'] * $vat_multiplier;
				}

				// Continue if neither status or price have updated.
				if ($local_invoice_history->procountor_statuscode == $remote_invoice['status'] && $local_invoice_history->yht_euro == $total_price)
					continue;

				// Updated status. In some cases, update invoice status, such as when
				// the invoice is marked sent/paid/invalidated in Procountor.
				switch ($remote_invoice['status']) {
					case 'UNFINISHED':
						// Set invoice status (tilanne) to 0 (unfinished).
						$local_invoice->tilanne = 0;
						$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
						$local_invoice->save();
						break;
					case 'APPROVED':
					case 'NOT_SENT':
						// Set invoice status (tilanne) to 1 (approved).
						$local_invoice->tilanne = 1;
						$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
						$local_invoice->save();
						break;
					case 'SENT':
						// Set invoice status (tilanne) to 2 (sent).
						$local_invoice->tilanne = 2;
						$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
						$local_invoice->save();
						break;
					case 'PAID':
						// Set invoice status (tilanne) to 3 (paid).
						$local_invoice->tilanne = 3;
						$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
						$local_invoice->save();
						break;
					case 'INVALIDATED':
						// Set invoice status (tilanne) to 999 (invalidated).
						$local_invoice->tilanne = 999;
						$local_invoice->tapahtumapvm = date("Y-m-d H:i:s");
						$local_invoice->save();
						break;
				}

				// <-- onko sama olemassa
	       		$criteria = new CDbCriteria();
		        $criteria->order = " id DESC ";
		        $criteria->condition = " lid='".$local_invoice->id."' ";
				$h = LaskuHistoria::model()->find($criteria);
				
				// Update history.
				$history_entry = new LaskuHistoria;
				//$history_entry->time = date("Y-m-d H:i:s", time());
				$history_entry->lid = $local_invoice->id;
				$history_entry->status = $pc->translateProcountorStatus($remote_invoice['status']);
				$history_entry->procountor_statuscode = $remote_invoice['status'];
				$history_entry->palvelu = "procountor";
				if(isset($h->id) and (int)$h->yht_euro >= $total_price and $local_invoice->tilanne == 3){
					$history_entry->yht_euro = (float)$h->yht_euro-$total_price;
				} else {
					$history_entry->yht_euro = $total_price;
				}
				
				if( Yii::app()->user->username == 'etunti' ){
					//echo (float)$h->yht_euro-$total_price.'<br>';
				}
				
				$is_olemassa = false;
				if(isset($h->id)){
					$last_attr = $h->attributes;
					unset($last_attr['id'], $last_attr['time']);
					$new_attr = $history_entry->attributes;
					$diff = array_diff($last_attr, $new_attr);
					if( count($diff) == 0 )
						$is_olemassa = true;
				}
				
				if(!$is_olemassa){
					$history_entry->save();
					$procountor_updated = true;
				}
				
			}

			if ($procountor_updated)
				$return .= '<p>Procountor laskut on päivitetty.</p>';
		}

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

	/**
	 * Send approved invoice when using Procountor. This is mainly called from
	 * invoices index, by 'Send all' and 'Send selected' buttons.
	 */
	protected function lahetaProcountor($id)
	{
		$pc = Yii::createComponent('Procountor');
		$l = Lasku::model()->findbypk($id);
		$result = 'OK';

		// Check that authorization is valid.
		if (!$pc->isAuthorized()) {
			$result = 'Procountor kirjautuminen on viallinen tai vanhentunut. Kirjaudu Procountoriin uudelleen asetuksista.';
		}
		// Ensure the invoice has been sent to Procountor (procountor_id defined).
		elseif ($l->procountor_id) {
			$send_results = $pc->sendInvoice($l->procountor_id);
			if (isset($send_results['errors'])) {
				$pc->logError('sendInvoice', $send_results, ['Lasku ID' => $id], 'Failed to send invoice.');
				$result = 'Laskun lähetys Procountorissa epäonnistui. Vika on ilmoitettu ylläpitoon.';
			}
		}
		// Invoice cannot be sent from Procountor because if was created with another API or locally.
		else {
			$result = 'Laskua ei voida lähettää Procountorissa koska sitä ei ole luotu Procountoriin Etunti käyttöliittymän kautta.';
		}

		return $result;
	}

	/**
	 * Handle the 'Send all' -button on invoice page (Procountor).
	 */
	public function actionLaheta_procountor()
	{
		$count = 0;
		foreach($_POST['ids'] ?? [] as $id) {
			if (!is_numeric($id))
				continue;
			$invoice = Lasku::model()->findByPk($id);
			if (($invoice->tilanne ?? 0) == 1) {
				if (($result = $this->lahetaProcountor($id)) != 'OK') {
					Yii::app()->user->setFlash('danger', $result);
					$this->redirect('index');
				}
				$count++;
			}
		}

		if ($count > 0)
			Yii::app()->user->setFlash('success', "$count laskua lähetettiin onnistuneesti.");
		else
			Yii::app()->user->setFlash('primary', 'Ei lähetettäviä laskuja.');
		$this->redirect('index');
	}

	public function actionLaheta_valitsemmat($id)
	{
		switch (Asetukset::model()->findByPk(1)->palvelu_tyyppi) {

			// Netvisor
			case 4:
				echo $this->lahetaNetvisoriin($id) ? "OK" : "Error";
				break;

			// Procountor
			case 5:
				echo $this->lahetaProcountor($id);
				break;

			// Not supported
			default:
				echo 'Valittu laskutuksen palvelutyyppi ei tue tätä toimintoa.';
				break;
		}
	}

	public function actionInsert_lahete()
	{
		//echo json_encode(array('ok' => $_POST));
		//exit;

       		$criteria = new CDbCriteria();
	        $criteria->condition = " from_date='".$_POST['from']."' AND to_date='".$_POST['to']."' AND asiakas_id='".$_POST['asiakas_id']."' ";
		$m = Autolahetteet::model()->find($criteria);
		if( isset($m->id) ){
			$model = $m;
			$model->alvsis = $_POST['alvsis'];
			$model->tab_array = json_encode($_POST['tab_array']);
			if(!$model->save()){
				echo json_encode(array('error' => $model->getErrors()));
				exit;
			}
		} else {
			$model = new Autolahetteet;
			$model->asiakas_id = $_POST['asiakas_id'];
			$model->adm_id = Yii::app()->user->id;
			$model->alvsis = $_POST['alvsis'];
			$model->from_date = $_POST['from'];
			$model->to_date = $_POST['to'];
			$model->tab_array = json_encode($_POST['tab_array']);
			if(!$model->save()){
				echo json_encode(array('error' => $model->getErrors()));
				exit;
			}
		}
		echo json_encode(array('ok' => 'ok'));
		exit;
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

	protected function tuotteetLista($id, $text)
	{
		$bod = '';
		if($id){ $bod .= '<option value="'.$id.'">'.$text.'</option>'; }
		$criteria = new CDbCriteria();
       		$criteria->order = " nimike ";
       		$criteria->condition = " 
			hinta_alv_0!=0 AND nayta_vain_onlinevarauksessa=0
		";
		$tp = TuotteetPalvelut::model()->findAll($criteria);
		foreach( $tp as $item){
			$bod .= '<option value="'.$item->id.'">'.$item->nimike.'</option>';
		}
		return $bod;
	}

	protected function lastLaskunumero()
	{
		$last_laskunumero = 1;
		$criteria = new CDbCriteria();
		$criteria->select = " id, MAX(ABS(laskunumero)) as laskunumero ";
		$vm = Lasku::model()->find($criteria);
		if( isset($vm->id) ){
			$last_laskunumero = $vm->laskunumero+1;
		}

		$netvisorList = $this->netvisorListByDay(date("Y-m-d", strtotime("first day of last month")), date("Y-m-d"));
		if( isset($netvisorList->ResponseStatus->Status) and $netvisorList->ResponseStatus->Status == 'OK' ){
			foreach($netvisorList->SalesInvoiceList->SalesInvoice as $list)
				$last_laskunumero = $list->InvoiceNumber;
		}
		return $last_laskunumero;
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

	protected function TyovuoroMobileVertailu($kohdeID, $tv_id, $pvm)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = " tv_id='".$tv_id."' AND kohdenID='".$kohdeID."' AND DATE(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')) = '".date("Y-m-d", strtotime($pvm))."' ";
		$mob = Mobile::model()->find($criteria);
		if( 
			isset($mob->id) 
			and isset($mob->tv_id) 
			and $mob->tv_id != 0 
			and $mob->status == 3 
		){
			$tv = Tyovuoroot::model()->findByPk($mob->tv_id);
			if( isset($tv->id) ){
				$asetukset = Asetukset::model()->findByPk(1);

				// <-- Totetuneen ajan mukaan
				if( 
					isset($asetukset->app_auto_hyvaksyminen) and $asetukset->app_auto_hyvaksyminen == 1 
					and isset($asetukset->app_hyvaksynnan_peruste) and $asetukset->app_hyvaksynnan_peruste == 0
				){
				    $aikavali = 0;
				    $mobile_kesto = strtotime($mob->loppui)-strtotime($mob->aloitan);
				    $tyovuoro_kesto = strtotime($tv->pvm.' '.$tv->loppu)-strtotime($tv->pvm.' '.$tv->alku);

				    if( isset($asetukset->app_auto_hyvaksyminen_aikavali) ){
					$aikavali = $asetukset->app_auto_hyvaksyminen_aikavali*60;
				    }

				    if(
					$aikavali > 0 and
					($tyovuoro_kesto == $mobile_kesto)
					or ( ($mobile_kesto > $tyovuoro_kesto) and ($mobile_kesto-$tyovuoro_kesto) <= $aikavali )
					or ( ($mobile_kesto < $tyovuoro_kesto) and ($tyovuoro_kesto-$mobile_kesto) <= $aikavali )
				    ){
					return true;
				    }

				}
				//     Totetuneen ajan mukaan -->

				// <-- Työvuoron aloitus ja lopetus mukaan
				if( 
					isset($asetukset->app_auto_hyvaksyminen) and $asetukset->app_auto_hyvaksyminen == 1 
					and isset($asetukset->app_hyvaksynnan_peruste) and $asetukset->app_hyvaksynnan_peruste == 1
				){
				    $aikavali = 0;
				    $mobile_aloitus = strtotime($mob->aloitan);
				    $mobile_lopetus = strtotime($mob->loppui);
				    $tyovuoro_aloitus = strtotime($tv->pvm.' '.$tv->alku);
				    $tyovuoro_lopetus = strtotime($tv->pvm.' '.$tv->loppu);

				    if( isset($asetukset->app_auto_hyvaksyminen_aikavali )){
					$aikavali = $asetukset->app_auto_hyvaksyminen_aikavali*60;
				    }

				    if(
					$aikavali > 0 and
					(
						(($mobile_aloitus+$aikavali) >= $tyovuoro_aloitus and $mobile_aloitus < $tyovuoro_lopetus) 
						and ($mobile_aloitus <= ($tyovuoro_aloitus+$aikavali) and $mobile_aloitus <= $tyovuoro_lopetus)
					)
					and (($mobile_lopetus-$aikavali) <= $tyovuoro_lopetus and $mobile_lopetus >= ($tyovuoro_lopetus-$aikavali))
				    ){
					return true;
				    }

				}
				//     Työvuoron aloitus ja lopetus mukaan -->
			}
		}

		return false;
	}
}

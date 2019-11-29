<?php

class TyovuorootController extends Controller
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
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','did','muisti','operatio', 'operatio_v3', 'viikko','fromto','autoinsert','autoremove','viikkottain', 'viikkottain_pdf', 'laheta','kk','pvmtid','laheta_k', 'muistin', 'muisticlear', 'muistissa', 'vkolopput', 'vkolopchange', 'uusitilaus', 'tv2', 'tv3', 'beta', 'did4', 'PoistaTv', 'valitse_kokopaiva', 'tv_kohteet', 'siivous_tyonimike', 'getKohdeByAsiakas', 'getKohdeById', 'getAsiakasByKohde', 'paivita_laatikot', 'poista_toistuva', 'onko_sama', 'asiakas_autocomplete', 'kohde_autocomplete', 'check_paallekkain', 'get_tekijantiedot', 'is_asiakas', 'is_yhteyshenkilo', 'lista', 'didnew', 'palautta_toistuva_pvm', 'did3', 'didnew3', 'siirto', 'vlupdater', 'palkkataulukko', 'hovertietoja', 'create4', 'update4', 'create4_form', 'update4_form'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index','view','updatetime','showohje','did','muisti','operatio','viikko','fromto','autoinsert','autoremove','viikkottain','laheta','kk','pvmtid','laheta_k'),
                		'message'=>Yii::t('main', 'Tämä TASO ei kuuluu teille'),
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
	   	    if($val > 0)
		   	   return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}

	protected function TimeToSec($time) {
	    $sec = 0;
	    foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
	    return $sec;
	}

	public function actionPalkkataulukko()
	{
		$asetukset = Asetukset::model()->findByPk(1);
		// <-- Order tyontekijat
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

		$from = date("d.m.Y",strtotime("first day of this month"));
		$to = date("d.m.Y");

		if(isset($_GET['from']) and !empty($_GET['from'])){ $from = $_GET['from']; }
		if(isset($_GET['to']) and !empty($_GET['to'])){ $to = $_GET['to']; }

       		$criteria = new CDbCriteria();
		$criteria->select = " id,tekijan_nimi ";

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

        	$criteria->condition = " aktiivinen=1 "; 

		if( isset($_GET['Tekija']) ){
			$ids = implode(",",$_GET['Tekija']);
	        	$criteria->addCondition ('id IN ('.$ids.') ');
		}

/*
		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			'pagination'=>false
		));
*/
		$model = Tyontekijat::model()->findAll($criteria);

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		//$dataProvider->pagination->pageSize = 50;
		$this->render('palkkataulukko', array(
			'model' => $model,
			'from' => $from,
			'to' => $to,
			'tt_order_1' => $tt_order_1,
			'tt_order_2' => $tt_order_2,
		));
		
	}

	protected function TP($tid,$from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$result = 0;
       		$criteria = new CDbCriteria();
        	$criteria->group = "DATE(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))";
	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND peruutettu=0
			AND status=3
		";
		$tv = Tyovuoroot::model()->findAll($criteria);
		return count($tv);

	}

	public function poissaolot($from,$to,$tid,$sairaus)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

       		$criteria = new CDbCriteria();
		$criteria->group = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') ";
	        $criteria->condition = " 
			tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND tyoajanlaatu LIKE '%(".$sairaus.")%'
		";
		$vl = Tyovuoroot::model()->findAll($criteria);

		return count($vl);
	}

	public function pyhapaivat($tid,$from,$to,$m)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$result 	= 0;
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
		$pvmSTR = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')='".implode("' OR DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')='",$pget)."'";
		}

		if(!empty($pvmSTR))
		$pvmSTR = " AND ($pvmSTR) ";

		$return 	= 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";

        	$criteria->condition = "  
			tid = '".$tid."'
			AND (status='2' OR status='3')
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND DAYOFWEEK(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))!=1
			$pvmSTR
			AND peruutettu=0
		";
		$tv = Tyovuoroot::model()->find($criteria);
		if(isset($tv->l_tunnit)){ $result = $tv->l_tunnit; }
		return $result;
	}
/*
	public function matkaIlta($tid,$from,$to)
	{
		$mobile = Yii::app()->createController('Mobile');
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$totalIlta = 0;

       		$criteria = new CDbCriteria();
        	$criteria->condition = "  
			tid = '".$tid."'
			AND status = '2'
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND peruutettu=0
		";

		$tv = Tyovuoroot::model()->findAll($criteria);
		foreach($tv as $l)
		{
		    $loppui = date("d.m.Y H:i",strtotime($l->pvm.' '.$l->loppu));
		    $aloitan = date("d.m.Y H:i",strtotime($l->pvm.' '.$l->alku));

		    $l->l_tunnit = (strtotime($loppui)-strtotime($aloitan));
		    $al = explode(" ",$aloitan);
		    $lop = explode(" ",$loppui);
		    $totalIlta += $mobile[0]->ilta($al,$lop);

		}

		return $totalIlta;

	}
*/
/*
	public function toteutu($tid,$sivu,$from,$to)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$result 	= 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
        	$criteria->condition = "  
			tid = '".$tid."'
			AND peruutettu=0
		";
		if($sivu == 'palkkataulukko'){ $criteria->addCondition (" status = '3' "); }
		if($sivu == 'yhteenveto')
		{
			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");
		}

	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");

		$tv = Tyovuoroot::model()->find($criteria);
		if(isset($tv->l_tunnit)){ $result = $tv->l_tunnit; }
		return $result;

	}
*/
	protected function TidfromtoTyovuoroAll($from, $to, $tids, $status, $time, $by_pvm=false){
		$set = [];
		if (is_array($tids)) {
			foreach($tids as $tid)
				$set[$tid] = 0;
			$tids = implode(", ", $tids);
		} else {
			$set[$tids] = 0;
		}

		// <-- Pyhapaivat
		$pyhapaivat = [];
		if($time==4){
			$asetukset = AsetuksetForAll::model()->findbypk(1);
			$p_explode = explode("\n", $asetukset->viralliset_pyhapaivat);
			$p_explode = array_map('trim', $p_explode); // clear spaces
			$p_explode = array_map('rtrim', $p_explode); // clear spaces

			$begin = date ("d.m.Y", strtotime($from));
			$end   = date ("d.m.Y", strtotime($to));
			while (strtotime($begin) <= strtotime($end)) {
                		if(in_array($begin, $p_explode)){
					$pyhapaivat[] = $begin;
				}
                		$begin = date ("d.m.Y", strtotime("+1 day", strtotime($begin)));
			}
			$pyhapaivat = "DATE(STR_TO_DATE(pvm, '%d.%m.%Y'))='".implode("' OR DATE(STR_TO_DATE(pvm, '%d.%m.%Y'))='", $pyhapaivat)."'";
		}
		//     Pyhapaivat -->

		// <-- Erikoislauantai
		$erikoislauantai = [];
		if($time==5){
			$asetukset = AsetuksetForAll::model()->findbypk(1);
			$p_explode = explode("\n", $asetukset->erikoislauantai);
			$p_explode = array_map('trim', $p_explode); // clear spaces
			$p_explode = array_map('rtrim', $p_explode); // clear spaces

			$begin = date ("d.m.Y", strtotime($from));
			$end   = date ("d.m.Y", strtotime($to));
			while (strtotime($begin) <= strtotime($end)) {
                		if(in_array($begin, $p_explode)){
					$erikoislauantai[] = $begin;
				}
                		$begin = date ("d.m.Y", strtotime("+1 day", strtotime($begin)));
			}
			$erikoislauantai = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%d.%m.%Y')='".implode("' OR DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%d.%m.%Y')='", $erikoislauantai)."'";
		}
		//     Erikoislauantai -->

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$status = "status='".implode("' OR status='", $status)."'";
		$criteria = new CDbCriteria();
		if($by_pvm)
		   $criteria->group = "DATE(STR_TO_DATE(pvm, '%d.%m.%Y')), tid";
		else
		   $criteria->group = "tid";

		// Helper function to avoid duplicate code (doesn't handle 'hyvaksytty' as it differs)
		$buildCriteria = function (CDbCriteria &$criteria) use ($from, $to, $tids, $status, $time, $pyhapaivat, $erikoislauantai) {
			// Select statements
			switch ($time) {
				case 0:
					$criteria->select = "
						pvm, tid, SUM(TIME_TO_SEC(TIMEDIFF(
							TIME(loppu), TIME(alku)
						))) as l_tunnit";
					break;
				case 1:
					// Note: 18000 at end of query is equal to TIME_TO_SEC(TIMEDIFF('23:00:00', '18:00:00'))
					$criteria->select = "pvm, tid, SUM(CASE
						WHEN
							TIME(alku) >= '18:00:00'
						THEN CASE
							WHEN
								TIME(loppu) > '23:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF('23:00:00', TIME(alku)))
							WHEN
								TIME(loppu) > '18:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(loppu), TIME(alku)))
							ELSE
								0
							END
						ELSE CASE
							WHEN
								TIME(loppu) > '23:00:00'
							THEN
								18000
							WHEN
								TIME(loppu) > '18:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(loppu), '18:00:00'))
							ELSE
								0
							END
						END) AS l_tunnit";
					break;
				case 2:
					$criteria->select = "pvm, tid, SUM(CASE
							WHEN
								TIME(loppu) <= '06:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(loppu), TIME(alku)))
							WHEN
								TIME(loppu) > '23:00:00'
							THEN CASE
								WHEN
									TIME(alku) <= '06:00:00'
								THEN
									TIME_TO_SEC(TIMEDIFF('06:00:00', TIME(alku))) +
									TIME_TO_SEC(TIMEDIFF(TIME(loppu), '23:00:00'))
								WHEN
									TIME(alku) > '23:00:00'
								THEN
									TIME_TO_SEC(TIMEDIFF(TIME(loppu), TIME(alku)))
								ELSE
									TIME_TO_SEC(TIMEDIFF(TIME(loppu), '23:00:00'))
								END
							WHEN
								TIME(alku) <= '06:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF('06:00:00', TIME(alku)))
							ELSE
								0
							END
						) AS l_tunnit";
					break;
				case 3:
					$criteria->select = "pvm, tid, SUM(CASE
						WHEN
							DAYOFWEEK(STR_TO_DATE(pvm, '%d.%m.%Y')) = 1
						THEN CASE
							WHEN
								DAYOFWEEK(STR_TO_DATE(pvm, '%d.%m.%Y')) = 1
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(loppu), TIME(alku)))
							ELSE
								TIME_TO_SEC(TIMEDIFF('23:59:00', TIME(alku))) + 60
							END
						WHEN
							DAYOFWEEK(STR_TO_DATE(pvm, '%d.%m.%Y')) = 1
						THEN
							TIME_TO_SEC(TIMEDIFF(TIME(loppu), '00:01')) + 60
						ELSE
							0
						END) AS l_tunnit";
					break;
				case 4:
					$criteria->select = "pvm, tid, SUM(CASE
						WHEN
							$pyhapaivat
						THEN 
							TIME_TO_SEC(TIMEDIFF(TIME(loppu), TIME(alku)))
						ELSE
							0
						END) AS l_tunnit";
					break;
				case 5:
					$criteria->select = "pvm, tid, SUM(CASE
						WHEN
							$erikoislauantai
						THEN 
							TIME_TO_SEC(TIMEDIFF(TIME(loppu), TIME(alku)))
						ELSE
							0
						END) AS l_tunnit";
					break;
			}

			// Conditions
			$criteria->condition = "
				tid IN ($tids)
				AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) BETWEEN '$from' AND '$to'
				AND peruutettu=0";
			if ($status) $criteria->addCondition($status);
		};

		// Build CDbCriteria
		$buildCriteria($criteria);
		$tv = Tyovuoroot::model()->findAll($criteria);

		if($by_pvm){
			foreach ($tv as $t){
				$set[date("Y-m-d", strtotime($t->pvm))][$t->tid] = $t->l_tunnit;
			}
		} else {
			foreach ($tv as $t)
				$set[$t->tid] += $t->l_tunnit;
		}
		return $set;
	}

	protected function TPBetweenTvAll($from,$to,$tids){

		$set = [];
		$t = [];
		if (is_array($tids)) {
			foreach($tids as $tid)
				$t[$tid] = 0;
			$tids = implode(", ", $t);
		} else {
			$t[$tids] = 0;
		}

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

       		$criteria = new CDbCriteria();
        	$criteria->select = "tid, pvm";
        	$criteria->group = "DATE(STR_TO_DATE(pvm, '%d.%m.%Y')), tid";
	        $criteria->condition = "
			DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) BETWEEN '".$from."' AND '".$to."' 
			AND peruutettu=0
		";
		$tv = Tyovuoroot::model()->findAll($criteria);
		foreach($tv as $item){
			$set[date("Y-m-d", strtotime($item->pvm))][$item->tid] = 1;
		}
		$total = [];
		foreach($set as $pvm=>$val){
			foreach($val as $tid=>$count){
				if(!isset($total[$tid])){ $total[$tid]=0; }
				$total[$tid] += $count;
			}
		}
		return $total;

	}
/*
	public function TidfromtoStatus($from,$to,$tid,$status)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$result = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
	        $criteria->condition = " 
			tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND status='".$status."'
			AND peruutettu=0
		";
		$tv = Tyovuoroot::model()->find($criteria);
		if(isset($tv->l_tunnit)){ $result = $tv->l_tunnit; }
		return $result;
	}
*/
	public function actionIs_yhteyshenkilo()
	{
		$bd = '';
		if(Yii::app()->request->getPost('yhteyshenkilo'))
		{
			$model = Asiakkaat::model()->find(" yhteyshenkilo LIKE '%".Yii::app()->request->getPost('yhteyshenkilo')."' ");
			if(isset($model->id))
			$bd = Yii::t('main', 'Asiakas löyty tietokannasta');	
		}
		echo json_encode($bd);
		exit;
	}

	public function actionIs_asiakas()
	{
		$bd = '';
		if(Yii::app()->request->getPost('yrityksen_nimi'))
		{
			$model = Asiakkaat::model()->find(" yrityksen_nimi LIKE '%".Yii::app()->request->getPost('yrityksen_nimi')."' ");
			if(isset($model->id))
			$bd .= Yii::t('main', 'Asiakas löyty tietokannasta');	
		}
		if(Yii::app()->request->getPost('sahkoposti'))
		{
			$model = Asiakkaat::model()->find(" sahkoposti LIKE '%".Yii::app()->request->getPost('sahkoposti')."' ");
			if(isset($model->id))
			$bd .= Yii::t('main', 'Asiakas löyty tietokannasta');	
		}
		echo json_encode($bd);
		exit;
	}

	public function actionGetKohdeByAsiakas($id)
	{
		$model = Kohteet::model()->findAll(" asiakas_id='".$id."' ");
			$bd = '';
			$bd .= '<option value>'.Yii::t('main', 'Valitse kohde').'</option>';
			foreach($model as $k)
			$bd .= '<option value="'.$k->id.'">'.$k->osoite.'</option>';


		echo json_encode($bd);	
	}

	public function actionGetKohdeById($id)
	{
		$model = Kohteet::model()->findByPk($id);
			$bd = '';
			$bd .= '<option value>'.Yii::t('main', 'Valitse kohde').'</option>';
			$bd .= '<option value="'.$model->id.'">'.$model->osoite.'</option>';


		echo json_encode($bd);	
	}

	public function actionGet_tekijantiedot($id)
	{

		$model = Tyontekijat::model()->findByPk($id);

		$bd = '';
		$bd .= '<div class="row">';
		$bd .= '<div class="col-sm-8">';
		$bd .= Yii::t('main', 'Nimi').': <b>'.$this->etuSukunimi($model->id).'</b><br>';
		$bd .= Yii::t('main', 'Työpuhelin').': <b>'.$model->laiten_puh.'</b><br>';
		$bd .= Yii::t('main', 'Oma puhelin').': <b>'.$model->tekijan_puh.'</b><br>';
		$bd .= Yii::t('main', 'Sähköpostiosoite').': <b>'.$model->tekijan_email.'</b><br>';
		$bd .= Yii::t('main', 'Kotiosoite').': <b>'.$model->tekijan_katuosoite.'</b><br>';

		$bd .= '</div><div class="col-sm-4">';
		$filepath = dirname(Yii::app()->getBasePath())."/img/tekijat/".Yii::app()->user->domain."/".$model->id.".jpg";
		if (file_exists($filepath)){
		   $imageData = base64_encode(file_get_contents($filepath));
		   $src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;
		   $bd .= '<div class="pull-right"><img src="'.$src.'" class="img-thumbnail"></div>';
		} else {
		   $bd .= '<img src="'.Yii::app()->request->baseUrl.'/img/tekijat/noname.jpg" class="img-thumbnail">';
		}
		$bd .= '</div></div>';

		echo json_encode(array('bd'=>$bd, 'etusuku' => $this->etuSukunimi($model->id)));	
	}


	public function actionOnko_sama($id, $pvm, $tid, $kohde, $alku, $loppu)
	{
		$return = array();
		$return = $this->onko_sama($id, $pvm, $tid, $kohde, $alku, $loppu);
		echo json_encode($return);
	}


	public function onko_sama($id, $pvm, $tid, $kohde, $alku, $loppu)
	{
		$return = array();

			$criteria=new CDbCriteria;
			$criteria->condition="
				tid='".$tid."'
				AND pvm='".date("d.m.Y", strtotime($pvm))."'
				AND kohde='".$kohde."'
				AND alku='".$alku."'
				AND loppu='".$loppu."'
				AND peruutettu=0
			";
			if(!empty($id))
			$criteria->addCondition(" id!='".$id."' ");

			$model = Tyovuoroot::model()->findAll($criteria);
			foreach($model as $data)
			{
				$osoite = '';
				$k = Kohteet::model()->findbypk($data->kohde);
				if(isset($k->id))
				$osoite = $k->osoite;

				$tt = '';
				$k = Tyontekijat::model()->findbypk($data->tid);
				if(isset($k->id))
				$tt = $k->tekijan_nimi;

				$return[] = array(
					'id'=>$data->id,
					'pvm'=>$data->pvm,
					'alku'=>$data->alku,
					'loppu'=>$data->loppu,
					'tekijan_nimi'=>$tt,
					'osoite'=>$osoite
				);
			}
			

		return $return;
	}


	public function actionCheck_paallekkain()
	{
		$count	= 0;
		$tid	= $_POST['tid'];
		$pvm	= $_POST['pvm'];
		$alku	= date("Y-m-d H:i:s", strtotime($_POST['alku']));
		$loppu	= date("Y-m-d H:i:s", strtotime($_POST['loppu']));
		

		$criteria=new CDbCriteria;
		$criteria->condition="
			tid='".$tid."' AND pvm='".date("d.m.Y", strtotime($pvm))."'
			AND (
				DATE_FORMAT(STR_TO_DATE(alku, '%H:%i'), '%Y-%m-%d %H:%i:%s') BETWEEN '".$alku."' AND '".$loppu."'
				OR DATE_FORMAT(STR_TO_DATE(loppu, '%H:%i'), '%Y-%m-%d %H:%i:%s') BETWEEN '".$alku."' AND '".$loppu."'
			)
			AND peruutettu=0
		";
		$model = Tyovuoroot::model()->findAll($criteria);
		$count = count($model);
		echo json_encode($count);
	}


	public function actionGetAsiakasByKohde($id)
	{
		$k = Kohteet::model()->findbypk($id);
		 if(isset($k->asiakas_id))
		  $a = Asiakkaat::model()->findbypk($k->asiakas_id);
		   if(isset($a->id))
		   {
			if(!empty($a->yrityksen_nimi))
				$asiakas = $a->yrityksen_nimi;
			else if(!empty($a->yhteyshenkilo) and empty($a->yrityksen_nimi))
				$asiakas = $a->yhteyshenkilo;
			else
				$asiakas = $a->osoite;

		    	echo json_encode($asiakas);
		   }
	}

	public function actionSiivous_tyonimike()
	{
		if(isset($_POST['siivousTyonimike']))
		{
			$siivousTyonimike = $_POST['siivousTyonimike'];
			$fromTV = date("Y-m-d", strtotime($_POST['fromTV']));
			$toTV = date("Y-m-d", strtotime($_POST['toTV']));
			$tekijanToimialue = $_POST['tekijanToimialue'];

			$criteria=new CDbCriteria;

			if(!empty($siivousTyonimike))
			{
			
			$criteria->addCondition (" 
				id IN(
				   SELECT tid FROM sivex_tvuoro
				   WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')
				   BETWEEN '".$fromTV."' AND '".$toTV."'
					AND kohde IN(
					   SELECT id FROM sivex_kohdet
					   WHERE siivous LIKE '%".$siivousTyonimike."%'
					)
				)
			");
			}
			if(!empty($tekijanToimialue))
			{
			$criteria->addCondition("
				tyo_toimialue LIKE '%".$tekijanToimialue."%' 
			");
			}

			$tv = Tyontekijat::model()->findAll($criteria);
			$t = array();
			foreach($tv as $data)
			$t[] = $data->id;

			echo json_encode($t);

		}
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

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			peruutettu=0
		";
		$dataProvider=new CActiveDataProvider('Tyovuoroot', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));
		$this->render('kk',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionPvmTid($pvm,$tid,$from)
	{

		$this->renderPartial('pvmtid',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'from'=>$from,
		));
	}

	public function actionLaheta($tid,$week,$year,$tulosta) {

		$tt = Tyontekijat::model()->findbypk($tid);

		if(Yii::app()->request->getPost('pdf'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
		  $html2pdf->setTestTdInOnePage(false);
	          $html2pdf->WriteHTML($this->renderPartial('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true));
	          $html2pdf->Output();

		} elseif(Yii::app()->request->getPost('pdf_email'))
		{


	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
		  $html2pdf->setTestTdInOnePage(false);
		  $thisHtml = $this->renderPartial('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true);
	          $html2pdf->WriteHTML($thisHtml);
         	  $content_PDF = $html2pdf->Output('my_doc.pdf', EYiiPdf::OUTPUT_TO_STRING);


		/* file */
		$file = $week.'_'.$year.'_'.$tid.'.pdf';
		$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;

  		if (!file_exists($path))
		  	mkdir($path, 0777, true);

		file_put_contents($path.'/'.$file, $content_PDF);
		/* file */
		$message = Yii::t('main', 'VIIKKO').'-'.$week.'<br>'.Yii::t('main', ' Liitteenä uusi PDF-tiedosto');
		if(isset($_POST['kirjenBody']) and !empty($_POST['kirjenBody']))
		$message .= '<br><div style="font-size: 120%">'.str_replace("\n", "<br>", $_POST['kirjenBody']).'</div>';
		

		$saaja = $tt->tekijan_email;
		$ft = FirmanTiedot::model()->findbypk(1);
		if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
		$saaja = array($tt->tekijan_email,$ft->sahkoposti);
	
		$subject = Yii::t('main', 'TYÖVUOROT'). ' '.$tt->tekijan_nimi;

		$mail = new YiiMailer();
		//$mail->clearLayout();//if layout is already set in config
		$mail->setFrom('no-reply@etunti.fi');
		$mail->setTo($saaja);
		$mail->setSubject($subject);
		$mail->setBody($message);
		$mail->setAttachment($path.'/'.$file);

			if($mail->send()){


							if(is_array($saaja)) $saaja = implode(",",$saaja); 
 
							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $saaja;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$file;
							$log->email_attachment_sisalto	= json_encode($thisHtml);
							$log->log_nimike	= 'tyovuoro_lahetys';
							$log->save();
							//     LOG -->

	

			  $this->render('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>false,'tt'=>$tt));
			} else {
			  echo 'Send error';
			}



		} else {
		  $this->render('laheta',array('tid'=>$tid,'week'=>$week,'year'=>$year,'tulosta'=>false,'tt'=>$tt));
		}


	}


	public function actionLaheta_k($week,$year,$tulosta) {

		if(Yii::app()->request->getPost('pdf'))
		{
			$tt = Tyontekijat::model()->findbypk($_POST['kuka']);

			$basePath = Yii::app()->basePath.'/../tmp/'.Yii::app()->user->domain.'/';
			$path = 'tmp/'.Yii::app()->user->domain.'/';

			if (!file_exists( $basePath )) {
			 	mkdir( $basePath, 0777, true );
			}
			$tiedosto = 'tyovuoro_'.date("d.m.Y");
			$html = '<meta charset="UTF-8">';
			$html .= $this->renderPartial('laheta',array('tid'=>$_POST['kuka'],'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true);

			file_put_contents($path.'/'.$tiedosto.'.html', $html);
			$output = exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf 2>&1'); //-O landscape
			header("Content-Length: " . filesize ( $path.$tiedosto.'.pdf' ) ); 
		        header("Content-type: application/pdf"); 
		        header("Content-disposition: attachment; filename=".basename($path.$tiedosto.'.pdf'));
		        readfile($path.$tiedosto.'.pdf');
			unlink($path.$tiedosto.'.html');
			unlink($path.$tiedosto.'.pdf');
			exit;

		} elseif(Yii::app()->request->getPost('pdf_email'))
		{

		$from = date("Y-m-d", strtotime("{$_POST['year']}-W{$_POST['week']}-1"));
		$to = date("Y-m-d", strtotime("{$_POST['year']}-W{$_POST['week']}-7"));

		$kenelle = json_decode(Yii::app()->request->getPost('kenelle'));
		$kenelle = array_filter($kenelle);

		// <-- Update piilota_mobiilista nollaksi
		$tids = "(tid='".implode("' OR tid='", $kenelle)."')";
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to'
			AND $tids
			AND peruutettu=0
		";

		if(isset($_POST['P']))
		$criteria->Addcondition ( " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%w') IN (".implode(",",$_POST['P']).") ");

		Tyovuoroot::model()->updateAll(array('piilota_mobiilista'=>'0'), $criteria);
		//    Update piilota_mobiilista nollaksi -->

  		if(!isset($_POST['P'])) {
		    echo 'Days error';
		    exit;
		}




		foreach($kenelle as $key)
		{
		 if(!empty($key))
		 {
			$tt = Tyontekijat::model()->findbypk($key);

			$html = '<meta charset="UTF-8">';
			$html .= $this->renderPartial('laheta',array('tid'=>$tt->id,'week'=>$week,'year'=>$year,'tulosta'=>true,'tt'=>$tt),true);

			$basePath = Yii::app()->basePath.'/../emails/tyovuorot/'.Yii::app()->user->domain.'/';
			$path = 'emails/tyovuorot/'.Yii::app()->user->domain.'/';
			if (!file_exists( $basePath )) {
			 	mkdir( $basePath, 0777, true );
			}
			$tiedosto = $week.'_'.$year.'_'.$key;
			file_put_contents($path.'/'.$tiedosto.'.html', $html);
			$output = exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf 2>&1'); //-O landscape

			$message = Yii::t('main', 'VIIKKO').'-'.$week.'<br>'.Yii::t('main', ' Liitteenä uusi PDF-tiedosto');
			if(isset($_POST['kirjenBody']) and !empty($_POST['kirjenBody']))
			$message .= '<br>'.str_replace("\n", "<br>", $_POST['kirjenBody']);
		
			$subject = Yii::t('main', 'TYÖVUOROT'). ' '.$tt->tekijan_nimi;

			$ft = FirmanTiedot::model()->findbypk(1);
			$mail = new YiiMailer();
			//$mail->clearLayout();//if layout is already set in config
			$mail->setFrom('no-reply@etunti.fi');
			$mail->setTo($tt->tekijan_email);
			$mail->setSubject($subject);
			$mail->setBody($message);
			$mail->setAttachment($path.'/'.$tiedosto.'.pdf');
			if($mail->send()){

 
							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->kuka 		= Yii::app()->user->nimi;
							$log->email_to 		= $tt->tekijan_email;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$tiedosto.'.pdf';
							$log->email_attachment_sisalto	= json_encode($html);
							$log->log_nimike	= 'tyovuoro_lahetys';
							$log->save();
							//     LOG -->
							if (file_exists( $path.$tiedosto.'.html' )) {
								unlink($path.$tiedosto.'.html');
							}

			}

		 }
		}


		// firmalle kaikki
		$ft = FirmanTiedot::model()->findbypk(1);
		if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
		{
			$saaja = $ft->sahkoposti;

			$html = '<meta charset="UTF-8">';
			$html .= $this->renderPartial('laheta_k',array('week'=>$week,'year'=>$year,'tulosta'=>'lista'),true);

			$basePath = Yii::app()->basePath.'/../emails/tyovuorot/'.Yii::app()->user->domain.'/';
			$path = 'emails/tyovuorot/'.Yii::app()->user->domain.'/';
			if (!file_exists( $basePath )) {
			 	mkdir( $basePath, 0777, true );
			}
			$tiedosto = $week.'_'.$year.'_'.$key.'_toimisto.pdf';
			file_put_contents($path.'/'.$tiedosto.'.html', $html);
			$output = exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf 2>&1'); //-O landscape

			$message = Yii::t('main', 'VIIKKO').'-'.$week.'<br>'.Yii::t('main', ' Liitteenä uusi PDF-tiedosto');
			if(isset($_POST['kirjenBody']) and !empty($_POST['kirjenBody']))
			$message .= '<br>'.str_replace("\n", "<br>", $_POST['kirjenBody']);

			$subject = Yii::t('main', 'TYÖVUOROT ').$week.'-'.$year;
	
			$mail = new YiiMailer();
			//$mail->clearLayout();//if layout is already set in config
			$mail->setFrom('no-reply@etunti.fi');
			$mail->setTo($saaja);
			$mail->setSubject($subject);
			$mail->setBody($message);
			$mail->setAttachment($path.'/'.$tiedosto.'.pdf');
			if($mail->send()){

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $saaja;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->email_attachment	= $path.'/'.$tiedosto.'.pdf';
							$log->email_attachment_sisalto	= json_encode($html);
							$log->log_nimike	= 'tyovuoro_lahetys';
							$log->save();
							//     LOG -->
							if (file_exists( $path.$tiedosto.'.html' )) {
								unlink($path.$tiedosto.'.html');
							}
			}

		}
		//

		  $this->redirect('viikkottain');

		} else {
		  $this->render('laheta_k',array('week'=>$week,'year'=>$year,'tulosta'=>false));
		}


	}
	public function actionViikkottain() {

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('viikkottain_pdf',array('no'=>'ei mitaan'),true));
	          $html2pdf->Output();
		} else {
		  $this->render('viikkottain');
		}
	}


	public function actionVkolopput()
	{
		if(isset($_SESSION['vkolopput']))
		   echo 1;
		else
		   echo 0;
		exit;

	}

	public function actionVkolopchange()
	{
		if(isset($_POST['nyt']) and $_POST['nyt'] == 0)
		   $_SESSION['vkolopput'] = true;
		elseif(isset($_POST['nyt']) and $_POST['nyt'] == 1)
		   unset($_SESSION['vkolopput']);
		exit;
	}

	public function actionValitse_kokopaiva()
	{
		if(isset($_POST['pvm']) and isset($_POST['tid']))
		{

			$criteria=new CDbCriteria;
			$criteria->condition = " 
				pvm='".date("d.m.Y", strtotime($_POST['pvm']))."' 
				AND tid='".$_POST['tid']."'
			";
			$tv = Tyovuoroot::model()->findAll($criteria);
			$asetukset = Asetukset::model()->findByPk(1);
			$for = '';
			if(isset($tv[0]))
			{
			  foreach($tv as $data)
			  {

			   	// <-- Tyoryhmat
				if( 
				   isset($data->kohteet) 
				   and isset($asetukset) 
				   and $asetukset->tyoryhmat_kohde == 1 
				){
					$site = Yii::app()->createController('Site');
					$arr = $site[0]->TyoryhmatHelper();
					if( count($arr) > 0 and !in_array($data->kohteet->tyoryhma, $arr)){
			   			continue;
					}
				}
			   	//    Tyoryhmat -->

				$id = $data->id."_".date("Ymd", strtotime($data->pvm))."_".$data->tid;
				$for = date("Ymd", strtotime($data->pvm))."_".$data->tid;
				$_SESSION['muistin'][$id] = $id;
			  }
			}
				print_r($_SESSION['muistin']);
		}
		exit;

	}

	public function actionMuistin()
	{
		if(isset($_POST['id']))
		{
			$_SESSION['muistin'][$_POST['id']] = $_POST['id'];
			print_r($_SESSION['muistin']);
			
		}

	}

	public function actionMuistissa()
	{
		if(isset($_SESSION['muistin']))
		   echo implode(",",$_SESSION['muistin']);
		else
		   echo 'muistityhja';
	}

	public function actionMuisticlear()
	{
		if(isset($_SESSION['muistin']) and isset($_POST['clear'])){
			echo json_encode($_SESSION['muistin']);
			unset($_SESSION['muistin']);
		}
	}

	public function actionPoistaTv()
	{

		$model = Tyovuoroot::model()->findbypk($_POST['poistaTv']);
		$return = array();

		if(	isset($_POST['toistuva_aktiivinen']) 
			and $_POST['toistuva_aktiivinen'] == 'true'
			and isset($model->toistuva_id)
			and $model->toistuva_id != 0
			and isset($_POST['pfrom']) and !empty($_POST['pfrom'])
			and isset($_POST['pto']) and !empty($_POST['pto'])
		)
		{

			$poistoCriteria = new CDbCriteria;
			$poistoCriteria->condition = " 
				toistuva_id='".$model->toistuva_id."' 
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN 
				'".date("Y-m-d", strtotime($_POST['pfrom']))."'
					AND '".date("Y-m-d", strtotime($_POST['pto']))."'
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
			";
			$m = Tyovuoroot::model()->findAll($poistoCriteria);

			
			foreach($m as $model)
			{

				$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
				$this->toistuvaDeletePvm($model->toistuva_id, $model->pvm);

				// <-- LOG
				if( isset($model->id) )
				{
					$model_log 	= 'Tyovuoroot';
					$name_log 	= 'Työvuorot';
					$status_log 	= 'Delete';
	
					$old_values = json_encode($model->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

			}

			Tyovuoroot::model()->deleteAll($poistoCriteria);

			// <-- Otetaan pois tyovuoro_id noista jotka on tehtty
			$upd_criteria = new CDBcriteria;
			$upd_criteria->condition=" 
				toistuva_id='".$model->toistuva_id."'
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') < CURDATE()
			";
			Tyovuoroot::model()->updateAll(array('toistuva_id'=>0), $upd_criteria);
			//    Otetaan pois tyovuoro_id noista jotka on tehtty -->

			// <-- Tsekataan, onko jai jonkun tyovuorojen koskemattomana
			$tsekka_tv = Tyovuoroot::model()->findAll(" toistuva_id='".$model->toistuva_id."' ");
			//  Tsekataan, onko jai jonkun tyovuorojen koskemattomana -->



			if( $tsekka_tv == null )
			{

				// <-- LOG
				if( isset($model->toistuva_id) )
				{
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$status_log 	= 'Delete';
					$t_m = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);
					$old_values = json_encode($t_m->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

				ToistuvatTyovuorot::model()->findByPk($model->toistuva_id)->delete();

			} else {

				// Keksi loogikka
			}


			echo json_encode($return);
			exit;
		}




		if(	isset($_POST['toistuva_aktiivinen']) 
			and $_POST['toistuva_aktiivinen'] != 'true'
		)
		{
			if(isset($model->id))
			{
				$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			}

			// <-- LOG
			if( isset($model->id) )
			{
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Delete';

				$old_values = json_encode($model->attributes);
				$new_values = null;
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($model->pvm) and $model->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($model->toistuva_id, $model->pvm);
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->

			Tyovuoroot::model()->deletebypk($model->id);

			echo json_encode($return);
			exit;
		}
	}

	public function actionOperatio($did_versio='did')
	{
		if( isset($_GET['did']) ){ $did_versio = $_GET['did']; }
		$asetukset = Asetukset::model()->findByPk(1);

		if(isset($_POST['checkThis']))
		{
			$did = $this->renderPartial($did_versio,array(
				'pvm'=>$_POST['newPvm'],
				'tid'=>$_POST['newTid'],
				'from'=>'ajax',
				'asetukset'=>$asetukset
			), true);
			echo json_encode($did.'//');
			exit;
		}

		// remove
		if(isset($_POST['remove']) and isset($_SESSION['muistin']))
		{
		foreach($_SESSION['muistin'] as $cp)
		{
			$ex = explode("_",$cp);

			$t = Tyovuoroot::model()->findbypk($ex[0]);
			if( !isset($t->id) ){ exit; }

			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($t->pvm) and $t->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($t->toistuva_id, $t->pvm);
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->


			if( is_array(json_decode($t->tyopaari, true)) ){
				$uusi_tp_arr = array();
				foreach(json_decode($t->tyopaari, true) as  $id => $tp_id){
					$tv = Tyovuoroot::model()->findByPk($id);
					if( isset($tv->id) and $tv->tid == $t->tid ){
						// ei mitaan koska pois
					} else {
						$uusi_tp_arr[$id] = $tp_id;
					}
				}
				foreach( $uusi_tp_arr as $k => $v ){
					if( count($uusi_tp_arr) == 1 ){
					Tyovuoroot::model()->updateByPk($k, array('tyopaari' => ''));
					break;
					}
					Tyovuoroot::model()->updateByPk($k, array('tyopaari' => json_encode($uusi_tp_arr)));
				}
			}
			

			// <-- LOG
			if( isset($t->id) )
			{
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Delete';

				$old_values = json_encode($t->attributes);
				$new_values = null;
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->


			Tyovuoroot::model()->deletebypk($ex[0]);
		}

			echo json_encode('//'.implode(",",$_SESSION['muistin']));
			exit;
		}

		// copy
		if(isset($_POST['copy']) and isset($_SESSION['muistin']))
		{
		foreach($_SESSION['muistin'] as $cp)
		{
			$ex = explode("_",$cp);
			$t = Tyovuoroot::model()->findbypk($ex[0]);
			if(!isset($t->pvm)){
				echo json_encode('Virhe: Työvuoroja ei löyty');
				exit;
			}
			$vanha_pvm = $t->pvm;

			$model=new Tyovuoroot;
			$model->attributes=$t->attributes;
			$model->pvm=date("d.m.Y",strtotime($_POST['newPvm']));
			$model->tid=$_POST['newTid'];
			if( $t->toistuva_id != 0 )
			{
				$model->tyopaari='';
			}
			$model->toistuva_id=0;
			if($model->save()){
			    if( $t->toistuva_id == 0 and  is_array(json_decode($t->tyopaari, true)) ){
				$uusi_tp_arr = array();
				$uusi_tp_arr[$model->id] = $model->tid;
				foreach(json_decode($t->tyopaari, true) as  $id => $tp_id){
					$uusi_tp_arr[$id] = $tp_id;
				}
				if( $vanha_pvm != $model->pvm and isset($uusi_tp_arr[$model->id])){
					unset($uusi_tp_arr[$model->id]);
					Tyovuoroot::model()->updateByPk($model->id, array('tyopaari' => ''));
				}
				foreach( $uusi_tp_arr as $k => $v ){
					if( count($uusi_tp_arr) == 1 ){
					Tyovuoroot::model()->updateByPk($k, array('tyopaari' => ''));
					break;
					}
					Tyovuoroot::model()->updateByPk($k, array('tyopaari' => json_encode($uusi_tp_arr)));
				}
			    }
			}

			// <-- LOG
			if( isset($t->id) and isset($model->id) )
			{
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Copy';

				$old_values = json_encode($t->attributes);
				$new_values = json_encode($model->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->
			
		}

			$did = $this->renderPartial($did_versio,array(
					'pvm'=>$_POST['newPvm'],
					'tid'=>$_POST['newTid'],
					'from'=>'ajax',
					'asetukset'=>$asetukset
			), true);
			echo json_encode($did.'//');
			exit;

		}
		// cut
		if(isset($_POST['cut']) and isset($_SESSION['muistin']))
		{
		foreach($_SESSION['muistin'] as $cp)
		{
			$ex = explode("_",$cp);
			$t = Tyovuoroot::model()->findbypk($ex[0]);
			if(isset($t->id))
			{
			$vanha_pvm = $t->pvm;
			if( $t->toistuva_id != 0)
			{
				echo json_encode('Error//Et voi siirtää toistuva työvuoro.');
				exit;
			}
/*
			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($t->pvm) and $t->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($t->toistuva_id, $t->pvm);
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->
*/

			$model=$t;
			$model->pvm=date("d.m.Y",strtotime($_POST['newPvm']));
			$model->tid=$_POST['newTid'];
			$model->toistuva_id=0;
			if($model->save()){
			    if( is_array(json_decode($t->tyopaari, true)) ){
				$uusi_tp_arr = array();
				foreach(json_decode($t->tyopaari, true) as  $id => $tp_id){
					$tv = Tyovuoroot::model()->findByPk($id);
					if( isset($tv->id) and $tv->tid == $t->tid ){
						$uusi_tp_arr[$model->id] = $model->tid;
					} else {
						$uusi_tp_arr[$id] = $tp_id;
					}
				}
				if( $vanha_pvm != $model->pvm and isset($uusi_tp_arr[$model->id])){
					unset($uusi_tp_arr[$model->id]);
					Tyovuoroot::model()->updateByPk($model->id, array('tyopaari' => ''));
				}
				foreach( $uusi_tp_arr as $k => $v ){
					if( count($uusi_tp_arr) == 1 ){
					Tyovuoroot::model()->updateByPk($k, array('tyopaari' => ''));
					break;
					}
					Tyovuoroot::model()->updateByPk($k, array('tyopaari' => json_encode($uusi_tp_arr)));
				}
			    }
			}

			// <-- LOG
			if( isset($t->id) and isset($model->id) )
			{
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Move';

				$old_values = json_encode($t->attributes);
				$new_values = json_encode($model->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

			} else {
			echo json_encode('id puuttuu');
			break;
			}		
		}


			$did = $this->renderPartial($did_versio,array(
				'pvm'=>$_POST['newPvm'],
				'tid'=>$_POST['newTid'],
				'from'=>'ajax',
				'asetukset'=>$asetukset
			), true);
			echo json_encode($did.'//'.implode(",",$_SESSION['muistin']));
			exit;
		}
	}

	public function toistuvaDeletePvm($toistuva_id, $pvm)
	{
		$toistuva = ToistuvatTyovuorot::model()->findbypk($toistuva_id);
		if(isset($toistuva->id))
		{
			$poistettu_pvm = array();
			$poistettu_pvm = json_decode($toistuva->poistettu_pvm, true);

			$poistettu_pvm[] = $pvm;
			ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('poistettu_pvm'=>json_encode($poistettu_pvm)));

		}
	}

	public function actionPalautta_toistuva_pvm($id, $pvm)
	{
		$toistuva = ToistuvatTyovuorot::model()->findByPk($id);
		if(isset($toistuva->poistettu_pvm) and is_array(json_decode($toistuva->poistettu_pvm, true)))
		{
			$poistettu_pvm = json_decode($toistuva->poistettu_pvm, true);
			$uusi_ketju = array_values( array_diff($poistettu_pvm, array($pvm)) );
			ToistuvatTyovuorot::model()->updateByPk($toistuva->id, array('poistettu_pvm' => json_encode($uusi_ketju)));
			echo json_encode('ok');
		}
		exit;
	}

	public function actionAutoinsert()
	{
	//print_r($_POST);
	  if(isset($_POST['checktietoja']))
	  {
		$m = Kohteet::model()->findbypk($_POST['kohdeVal']);
		$k = explode("//",$m->kenella_on_avain);

		  $ohje = '';
		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."\n";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."\n\n";
		if(!empty($m->aikataulu))
		  $ohje .= "\nAikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet: ".$m->toimenpiteet;
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut: ".$m->muut;
		echo $ohje;

		exit;
	  }

	  if(isset($_POST['asenna']))
	  {

		$fi = array(
		    1=>'Maanantai',
		    2=>'Tiistai',
		    3=>'Keskkiviikko',
		    4=>'Torstai',
		    5=>'Perjantai',
		    6=>'Lauantai',
		    0=>'Sunnuntai',
		);

		$pvmstart 	= $_POST['pfrom'];
		$startdate 	= strtotime($_POST['pfrom']);
		$enddate	= strtotime($_POST['pto']);
		$w		= $_POST['P'];
		$v 		= $_POST['viikkoja'];

		  $i=0; 
		  $var = 0;
		  if($v == 2 and date('W',$startdate)%2 == 1)
		  $var = 1;
		  elseif($v == 4 and date('W',$startdate)%2 == 0)
		  $var = 2;
		  elseif($v == 4 and date('W',$startdate)%2 == 1)
		  $var = 1;
		  elseif($v == 3 and date('W',$startdate)%3 == 1)
		  $var = 1;
		  elseif($v == 3 and date('W',$startdate)%2 == 0)
		  $var = 2;

		  while($startdate<$enddate) 
		   {  

		      $ero = (date('W',$startdate) %$v);
		      //echo date('d.m',$startdate).", ".date('W',$startdate)." | ".$var." | ".$ero."\n";


		      if(in_array(date('w',$startdate),$w) and $ero == $var)
		      {

		    	$pvm = date('d.m.Y',$startdate);
		    	echo $pvm." ".$fi[date('w',$startdate)]."\n";

			if($_POST['valmis'] == "true")
			{

				$t = new Tyovuoroot;
				$t->tid = $_POST['tekija'];
				$t->kohde = $_POST['kohde'];
				$t->pvm = $pvm;
				$t->alku = $_POST['tfrom'];
				$t->loppu = $_POST['tto'];
				//$t->tyoajanlaatu = $_POST['tyoajanlaatu'];
				$t->tyoajanmerkinta = $_POST['tyoajanmerkinta'];
				$t->tietoja = $_POST['tietoja'];
				$t->save();
		
			}

		      }

			$i++; 
			$startdate+=86400; 

		   }	
	
		exit;
	  }
	
		$this->renderPartial('autoinsert');
	}


	public function actionAutoremove()
	{
	//print_r($_POST);

	  if(isset($_POST['asenna']))
	  {

		$fi = array(
		    1=>'Maanantai',
		    2=>'Tiistai',
		    3=>'Keskkiviikko',
		    4=>'Torstai',
		    5=>'Perjantai',
		    6=>'Lauantai',
		    0=>'Sunnuntai',
		);

		$startdate 	= strtotime($_POST['pfrom']);
		$enddate	= strtotime($_POST['pto']);
		$w		= $_POST['P'];
		$v 		= $_POST['viikkoja'];

		  $i=0; 
		  $var = 0;
		  if($v == 2 and date('W',$startdate)%2 == 1)
		  $var = 1;
		  elseif($v == 4 and date('W',$startdate)%2 == 0)
		  $var = 2;
		  elseif($v == 4 and date('W',$startdate)%2 == 1)
		  $var = 1;
		  elseif($v == 3 and date('W',$startdate)%3 == 1)
		  $var = 1;
		  elseif($v == 3 and date('W',$startdate)%2 == 0)
		  $var = 2;

		  while($startdate<$enddate) 
		   {  
		      $ero = (date('W',$startdate) %$v);
		      //echo date('d.m',$startdate).", ".date('W',$startdate)." | ".$var." | ".$ero."\n";


		      if(in_array(date('w',$startdate),$w) and $ero == $var)
		      {
		    	$pvm = date('d.m.Y',$startdate);
		    	echo $pvm.' '.$fi[date('w',$startdate)]."\n";

			if($_POST['valmis'] == "true")
			{

			  Tyovuoroot::model()->deleteAll(" tid='".$_POST['tekija']."' and pvm='".$pvm."' and kohde='".$_POST['kohde']."' and alku='".$_POST['tfrom']."' and loppu='".$_POST['tto']."' ");

			}
		      }

			$i++; 
			$startdate+=86400; 

		   }	
	
		exit;
	  }
	
		$this->renderPartial('autoremove');
	}





	public function actionShowohje($id, $tv_id=null)
	{
		$asetukset = Asetukset::model()->findbypk(1);
		$tietoja = $asetukset->tyovuoro_tietoja_mobiilisovellukseen;
		$m = Kohteet::model()->findbypk($id);
		if($m === null){
			//throw new CHttpException(404, 'Kohdetta '.$id.' ei löydy');
			echo json_encode('Kohdetta '.$id.' ei löydy');
			exit;
		}

		$k = explode("//",$m->kenella_on_avain);

		$tyo_erittelyt = json_decode($m->tyo_erittelyt, true);
		if($tv_id !== null){
			$tv = Tyovuoroot::model()->find(" id='".$tv_id."' AND kohde='".$id."' ");
			if(isset($tv->id)){
				$tyo_erittelyt = json_decode($tv->tyo_erittelyt, true);
			}
		}

		  $ohje = '';
		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."<br>";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."<br>";
		if(!empty($m->aikataulu))
		  $ohje .= "<br>Aikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "<br>Toimenpiteet: ".$m->toimenpiteet;
		if(!empty($m->tietoja)){
		  $ohje .= "<br>Tietoja: ".$m->tietoja;
		  $tietoja = $m->tietoja;
		}
		if(!empty($m->muut))
		  $ohje .= "<br>Muut: ".$m->muut;
		echo json_encode(array($ohje,$tietoja,$m->arvioitu_kesto,$m->osoite,$m->pnumero,$m->kaupunki,$tyo_erittelyt,$m->puh_nro,$m->email));
	
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionDid($pvm,$tid,$kohde,$from)
	{
		if(isset($tietoja)) $tietoja = 1; else $tietoja = 0;
		if(isset($_GET['asiakas'])) $asiakas = $_GET['asiakas']; else $asiakas = '';
		$asetukset = Asetukset::model()->findByPk(1);
		$this->renderPartial('did',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'kohde'=>$kohde,
			'asiakas'=>$asiakas,
			'from'=>$from,
			'tietoja'=>$tietoja,
			'asetukset'=>$asetukset
		));
	}

	public function actionDid3($pvm,$tid,$kohde,$from)
	{
		if(isset($tietoja)) $tietoja = 1; else $tietoja = 0;
		if(isset($_GET['asiakas'])) $asiakas = $_GET['asiakas']; else $asiakas = '';
		$asetukset = Asetukset::model()->findByPk(1);
		$this->renderPartial('did3',array(
			'pvm'=>$pvm,
			'tid'=>$tid,
			'kohde'=>$kohde,
			'asiakas'=>$asiakas,
			'from'=>$from,
			'tietoja'=>$tietoja,
			'asetukset'=>$asetukset
		));
	}

	public function actionDidnew()
	{
	     if(isset($_POST['kohteet_siivous']) and is_array(json_decode($_POST['kohteet_siivous'], true)))
	     $ks = json_decode($_POST['kohteet_siivous'], true);
	     else
	     $ks = array();


	     $asetukset = Asetukset::model()->findByPk(1);
 	     $this->renderPartial('did',array(
					'pvm'=>$_POST['pvm'],
					'tid'=>$_POST['tid'],
					'from'=>$_POST['from'], 
					'kohteet_siivous'=>$ks, 
					'asetukset'=>$asetukset,
					'asiakas'=>$_POST['asiakas'],
					'kohde'=>$_POST['kohde'],
	     ));
	     exit;
	}

	public function actionTv3()
	{
		if( !isset(Yii::app()->session['ov_poisto']) ){
			$this->poistaminenOnlineVarauksetJokaMeniOhi();
			Yii::app()->session['ov_poisto'] = 'suorittu';
		}

		$kohteet_siivous = array();

		// <-- Reset
		if(isset($_GET['reset']))
		{
			unset(Yii::app()->session['from']);
			unset(Yii::app()->session['to']);
			unset(Yii::app()->session['asiakas']);
			unset(Yii::app()->session['kohde']);
			unset(Yii::app()->session['tyontekijat']);
			unset(Yii::app()->session['tyo_toimialue']);
			unset(Yii::app()->session['kohteiden_tyonimike']);
			unset(Yii::app()->session['tyoryhma']);

			$this->redirect(array('tv3'));
		}
		//     Reset -->

		// <-- Post haku
		if(isset($_POST['haku']))
		{

			if(isset($_POST['kohteiden_tyonimike']) and !empty($_POST['kohteiden_tyonimike']))
				Yii::app()->session['kohteiden_tyonimike'] = $_POST['kohteiden_tyonimike'];
			if(isset($_POST['kohteiden_tyonimike']) and empty($_POST['kohteiden_tyonimike']))
				unset(Yii::app()->session['kohteiden_tyonimike']);

			if(isset($_POST['tyo_toimialue']) and !empty($_POST['tyo_toimialue']))
				Yii::app()->session['tyo_toimialue'] = $_POST['tyo_toimialue'];
			if(!isset($_POST['tyo_toimialue']))
				unset(Yii::app()->session['tyo_toimialue']);

			if(isset($_POST['tyoryhma']) and !empty($_POST['tyoryhma']))
				Yii::app()->session['tyoryhma'] = $_POST['tyoryhma'];
			if(!isset($_POST['tyoryhma']))
				unset(Yii::app()->session['tyoryhma']);

			// <-- Asiakas
			if(isset($_POST['asiakas']) and !empty($_POST['asiakas']))

				Yii::app()->session['asiakas'] = $_POST['asiakas'];
			if(isset($_POST['asiakas']) and empty($_POST['asiakas']))
				unset(Yii::app()->session['asiakas']);
			// Asiakas -->
	
			// <-- Kohde
			if(isset($_POST['kohde']) and !empty($_POST['kohde']))
				Yii::app()->session['kohde'] = $_POST['kohde'];
			if(isset($_POST['kohde']) and empty($_POST['kohde']))
				unset(Yii::app()->session['kohde']);
			// Kohde -->
	
			// <-- tyontekijat
			if(isset($_POST['tyontekijat']) and !empty($_POST['tyontekijat']))
				Yii::app()->session['tyontekijat'] = $_POST['tyontekijat'];
			if(!isset($_POST['tyontekijat']))
				unset(Yii::app()->session['tyontekijat']);
			//  tyontekijat -->

			if(isset($_POST['from']) and !empty($_POST['from']))
				Yii::app()->session['from'] = date("Y-m-d",strtotime($_POST['from']));
	
			if(isset($_POST['to']) and !empty($_POST['to']))
				Yii::app()->session['to'] = date("Y-m-d",strtotime($_POST['to']));


			$this->redirect(array('tv3'));
		}		
		//  Post haku -->


		if(!isset(Yii::app()->session['from']))
			Yii::app()->session['from'] = date("Y-m-d");
		if(!isset(Yii::app()->session['to']))
			Yii::app()->session['to'] = date("Y-m-d",strtotime("+2 week", time()));



		$asetukset = Asetukset::model()->findByPk(1);
       		$criteria = new CDbCriteria();

		// <-- Oletus arvot
		if(!isset(Yii::app()->session['tyontekijat']))
		{

			// <-- Return order etu ja sukunimella
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->etuSukunimiCriteria($criteria);
			//     Return order etu ja sukunimella -->

	        	$criteria->select = "id,tekijan_nimi";
	        	$criteria->condition = ' aktiivinen=1 ';

			// <-- Tyoryhmat
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			} 
			//    Tyoryhmat -->

			$tt = Tyontekijat::model()->findAll($criteria);
			$tekijatOletuksena = array();
			foreach($tt as $t)
			$tekijatOletuksena[] = $t->id;
	
			Yii::app()->session['tyontekijat'] = $tekijatOletuksena;
		}
		// Oletus arvot -->


		if(Yii::app()->session['tyontekijat'])
		{

			if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0)
				$criteria->order = " tekijan_nimi ";
			else
				$criteria->order = " sukunimi ";


        		$criteria->select = "id,tekijan_nimi";
        		$criteria->condition = " aktiivinen = '1' ";

		    	if(count(Yii::app()->session['tyontekijat'] > 1))
		      	$ids = implode(",", Yii::app()->session['tyontekijat']);
		    	else
		      	$ids = Yii::app()->session['tyontekijat'][0];

	        	$criteria->addCondition ('id IN ('.$ids.') ');
		}



		// <-- kohteiden_tyonimike
		if(isset(Yii::app()->session['kohteiden_tyonimike']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%'
		       )
		   )
		   ");

			$criteriaK = new CDbCriteria();
	       		$criteriaK->select = "id";
	       		$criteriaK->condition = " 
				siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%' 
				AND id IN(
					SELECT kohde FROM sivex_tvuoro 
					WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     			BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
				)
			";
			$k = Kohteet::model()->findAll($criteriaK);
			foreach($k as $kohde)
			 $kohteet_siivous[] = $kohde->id;

		}
		//   kohteiden_tyonimike -->

		// <-- tyo_toimialue
		if(isset(Yii::app()->session['tyo_toimialue']))
		{

		   $tyo_toimialue_like = "tyo_toimialue LIKE '%".implode("%' OR tyo_toimialue LIKE '%", Yii::app()->session['tyo_toimialue'])."%'";
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND tid IN 
		       (
			    SELECT id FROM sivex_ttekijat WHERE $tyo_toimialue_like
		       )
		   )
		   ");
		}
		//   tyo_toimialue -->

		// <-- tyoryhma
		if(isset(Yii::app()->session['tyoryhma']))
		{
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(Yii::app()->session['tyoryhma']);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			}
		}
		//   tyoryhma -->

		// <-- Asiakas
		if(isset(Yii::app()->session['asiakas']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE yrityksen_nimi 
					LIKE '%".Yii::app()->session['asiakas']."%' 
					OR yhteyshenkilo LIKE '%".Yii::app()->session['asiakas']."%' 
					OR puhelin LIKE '%".Yii::app()->session['asiakas']."%'
			    )
		       )
		   )
		   ");
		}
		// Asiakas -->

		// <-- Kohde
		if(isset(Yii::app()->session['kohde']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE osoite 
					LIKE '%".Yii::app()->session['kohde']."%' 
					OR puh_nro LIKE '%".Yii::app()->session['kohde']."%'
		       )
		   )
		   ");
		}
		//  Kohde -->

		$tyontekijat_model = Tyontekijat::model()->findAll($criteria);


		(isset(Yii::app()->session['asiakas'])) ? 	$asiakas = Yii::app()->session['asiakas'] : $asiakas ='';
		(isset(Yii::app()->session['kohde'])) ? 	$kohde = Yii::app()->session['kohde'] : $kohde ='';

		$this->render('tv3', array(
			'tyontekijat_model'	=>$tyontekijat_model,
			'from'			=>Yii::app()->session['from'],
			'to'			=>Yii::app()->session['to'],
			'tyontekijat'		=>Yii::app()->session['tyontekijat'],
			'kohteet_siivous'	=>$kohteet_siivous,
			'asiakas'		=>$asiakas,
			'kohde'			=>$kohde,
		));
	}

	public function actionBeta($kohteet_siivous=array(), $kohde='', $asiakas='') {
		$site = Yii::app()->createController('Site');
		$arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
		$asetukset = Asetukset::model()->findByPk(1);

		// <-- Reset
		if(isset($_GET['reset']))
		{
			unset(Yii::app()->session['year']);
			unset(Yii::app()->session['week']);
			unset(Yii::app()->session['vkolopput']);
			unset(Yii::app()->session['asiakas']);
			unset(Yii::app()->session['kohde']);
			unset(Yii::app()->session['tyontekijat']);
			unset(Yii::app()->session['tyo_toimialue']);
			unset(Yii::app()->session['kohteiden_tyonimike']);
			unset(Yii::app()->session['tyoryhma']);

			$this->redirect(array('index'));
		}
		//     Reset -->

		// <-- Post haku
		if(isset($_POST['haku']))
		{
			if(isset($_POST['kohteiden_tyonimike']) and !empty($_POST['kohteiden_tyonimike']))
				Yii::app()->session['kohteiden_tyonimike'] = $_POST['kohteiden_tyonimike'];
			if(isset($_POST['kohteiden_tyonimike']) and empty($_POST['kohteiden_tyonimike']))
				unset(Yii::app()->session['kohteiden_tyonimike']);

			if(isset($_POST['tyo_toimialue']) and !empty($_POST['tyo_toimialue']))
				Yii::app()->session['tyo_toimialue'] = $_POST['tyo_toimialue'];
			if(!isset($_POST['tyo_toimialue']))
				unset(Yii::app()->session['tyo_toimialue']);

			if(isset($_POST['tyoryhma']) and !empty($_POST['tyoryhma']))
				Yii::app()->session['tyoryhma'] = $_POST['tyoryhma'];
			if(!isset($_POST['tyoryhma']))
				unset(Yii::app()->session['tyoryhma']);

			// <-- Asiakas
			if(isset($_POST['asiakas']) and !empty($_POST['asiakas']))

				Yii::app()->session['asiakas'] = $_POST['asiakas'];
			if(isset($_POST['asiakas']) and empty($_POST['asiakas']))
				unset(Yii::app()->session['asiakas']);
			// Asiakas -->
	
			// <-- Kohde
			if(isset($_POST['kohde']) and !empty($_POST['kohde']))
				Yii::app()->session['kohde'] = $_POST['kohde'];
			if(isset($_POST['kohde']) and empty($_POST['kohde']))
				unset(Yii::app()->session['kohde']);
			// Kohde -->
	
			// <-- tyontekijat
			if(isset($_POST['tyontekijat']) and !empty($_POST['tyontekijat']))
				Yii::app()->session['tyontekijat'] = $_POST['tyontekijat'];
			if(!isset($_POST['tyontekijat']))
				unset(Yii::app()->session['tyontekijat']);
			//  tyontekijat -->

			if(isset($_POST['from']) and !empty($_POST['from']))
				Yii::app()->session['from'] = date("Y-m-d",strtotime($_POST['from']));
	
			if(isset($_POST['to']) and !empty($_POST['to']))
				Yii::app()->session['to'] = date("Y-m-d",strtotime($_POST['to']));

			$this->redirect(array('beta'));
		}		
		//  Post haku -->

		if(!isset(Yii::app()->session['from']) or !isset(Yii::app()->session['to'])){
			Yii::app()->session['from'] = date("Y-m-d");
			Yii::app()->session['to'] = date("Y-m-d", strtotime(Yii::app()->session['from'].' Friday next week'));
		}

		// <-- Order tyontekijat
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

       		$criteria = new CDbCriteria();
		$criteria->select = "id, $tt_order_1, $tt_order_2";
		$criteria->order = "$tt_order_1 ASC";
		$criteria->condition = "
			aktiivinen=1
		";
		if(isset(Yii::app()->session['tyontekijat']) and count(Yii::app()->session['tyontekijat'] > 0)){
		      	$ids = implode(",", Yii::app()->session['tyontekijat']);
		        $criteria->addCondition ('id IN ('.$ids.') ');
		}

		// <-- Tyontekijat muistiin
		$tt = array();
		$tyontekijat = Tyontekijat::model()->findAll($criteria);
		foreach($tyontekijat as $item){
			$tt[$item->id] = array('etusukunimi' => $item->$tt_order_1.' '.$item->$tt_order_2);
		}
		//     Tyontekijat muistiin -->

       		$criteria = new CDbCriteria();
		$criteria->with = array('kohteet');
		//$criteria->limit = "10";
		$criteria->select = "id, tid, toistuva_id, osoite, pvm, alku, loppu, tyoajanmerkinta, tyoajanlaatu, status";
		$criteria->order = "alku ASC"; //tt.$tt_order_1 ASC, 
		$criteria->condition = "
			toistuva_id=0
			AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		";

		// <-- Asiakas
		if(isset($asiakas) and !empty($asiakas))
		{
		$criteria->addCondition ("
		kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE 
				yrityksen_nimi LIKE '%".$asiakas."%' 
				OR yhteyshenkilo LIKE '%".$asiakas."%' 
				OR puhelin LIKE '%".$asiakas."%'
			    )
		       )
		   ");
		}
		// Asiakas -->

		// <-- Kohde
		if(isset($kohde) and !empty($kohde))
		{
	           $criteria->addCondition ("
		   kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE 
				osoite LIKE '%".$kohde."%' 
				OR puh_nro LIKE '%".$kohde."%'
		       )
		   ");
		}
		// Kohde -->

		if(isset($kohteet_siivous) and count($kohteet_siivous) > 0)
		{
		$impl = implode(',',$kohteet_siivous);
		$criteria->addCondition  (" kohde IN ($impl) ");
		}

		if(isset(Yii::app()->session['tyontekijat']) and count(Yii::app()->session['tyontekijat'] > 0)){
		      	$ids = implode(",", Yii::app()->session['tyontekijat']);
		        $criteria->addCondition ('tid IN ('.$ids.') OR tid=0');
		}

		// <-- Status
		$status = [];
		$status[10] = '<i class="tvikooni fa fa-cutlery text-success"></i>';
		$status[2] = '<i class="tvikooni fa fa-bus text-warning"></i>';
		$status[3] = '<i class="tvikooni fa fa-hourglass text-info"></i>';
		$status[11] = '<i class="tvikooni fa fa-clock-o text-info"></i>';
		// Status -->

		// <-- Tv array
		$tv = Tyovuoroot::model()->findAll($criteria);
		$tv_arr = [];
		$toistuva_ids = [];
		foreach($tv as $arvo){
			$return = $this->laatikkorakenne($arvo, $status, false);
			if( isset($return['laatikko']['osoite']) ){
				$tv_arr[$arvo->tid][$arvo->pvm][] = $return['laatikko']['osoite'];
				//if( $arvo->toistuva_id > 0 )
				//$toistuva_ids[$arvo->tid][$arvo->pvm][$arvo->toistuva_id] = $arvo->toistuva_id;
			}
		}

		// <-- toistuvat
		$toistuva_from = Yii::app()->session['from'];
		$toistuva_to = Yii::app()->session['to'];
       		$criteria = new CDbCriteria(); 
		$criteria->condition = "
			DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) BETWEEN '".$toistuva_from."' AND '".$toistuva_to."'
			OR
			DATE(STR_TO_DATE(pto, '%d.%m.%Y')) BETWEEN '".$toistuva_from."' AND '".$toistuva_to."'
			OR
			(DATE(STR_TO_DATE(pfrom, '%d.%m.%Y')) < '".$toistuva_from."' AND DATE(STR_TO_DATE(pto, '%d.%m.%Y')) > '".$toistuva_to."')

		";
		$t = ToistuvatTyovuorot::model()->findAll($criteria);
		$toistuvat_arr = [];
		foreach($t as $arvo){
			// <-- Tids
			$tids = [];
			if( !empty($arvo->tyopaari) ){
				foreach(json_decode($arvo->tyopaari, true) as $tid){
					$tids[$tid] = $tid;
				}
			} else {
				$tids[$arvo->tid] = $arvo->tid;
			}

			// <-- Poistettu_pvms
			$poistettu_pvms = [];
			if( !empty($arvo->poistettu_pvm) ){
				foreach(json_decode($arvo->poistettu_pvm, true) as $ppvm){
					$poistettu_pvms[$arvo->id][$ppvm] = $ppvm;
				}
			}

			$weeks = new DatePeriod(
			    new DateTime($arvo->pfrom), 
			    new DateInterval('P'.$arvo->viikkoja.'W'), 
			    new DateTime(date("Y-m-d", strtotime($toistuva_to.' +1 day')))
			);
			$pvms = [];
			foreach ($weeks as $wk) {
				if( strtotime($wk->format('Y-m-d')) >= strtotime($toistuva_from) ){
					$pvm_intrvl = new DatePeriod(
					    new DateTime(date("Y-m-d", strtotime($wk->format('Y').'W'.$wk->format('W').'1'))), 
					    new DateInterval('P1D'), 
					    new DateTime(date("Y-m-d", strtotime($wk->format('Y').'W'.$wk->format('W').'7')))
					);
					foreach ($pvm_intrvl as $pvm) {
						if( isset($poistettu_pvms[$arvo->id][$pvm->format('d.m.Y')]) ){ continue; }
						if(in_array($pvm->format('w'), json_decode($arvo->viikko_paivat, true))){
							$return = $this->laatikkorakenne($arvo, $status, true);
							if( isset($return['laatikko']['osoite']) ){
								foreach($tids as $tid){
									//if( !isset($toistuva_ids[$tid][$pvm->format('d.m.Y')][$arvo->id]) )
										$toistuvat_arr[$tid][$pvm->format('d.m.Y')][] = $return['laatikko']['osoite'];
								}
							}
						}
					}
				}
			}
		}
		//     toistuvat -->

		//$merge = array_merge($tv_arr, $toistuvat_arr);
		/*
		echo '<pre>';
		print_r( $toistuvat_arr );
		echo '</pre>';
		exit;
		*/

		//     Tv array -->

		$this->render('tv4', array(
			'tt_order_1' 	=> $tt_order_1,
			'tt_order_2' 	=> $tt_order_2,
			'tt'		=> $tt,
			'tv_arr'	=> $tv_arr,
			'toistuvat_arr' => $toistuvat_arr,
			'from'		=> Yii::app()->session['from'],
			'to'		=> Yii::app()->session['to'],
			'kohteet_siivous' => $kohteet_siivous,
			'kohde' 	=> $kohde,
			'asiakas' 	=> $asiakas,
			'arrDate'	=> $arrDate,
			'site'		=> $site,
		));

	}

	protected function laatikkorakenne($arvo, $status, $toistuva){
			$return = [];
			$toistuva_id = ($toistuva)? 'toistuva_id="'.$arvo['id'].'"' : '';
			$toistuva_icon = ($toistuva)? '<i class="text-success fa fa-repeat"></i> ' : '';
			$osoite = (!empty($arvo['osoite']))?$arvo['osoite']:(isset($arvo['kohteet']['osoite']))?$arvo['kohteet']['osoite']:'';
			$color = '#888';
			$bgcol = 'color:#333';
			if(!empty($arvo['tyoajanmerkinta'])){
				$expl = explode("/",$arvo['tyoajanmerkinta']);
				if(isset($expl[1]) and !empty($expl[1])){
					$color = $expl[1];
					$bgcol = 'color:'.$color;
				}
			}
			if(!empty($arvo['tyoajanlaatu'])){
				$expl1 = explode("/",$arvo['tyoajanlaatu']);
				if(isset($expl1[1]) and !empty($expl1[1])){ $color = $expl1[1]; }
				$return['osoite'] = (isset($expl1[0])) ? '<b class="tv_edit" '.$toistuva_id.' id="'.$arvo['id'].'" style="color:'.$color.'">'.$expl1[0].'</b>' : '';
			} else {
				$return['osoite'] = '<span class="tv_edit" '.$toistuva_id.' id="'.$arvo['id'].'" style="'.$bgcol.'">'.((isset($status[$arvo['status']]))?$status[$arvo['status']]:'').$toistuva_icon.''.$arvo['alku'].'-'.$arvo['loppu'].'<br> '.$osoite.'</span>';

			}

			$arr = [ 'laatikko' => $return ];
			return $arr;
	}

	public function actionDid4($pvm, $tid) {

		$content = $this->renderPartial('did4', array(
			'pvm' 	=> $pvm,
			'tid' 	=> $tid,
		), true);
	     	echo $content;
		exit;
	}

	public function actionHovertietoja($tv_id) {

		$arrDate = array(1=>"Ma",2=>"Ti",3=>"Ke",4=>"To",5=>"Pe",6=>"La",7=>"Su");
		$asetukset = Asetukset::model()->findByPk(1);
		$asetukset_new = array();
		$asetukset_new['tyoryhmat_kohde'] = $asetukset->tyoryhmat_kohde;
		$asetukset_new['paikkakunta_tyovuorossa'] = $asetukset->paikkakunta_tyovuorossa;
		$asetukset_new['asiakas_tyovuorossa'] = $asetukset->asiakas_tyovuorossa;
		$asetukset_new['lasketaanko_lounastauko'] = $asetukset->lasketaanko_lounastauko;
		$tvVal = Tyovuoroot::model()->findByPk($tv_id);
		$hovertietoja = '';

		$columnDate = date("N/d.m",strtotime($tvVal->pvm));
		$explColDate = explode("/",$columnDate);

		$hovertietoja .= '<h4>'.$arrDate[$explColDate[0]].', '.$explColDate[1].' '.$this->etuSukunimi($tvVal->tid).'</h4>';

		// <-- Osoite
		$osoite = '';
		if(!empty($tvVal->osoite)){
			$osoite = $tvVal->osoite;
		} elseif(empty($tvVal->osoite) and isset($tvVal->kohteet->osoite)){
			$osoite = $tvVal->kohteet->osoite;
		}
		// Osoite -->

		// <-- Status
		$status = '';
		if($tvVal->status == 10){
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-success' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-cutlery '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 2) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-warning' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-bus '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 3) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-hourglass '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 11) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-clock-o '.$teksti_vari.'"></i>';
		}
		// Status -->

		// <-- Toistuva
		$toistuva = '';
		if($tvVal->toistuva_id != 0){
			$toistuva = ' <i class="tvikooni fa fa-repeat text-success" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Toistuva työvuoro').'"></i>';
		}
		// Toistuva -->

		// <-- Avaimet
		$avaimet = '';
		if(isset($tvVal->avaimet) and count($tvVal->avaimet) > 0){
			$avaimet =  ' <i class="tvikooni fa fa-key"></i>';
		}
		// Avaimet -->

		// <-- Asiakas nakyvissa
		$asiakasNakyvissa = '';
		if($asetukset_new['asiakas_tyovuorossa'] == 1){
		$name = '';
		if(isset($tvVal->kohteet->asiakkaat) and $tvVal->kohteet->asiakkaat->tyyppi == 'yritys')
		$name = $tvVal->kohteet->asiakkaat->yrityksen_nimi;
		if(isset($tvVal->kohteet->asiakkaat) and $tvVal->kohteet->asiakkaat->tyyppi == 'henkilo')
		$name = $tvVal->kohteet->asiakkaat->yhteyshenkilo;
		if(!empty($name)){ $asiakasNakyvissa = '<b>Asiakas:</b> '.$name.'<br>'; }
		}
		//  Asiakas nakyvissa -->

		// <-- Paikkakunta nakyvissa
		$paikkakuntaNakyvissa = '';
		if($asetukset_new['paikkakunta_tyovuorossa'] == 1){
		$paikkakunta = '';
		if(isset($tvVal->kohteet->kaupunki) and !empty($tvVal->kohteet->kaupunki))
		$paikkakunta = $tvVal->kohteet->kaupunki;
		if(!empty($paikkakunta)){ $paikkakuntaNakyvissa = '<b>Paikkakunta:</b> '.$paikkakunta.'<br>'; }
		}
		//  Paikkakunta nakyvissa -->

		// <-- Hovertietoja generoi
		// <-- peruutettu
		if($tvVal['peruutettu'] == 1 and isset($tv_controller)){
			$hovertietoja .= '<h3 class="text-danger">'. $this->peruutettuArray()[1] .'</h3>';
			$bgcol = 'color:red';
		}
		if($tvVal['peruutettu'] == 2 and isset($tv_controller)){
			$hovertietoja .= '<h3 class="text-danger">'. $this->peruutettuArray()[2] .'</h3>';
			$bgcol = 'color:red';
		}
		//    peruutettu -->
		$hovertietoja .= $asiakasNakyvissa;
		//if(!empty($asiakasNakyvissa)){ $title .= ', '; }
		$hovertietoja .= $paikkakuntaNakyvissa;
		$hovertietoja .= '<br><p><span class="didstatus">'.$status.$toistuva.$avaimet.'</span>&nbsp; &nbsp;<b>'.$tvVal->alku.'-'.$tvVal->loppu.'</b>: '.$osoite.'</p>';
		if( $tvVal->tyopaari != '' and $tvVal->tyopaari != "[\"$tvVal->tid\"]" ){
		$hovertietoja .= '<div class="hover_well"><h5>Työparit</h5>';
		   foreach(json_decode($tvVal->tyopaari, true) as $tyopaari){
			if( $tvVal->tid != $tyopaari )
			$hovertietoja .=  $this->etuSukunimi($tyopaari).'<br>';
		   }
		$hovertietoja .= '</div>';
		}
		if( !empty($tvVal->tietoja) ){ $hovertietoja .= '<div class="hover_well"><h5>Tietoja:</h5> '.str_replace("\n", "<br>", $tvVal->tietoja).'</div>'; }
		//    Hovertietoja generoi -->

		echo json_encode($hovertietoja);
		exit;
	}

	protected function time_to_float($time) {
	    $timeArr = explode(":", $time);
	    return $timeArr[0] + ($timeArr[1] / 60);
	}

	public function actionDidnew3()
	{
	     if(isset($_POST['kohteet_siivous']) and is_array(json_decode($_POST['kohteet_siivous'], true)))
	     $ks = json_decode($_POST['kohteet_siivous'], true);
	     else
	     $ks = array();


	     $asetukset = Asetukset::model()->findByPk(1);
 	     $this->renderPartial('did3',array(
					'pvm'=>$_POST['pvm'],
					'tid'=>$_POST['tid'],
					'from'=>$_POST['from'], 
					'kohteet_siivous'=>$ks, 
					'asetukset'=>$asetukset,
					'asiakas'=>$_POST['asiakas'],
					'kohde'=>$_POST['kohde'],
	     ));
	     exit;
	}

	public function actionViikko($tid,$viikko,$year)
	{

		$this->renderPartial('viikko',array(
			'tid'=>$tid,
			'viikko'=>$viikko,
			'year'=>$year,
		));
	}

	public function actionFromto($tid)
	{

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		$this->renderPartial('fromto',array(
			'tid'=>$tid,
		));
	}

	public function actionCreate()
	{

		$return = array();

		if(isset($_POST['ToistuvatTyovuorot']) and isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on')
		{
			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];

			$toistuva=new ToistuvatTyovuorot;
			$toistuva->attributes=$_POST['ToistuvatTyovuorot'];
			$toistuva->attributes=$_POST['Tyovuoroot'];
			$toistuva->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if( is_array($toistuva->lisa_tuotteet) and count($toistuva->lisa_tuotteet) > 0 ){
				$toistuva->lisa_tuotteet = json_encode($toistuva->lisa_tuotteet);
			} else {
				$toistuva->lisa_tuotteet = '';
			}
			if( is_array($toistuva->tyo_erittelyt) and count($toistuva->tyo_erittelyt) > 0 ){
				$toistuva->tyo_erittelyt = json_encode($toistuva->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$toistuva->tyo_erittelyt = '';
			}
			if( is_array($toistuva->muistiinpano) and count($toistuva->muistiinpano) > 0 ){
				$toistuva->muistiinpano = json_encode($toistuva->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$toistuva->muistiinpano = '';
			}

			if(isset($_POST['P'])){	$toistuva->viikko_paivat=json_encode($_POST['P']); }

			if($saankoSuoritta == 1)
			{
				if(!$toistuva->save())
				{
					$return[] = array('ERROR'=>json_encode(var_dump($toistuva->getErrors())));
				}
			}
	
			if(!isset($_POST['tyopaari']))
			{
			  	$return[] = $this->toistuvaInsert(
					null,
					$toistuva,
					$toistuva->tid,  
					json_decode($toistuva->viikko_paivat, true),
					'', // tyopaari
					$saankoSuoritta,
					null
				);
			}

			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    // <-- Lisätään pää työntekijä
			    $_POST['tyopaari'][] = $toistuva->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$return[] = $this->toistuvaInsert(
					null,
					$toistuva,
					$tid,  
					json_decode($toistuva->viikko_paivat, true),
					json_encode($_POST['tyopaari']),
					$saankoSuoritta,
					null
					);
			    }

				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tyopaari' => json_encode($_POST['tyopaari'])));
			}
			// jos on tyopaari -->


			// <-- LOG
			if( $saankoSuoritta == 1 )
			{
			$model_log 	= 'ToistuvatTyovuorot';
			$name_log 	= 'Toistuvat työvuorot';
			$status_log 	= 'Create';
			if(isset($toistuva->id))
			{
				$old_values = null;
				$n_m = ToistuvatTyovuorot::model()->findbypk($toistuva->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			}
			//     LOG -->


			echo json_encode($return);
			exit;
		}






		$model=new Tyovuoroot;

		if(isset($_POST['Tyovuoroot']))
		{

			$model->attributes=$_POST['Tyovuoroot'];
			if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
				$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
			} else {
				$model->lisa_tuotteet = '';
			}
			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$model->tyo_erittelyt = '';
			}
			if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 ){
				$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$model->muistiinpano = '';
			}
			// <-- Apuaika
			if(isset($_POST['Tyovuoroot']['apuaika']) and $_POST['Tyovuoroot']['apuaika'] == 1)
				$model->apuaika = 1;
			else if(isset($_POST['Tyovuoroot']['apuaika']) and $_POST['Tyovuoroot']['apuaika'] != 1)
				$model->apuaika = 0;
			//     Apuaika -->

			$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if($model->save())
			{

			// <-- PushNotify
			if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
			$this->pushNotifySending($model->id);
			// PushNotify -->



			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    $luotu = array();
			    $luotu[$model->id] = $model->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$m=new Tyovuoroot;
				$m->attributes=$_POST['Tyovuoroot'];
				$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				$m->tid=$tid;
				if( is_array($model->tyopaari) and count($m->tyopaari) > 0 ){
					$m->tyopaari = json_encode($m->tyopaari);
				} else {
					$m->tyopaari = '';
				}
				if( isset($_POST['Tyovuoroot']['lisa_tuotteet']) and is_array($_POST['Tyovuoroot']['lisa_tuotteet']) ){
					$m->lisa_tuotteet = json_encode($_POST['Tyovuoroot']['lisa_tuotteet']);
				} else {
					$m->lisa_tuotteet = '';
				}
				if( is_array($m->tyo_erittelyt) and count($m->tyo_erittelyt) > 0 ){
					$m->tyo_erittelyt = json_encode($m->tyo_erittelyt, JSON_FORCE_OBJECT);
				} else {
					$m->tyo_erittelyt = '';
				}
				if($m->save())
				{
					$luotu[$m->id] = $m->tid;
					$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));

					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($m->id);
					// PushNotify -->

				}

			    }
			    foreach($luotu as $k=>$v)
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));


			}
			// jos on tyopaari -->


			// <-- LOG
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Create';
			if(isset($_POST[$model_log]))
			{
				$old_values = null;
				$n_m = Tyovuoroot::model()->findbypk($model->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->


			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			echo json_encode($return);

			}
		exit;
		}


  	$tnimi = '';
	if(isset($_POST['tid']) and $_POST['tid'] != 0){
  	  $tekija = Tyontekijat::model()->findbypk($_POST['tid']);
	  $tnimi = $this->etuSukunimi($tekija->id);


		// Tyosuhde oikeus
		$oikeus = '<div class="alert alert-danger">'.Yii::t('main', 'Työsuhdetta ei ole määritelty tai työsuhde ei ole voimassa.').'</div>';
		$pvm = date("Ymd", strtotime($_POST['pvm']));
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			tid='".$tekija->id."' 
		";
		$ts = Tyosuhdet::model()->find($criteria);
		if(isset($ts->id) and !empty($ts->alku))
		{
			$alku = date("Ymd", strtotime($ts->alku));

			if($pvm >= $alku and empty($ts->loppu))
			$oikeus = '';
			elseif($pvm >= $alku and !empty($ts->loppu) and $pvm <= date("Ymd", strtotime($ts->loppu)))
			$oikeus = '';
		}
		// Tyosuhde oikeus

	}
	?>


        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"><i class="fa fa-clock-o"></i> 
		<?php echo Yii::t('main', 'Työvuoron suunnittelu').': '.$tnimi; ?> <span class="kohteen_lisatiedot"></span>
	      </span>
            </div>
            <!-- end .panel-heading section -->

              <div class="panel-body p25">
		<?php
		if(isset($oikeus)) echo $oikeus;
		$this->renderPartial('_form',array(
			'model'=>$model,
		));
		?>
              </div>
              <!-- end .form-body section -->


          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->



	<?php
	}


	public function actionPaivita_laatikot()
	{
		if(isset($_POST['tids']))
		{
			$tids_arr = $_POST['tids'];
			$tids_arr = array_unique(array_values($tids_arr));

			$kohde = '';
			if(isset(Yii::app()->session['kohde']))
			{
				$kohde = Yii::app()->session['kohde'];
			}
			$asiakas = '';
			if(isset(Yii::app()->session['asiakas']))
			{
				$asiakas = Yii::app()->session['asiakas'];
			}

			$return = array();
			if(isset(Yii::app()->session['from']) and isset(Yii::app()->session['to']))
			{

			   foreach($tids_arr as $tid)
			   {
				$start_date = Yii::app()->session['from'];
				$end_date = Yii::app()->session['to'];

				while (strtotime($start_date) <= strtotime($end_date)) {
					$return[] = array(
						'tid'=>$tid, 
						'pvm'=>date("d.m.Y", strtotime($start_date)), 
						'ymd'=>date("Ymd",strtotime($start_date)),
						'kohde' => $kohde,
						'asiakas' => $asiakas
					);

					$start_date = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
				}
			   }
			}

			echo json_encode($return);
			exit;
		}
	}


	public function actionUpdate($id)
	{

		$model=$this->loadModel($id);
		if(!isset($model->id)){ die('Työvuoroja '.$id.' ei löydy.'); }
		$edellinenToistuva = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);
		$return = array();

		// <-- Toistuva tyovuorot ja tyoparit
		if(
			isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) 
			and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on'
		)
		{
			$fi = $this->vkoPaivat();
			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];

			if(isset($edellinenToistuva->id))
				$toistuva = ToistuvatTyovuorot::model()->findByPk($edellinenToistuva->id);
			else
				$toistuva = new ToistuvatTyovuorot;

			$toistuva->attributes = $_POST['ToistuvatTyovuorot'];
			$toistuva->attributes = $_POST['Tyovuoroot'];
			$toistuva->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if( is_array($toistuva->lisa_tuotteet) and count($toistuva->lisa_tuotteet) > 0 ){
				$toistuva->lisa_tuotteet = json_encode($toistuva->lisa_tuotteet);
			} else {
				$toistuva->lisa_tuotteet = '';
			}
			if( is_array($toistuva->tyo_erittelyt) and count($toistuva->tyo_erittelyt) > 0 ){
				$toistuva->tyo_erittelyt = json_encode($toistuva->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$toistuva->tyo_erittelyt = '';
			}
			if( is_array($toistuva->muistiinpano) and count($toistuva->muistiinpano) > 0 ){
				$toistuva->muistiinpano = json_encode($toistuva->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$toistuva->muistiinpano = '';
			}

			// <-- viikko_paivat
			if(isset($_POST['P'])){	
				$toistuva->viikko_paivat=json_encode($_POST['P']);
			}
			//     viikko_paivat -->


			// <-- SUORITTAMINEN
			if($saankoSuoritta == 1)
			{

				// <-- Varauksesta pois original
				if( $model->tid == 0 ){
					$criteria = new CDBcriteria;
					$criteria->condition = " 
						tid='0'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= '".date("Y-m-d", strtotime($toistuva->pvm))."'
						AND toistuva_id!=0
						AND toistuva_id='".$toistuva->id."'
					";
					$tv_pois = Tyovuoroot::model()->findAll($criteria);
					foreach($tv_pois as $item){
						$tv = Tyovuoroot::model()->findbypk($item->id);
						if(isset($tv->id)){
						// <-- LOG
						$model_log 	= 'Tyovuoroot';
						$name_log 	= 'Työvuorot';
						$status_log 	= 'Auto Delete';
	
						$old_values = json_encode($tv->attributes);
						$new_values = null;
						$site = Yii::app()->createController('Site');
						$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
						//     LOG -->
						$this->loadModel($tv->id)->delete();
						}
					}

				}
				//     Varauksesta pois original -->

				if(!$toistuva->save()){	$return[] = array('ERROR'=>json_encode(var_dump($toistuva->getErrors()))); }

				// <-- Poistetaanko vai säilytetäänkö vanhan ja uuden aloituspäivämäärän väliin jäävät työvuorot
				if(
					isset($_POST['poisto_alkaen_taaksepain'])
					and isset($edellinenToistuva->id)
					and strtotime($_POST['ToistuvatTyovuorot']['pfrom']) > strtotime($edellinenToistuva->pfrom)
				)
				{
					$pois_valipavm = new CDBcriteria;
					$pois_valipavm->condition=" 
						toistuva_id='".$toistuva->id."'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
						BETWEEN '".date("Y-m-d",strtotime($edellinenToistuva->pfrom))."'
						AND '".date("Y-m-d",strtotime($_POST['ToistuvatTyovuorot']['pfrom']))."'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
					";
					$tv_pois = Tyovuoroot::model()->findAll($pois_valipavm);
					foreach($tv_pois as $item){
						$tv = Tyovuoroot::model()->findbypk($item->id);
						if(isset($tv->id)){
						// <-- LOG
						$model_log 	= 'Tyovuoroot';
						$name_log 	= 'Työvuorot';
						$status_log 	= 'Auto Delete';
	
						$old_values = json_encode($tv->attributes);
						$new_values = null;
						$site = Yii::app()->createController('Site');
						$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
						//     LOG -->
						$this->loadModel($tv->id)->delete();
						}
					}
				}
				//     Poistetaanko vai säilytetäänkö vanhan ja uuden aloituspäivämäärän väliin jäävät työvuorot -->


				if(
					!isset($_POST['poisto_alkaen_taaksepain'])
					and isset($edellinenToistuva->id)
					and strtotime($_POST['ToistuvatTyovuorot']['pfrom']) > strtotime($edellinenToistuva->pfrom)
				)
				{
					$upd_valipavm = new CDBcriteria;
					$upd_valipavm->condition=" 
						toistuva_id='".$toistuva->id."'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
						BETWEEN '".date("Y-m-d",strtotime($edellinenToistuva->pfrom))."'
						AND '".date("Y-m-d",strtotime($_POST['ToistuvatTyovuorot']['pfrom']." -1 day"))."'
					";
					Tyovuoroot::model()->updateAll(array('toistuva_id'=>0), $upd_valipavm);
				}


			}

			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    // <-- Lisätään pää työntekijä
			    if( $toistuva->tid != 0 ){
			    		$_POST['tyopaari'][] = $toistuva->tid;
			    }

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$return[] = $this->toistuvaInsert(
					$id,
					$toistuva, 
					$tid, 
					json_decode($toistuva->viikko_paivat, true),
					json_encode($_POST['tyopaari']),
					$saankoSuoritta,
					$edellinenToistuva
					);
			    }

				if($saankoSuoritta == 1)
				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tyopaari' => json_encode($_POST['tyopaari'])));

			} else { // jos on tyopaari -->

				$return[] = $this->toistuvaInsert(
					$id,
					$toistuva,
					$toistuva->tid, 
					json_decode($toistuva->viikko_paivat, true),
					'', // tyopaari
					$saankoSuoritta,
					$edellinenToistuva
				);

			}


			if($saankoSuoritta == 1)
			{
					// <-- Poistetaan tyopaari
					$tp_post = (isset($_POST['tyopaari']))? $_POST['tyopaari']:array();
				    	if(!empty($edellinenToistuva->tyopaari) and is_array(json_decode($edellinenToistuva->tyopaari, true))){
				    	   $diff = array_diff(json_decode($edellinenToistuva->tyopaari, true), $tp_post);
				    	   foreach($diff as $v)
				    	   {
						if( $model->tid != $v ){
							$criteria = new CDBcriteria;
							$criteria->condition = " 
								tid='".$v."'
								AND toistuva_id!=0
								AND toistuva_id='".$toistuva->id."'
							";
							$tv_pois = Tyovuoroot::model()->findAll($criteria);
							foreach($tv_pois as $item){
								$tv = Tyovuoroot::model()->findbypk($item->id);
								if(isset($tv->id)){
								// <-- LOG
								$model_log 	= 'Tyovuoroot';
								$name_log 	= 'Työvuorot';
								$status_log 	= 'Auto Delete';
	
								$old_values = json_encode($tv->attributes);
								$new_values = null;
								$site = Yii::app()->createController('Site');
								$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
								//     LOG -->
								$this->loadModel($tv->id)->delete();
								}
							}
						}
				    	   }
				    	}
					//     Poistetaan tyopaari -->

					$nt = ToistuvatTyovuorot::model()->findbypk($toistuva->id);
					if(isset($edellinenToistuva->id))
					{
					// <-- LOG
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$status_log 	= 'Update';
					$old_values = json_encode($edellinenToistuva->attributes);
					$new_values = json_encode($nt->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
					//     LOG -->
					} else {
					// <-- LOG
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$status_log 	= 'Create';
					$old_values = null;
					$new_values = json_encode($nt->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
					//     LOG -->
					}
			}


			if( count($return) > 0 )
				echo json_encode($return);
			else
				echo json_encode(array('ERROR'=>'Ei muutoksia'));
			exit;
		}
		//     Toistuva tyovuorot ja tyoparit -->








		// Jos Toistuva Ruksi ei ole päällä
		if(isset($_POST['Tyovuoroot']) and !isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']))
		{


		// <-- LOG
		$model_log 	= 'Tyovuoroot';
		$name_log 	= 'Työvuorot';
		$status_log 	= 'Update';
		if(isset($_POST[$model_log]))
		{
			$old_values = json_encode($model->attributes);
			$new_values = json_encode($_POST[$model_log]);
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
		}
		//     LOG -->



			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($model->pvm) and $model->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($model->toistuva_id, $model->pvm);
				$_POST['Tyovuoroot']['toistuva_id'] = 0;
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->


			$_POST['Tyovuoroot']['pvm'] = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			// <-- Tyontekijan vaihto
			if( $model->tid != $_POST['Tyovuoroot']['tid'] )
			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			// Tyontekijan vaihto -->

			$toistuva_id = $model->toistuva_id;

			// <-- Edico viesti jos peruutettu
			if( isset($model->kohteet->asiakas_id) 
				and $model->kohde == $_POST['Tyovuoroot']['kohde']
				and $model->peruutettu == 0 
				and $_POST['Tyovuoroot']['peruutettu'] != 0)
			{
				$edico_viesti = Yii::t('main', 'Työvuoro on peruutettu').".\n".$_POST['Tyovuoroot']['pvm'].", ".$_POST['Tyovuoroot']['alku']."-".$_POST['Tyovuoroot']['loppu'];
				Domainit::sendGCMeDico($model->kohteet->asiakas_id, Yii::t('main', 'Työvuoro on peruutettu'), $edico_viesti, null);
			}
			//     Edico viesti jos peruutettu -->

			$model->attributes = $_POST['Tyovuoroot'];
			if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
				$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
			} else {
				$model->lisa_tuotteet = '';
			}
			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$model->tyo_erittelyt = '';
			}
			if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 ){
				$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$model->muistiinpano = '';
			}
			if($model->save()){

				// <-- Onko tyopari esitetty
				$post_tyopaari = array();
				if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
				$post_tyopaari = $_POST['tyopaari'];
				// Onko tyopari esitetty -->
	
				// <-- Vanhat
				$vanhat = json_decode($model->tyopaari, true);
				$vanhat_arr = array();
				if(is_array($vanhat))
				{
				   foreach($vanhat as $tyovuoroID=>$tid)
				   {
					$vanhat_arr[$tid] = $tyovuoroID;
				   }
				}
				// Vanhat -->

				if(count($post_tyopaari) == 0)
				Tyovuoroot::model()->updatebypk($model->id, array('tyopaari' => ''));


				// <-- jos on tyopaari
				if(count($post_tyopaari) > 0)
				{
	
				$luotu = array();
				$arr = array();
				$luotu[$model->id] = $model->tid;
				$arr[$model->id] = array($model->tid,$model->pvm);
	
				    foreach($_POST['tyopaari'] as $tid)
				    {
					if( isset($vanhat_arr[$tid]) ){
						$m = Tyovuoroot::model()->findByPk($vanhat_arr[$tid]);
					} else {
						$m = new Tyovuoroot;
					}
					if( count($m) == 0 ){ $m = new Tyovuoroot; }

					$m->attributes=$_POST['Tyovuoroot'];
					$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
					$m->tid=$tid;

					if( is_array($m->lisa_tuotteet) and count($m->lisa_tuotteet) > 0 ){
						$m->lisa_tuotteet = json_encode($m->lisa_tuotteet);
					} else {
						$m->lisa_tuotteet = '';
					}
					if( is_array($m->tyo_erittelyt) and count($m->tyo_erittelyt) > 0 ){
						$m->tyo_erittelyt = json_encode($m->tyo_erittelyt, JSON_FORCE_OBJECT);
					} else {
						$m->tyo_erittelyt = '';
					}
					if( is_array($m->muistiinpano) and count($m->muistiinpano) > 0 ){
						$m->muistiinpano = json_encode($m->muistiinpano, JSON_FORCE_OBJECT);
					} else {
						$m->muistiinpano = '';
					}

					if($m->save())
					{

						// <-- Poistetaan tyovuoro henkilosta joka oli toistuvissa
						if( $toistuva_id != 0 )
						{
						$criteria = new CDBcriteria;
						$criteria->condition = " 
							pvm='".$m->pvm."' 
							AND tid='".$m->tid."'
							AND toistuva_id!=0
							AND toistuva_id='".$toistuva_id."'
						";
						$tv_pois = Tyovuoroot::model()->findAll($criteria);
						foreach($tv_pois as $item){
							$tv = Tyovuoroot::model()->findbypk($item->id);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
						}
						}
						//  Poistetaan tyovuoro henkilosta joka oli toistuvissa -->

						$luotu[$m->id] = $m->tid;
				    		$arr[$m->id] = array($m->tid,$m->pvm);
					} else {
						echo json_encode($m->getErrors());
						exit;
					}
				    }
	
				    if(is_array($vanhat)){
				    	$diff = array_diff($vanhat, $_POST['tyopaari']);
				    	foreach($diff as $k => $v)
				    	{
						if($k!=$model->id){
							$m = Tyovuoroot::model()->findByPk($k);
							if( isset($m->id) ){
							$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));
							}

							$tv = Tyovuoroot::model()->findbypk($k);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
						
							if(isset($luotu[$k])){	unset($luotu[$k]); }	
						}
				    	}
				    }

				    foreach($arr as $k => $v)
				    {
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));
					$return[] = array('tid'=>$v[0], 'pvm'=>$v[1], 'ymd'=>date("Ymd",strtotime($v[1])));
		
					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($k);
					// PushNotify -->
				    }
	
				}

				if(is_array($vanhat) and !isset($_POST['tyopaari'])){
				    	foreach($vanhat as $k => $v)
				    	{
						if($k!=$model->id){
							$m = Tyovuoroot::model()->findByPk($k);
							if( isset($m->id) ){
							$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));
							}

							$tv = Tyovuoroot::model()->findbypk($k);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
							if(isset($luotu[$k])){	unset($luotu[$k]); }	
						}
				    	}
				}
				// jos on tyopaari -->

				if(count($post_tyopaari) == 0 and $model->toistuva_id == 0)
				{
					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($model->id);
					// PushNotify -->
				}

			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			echo json_encode($return);

			} else { // model save 
				echo json_encode($model->getErrors());
			}

			exit;
		}





		$criteria = new CDBcriteria;
		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->
		$criteria->condition="aktiivinen=1";
	  	$t = Tyontekijat::model()->findAll($criteria);
		$tekijan_nimi = '<select id="tekijanVaihdo" class="form-control" '.((!empty($model->tyopaari))?'disabled':'').'>';
		if(count($t) > 0)
		{
		   if($model->tid == 0)
		   $tekijan_nimi .= '<option value="'.$model->id.'">'.Yii::t('main', 'Valitse').'</option>';

		   foreach($t as $tekijanData)
		   {
			if($tekijanData->id == $model->tid)
			$tekijan_nimi .= '<option value="'.$tekijanData->id.'" selected>'.$this->etuSukunimi($tekijanData->id).'</option>';
			else
			$tekijan_nimi .= '<option value="'.$tekijanData->id.'">'.$this->etuSukunimi($tekijanData->id).'</option>';
		   }
		}
		$tekijan_nimi .= '</select>';

	?>


        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"><i class="fa fa-clock-o"></i> 
		<?php echo Yii::t('main', 'Työvuoron suunnittelu').' #'.$model->id.' <span class="kohteen_lisatiedot"></span> '.$tekijan_nimi; ?>
	      </span>
            </div>
            <!-- end .panel-heading section -->
              <div class="panel-body p25">
		<?php
		$this->renderPartial('_form',array(
			'model'=>$model,
		));
		?>
              </div>
              <!-- end .form-body section -->


          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->

	<?php
	}

	public function actionCreate4()
	{

		$return = array();

		if(isset($_POST['ToistuvatTyovuorot']) and isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on')
		{
			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];

			$toistuva=new ToistuvatTyovuorot;
			$toistuva->attributes=$_POST['ToistuvatTyovuorot'];
			$toistuva->attributes=$_POST['Tyovuoroot'];
			$toistuva->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if( is_array($toistuva->lisa_tuotteet) and count($toistuva->lisa_tuotteet) > 0 ){
				$toistuva->lisa_tuotteet = json_encode($toistuva->lisa_tuotteet);
			} else {
				$toistuva->lisa_tuotteet = '';
			}
			if( is_array($toistuva->tyo_erittelyt) and count($toistuva->tyo_erittelyt) > 0 ){
				$toistuva->tyo_erittelyt = json_encode($toistuva->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$toistuva->tyo_erittelyt = '';
			}
			if( is_array($toistuva->muistiinpano) and count($toistuva->muistiinpano) > 0 ){
				$toistuva->muistiinpano = json_encode($toistuva->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$toistuva->muistiinpano = '';
			}

			if(isset($_POST['P'])){	$toistuva->viikko_paivat=json_encode($_POST['P']); }

			if($saankoSuoritta == 1)
			{
				if(!$toistuva->save())
				{
					$return[] = array('ERROR'=>json_encode(var_dump($toistuva->getErrors())));
				}
			}
	
			if(!isset($_POST['tyopaari']))
			{
			  	$return[] = $this->toistuvaInsert(
					null,
					$toistuva,
					$toistuva->tid,  
					json_decode($toistuva->viikko_paivat, true),
					'', // tyopaari
					$saankoSuoritta,
					null
				);
			}

			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    // <-- Lisätään pää työntekijä
			    $_POST['tyopaari'][] = $toistuva->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$return[] = $this->toistuvaInsert(
					null,
					$toistuva,
					$tid,  
					json_decode($toistuva->viikko_paivat, true),
					json_encode($_POST['tyopaari']),
					$saankoSuoritta,
					null
					);
			    }

				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tyopaari' => json_encode($_POST['tyopaari'])));
			}
			// jos on tyopaari -->


			// <-- LOG
			if( $saankoSuoritta == 1 )
			{
			$model_log 	= 'ToistuvatTyovuorot';
			$name_log 	= 'Toistuvat työvuorot';
			$status_log 	= 'Create';
			if(isset($toistuva->id))
			{
				$old_values = null;
				$n_m = ToistuvatTyovuorot::model()->findbypk($toistuva->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			}
			//     LOG -->


			echo json_encode($return);
			exit;
		}

		$model=new Tyovuoroot;

		if(isset($_POST['Tyovuoroot']))
		{

			$model->attributes=$_POST['Tyovuoroot'];
			if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
				$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
			} else {
				$model->lisa_tuotteet = '';
			}
			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$model->tyo_erittelyt = '';
			}
			if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 ){
				$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$model->muistiinpano = '';
			}
			// <-- Apuaika
			if(isset($_POST['Tyovuoroot']['apuaika']) and $_POST['Tyovuoroot']['apuaika'] == 1)
				$model->apuaika = 1;
			else if(isset($_POST['Tyovuoroot']['apuaika']) and $_POST['Tyovuoroot']['apuaika'] != 1)
				$model->apuaika = 0;
			//     Apuaika -->

			$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if($model->save())
			{

			// <-- PushNotify
			if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
			$this->pushNotifySending($model->id);
			// PushNotify -->



			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    $luotu = array();
			    $luotu[$model->id] = $model->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$m=new Tyovuoroot;
				$m->attributes=$_POST['Tyovuoroot'];
				$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				$m->tid=$tid;
				if( is_array($model->tyopaari) and count($m->tyopaari) > 0 ){
					$m->tyopaari = json_encode($m->tyopaari);
				} else {
					$m->tyopaari = '';
				}
				if( isset($_POST['Tyovuoroot']['lisa_tuotteet']) and is_array($_POST['Tyovuoroot']['lisa_tuotteet']) ){
					$m->lisa_tuotteet = json_encode($_POST['Tyovuoroot']['lisa_tuotteet']);
				} else {
					$m->lisa_tuotteet = '';
				}
				if( is_array($m->tyo_erittelyt) and count($m->tyo_erittelyt) > 0 ){
					$m->tyo_erittelyt = json_encode($m->tyo_erittelyt, JSON_FORCE_OBJECT);
				} else {
					$m->tyo_erittelyt = '';
				}
				if($m->save())
				{
					$luotu[$m->id] = $m->tid;
					$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));

					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($m->id);
					// PushNotify -->

				}

			    }
			    foreach($luotu as $k=>$v)
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));


			}
			// jos on tyopaari -->


			// <-- LOG
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Create';
			if(isset($_POST[$model_log]))
			{
				$old_values = null;
				$n_m = Tyovuoroot::model()->findbypk($model->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->


			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			echo json_encode($return);

			}
		exit;
		}

	}

	public function actionCreate4_form($pvm, $tid)
	{

		// Tyosuhde oikeus
		$oikeus = '<div class="alert alert-danger">'.Yii::t('main', 'Työsuhdetta ei ole määritelty tai työsuhde ei ole voimassa.').'</div>';
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			tid='".$tid."' 
		";
		$ts = Tyosuhdet::model()->find($criteria);
		if(isset($ts->id) and !empty($ts->alku))
		{
			$alku = date("Ymd", strtotime($ts->alku));

			if($pvm >= $alku and empty($ts->loppu))
			$oikeus = '';
			elseif($pvm >= $alku and !empty($ts->loppu) and $pvm <= date("Ymd", strtotime($ts->loppu)))
			$oikeus = '';
		}
		// Tyosuhde oikeus

		$model=new Tyovuoroot;

		$form_content = '';
	        $form_content = '
	        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
	          <div class="panel">
	            <div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
					<span aria-hidden="true">&times;</span>
				</button>
	              <span class="panel-title"><i class="fa fa-clock-o"></i> 
			'.Yii::t('main', 'Työvuoron suunnittelu').': '.$this->etuSukunimi($tid).' <span class="kohteen_lisatiedot"></span>
		      </span>
	            </div>
	              <div class="panel-body p25">
			'.((isset($oikeus))?$oikeus:'').'
			'.$this->renderPartial('_form4',array('model'=>$model), true).'
	              </div>
	          </div>
	        </div>';
		echo json_encode($form_content);
		exit;
	}

	public function actionUpdate4_form($id, $toistuva_id=0)
	{

		if( $toistuva_id != 0 ){
			$model = ToistuvatTyovuorot::model()->findByPk($id);
			$toistuva = true;
		} else {
			$model=$this->loadModel($id);
			$toistuva = false;
		}

		$criteria = new CDBcriteria;
		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->
		$criteria->condition="aktiivinen=1";
	  	$t = Tyontekijat::model()->findAll($criteria);
		$tekijan_nimi = '<select id="tekijanVaihdo" class="form-control" '.((!empty($model->tyopaari))?'disabled':'').'>';
		if(count($t) > 0)
		{
		   if($model->tid == 0)
		   $tekijan_nimi .= '<option value="'.$model->id.'">'.Yii::t('main', 'Valitse').'</option>';

		   foreach($t as $tekijanData)
		   {
			if($tekijanData->id == $model->tid)
			$tekijan_nimi .= '<option value="'.$tekijanData->id.'" selected>'.$this->etuSukunimi($tekijanData->id).'</option>';
			else
			$tekijan_nimi .= '<option value="'.$tekijanData->id.'">'.$this->etuSukunimi($tekijanData->id).'</option>';
		   }
		}
		$tekijan_nimi .= '</select>';

		$form_content = '';
	        $form_content = '
	        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
	          <div class="panel">
	            <div class="panel-heading">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
					<span aria-hidden="true">&times;</span>
				</button>
	              <span class="panel-title"><i class="fa fa-clock-o"></i> 
			'.Yii::t('main', 'Työvuoron suunnittelu').' '.(($toistuva)?'ketju: ':'').' #'.$model->id.' <span class="kohteen_lisatiedot"></span> '.$tekijan_nimi.'
		      </span>
	            </div>
	            <!-- end .panel-heading section -->
	              <div class="panel-body p25">
			'.$this->renderPartial('_form4',array('model'=>$model, 'toistuva'=>$toistuva), true).'
	              </div>
	          </div>
	        </div>';
	
		echo json_encode($form_content);
		exit;
	}

	public function actionUpdate4($id)
	{

		$model=$this->loadModel($id);
		if(!isset($model->id)){ die('Työvuoroja '.$id.' ei löydy.'); }
		$edellinenToistuva = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);
		$return = array();

		// <-- Toistuva tyovuorot ja tyoparit
		if(
			isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']) 
			and $_POST['ToistuvatTyovuorot']['toistuva_aktiivinen'] == 'on'
		)
		{
			$fi = $this->vkoPaivat();
			$saankoSuoritta = $_POST['ToistuvatTyovuorot']['sopivatPaivat'];

			if(isset($edellinenToistuva->id))
				$toistuva = ToistuvatTyovuorot::model()->findByPk($edellinenToistuva->id);
			else
				$toistuva = new ToistuvatTyovuorot;

			$toistuva->attributes = $_POST['ToistuvatTyovuorot'];
			$toistuva->attributes = $_POST['Tyovuoroot'];
			$toistuva->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			if( is_array($toistuva->lisa_tuotteet) and count($toistuva->lisa_tuotteet) > 0 ){
				$toistuva->lisa_tuotteet = json_encode($toistuva->lisa_tuotteet);
			} else {
				$toistuva->lisa_tuotteet = '';
			}
			if( is_array($toistuva->tyo_erittelyt) and count($toistuva->tyo_erittelyt) > 0 ){
				$toistuva->tyo_erittelyt = json_encode($toistuva->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$toistuva->tyo_erittelyt = '';
			}
			if( is_array($toistuva->muistiinpano) and count($toistuva->muistiinpano) > 0 ){
				$toistuva->muistiinpano = json_encode($toistuva->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$toistuva->muistiinpano = '';
			}

			// <-- viikko_paivat
			if(isset($_POST['P'])){	
				$toistuva->viikko_paivat=json_encode($_POST['P']);
			}
			//     viikko_paivat -->


			// <-- SUORITTAMINEN
			if($saankoSuoritta == 1)
			{

				// <-- Varauksesta pois original
				if( $model->tid == 0 ){
					$criteria = new CDBcriteria;
					$criteria->condition = " 
						tid='0'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= '".date("Y-m-d", strtotime($toistuva->pvm))."'
						AND toistuva_id!=0
						AND toistuva_id='".$toistuva->id."'
					";
					$tv_pois = Tyovuoroot::model()->findAll($criteria);
					foreach($tv_pois as $item){
						$tv = Tyovuoroot::model()->findbypk($item->id);
						if(isset($tv->id)){
						// <-- LOG
						$model_log 	= 'Tyovuoroot';
						$name_log 	= 'Työvuorot';
						$status_log 	= 'Auto Delete';
	
						$old_values = json_encode($tv->attributes);
						$new_values = null;
						$site = Yii::app()->createController('Site');
						$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
						//     LOG -->
						$this->loadModel($tv->id)->delete();
						}
					}

				}
				//     Varauksesta pois original -->

				if(!$toistuva->save()){	$return[] = array('ERROR'=>json_encode(var_dump($toistuva->getErrors()))); }

				// <-- Poistetaanko vai säilytetäänkö vanhan ja uuden aloituspäivämäärän väliin jäävät työvuorot
				if(
					isset($_POST['poisto_alkaen_taaksepain'])
					and isset($edellinenToistuva->id)
					and strtotime($_POST['ToistuvatTyovuorot']['pfrom']) > strtotime($edellinenToistuva->pfrom)
				)
				{
					$pois_valipavm = new CDBcriteria;
					$pois_valipavm->condition=" 
						toistuva_id='".$toistuva->id."'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
						BETWEEN '".date("Y-m-d",strtotime($edellinenToistuva->pfrom))."'
						AND '".date("Y-m-d",strtotime($_POST['ToistuvatTyovuorot']['pfrom']))."'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
					";
					$tv_pois = Tyovuoroot::model()->findAll($pois_valipavm);
					foreach($tv_pois as $item){
						$tv = Tyovuoroot::model()->findbypk($item->id);
						if(isset($tv->id)){
						// <-- LOG
						$model_log 	= 'Tyovuoroot';
						$name_log 	= 'Työvuorot';
						$status_log 	= 'Auto Delete';
	
						$old_values = json_encode($tv->attributes);
						$new_values = null;
						$site = Yii::app()->createController('Site');
						$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
						//     LOG -->
						$this->loadModel($tv->id)->delete();
						}
					}
				}
				//     Poistetaanko vai säilytetäänkö vanhan ja uuden aloituspäivämäärän väliin jäävät työvuorot -->


				if(
					!isset($_POST['poisto_alkaen_taaksepain'])
					and isset($edellinenToistuva->id)
					and strtotime($_POST['ToistuvatTyovuorot']['pfrom']) > strtotime($edellinenToistuva->pfrom)
				)
				{
					$upd_valipavm = new CDBcriteria;
					$upd_valipavm->condition=" 
						toistuva_id='".$toistuva->id."'
						AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
						BETWEEN '".date("Y-m-d",strtotime($edellinenToistuva->pfrom))."'
						AND '".date("Y-m-d",strtotime($_POST['ToistuvatTyovuorot']['pfrom']." -1 day"))."'
					";
					Tyovuoroot::model()->updateAll(array('toistuva_id'=>0), $upd_valipavm);
				}


			}

			// <-- jos on tyopaari
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    // <-- Lisätään pää työntekijä
			    if( $toistuva->tid != 0 ){
			    		$_POST['tyopaari'][] = $toistuva->tid;
			    }

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$return[] = $this->toistuvaInsert(
					$id,
					$toistuva, 
					$tid, 
					json_decode($toistuva->viikko_paivat, true),
					json_encode($_POST['tyopaari']),
					$saankoSuoritta,
					$edellinenToistuva
					);
			    }

				if($saankoSuoritta == 1)
				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('tyopaari' => json_encode($_POST['tyopaari'])));

			} else { // jos on tyopaari -->

				$return[] = $this->toistuvaInsert(
					$id,
					$toistuva,
					$toistuva->tid, 
					json_decode($toistuva->viikko_paivat, true),
					'', // tyopaari
					$saankoSuoritta,
					$edellinenToistuva
				);

			}


			if($saankoSuoritta == 1)
			{
					// <-- Poistetaan tyopaari
					$tp_post = (isset($_POST['tyopaari']))? $_POST['tyopaari']:array();
				    	if(!empty($edellinenToistuva->tyopaari) and is_array(json_decode($edellinenToistuva->tyopaari, true))){
				    	   $diff = array_diff(json_decode($edellinenToistuva->tyopaari, true), $tp_post);
				    	   foreach($diff as $v)
				    	   {
						if( $model->tid != $v ){
							$criteria = new CDBcriteria;
							$criteria->condition = " 
								tid='".$v."'
								AND toistuva_id!=0
								AND toistuva_id='".$toistuva->id."'
							";
							$tv_pois = Tyovuoroot::model()->findAll($criteria);
							foreach($tv_pois as $item){
								$tv = Tyovuoroot::model()->findbypk($item->id);
								if(isset($tv->id)){
								// <-- LOG
								$model_log 	= 'Tyovuoroot';
								$name_log 	= 'Työvuorot';
								$status_log 	= 'Auto Delete';
	
								$old_values = json_encode($tv->attributes);
								$new_values = null;
								$site = Yii::app()->createController('Site');
								$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
								//     LOG -->
								$this->loadModel($tv->id)->delete();
								}
							}
						}
				    	   }
				    	}
					//     Poistetaan tyopaari -->

					$nt = ToistuvatTyovuorot::model()->findbypk($toistuva->id);
					if(isset($edellinenToistuva->id))
					{
					// <-- LOG
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$status_log 	= 'Update';
					$old_values = json_encode($edellinenToistuva->attributes);
					$new_values = json_encode($nt->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
					//     LOG -->
					} else {
					// <-- LOG
					$model_log 	= 'ToistuvatTyovuorot';
					$name_log 	= 'Toistuvat työvuorot';
					$status_log 	= 'Create';
					$old_values = null;
					$new_values = json_encode($nt->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
					//     LOG -->
					}
			}


			if( count($return) > 0 )
				echo json_encode($return);
			else
				echo json_encode(array('ERROR'=>'Ei muutoksia'));
			exit;
		}
		//     Toistuva tyovuorot ja tyoparit -->


		// Jos Toistuva Ruksi ei ole päällä
		if(isset($_POST['Tyovuoroot']) and !isset($_POST['ToistuvatTyovuorot']['toistuva_aktiivinen']))
		{


		// <-- LOG
		$model_log 	= 'Tyovuoroot';
		$name_log 	= 'Työvuorot';
		$status_log 	= 'Update';
		if(isset($_POST[$model_log]))
		{
			$old_values = json_encode($model->attributes);
			$new_values = json_encode($_POST[$model_log]);
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
		}
		//     LOG -->



			// <-- Jos toistuva, otetaan sen Päivämäärä pois ketjusta
			if( isset($model->pvm) and $model->toistuva_id != 0)
			{
				$this->toistuvaDeletePvm($model->toistuva_id, $model->pvm);
				$_POST['Tyovuoroot']['toistuva_id'] = 0;
			}
			//     Jos toistuva, otetaan sen Päivämäärä pois ketjusta -->


			$_POST['Tyovuoroot']['pvm'] = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));

			// <-- Tyontekijan vaihto
			if( $model->tid != $_POST['Tyovuoroot']['tid'] )
			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			// Tyontekijan vaihto -->

			$toistuva_id = $model->toistuva_id;

			// <-- Edico viesti jos peruutettu
			if( isset($model->kohteet->asiakas_id) 
				and $model->kohde == $_POST['Tyovuoroot']['kohde']
				and $model->peruutettu == 0 
				and $_POST['Tyovuoroot']['peruutettu'] != 0)
			{
				$edico_viesti = Yii::t('main', 'Työvuoro on peruutettu').".\n".$_POST['Tyovuoroot']['pvm'].", ".$_POST['Tyovuoroot']['alku']."-".$_POST['Tyovuoroot']['loppu'];
				Domainit::sendGCMeDico($model->kohteet->asiakas_id, Yii::t('main', 'Työvuoro on peruutettu'), $edico_viesti, null);
			}
			//     Edico viesti jos peruutettu -->

			$model->attributes = $_POST['Tyovuoroot'];
			if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
				$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
			} else {
				$model->lisa_tuotteet = '';
			}
			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$model->tyo_erittelyt = '';
			}
			if( is_array($model->muistiinpano) and count($model->muistiinpano) > 0 ){
				$model->muistiinpano = json_encode($model->muistiinpano, JSON_FORCE_OBJECT);
			} else {
				$model->muistiinpano = '';
			}
			if($model->save()){

				// <-- Onko tyopari esitetty
				$post_tyopaari = array();
				if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
				$post_tyopaari = $_POST['tyopaari'];
				// Onko tyopari esitetty -->
	
				// <-- Vanhat
				$vanhat = json_decode($model->tyopaari, true);
				$vanhat_arr = array();
				if(is_array($vanhat))
				{
				   foreach($vanhat as $tyovuoroID=>$tid)
				   {
					$vanhat_arr[$tid] = $tyovuoroID;
				   }
				}
				// Vanhat -->

				if(count($post_tyopaari) == 0)
				Tyovuoroot::model()->updatebypk($model->id, array('tyopaari' => ''));


				// <-- jos on tyopaari
				if(count($post_tyopaari) > 0)
				{
	
				$luotu = array();
				$arr = array();
				$luotu[$model->id] = $model->tid;
				$arr[$model->id] = array($model->tid,$model->pvm);
	
				    foreach($_POST['tyopaari'] as $tid)
				    {
					if( isset($vanhat_arr[$tid]) ){
						$m = Tyovuoroot::model()->findByPk($vanhat_arr[$tid]);
					} else {
						$m = new Tyovuoroot;
					}
					if( count($m) == 0 ){ $m = new Tyovuoroot; }

					$m->attributes=$_POST['Tyovuoroot'];
					$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
					$m->tid=$tid;

					if( is_array($m->lisa_tuotteet) and count($m->lisa_tuotteet) > 0 ){
						$m->lisa_tuotteet = json_encode($m->lisa_tuotteet);
					} else {
						$m->lisa_tuotteet = '';
					}
					if( is_array($m->tyo_erittelyt) and count($m->tyo_erittelyt) > 0 ){
						$m->tyo_erittelyt = json_encode($m->tyo_erittelyt, JSON_FORCE_OBJECT);
					} else {
						$m->tyo_erittelyt = '';
					}
					if( is_array($m->muistiinpano) and count($m->muistiinpano) > 0 ){
						$m->muistiinpano = json_encode($m->muistiinpano, JSON_FORCE_OBJECT);
					} else {
						$m->muistiinpano = '';
					}

					if($m->save())
					{

						// <-- Poistetaan tyovuoro henkilosta joka oli toistuvissa
						if( $toistuva_id != 0 )
						{
						$criteria = new CDBcriteria;
						$criteria->condition = " 
							pvm='".$m->pvm."' 
							AND tid='".$m->tid."'
							AND toistuva_id!=0
							AND toistuva_id='".$toistuva_id."'
						";
						$tv_pois = Tyovuoroot::model()->findAll($criteria);
						foreach($tv_pois as $item){
							$tv = Tyovuoroot::model()->findbypk($item->id);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
						}
						}
						//  Poistetaan tyovuoro henkilosta joka oli toistuvissa -->

						$luotu[$m->id] = $m->tid;
				    		$arr[$m->id] = array($m->tid,$m->pvm);
					} else {
						echo json_encode($m->getErrors());
						exit;
					}
				    }
	
				    if(is_array($vanhat)){
				    	$diff = array_diff($vanhat, $_POST['tyopaari']);
				    	foreach($diff as $k => $v)
				    	{
						if($k!=$model->id){
							$m = Tyovuoroot::model()->findByPk($k);
							if( isset($m->id) ){
							$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));
							}

							$tv = Tyovuoroot::model()->findbypk($k);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
						
							if(isset($luotu[$k])){	unset($luotu[$k]); }	
						}
				    	}
				    }

				    foreach($arr as $k => $v)
				    {
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));
					$return[] = array('tid'=>$v[0], 'pvm'=>$v[1], 'ymd'=>date("Ymd",strtotime($v[1])));
		
					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($k);
					// PushNotify -->
				    }
	
				}

				if(is_array($vanhat) and !isset($_POST['tyopaari'])){
				    	foreach($vanhat as $k => $v)
				    	{
						if($k!=$model->id){
							$m = Tyovuoroot::model()->findByPk($k);
							if( isset($m->id) ){
							$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));
							}

							$tv = Tyovuoroot::model()->findbypk($k);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
							if(isset($luotu[$k])){	unset($luotu[$k]); }	
						}
				    	}
				}
				// jos on tyopaari -->

				if(count($post_tyopaari) == 0 and $model->toistuva_id == 0)
				{
					// <-- PushNotify
					if(isset($_POST['Tyovuoroot']['PushNotify']) and $_POST['Tyovuoroot']['PushNotify'] == 'on')
					$this->pushNotifySending($model->id);
					// PushNotify -->
				}

			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)));
			echo json_encode($return);

			} else { // model save 
				echo json_encode($model->getErrors());
			}

			exit;
		}

	}

	protected function pushNotifySending($tv_id)
	{
		$m = Tyovuoroot::model()->findByPk($tv_id);
		$t = Tyontekijat::model()->findbypk($m->tid);
		$k = Kohteet::model()->findbypk($m->kohde);
		if(isset($k->osoite) and !empty($k->osoite) and isset($t->id))
		{
			$pushviesti = "Työvuorosi on muuttunut. Alta löydät uudet tiedot:\n
				".$m->pvm."
				".$m->alku."-".$m->loppu." ".$k->osoite."
				".$m->tietoja;

			Domainit::sendGCM($t->id,"Hei ".$t->tekijan_nimi,$pushviesti, null);
		}
	}

	protected function hinnastoHintaat($tp, $asiakkaat, $kohteet)
	{
		$return = [];
		// <-- 1. TuotteetPalvelut
		if(isset($tp->id))
		{
			$return['tp_nimike'] 	= $tp->nimike;
			$return['tp_id'] 	= $tp->id;
			$return['hinta_alv_0'] 	= $tp->hinta_alv_0;
			$return['hinta_alv_sis'] = $tp->hinta_alv_sis;
			$return['alv'] 		= $tp->alv;
			$return['yksikko']	= $tp->yksikko;
		}
		//     TuotteetPalvelut -->

		// <-- 2. Asiakas
		if(isset($tp->id) and isset($asiakkaat->id) and $asiakkaat->hinnasto_id != 0)
		{
			$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$asiakkaat->hinnasto_id."' ");
			if(isset($hinnasto->id))
			{
				$return['hinnasto_rivi_id'] 	= $hinnasto->id;
				$return['hinta_alv_0'] 		= $hinnasto->hinnasto_hinta;
				$return['hinta_alv_sis'] 	= $hinnasto->hinnasto_yht;
				$return['alv'] 			= $hinnasto->hinnasto_alv;
				$return['yksikko']		= $hinnasto->hinnasto_yksikko;
			}
		}
		//     Asiakas -->

		// <-- 3. Kohteet
		if(isset($tp->id) and isset($kohteet->id) and $kohteet->hinnasto_id != 0)
		{
			$hinnasto = HinnastotRivi::model()->find(" tuote_palvelu_id='".$tp->id."' AND hinnastot_id='".$kohteet->hinnasto_id."' ");
			if(isset($hinnasto->id))
			{
				$return['hinnasto_rivi_id'] 	= $hinnasto->id;
				$return['hinta_alv_0'] 		= $hinnasto->hinnasto_hinta;
				$return['hinta_alv_sis'] 	= $hinnasto->hinnasto_yht;
				$return['alv'] 			= $hinnasto->hinnasto_alv;
				$return['yksikko']		= $hinnasto->hinnasto_yksikko;
			}
		}
		//     Kohteet -->

		return $return; 
	}

	public function actionUusitilaus()
	{

	if(!isset($_POST['Tyovuoroot']))
	{
	?>

        <!-- Admin Form Popup -->
        <div id="modal-form" class=" popup-basic popup-xl admin-form mfp-with-anim mfp-hide">
          <div class="panel">
            <div class="panel-heading">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">
				<span aria-hidden="true">&times;</span>
			</button>
              <span class="panel-title"><i class="fa fa-clock-o"></i> 

		<?php echo Yii::t('main', 'Uusi tilaus'); ?>
	      </span>
            </div>
            <!-- end .panel-heading section -->

              <div class="panel-body p25">
	<?php
	}

		$model=new Tyovuoroot;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$return = array();

		// <-- Oleva asiakas
		if(isset($_POST['Tyovuoroot']['kohde']) and $_POST['Tyovuoroot']['kohde'] > 0)
		{

		$kohteet = Kohteet::model()->findByPk($_POST['Tyovuoroot']['kohde']);
		$asiakkaat = Asiakkaat::model()->findByPk($kohteet->asiakas_id);

		if(!isset($kohteet->id) and !isset($asiakkaat->id))
		{
			echo json_encode($return);
			exit;
		}

		$model->attributes=$_POST['Tyovuoroot'];
		if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
			$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
		} else {
			$model->lisa_tuotteet = '';
		}
		if( is_array($model->tyopaari) and count($model->tyopaari) > 0 ){
			$model->tyopaari = json_encode($model->tyopaari);
		} else {
			$model->tyopaari = '';
		}

		$model->kohde = $kohteet->id;
		$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
		if($model->save())
		{

			// <-- LOG
			$model_log 	= 'Tyovuoroot';
			$name_log 	= 'Työvuorot';
			$status_log 	= 'Create';
			if(isset($_POST[$model_log]))
			{
				$old_values = null;
				$n_m = Tyovuoroot::model()->findbypk($model->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

			// <-- jos on tyopaari
			$luotu = array();
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    $luotu[$model->id] = $model->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$m=new Tyovuoroot;
				$m->attributes=$_POST['Tyovuoroot'];
				$m->tyopaari = $model->tyopaari;
				$m->lisa_tuotteet = $model->lisa_tuotteet;
				$m->kohde = $kohteet->id;
				$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				$m->tid=$tid;
				$m->status=3;
				if(!$m->save())
				{
					echo json_encode($m->getErrors());
					exit;
				} else {
					$luotu[$m->id] = $m->tid;
					$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));
				}

			    }
			    foreach($luotu as $k=>$v)
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));


			}
			// jos on tyopaari -->


				$sum = 0;
				$alv_0 = 0;
				$alv_sum = 0;
				$yht_alv_nolla = 0;
				$yht_alv = 0;
				$yht_alv_sis = 0;
				   if(isset($_POST['vieposti']) and isset($asiakkaat->sahkoposti) and !empty($asiakkaat->sahkoposti))
				   {
					$message = '<div>';
					$message .= '
					Asiakas: '.$asiakkaat->yhteyshenkilo.'<br>
					Työvuorot:  '.$model->pvm.', '.$model->alku.'-'.$model->loppu.'<br>';
					$message .= '<style>.lahetys_taulu table {border-collapse: collapse; border: 1px solid grey;} .lahetys_taulu th, .lahetys_taulu td{border: 1px solid grey; padding: 7px 15px;}</style>';

					// <-- Paatuote
					$tp = TuotteetPalvelut::model()->findByPK($model->tuoteID);
					if( isset($tp->id) ){
					$message .= '<table class="lahetys_taulu">';
					$message .= '<tr>';
					$message .= '<th>Tuote/Palvelu</th>';
					$message .= '<th>Työntekijät</th>';
					$message .= '<th>Tunnit</th>';
					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<th>Tuntihinta</th>';
					$message .= '<th>Hinta</th>';
					$message .= '<th>ALV</th>';
					$message .= '<th>Yhteensä</th>';
					}
					$message .= '</tr>';

						$tp_maara = (isset($_POST['tyopaari']))?(count($_POST['tyopaari'])+1):1;
						$return_hinnaasto = $this->hinnastoHintaat($tp, $asiakkaat, $kohteet);
						$maara 	= $this->num( strtotime($model->loppu)-strtotime($model->alku) );
						$tunti_hinta = $return_hinnaasto['hinta_alv_0'];
						$hinta_alv_0 = ($return_hinnaasto['hinta_alv_0']*($maara*$tp_maara));
						$hinta_alv_sis = ($return_hinnaasto['hinta_alv_sis']*($maara*$tp_maara));
						$alv = ($hinta_alv_sis-$hinta_alv_0);
						$yht_alv_nolla += $hinta_alv_0;
						$yht_alv += $alv;
						$yht_alv_sis += $hinta_alv_sis;

						if(isset($return_hinnaasto['tp_nimike']) and isset($return_hinnaasto['hinta_alv_0'])){
						$message .= '<tr>';
						$message .= '<td>'.$return_hinnaasto['tp_nimike'].'</td>';
						$message .= '<td>'.$tp_maara.'</td>';
						$message .= '<td>'.$maara.'</td>';
						if(isset($_POST['vie_hintatietoja'])){
						$message .= '<td>'.number_format($tunti_hinta, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($hinta_alv_0, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($alv, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($hinta_alv_sis, 2, ',', ' ').'</td>';
						}
						$message .= '</tr>';
						}
					$message .= '</table>';
					}
					//     Paatuote -->

					if(isset($_POST['vie_hintatietoja']) and !empty($asiakkaat->hinta) and $asiakkaat->hinta_tyyppi == 1)
					{
						$tp_maara = (isset($_POST['tyopaari']))?(count($_POST['tyopaari'])+1):1;
						$tuntia = ((strtotime($model->loppu)-strtotime($model->alku))/3600);
						if( count($luotu) > 0 )
						$tuntia = $tuntia * count($luotu);

						$sum = (($asiakkaat->hinta*$tuntia) + ((($asiakkaat->hinta*$asiakkaat->alv)/100)*$tuntia))*$tp_maara;
						$alv_0 = ($asiakkaat->hinta*$tuntia)*$tp_maara;
						$alv_sum = $sum-$alv_0;

						$message .= 'Työntekijät: '.$tp_maara.'<br>';
						$message .= 'Hinta ALV 0: '.number_format($alv_0, 2, ',', ' ').' &euro;<br>';
						$message .= 'ALV: '.number_format($alv_sum, 2, ',', ' ').' &euro;<br>';
						$message .= 'Hinta: '.number_format($sum, 2, ',', ' ').' &euro;<br>';
						$yht_alv_nolla += $alv_0;
						$yht_alv += $alv_sum;
						$yht_alv_sis += $sum;
					}

					// <-- Lisatuotteet
					$lisa_tuotteet = json_decode($model->lisa_tuotteet, true);
					if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote'])  ){
					$message .= '<table class="lahetys_taulu">';
					$message .= '<tr>';
					$message .= '<th>Tuote/Palvelu</th>';
					$message .= '<th>Määrä</th>';
					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<th>Hinta</th>';
					$message .= '<th>ALV</th>';
					$message .= '<th>Yhteensä</th>';
					}
					$message .= '</tr>';
					   foreach($lisa_tuotteet['tuote'] as $k => $v){
						$tp = TuotteetPalvelut::model()->findByPK($v);
						if( isset($tp->id) ){
							$return_hinnaasto = $this->hinnastoHintaat($tp, $asiakkaat, $kohteet);
							$maara = json_decode($model->lisa_tuotteet, true)['maara'][$k];
							$hinta_alv_0 = $return_hinnaasto['hinta_alv_0']*$maara;
							$hinta_alv_sis = $return_hinnaasto['hinta_alv_sis']*$maara;
							$alv	= ($hinta_alv_sis-$hinta_alv_0);
							$yht_alv_nolla += $hinta_alv_0;
							$yht_alv += $alv;
							$yht_alv_sis += $hinta_alv_sis;

							if(isset($return_hinnaasto['tp_nimike']) and isset($return_hinnaasto['hinta_alv_0'])){
							$message .= '<tr>';
							$message .= '<td>'.$return_hinnaasto['tp_nimike'].'</td>';
							$message .= '<td>'.$maara.'</td>';
							if(isset($_POST['vie_hintatietoja'])){
							$message .= '<td>'.number_format($hinta_alv_0, 2, ',', ' ').'</td>';
							$message .= '<td>'.number_format($alv, 2, ',', ' ').'</td>';
							$message .= '<td>'.number_format($hinta_alv_sis, 2, ',', ' ').'</td>';
							}
							$message .= '</tr>';
							}
						}
					   }
					$message .= '</table>';
					}
					//     Lisatuotteet -->

					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<br>';
					$message .= '<h3>Veroton hinta '.number_format($yht_alv_nolla, 2, ',', ' ').'&euro;<br>';
					$message .= 'ALV '.number_format($yht_alv, 2, ',', ' ').'&euro;<br>';
					$message .= 'Hinta ALV sis. '.number_format($yht_alv_sis, 2, ',', ' ').'&euro;<br>';
					$message .= '</h3><br>';
					}


					if(!empty($model->toimenpiteet))
					$message .= str_replace("\n", "<hr><br>",$model->toimenpiteet)."<br>";

					if(isset($_POST['Tyovuoroot']['tilausviesti']) and !empty($_POST['Tyovuoroot']['tilausviesti']))
					$message .= str_replace("\n", "<br>", $_POST['Tyovuoroot']['tilausviesti']);

					$message .= '<h2>Kiitos tilauksesta.</h2>';
					$message .= '</div>';
					$subject = Yii::t('main', 'Kiitos tilauksesta');

					$ft = FirmanTiedot::model()->findByPk(1);
					$mail = new YiiMailer();
					//$mail->clearLayout();//if layout is already set in config
					$mail->setFrom('no-reply@etunti.fi');
					$mail->setTo($asiakkaat->sahkoposti);
					$mail->setSubject($subject);
					$mail->setBody($message);

					foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/toimitusehdot*')) as $file) {
						$mail->setAttachment($file);
						//break;
					}

					if($mail->send())
					{


							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $asiakkaat->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->log_nimike	= 'uusi_tilaus';
							$log->save();
							//     LOG -->
					}
				   }


			$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)), 'alv'=>$alv_sum, 'alv_0' => $alv_0, 'sum' => $sum);
		}

		echo json_encode($return);
		exit;

		}
		//  Oleva asiakas -->

		if(isset($_POST['Tyovuoroot']))
		{

		$asiakkaat = new Asiakkaat;
		$asiakkaat->attributes = $_POST['Asiakkaat'];
		$asiakkaat->aktiivinen = 1;
		if(isset($_POST['Asiakkaat']['ryhma'])){
			$asiakkaat->ryhma=json_encode($_POST['Asiakkaat']['ryhma']);
		} else {
			$asiakkaat->ryhma="";
		}

		  if($asiakkaat->save())
		  {

			// <-- LOG
			$model_log 	= 'Asiakkaat';
			$name_log 	= 'Asiakkaat';
			$status_log 	= 'Create';
			if(isset($_POST[$model_log]))
			{
				$old_values = null;
				$n_m = Asiakkaat::model()->findbypk($asiakkaat->id);
				$new_values = json_encode($n_m->attributes);
				$site = Yii::app()->createController('Site');
				$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
			}
			//     LOG -->

	       		$criteria = new CDbCriteria();
	       		$criteria->order = " cast(asiakasnumero as unsigned) DESC  ";
			$anum = Asiakkaat::model()->find($criteria);
			if( isset($anum->id) ){ $nextnum = $anum->asiakasnumero+1; } else { $nextnum = $anum->id; }		
			Asiakkaat::model()->updateByPk($asiakkaat->id, array( 'asiakasnumero' => $nextnum ));


			$kohteet = new Kohteet;
			$kohteet->asiakas_id = $asiakkaat->id;
			$kohteet->hinnasto_id = $asiakkaat->hinnasto_id;
			$kohteet->uusi_tilaus = 1;
			$kohteet->aktiivinen = 1;

			if(!empty($asiakkaat->yrityksen_nimi))
				$kohteet->etu_suku_nimet =$asiakkaat->yrityksen_nimi;
			elseif(empty($asiakkaat->yrityksen_nimi) and !empty($asiakkaat->yhteyshenkilo))
				$kohteet->etu_suku_nimet = $asiakkaat->yhteyshenkilo;

			if($_POST['onkoAsOsoiteSamaKunKohde'] == 'ei')
				$kohteet->osoite = $_POST['kohteenOsoite'];
			else
				$kohteet->osoite = $asiakkaat->osoite;

			$kohteet->puh_nro = $asiakkaat->puhelin;
			$kohteet->pnumero = $asiakkaat->postinumero;
			$kohteet->kaupunki = $asiakkaat->kaupunki;
			$kohteet->email = $asiakkaat->sahkoposti;

			$toimenpiteet = $_POST['Tyovuoroot']['toimenpiteet'];
			if(isset($_POST['onkoKokeilusiivous']) and !empty($_POST['onkoKokeilusiivous']))
			$toimenpiteet .= "\n".Yii::t('main', 'Onko kokeilusiivous').": ".$_POST['onkoKokeilusiivous']."\n";
			if(isset($_POST['oven_avaaminen']) and !empty($_POST['oven_avaaminen']))
			$toimenpiteet .= "\n".Yii::t('main', 'Oven avaaminen').": ".$_POST['oven_avaaminen']."\n";
			if(isset($_POST['mihin_avain_palautetaan']) and !empty($_POST['mihin_avain_palautetaan']))
			$toimenpiteet .= "\n".Yii::t('main', 'Mihin avain palautetaan').": ".$_POST['mihin_avain_palautetaan']."\n";
			if(isset($_POST['mihin_pysakoida_auto']) and !empty($_POST['mihin_pysakoida_auto']))
			$toimenpiteet .= "\n".Yii::t('main', 'Mihin työntekijä voi pysäköidä auton').": ".$_POST['mihin_pysakoida_auto']."\n";
			if(isset($_POST['onkoMaksajanTiedotSama']) and $_POST['onkoMaksajanTiedotSama'] == 'Ei'){
			$toimenpiteet .= "\n".Yii::t('main', 'Maksajan tiedot sama kuin tilaaja').": ".$_POST['onkoMaksajanTiedotSama']."\n";
			$toimenpiteet .= Yii::t('main', 'Maksajan tiedot').": ".$_POST['MaksajanTiedot']."\n";
			}
			if(isset($_POST['LahjakortinNumero']) and !empty($_POST['LahjakortinNumero']))
			$toimenpiteet .= "\n".Yii::t('main', 'Lahjakortin numero').": ".$_POST['LahjakortinNumero']."\n";



			$kohteet->toimenpiteet = $toimenpiteet;

			$kohteet->muut = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']))."\n".date("H:i",strtotime($_POST['Tyovuoroot']['alku']))."-".date("H:i",strtotime($_POST['Tyovuoroot']['loppu']))."\nHinta: ".$asiakkaat->hinta;

		  	   if($kohteet->save())
		  	   {

				// <-- LOG
				$model_log 	= 'Kohteet';
				$name_log 	= 'Kohteet';
				$status_log 	= 'Create';
					$old_values = null;
					$n_m = Kohteet::model()->findbypk($kohteet->id);
					$new_values = json_encode($n_m->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

				$model->attributes=$_POST['Tyovuoroot'];
				if( is_array($model->lisa_tuotteet) and count($model->lisa_tuotteet) > 0 ){
					$model->lisa_tuotteet = json_encode($model->lisa_tuotteet);
				} else {
					$model->lisa_tuotteet = '';
				}
				if( is_array($model->tyopaari) and count($model->tyopaari) > 0 ){
					$model->tyopaari = json_encode($model->tyopaari);
				} else {
					$model->tyopaari = '';
				}
				$model->kohde = $kohteet->id;
				$model->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				if($model->save())
				{
				
				// <-- LOG
				$model_log 	= 'Tyovuoroot';
				$name_log 	= 'Työvuorot';
				$status_log 	= 'Create';
				if(isset($_POST[$model_log]))
				{
					$old_values = null;
					$n_m = Tyovuoroot::model()->findbypk($model->id);
					$new_values = json_encode($n_m->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->


			// <-- jos on tyopaari
			$luotu = array();
			if(isset($_POST['tyopaari']) and count($_POST['tyopaari']) > 0)
			{

			    $luotu[$model->id] = $model->tid;

			    foreach($_POST['tyopaari'] as $tid)
			    {
				$m=new Tyovuoroot;
				$m->attributes=$_POST['Tyovuoroot'];
				$m->tyopaari = $model->tyopaari;
				$m->lisa_tuotteet = $model->lisa_tuotteet;
				$m->kohde = $kohteet->id;
				$m->pvm = date("d.m.Y",strtotime($_POST['Tyovuoroot']['pvm']));
				$m->tid=$tid;
				$m->status=3;
				if($m->save())
				{
					$luotu[$m->id] = $m->tid;
					$return[] = array('tid'=>$m->tid, 'pvm'=>$m->pvm, 'ymd'=>date("Ymd",strtotime($m->pvm)));

				}

			    }
			    foreach($luotu as $k=>$v)
					Tyovuoroot::model()->updatebypk($k, array('tyopaari' => json_encode($luotu)));


			}
			// jos on tyopaari -->

				$sum = 0;
				$alv_0 = 0;
				$alv_sum = 0;
				$yht_alv_nolla = 0;
				$yht_alv = 0;
				$yht_alv_sis = 0;
				   if(isset($_POST['vieposti']) and isset($asiakkaat->sahkoposti) and !empty($asiakkaat->sahkoposti))
				   {
					$message = '<div>';
					$message .= '
					Asiakas: '.$asiakkaat->yhteyshenkilo.'<br>
					Työvuorot:  '.$model->pvm.', '.$model->alku.'-'.$model->loppu.'<br>';
					$message .= '<style>.lahetys_taulu table {border-collapse: collapse; border: 1px solid grey;} .lahetys_taulu th, .lahetys_taulu td{border: 1px solid grey; padding: 7px 15px;}</style>';

					// <-- Paatuote
					$tp = TuotteetPalvelut::model()->findByPK($model->tuoteID);
					if( isset($tp->id) ){
					$message .= '<table class="lahetys_taulu">';
					$message .= '<tr>';
					$message .= '<th>Tuote/Palvelu</th>';
					$message .= '<th>Työntekijät</th>';
					$message .= '<th>Tunnit</th>';
					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<th>Tuntihinta</th>';
					$message .= '<th>Hinta</th>';
					$message .= '<th>ALV</th>';
					$message .= '<th>Yhteensä</th>';
					}
					$message .= '</tr>';

						$tp_maara = (isset($_POST['tyopaari']))?(count($_POST['tyopaari'])+1):1;
						$return_hinnaasto = $this->hinnastoHintaat($tp, $asiakkaat, $kohteet);
						$maara 	= $this->num( strtotime($model->loppu)-strtotime($model->alku) );
						$tunti_hinta = $return_hinnaasto['hinta_alv_0'];
						$hinta_alv_0 = ($return_hinnaasto['hinta_alv_0']*($maara*$tp_maara));
						$hinta_alv_sis = ($return_hinnaasto['hinta_alv_sis']*($maara*$tp_maara));
						$alv = ($hinta_alv_sis-$hinta_alv_0);
						$yht_alv_nolla += $hinta_alv_0;
						$yht_alv += $alv;
						$yht_alv_sis += $hinta_alv_sis;

						if(isset($return_hinnaasto['tp_nimike']) and isset($return_hinnaasto['hinta_alv_0'])){
						$message .= '<tr>';
						$message .= '<td>'.$return_hinnaasto['tp_nimike'].'</td>';
						$message .= '<td>'.$tp_maara.'</td>';
						$message .= '<td>'.$maara.'</td>';
						if(isset($_POST['vie_hintatietoja'])){
						$message .= '<td>'.number_format($tunti_hinta, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($hinta_alv_0, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($alv, 2, ',', ' ').'</td>';
						$message .= '<td>'.number_format($hinta_alv_sis, 2, ',', ' ').'</td>';
						}
						$message .= '</tr>';
						}
					$message .= '</table>';
					}
					//     Paatuote -->

					if(isset($_POST['vie_hintatietoja']) and !empty($asiakkaat->hinta) and $asiakkaat->hinta_tyyppi == 1)
					{
						$tp_maara = (isset($_POST['tyopaari']))?(count($_POST['tyopaari'])+1):1;
						$tuntia = ((strtotime($model->loppu)-strtotime($model->alku))/3600);
						if( count($luotu) > 0 )
						$tuntia = $tuntia * count($luotu);

						$sum = (($asiakkaat->hinta*$tuntia) + ((($asiakkaat->hinta*$asiakkaat->alv)/100)*$tuntia))*$tp_maara;
						$alv_0 = ($asiakkaat->hinta*$tuntia)*$tp_maara;
						$alv_sum = $sum-$alv_0;

						$message .= 'Työntekijät: '.$tp_maara.'<br>';
						$message .= 'Hinta ALV 0: '.number_format($alv_0, 2, ',', ' ').' &euro;<br>';
						$message .= 'ALV: '.number_format($alv_sum, 2, ',', ' ').' &euro;<br>';
						$message .= 'Hinta: '.number_format($sum, 2, ',', ' ').' &euro;<br>';
						$yht_alv_nolla += $alv_0;
						$yht_alv += $alv_sum;
						$yht_alv_sis += $sum;
					}

					// <-- Lisatuotteet
					$lisa_tuotteet = json_decode($model->lisa_tuotteet, true);
					if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote'])  ){
					$message .= '<table class="lahetys_taulu">';
					$message .= '<tr>';
					$message .= '<th>Tuote/Palvelu</th>';
					$message .= '<th>Määrä</th>';
					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<th>Hinta</th>';
					$message .= '<th>ALV</th>';
					$message .= '<th>Yhteensä</th>';
					}
					$message .= '</tr>';
					   foreach($lisa_tuotteet['tuote'] as $k => $v){
						$tp = TuotteetPalvelut::model()->findByPK($v);
						if( isset($tp->id) ){
							$return_hinnaasto = $this->hinnastoHintaat($tp, $asiakkaat, $kohteet);
							$maara = json_decode($model->lisa_tuotteet, true)['maara'][$k];
							$hinta_alv_0 = $return_hinnaasto['hinta_alv_0']*$maara;
							$hinta_alv_sis = $return_hinnaasto['hinta_alv_sis']*$maara;
							$alv	= ($hinta_alv_sis-$hinta_alv_0);
							$yht_alv_nolla += $hinta_alv_0;
							$yht_alv += $alv;
							$yht_alv_sis += $hinta_alv_sis;

							if(isset($return_hinnaasto['tp_nimike']) and isset($return_hinnaasto['hinta_alv_0'])){
							$message .= '<tr>';
							$message .= '<td>'.$return_hinnaasto['tp_nimike'].'</td>';
							$message .= '<td>'.$maara.'</td>';
							if(isset($_POST['vie_hintatietoja'])){
							$message .= '<td>'.number_format($hinta_alv_0, 2, ',', ' ').'</td>';
							$message .= '<td>'.number_format($alv, 2, ',', ' ').'</td>';
							$message .= '<td>'.number_format($hinta_alv_sis, 2, ',', ' ').'</td>';
							}
							$message .= '</tr>';
							}
						}
					   }
					$message .= '</table>';
					}
					//     Lisatuotteet -->

					if(isset($_POST['vie_hintatietoja'])){
					$message .= '<br>';
					$message .= '<h3>Veroton hinta '.number_format($yht_alv_nolla, 2, ',', ' ').'&euro;<br>';
					$message .= 'ALV '.number_format($yht_alv, 2, ',', ' ').'&euro;<br>';
					$message .= 'Hinta ALV sis. '.number_format($yht_alv_sis, 2, ',', ' ').'&euro;<br>';
					$message .= '</h3><br>';
					}


					if(!empty($model->toimenpiteet))
					$message .= str_replace("\n", "<hr><br>",$model->toimenpiteet)."<br>";

					if(isset($_POST['Tyovuoroot']['tilausviesti']) and !empty($_POST['Tyovuoroot']['tilausviesti']))
					$message .= str_replace("\n", "<br>", $_POST['Tyovuoroot']['tilausviesti']);

					$message .= '<h2>Kiitos tilauksesta.</h2>';
					$message .= '</div>';
					$subject = Yii::t('main', 'Kiitos tilauksesta');

					$ft = FirmanTiedot::model()->findByPk(1);
					$mail = new YiiMailer();
					//$mail->clearLayout();//if layout is already set in config
					$mail->setFrom('no-reply@etunti.fi');
					$mail->setTo($asiakkaat->sahkoposti);
					$mail->setSubject($subject);
					$mail->setBody($message);

					foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/toimitusehdot*')) as $file) {
						$mail->setAttachment($file);
						//break;
					}

					if($mail->send())
					{


							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $asiakkaat->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->log_nimike	= 'uusi_tilaus';
							$log->save();
							//     LOG -->
					}
				   }
				
					$return[] = array('tid'=>$model->tid, 'pvm'=>$model->pvm, 'ymd'=>date("Ymd",strtotime($model->pvm)), 'alv'=>$alv_sum, 'alv_0' => $alv_0, 'sum' => $sum);
				  	echo json_encode($return);
					exit;
				}
			   } else { // Kohde save error

			  	echo json_encode($kohteet->getErrors());
			  	exit;

			   }


		  } else { // Asiakas save error

		  	echo json_encode($asiakkaat->getErrors());
		  	exit;

		  }

		  echo json_encode('Error');
		  exit;
		}

	if(!isset($_POST['Tyovuoroot']))
	{
		$this->renderPartial('uusitilaus',array(
			'model'=>$model,
		));
	?>
          </div>
          <!-- end: .panel -->
        </div>
        <!-- end: .admin-form -->
	<?php
	}
	}


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
		if( !isset(Yii::app()->session['ov_poisto']) ){
			$this->poistaminenOnlineVarauksetJokaMeniOhi();
			Yii::app()->session['ov_poisto'] = 'suorittu';
		}
		$kohteet_siivous = array();

		// <-- Reset
		if(isset($_GET['reset']))
		{
			unset(Yii::app()->session['year']);
			unset(Yii::app()->session['week']);
			unset(Yii::app()->session['vkolopput']);
			unset(Yii::app()->session['asiakas']);
			unset(Yii::app()->session['kohde']);
			unset(Yii::app()->session['tyontekijat']);
			unset(Yii::app()->session['tyo_toimialue']);
			unset(Yii::app()->session['kohteiden_tyonimike']);
			unset(Yii::app()->session['tyoryhma']);

			$this->redirect(array('index'));
		}
		//     Reset -->


		// <-- GET haku
		if(isset($_GET['year']) or isset($_GET['week']))
		{

			if(isset($_GET['year']) and !empty($_GET['year']))
				Yii::app()->session['year'] = $_GET['year'];
			
			if(isset($_GET['week']) and !empty($_GET['week']))
				Yii::app()->session['week'] = $_GET['week'];

			if(isset($_GET['tid']) and !empty($_GET['tid']))
				Yii::app()->session['tyontekijat'] = array($_GET['tid']);

			if(isset($_GET['tv_id'])){ $this->redirect(array('index', 'tv_id' => $_GET['tv_id'])); } 
			$this->redirect(array('index'));
		}		
		//  GET haku -->


		// <-- Post haku
		if(isset($_POST['haku']))
		{

			if(isset($_POST['kohteiden_tyonimike']) and !empty($_POST['kohteiden_tyonimike']))
				Yii::app()->session['kohteiden_tyonimike'] = $_POST['kohteiden_tyonimike'];
			if(isset($_POST['kohteiden_tyonimike']) and empty($_POST['kohteiden_tyonimike']))
				unset(Yii::app()->session['kohteiden_tyonimike']);

			if(isset($_POST['tyo_toimialue']) and !empty($_POST['tyo_toimialue']))
				Yii::app()->session['tyo_toimialue'] = $_POST['tyo_toimialue'];
			if(!isset($_POST['tyo_toimialue']))
				unset(Yii::app()->session['tyo_toimialue']);

			if(isset($_POST['tyoryhma']) and !empty($_POST['tyoryhma']))
				Yii::app()->session['tyoryhma'] = $_POST['tyoryhma'];
			if(!isset($_POST['tyoryhma']))
				unset(Yii::app()->session['tyoryhma']);

			// <-- Asiakas
			if(isset($_POST['asiakas']) and !empty($_POST['asiakas']))
				Yii::app()->session['asiakas'] = $_POST['asiakas'];
			if(isset($_POST['asiakas']) and empty($_POST['asiakas']))
				unset(Yii::app()->session['asiakas']);
			// Asiakas -->
	
			// <-- Kohde
			if(isset($_POST['kohde']) and !empty($_POST['kohde']))
				Yii::app()->session['kohde'] = $_POST['kohde'];
			if(isset($_POST['kohde']) and empty($_POST['kohde']))
				unset(Yii::app()->session['kohde']);
			// Kohde -->
	
			// <-- tyontekijat
			if(isset($_POST['tyontekijat']) and !empty($_POST['tyontekijat']))
				Yii::app()->session['tyontekijat'] = $_POST['tyontekijat'];
			if(!isset($_POST['tyontekijat']))
				unset(Yii::app()->session['tyontekijat']);
			//  tyontekijat -->

			if(isset($_POST['year']) and !empty($_POST['year']))
				Yii::app()->session['year'] = $_POST['year'];
			
			if(isset($_POST['week']) and !empty($_POST['week']))
				Yii::app()->session['week'] = $_POST['week'];





			$this->redirect(array('index'));
		}		
		//  Post haku -->


		// <-- Year Week
		if(!isset(Yii::app()->session['year']))
			Yii::app()->session['year'] = date("Y");

		if(!isset(Yii::app()->session['week']))
			Yii::app()->session['week'] = date("W");

		$year = Yii::app()->session['year'];
		$week = sprintf("%02d", Yii::app()->session['week']);
		Yii::app()->session['week'] = $week;
		//    Year Week -->


		if(!isset(Yii::app()->session['vkolopput']))
			$numDays = 5;
		else
			$numDays = 7;


		Yii::app()->session['from'] = date("Y-m-d", strtotime($year ."W". $week.'1'));
		Yii::app()->session['to'] = date("Y-m-d", strtotime($year ."W". $week . $numDays));


       		$criteria = new CDbCriteria();

		// <-- Oletus arvot
		if(!isset(Yii::app()->session['tyontekijat']))
		{

			// <-- Return order etu ja sukunimella
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->etuSukunimiCriteria($criteria);
			//     Return order etu ja sukunimella -->

	        	$criteria->select = "id,tekijan_nimi, sukunimi";
	        	$criteria->condition = ' aktiivinen=1 ';

			// <-- Tyoryhmat
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			} 
			//    Tyoryhmat -->

			$tt = Tyontekijat::model()->findAll($criteria);
			$tekijatOletuksena = array();
			foreach($tt as $t)
			$tekijatOletuksena[] = $t->id;
	
			Yii::app()->session['tyontekijat'] = $tekijatOletuksena;
		}
		// Oletus arvot -->



		if(Yii::app()->session['tyontekijat'])
		{

			// <-- Return order etu ja sukunimella
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->etuSukunimiCriteria($criteria);
			//     Return order etu ja sukunimella -->


        		$criteria->select = "id,tekijan_nimi, sukunimi, tyoryhma";
        		$criteria->condition = " aktiivinen = '1' ";

		    	if(count(Yii::app()->session['tyontekijat'] > 1))
		      	$ids = implode(",", Yii::app()->session['tyontekijat']);
		    	else
		      	$ids = Yii::app()->session['tyontekijat'][0];


	        	$criteria->addCondition ('id IN ('.$ids.') ');
		}


		// <-- kohteiden_tyonimike
		if(isset(Yii::app()->session['kohteiden_tyonimike']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%'
		       )
		   )
		   ");

			$criteriaK = new CDbCriteria();
	       		$criteriaK->select = "id";
	       		$criteriaK->condition = " 
				siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%' 
				AND id IN(
					SELECT kohde FROM sivex_tvuoro 
					WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     			BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
				)
			";
			$k = Kohteet::model()->findAll($criteriaK);
			foreach($k as $kohde)
			 $kohteet_siivous[] = $kohde->id;

		}
		//   kohteiden_tyonimike -->


		// <-- tyo_toimialue
		if(isset(Yii::app()->session['tyo_toimialue']))
		{

		   $arr = array();
		   foreach(Yii::app()->session['tyo_toimialue'] as $it)
		   {
			$arr[] = str_replace("\\", "\\\\\\\\", json_encode($it));
		   }

		   $tyo_toimialue_like = "tyo_toimialue LIKE '%".implode("%' OR tyo_toimialue LIKE '%", $arr)."%'";
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		   )
		   AND ($tyo_toimialue_like)
		   ");
		}
		//   tyo_toimialue -->

		// <-- tyoryhma
		if(isset(Yii::app()->session['tyoryhma']))
		{
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(Yii::app()->session['tyoryhma']);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			}
		}
		//   tyoryhma -->

		// <-- Asiakas
		if(isset(Yii::app()->session['asiakas']))

		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE yrityksen_nimi 
					LIKE '%".Yii::app()->session['asiakas']."%' 
					OR yhteyshenkilo LIKE '%".Yii::app()->session['asiakas']."%' 
					OR puhelin LIKE '%".Yii::app()->session['asiakas']."%'
			    )
		       )
		   )
		   ");
		}
		// Asiakas -->

		// <-- Kohde
		if(isset(Yii::app()->session['kohde']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE osoite 
					LIKE '%".Yii::app()->session['kohde']."%' 
					OR puh_nro LIKE '%".Yii::app()->session['kohde']."%'
		       )
		   )
		   ");
		}
		//  Kohde -->

		$tyontekijat_model = Tyontekijat::model()->findAll($criteria);

		(isset(Yii::app()->session['asiakas'])) ? 	$asiakas = Yii::app()->session['asiakas'] : $asiakas ='';
		(isset(Yii::app()->session['kohde'])) ? 	$kohde = Yii::app()->session['kohde'] : $kohde ='';

		$this->render('index', array(
			'tyontekijat_model'	=>$tyontekijat_model,
			'tyontekijat'		=>Yii::app()->session['tyontekijat'],
			'year'			=>$year,
			'week'			=>$week,
			'numDays'		=>$numDays,
			'kohteet_siivous'	=>$kohteet_siivous,
			'asiakas'		=>$asiakas,
			'kohde'			=>$kohde,
			//'wkMaara'		=>$wkMaara,
		));

	}

	public function actionTv2()
	{
		if( !isset(Yii::app()->session['ov_poisto']) ){
			$this->poistaminenOnlineVarauksetJokaMeniOhi();
			Yii::app()->session['ov_poisto'] = 'suorittu';
		}

		$kohteet_siivous = array();

		// <-- Reset
		if(isset($_GET['reset']))
		{
			unset(Yii::app()->session['from']);
			unset(Yii::app()->session['to']);
			unset(Yii::app()->session['asiakas']);
			unset(Yii::app()->session['kohde']);
			unset(Yii::app()->session['tyontekijat']);
			unset(Yii::app()->session['tyo_toimialue']);
			unset(Yii::app()->session['kohteiden_tyonimike']);
			unset(Yii::app()->session['tyoryhma']);

			$this->redirect(array('tv2'));
		}
		//     Reset -->

		// <-- Post haku
		if(isset($_POST['haku']))
		{

			if(isset($_POST['kohteiden_tyonimike']) and !empty($_POST['kohteiden_tyonimike']))
				Yii::app()->session['kohteiden_tyonimike'] = $_POST['kohteiden_tyonimike'];
			if(isset($_POST['kohteiden_tyonimike']) and empty($_POST['kohteiden_tyonimike']))
				unset(Yii::app()->session['kohteiden_tyonimike']);

			if(isset($_POST['tyo_toimialue']) and !empty($_POST['tyo_toimialue']))
				Yii::app()->session['tyo_toimialue'] = $_POST['tyo_toimialue'];
			if(!isset($_POST['tyo_toimialue']))
				unset(Yii::app()->session['tyo_toimialue']);

			if(isset($_POST['tyoryhma']) and !empty($_POST['tyoryhma']))
				Yii::app()->session['tyoryhma'] = $_POST['tyoryhma'];
			if(!isset($_POST['tyoryhma']))
				unset(Yii::app()->session['tyoryhma']);

			// <-- Asiakas
			if(isset($_POST['asiakas']) and !empty($_POST['asiakas']))

				Yii::app()->session['asiakas'] = $_POST['asiakas'];
			if(isset($_POST['asiakas']) and empty($_POST['asiakas']))
				unset(Yii::app()->session['asiakas']);
			// Asiakas -->
	
			// <-- Kohde
			if(isset($_POST['kohde']) and !empty($_POST['kohde']))
				Yii::app()->session['kohde'] = $_POST['kohde'];
			if(isset($_POST['kohde']) and empty($_POST['kohde']))
				unset(Yii::app()->session['kohde']);
			// Kohde -->
	
			// <-- tyontekijat
			if(isset($_POST['tyontekijat']) and !empty($_POST['tyontekijat']))
				Yii::app()->session['tyontekijat'] = $_POST['tyontekijat'];
			if(!isset($_POST['tyontekijat']))
				unset(Yii::app()->session['tyontekijat']);
			//  tyontekijat -->

			if(isset($_POST['from']) and !empty($_POST['from']))
				Yii::app()->session['from'] = date("Y-m-d",strtotime($_POST['from']));
	
			if(isset($_POST['to']) and !empty($_POST['to']))
				Yii::app()->session['to'] = date("Y-m-d",strtotime($_POST['to']));


			$this->redirect(array('tv2'));
		}		
		//  Post haku -->


		if(!isset(Yii::app()->session['from']))
			Yii::app()->session['from'] = date("Y-m-d");
		if(!isset(Yii::app()->session['to']))
			Yii::app()->session['to'] = date("Y-m-d",strtotime("+1 month", time()));



		$asetukset = Asetukset::model()->findByPk(1);
       		$criteria = new CDbCriteria();

		// <-- Oletus arvot
		if(!isset(Yii::app()->session['tyontekijat']))
		{

			// <-- Return order etu ja sukunimella
			$site = Yii::app()->createController('Site');
			$criteria = $site[0]->etuSukunimiCriteria($criteria);
			//     Return order etu ja sukunimella -->

	        	$criteria->select = "id,tekijan_nimi";
	        	$criteria->condition = ' aktiivinen=1 ';

			// <-- Tyoryhmat
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			} 
			//    Tyoryhmat -->

			$tt = Tyontekijat::model()->findAll($criteria);
			$tekijatOletuksena = array();
			foreach($tt as $t)
			$tekijatOletuksena[] = $t->id;
	
			Yii::app()->session['tyontekijat'] = $tekijatOletuksena;
		}
		// Oletus arvot -->


		if(Yii::app()->session['tyontekijat'])
		{

			if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0)
				$criteria->order = " tekijan_nimi ";
			else
				$criteria->order = " sukunimi ";


        		$criteria->select = "id,tekijan_nimi";
        		$criteria->condition = " aktiivinen = '1' ";

		    	if(count(Yii::app()->session['tyontekijat'] > 1))
		      	$ids = implode(",", Yii::app()->session['tyontekijat']);
		    	else
		      	$ids = Yii::app()->session['tyontekijat'][0];

	        	$criteria->addCondition ('id IN ('.$ids.') ');
		}



		// <-- kohteiden_tyonimike
		if(isset(Yii::app()->session['kohteiden_tyonimike']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%'
		       )
		   )
		   ");

			$criteriaK = new CDbCriteria();
	       		$criteriaK->select = "id";
	       		$criteriaK->condition = " 
				siivous LIKE '%".Yii::app()->session['kohteiden_tyonimike']."%' 
				AND id IN(
					SELECT kohde FROM sivex_tvuoro 
					WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     			BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
				)
			";
			$k = Kohteet::model()->findAll($criteriaK);
			foreach($k as $kohde)
			 $kohteet_siivous[] = $kohde->id;

		}
		//   kohteiden_tyonimike -->

		// <-- tyo_toimialue
		if(isset(Yii::app()->session['tyo_toimialue']))
		{

		   $tyo_toimialue_like = "tyo_toimialue LIKE '%".implode("%' OR tyo_toimialue LIKE '%", Yii::app()->session['tyo_toimialue'])."%'";
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND tid IN 
		       (
			    SELECT id FROM sivex_ttekijat WHERE $tyo_toimialue_like
		       )
		   )
		   ");
		}
		//   tyo_toimialue -->

		// <-- tyoryhma
		if(isset(Yii::app()->session['tyoryhma']))
		{
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(Yii::app()->session['tyoryhma']);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		        	$criteria->addCondition (" id IN ($ids) ");
			}
		}
		//   tyoryhma -->

		// <-- Asiakas
		if(isset(Yii::app()->session['asiakas']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE yrityksen_nimi 
					LIKE '%".Yii::app()->session['asiakas']."%' 
					OR yhteyshenkilo LIKE '%".Yii::app()->session['asiakas']."%' 
					OR puhelin LIKE '%".Yii::app()->session['asiakas']."%'
			    )
		       )
		   )
		   ");
		}
		// Asiakas -->

		// <-- Kohde
		if(isset(Yii::app()->session['kohde']))
		{
	           $criteria->addCondition ("
		   id IN (  
		     SELECT tid FROM sivex_tvuoro WHERE DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
		     BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."'
		       AND kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE osoite 
					LIKE '%".Yii::app()->session['kohde']."%' 
					OR puh_nro LIKE '%".Yii::app()->session['kohde']."%'
		       )
		   )
		   ");
		}
		//  Kohde -->

		$tyontekijat_model = Tyontekijat::model()->findAll($criteria);


		(isset(Yii::app()->session['asiakas'])) ? 	$asiakas = Yii::app()->session['asiakas'] : $asiakas ='';
		(isset(Yii::app()->session['kohde'])) ? 	$kohde = Yii::app()->session['kohde'] : $kohde ='';

		$this->render('tv2', array(
			'tyontekijat_model'	=>$tyontekijat_model,
			'from'			=>Yii::app()->session['from'],
			'to'			=>Yii::app()->session['to'],
			'tyontekijat'		=>Yii::app()->session['tyontekijat'],
			'kohteet_siivous'	=>$kohteet_siivous,
			'asiakas'		=>$asiakas,
			'kohde'			=>$kohde,
		));
	}

	public function actionTv_kohteet()
	{
		$this->poistaminenOnlineVarauksetJokaMeniOhi();
		$this->render('tv_kohteet');
	}

	protected function poistaminenOnlineVarauksetJokaMeniOhi()
	{

		$asetukset = Asetukset::model()->findByPk(1);
		// <-- Poistaminen
		$criteria=new CDbCriteria;
		$criteria->order= " id DESC "; 
		$criteria->condition= " 
			(time + INTERVAL ".$asetukset->onlinevaraus_autoremove." MINUTE) < NOW()
			AND osoiteOnline=1
		";
		$poistaminen = Tyovuoroot::model()->findAll($criteria);

		foreach($poistaminen as $item){

				$tv = Tyovuoroot::model()->findByPk($item->id);
				// <-- LOG
				$model_log 	= 'Tyovuoroot';
				$name_log 	= 'Työvuorot';
				$status_log 	= 'Auto Delete';
	
					$old_values = json_encode($tv->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
		}

		$criteria=new CDbCriteria;
		$criteria->order= " id DESC "; 
		$criteria->condition= " 
			(time + INTERVAL ".$asetukset->onlinevaraus_autoremove." MINUTE) < NOW()
			AND osoiteOnline=1
		";
		$tv_pois = Tyovuoroot::model()->findAll($criteria);

						foreach($tv_pois as $item){
							$tv = Tyovuoroot::model()->findbypk($item->id);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
						}

		// Poistaminen -->

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tyovuoroot('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyovuoroot']))
			$model->attributes=$_GET['Tyovuoroot'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyovuoroot the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyovuoroot::model()->findByPk($id);
		if($model===null)
		{
			$tilanne = '('.date("d.m.Y H:i").' - '.Yii::app()->user->nimi.'): Työvuoroja '.$id.' ei löydy.';
			$log=new Log;
			$log->log_category 	= 3;
			$log->kuka 		= Yii::app()->user->nimi;
			$log->log_nimike	= 'error';
			$log->model		= 'Tyovuoroot';
			$log->tilanne		= $tilanne;
			$log->save();

			//throw new CHttpException(404, $tilanne);
			echo $tilanne;
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyovuoroot $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyovuoroot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
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

	public function tilanteet()
	{
        	$l = array(
			3=>Yii::t('main', 'Työ'),
			2=>Yii::t('main', 'Matka'),
			10=>Yii::t('main', 'Lounastauko'),
			11=>Yii::t('main', 'Lomat ja poissaolot')
		);
		return $l;
	}


	protected function toistuvaInsert($id, $attr, $tid, $viikko_paivat, $tyopaari, $saankoSuoritta, $edellinenToistuva)
	{

		$suoritettu_ids = array(); // TUORE
		$fi = $this->vkoPaivat();
		$tt = Tyontekijat::model()->findByPk($tid);
		
		// <-- Tsekataan poistettut PVM
		$poistettu_pvm = array();
		$toistuva = ToistuvatTyovuorot::model()->findByPk($attr->id);
		if(isset($toistuva->poistettu_pvm) and is_array(json_decode($toistuva->poistettu_pvm, true)))
		{
			$poistettu_pvm = json_decode($toistuva->poistettu_pvm, true);
		}
		//     Tsekataan poistettut PVM -->

		$startDate	= date("Y-m-d", strtotime($attr->pfrom));
		$end_date	= date("Y-m-d", strtotime($attr->pto));

		$weeks = new DatePeriod(
		    new DateTime($startDate), 
		    new DateInterval('P'.$attr->viikkoja.'W'), 
		    new DateTime($end_date)
		);
		$sopivaViikot = [];
		foreach ($weeks as $wk) {
			$sopivaViikot[$wk->format('YW')] = $wk->format('YW');
		}

		$w		= $viikko_paivat;
		$return 	= array();
		$tyopaariUpdater = array();
		$date		= date("d.m.Y", strtotime($startDate));
 		while (strtotime($date) <= strtotime($end_date)) {

			$viikonNumero = (date('YW',strtotime($date)));

	                if( 
				in_array(date('N',strtotime($date)),$w) 
				and in_array($viikonNumero,$sopivaViikot) 
			)
			{
				$pvm = $date;
				//$return[] = array('tid'=>$tid, 'pvm'=>$pvm, 'ymd'=>date("Ymd",strtotime($pvm)));

				$tekijan_nimi='';
				if(isset($tt->tekijan_nimi) and $tid!=0)
					$tekijan_nimi=$this->etuSukunimi($tid);
				elseif(!isset($tt->tekijan_nimi) and $tid==0)
					$tekijan_nimi='VARAUS';

				if(in_array($pvm, $poistettu_pvm))
				{
							$return[] = array(
								'tid'=>$tid, 
								'pvm'=>$pvm, 
								'ymd'=>date("Ymd",strtotime($pvm)), 
								'isSaved'=>false, 
								'tekijan_nimi'=>$tekijan_nimi, 
								'vkopvm' => $fi[date("N",strtotime($pvm))],
								'toistuva_id'=>$attr->id,
								'otettu_pois'=>true 
							);
				} else {

					$tilanne = 'uusi';
					$chk_toistuvat_tv = Tyovuoroot::model()->find(" tid='".$tid."' AND pvm='".$pvm."' AND toistuva_id!=0 AND toistuva_id='".$attr->id."' ");
					$chk_oleva_tv = Tyovuoroot::model()->find(" tid='".$tid."' AND pvm='".$pvm."' AND id='".$id."' ");
					if( isset($chk_toistuvat_tv->id) ){
						$t = $chk_toistuvat_tv;
						$tilanne = 'muokkaus';
					} elseif( isset($chk_oleva_tv->id) ){
						$t = $chk_oleva_tv;
						$tilanne = 'muokkaus';
					} else {
						$t = new Tyovuoroot;
					}
					$t->attributes = $attr->attributes;
					$t->tid = $tid;
					$t->pvm = $pvm;
					$t->tyopaari = $tyopaari;
					$t->toistuva_id = $attr->id;
					if($saankoSuoritta == 1)
					{
						if($t->save())
						{
							$return[] = array(
								'tid'=>$t->tid, 
								'pvm'=>$t->pvm, 
								'ymd'=>date("Ymd",strtotime($t->pvm)), 
								'isSaved'=>true, 
								'tvuoro_id'=>$t->id
							);

						} else {
							$return[] = array('ERROR'=>json_encode(var_dump($t->getErrors())));
						}

					} else {
							$return[] = array(
								'tid'=>$tid, 
								'pvm'=>$pvm, 
								'ymd'=>date("Ymd",strtotime($pvm)), 
								'isSaved'=>false, 
								'tekijan_nimi'=>$tekijan_nimi, 
								'vkopvm' => $fi[date("N",strtotime($pvm))], 
								'tilanne' => $tilanne 
							);

					}

					if( isset($t->id) ){	$suoritettu_ids[] = $t->id; }

				} // if otettu pois

			}
	                $date = date ("d.m.Y", strtotime("+1 day", strtotime($date)));
		}

		//$return[] = array('ERROR' => json_encode($tid." ".$toistuva->tid));
		if( count($suoritettu_ids) > 0 ){
			$tilanne = 'poistetaan';
			$ids = implode(",", $suoritettu_ids);
			$criteria_1 = new CDBcriteria;
			$criteria_1->condition=" 
				tid='".$tid."'
				AND toistuva_id!=0
				AND toistuva_id='".$attr->id."'
				AND id NOT IN ($ids)
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
			";
			$pois_1 = Tyovuoroot::model()->findAll($criteria_1);
			if( isset($edellinenToistuva->pfrom) and strtotime($attr->pfrom) > strtotime($edellinenToistuva->pfrom) and !isset($_POST['poisto_alkaen_taaksepain']) ){
				$tilanne = 'pois_ketjusta';
			}

			foreach($pois_1 as $item){
				$return[] = array(
					'tid'=>$item->tid, 
					'pvm'=>$item->pvm, 
					'ymd'=>date("Ymd",strtotime($item->pvm)), 
					'isSaved'=>false, 
					'tekijan_nimi' => $this->etuSukunimi($item->tid), 
					'vkopvm' => $fi[date("N",strtotime($item->pvm))], 
					'tilanne' => $tilanne
				);
			}
			if($saankoSuoritta == 1){
			$tv_pois = Tyovuoroot::model()->findAll($criteria_1);
						foreach($tv_pois as $item){
							$tv = Tyovuoroot::model()->findbypk($item->id);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
						}
			}
		}

		if( isset($edellinenToistuva->tid) and $tid != $edellinenToistuva->tid and empty($tyopaari) ){
			$criteria_2 = new CDBcriteria;
			$criteria_2->condition=" 
				tid='".$edellinenToistuva->tid."'
				AND toistuva_id!=0
				AND toistuva_id='".$attr->id."'
				AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') >= CURDATE()
			";
			$pois_2 = Tyovuoroot::model()->findAll($criteria_2);
			foreach($pois_2 as $item){
				$return[] = array(
					'tid'=>$item->tid, 
					'pvm'=>$item->pvm, 
					'ymd'=>date("Ymd",strtotime($item->pvm)), 
					'isSaved'=>false, 
					'tekijan_nimi' => $this->etuSukunimi($item->tid), 
					'vkopvm' => $fi[date("N",strtotime($item->pvm))], 
					'tilanne' => 'poistetaan'
				);
			}
			if($saankoSuoritta == 1){
			$tv_pois = Tyovuoroot::model()->findAll($criteria_2);
						foreach($tv_pois as $item){
							$tv = Tyovuoroot::model()->findbypk($item->id);
							if(isset($tv->id)){
							// <-- LOG
							$model_log 	= 'Tyovuoroot';
							$name_log 	= 'Työvuorot';
							$status_log 	= 'Auto Delete';
	
							$old_values = json_encode($tv->attributes);
							$new_values = null;
							$site = Yii::app()->createController('Site');
							$site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
							//     LOG -->
							$this->loadModel($tv->id)->delete();
							}
						}
			}
		}


		return $return;

	}

	protected function vkoPaivat(){

		$arr = array(
		    1=>'Maanantai',
		    2=>'Tiistai',
		    3=>'Keskiviikko',
		    4=>'Torstai',
		    5=>'Perjantai',
		    6=>'Lauantai',
		    7=>'Sunnuntai',
		);
		return $arr;
	}

	public function actionAsiakas_autocomplete($key)
	{

		$criteria=new CDbCriteria;
		$criteria->order =" yrityksen_nimi!='' DESC,yhteyshenkilo!='' DESC";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		$criteria->addCondition (" 
			aktiivinen=1 
			AND (yrityksen_nimi LIKE '%".$key."%' OR yhteyshenkilo LIKE '%".$key."%' OR osoite LIKE '%".$key."%' )	
		");

 		$as = Asiakkaat::model()->findAll($criteria);
		$nm = array();
		$return = '';
		$return .= '
			<div class="row" style="position:absolute; z-index:9999999;margin-left:0px">
			  <div class="list-group">';

		if( count($as) > 0 )
		{
			foreach($as as $a)
			{
				if(!empty($a->yrityksen_nimi))
				$nm = array($a->yrityksen_nimi, $a->id);
				elseif(!empty($a->yhteyshenkilo))
				$nm = array($a->yhteyshenkilo, $a->id);
				else
				$nm = array($a->osoite, $a->id);
				
				$return .= '<a href="#" class="list-group-item asiakasSelecter" for="'.$nm[1].'">'.$nm[0].'</a>';
			}
		}

		$criteria=new CDbCriteria;
		$criteria->order =" etu_suku_nimet!='' DESC,etu_suku_nimet!='' DESC";


		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		$criteria->addCondition (" 
			aktiivinen=1 
			AND etu_suku_nimet LIKE '%".$key."%'	
		");

 		$k = Kohteet::model()->findAll($criteria);
		if( count($k) > 0 )
		{
		$return .= '<a href="#" class="list-group-item bg-warning"><h4 style="color:white">'.Yii::t('main', 'Kohteen yhteyshenkilöt').'</h4></a>';

			foreach($k as $item)
			{
				$return .= '<a href="#" class="list-group-item kohteenSelecter bg-warning" style="color:white" for="'.$item->id.'">'.$item->etu_suku_nimet.', '.$item->osoite.'</a>';
			}
		}
		$return .='</div></div>';

		if( count($as) > 0 or count($k) > 0 )
			echo json_encode($return);
		else
			echo json_encode('');
	}


	public function actionKohde_autocomplete($key)
	{

		$criteria=new CDbCriteria;
		$criteria->order =" osoite!='' DESC, osoite ASC";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		$criteria->addCondition (" 
			osoite LIKE '%".$key."%'	
		");

 		$as = Kohteet::model()->findAll($criteria);
		$nm = array();
		$return = '';
		if( count($as) > 0 )
		{

		$return .= '
			<div class="row" style="position:absolute; z-index:9999999;margin-left:0px">
			  <div class="list-group">';
			foreach($as as $a)
			{
				if(!empty($a->osoite))	
				$return .= '<a href="#" class="list-group-item kohdeSelecter" for="'.$a->id.'">'.$a->osoite.'</a>';
			}
			$return .='</div></div>';
		}





		echo json_encode($return);

	}


	public function previousNextWeeks($year,$week)
	{

		$previousWeek 	= date("W",strtotime($year ."W". $week.' -1 week'));

		if($previousWeek == '01') 
			$previousYear = $year;
		else
			$previousYear	= date("Y",strtotime($year ."W". $week.' -1 week'));

		$nextWeek 	= date("W",strtotime($year ."W". $week.' +1 week'));

		if($nextWeek == '01') 
			$nextYear = $year+1;
		else
			$nextYear 	= date("Y",strtotime($year ."W". $week.' +1 week'));

		$arr = array(
			'previousWeek' 	=> $previousWeek,
			'previousYear' 	=> $previousYear,
			'nextWeek' 	=> $nextWeek,
			'nextYear' 	=> $nextYear,
		);
		return $arr;
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

	protected function peruutettuArray()
	{
		$list = array(
		1 => Yii::t('main', 'Peruutettu'), 
		2 => Yii::t('main', 'Peruutettu laskutettava')
		);
		return $list;
	}

	public function actionLista()
	{

		if(isset($_POST['asiakkaatPerSivu']))
		{
			Yii::app()->user->setState('asiakkaatPerSivu', $_POST['asiakkaatPerSivu']);
			echo json_encode($_POST['asiakkaatPerSivu']);
			exit;
		}

		$from = date("d.m.Y", strtotime('first day of this month'));
		$to = date("d.m.Y");
		if(isset($_GET['from']) and !empty($_GET['from']))
		$from = date("d.m.Y", strtotime($_GET['from']));
		if(isset($_GET['to']) and !empty($_GET['to']))
		$to = date("d.m.Y", strtotime($_GET['to']));


		$criteria = new CDBCriteria;
        	$criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
        	$criteria->condition = " 				
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND peruutettu=0 
		";

		if(isset($_GET['tekijaPaaSivulla']))
		{
			$impl = implode(",", $_GET['tekijaPaaSivulla']);
	        	$criteria->addCondition (" tid IN ($impl) ");
		}
		if(isset($_GET['laskutettu']) and !empty($_GET['laskutettu']))
		{
	        	$criteria->addCondition (" laskutettu='".$_GET['laskutettu']."' ");
		} else {
	        	$criteria->addCondition (" laskutettu='0' ");
		}
		if(isset($_GET['uusi_tilaus']) and !empty($_GET['uusi_tilaus']))
		{
	        	$criteria->addCondition (" uusi_tilaus='".$_GET['uusi_tilaus']."' ");
		} else {
	        	$criteria->addCondition (" uusi_tilaus='0' ");
		}
		if(isset($_GET['status']) and !empty($_GET['status']))
		{
			$impl_status = implode(",", $_GET['status']);
	        	$criteria->addCondition (" status IN ($impl_status) ");
		}
		if(isset($_GET['yrityksen_nimi']))
		{
	        	$criteria->addCondition (" kohde IN (SELECT id FROM sivex_kohdet WHERE 
				asiakas_id IN (
					SELECT id FROM asiakkaat WHERE
					yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR yhteyshenkilo LIKE '%".$_GET['yrityksen_nimi']."%'
				)
			) ");
		}
		if(isset($_GET['osoite']))
		{
	        	$criteria->addCondition (" kohde IN (SELECT id FROM sivex_kohdet WHERE osoite LIKE '%".$_GET['osoite']."%') ");
		}


		$dataProvider=new CActiveDataProvider('Tyovuoroot', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$perSivu = 50;
		if(isset(Yii::app()->user->asiakkaatPerSivu)){
			$perSivu = Yii::app()->user->asiakkaatPerSivu;
		}
		$dataProvider->pagination->pageSize = $perSivu;


		$this->render('lista', array(
			'dataProvider' => $dataProvider,
			'perSivu' => $perSivu,
			'from' => $from,
			'to' => $to,
		));

	}

	public function actionSiirto($kenelta=null, $kenelle=null, $alkaen=null)
	{
//die('Suljettu 11.10.2018 asti');
		if( $alkaen !== null and date('Ymd', strtotime($alkaen)) < date('Ymd') ){
			Yii::app()->user->setFlash('danger','Työvuoroja menneisyydestä ei voida siirtää.');
				$this->redirect(array('siirto'));
		}

		$data_kenelta = array();
		$data_kenelle = array();
		if( $kenelta !== null and $kenelle !== null ){
			$criteria = new CDBCriteria;
	        	$criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') ASC ";
	        	$criteria->condition = " 				
				tid='".$kenelta."'
				AND peruutettu=0 
			";
			if( $alkaen !== null ){
				$criteria->addCondition(" DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m%d') >= ".date('Ymd', strtotime($alkaen))." ");
			}
			$data_kenelta = Tyovuoroot::model()->findAll($criteria);

			$criteria = new CDBCriteria;
	        	$criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') ASC ";
	        	$criteria->condition = " 				
				tid='".$kenelle."'
			";
			$data_kenelle = Tyovuoroot::model()->findAll($criteria);
		}

		$this->render('siirto', array(
			'data_kenelta' => $data_kenelta,
			'data_kenelle' => $data_kenelle,
			'alkaen' => $alkaen
		));

	}

	protected function getKohde($id)
	{
		$k = Kohteet::model()->findbypk($id);
		if(isset($k->id))
		return $k;
	}

	public function eiLasketaSubStr($val)
	{

		$return = false;
		if (strpos($val, 'Ei lasketa') !== false or strpos($val, 'Varallaolo') !== false) {
		    $return = true;
		}
		return $return;
	}

	public function actionVlupdater($id,$txt)
	{

	
	   if($id == 'new' and $txt == '')
	   {
		$model=new Vuosilomat;
		if(isset($_POST['Vuosilomat']))
		{
			$model->attributes=$_POST['Vuosilomat'];
			if($model->save()){
				echo $model->id.'//'.$model->tid.'//'.$model->pvm.'//'.$model->status;

			//$valikkoot = Valikkoot::model()->find(" select_type='tyoajanlaatu' and value like '%".$lat."%' ");
			$tv = new Tyovuoroot;
			$tv->tid=$model->tid;
			$tv->pvm=date("d.m.Y",strtotime($model->pvm));
			$tv->tyoajanlaatu=$_POST['Vuosilomat']['tyoajanlaatu'];
			$tv->alku='00:00';
			$tv->loppu='00:00';
			$tv->pituus='00:00';
			$tv->tietoja=$_POST['Vuosilomat']['tietoja'];
			$tv->save();
			} else {
				print_r($_POST);
			}
		}

	   } else {
		//Tyovuoroot::model()->deleteAll(" tid = '".$_POST['Vuosilomat']['tid']."' and pvm='".date("d.m.Y",strtotime($_POST['Vuosilomat']['pvm']))."' and tyoajanlaatu like '%".$txt."%' ");
		//$this->loadModel($id)->delete();
		// pois kaytosta 06.06.2019
				echo 'removed';
	   }


		//$this->renderPartial('vlupdater');

	}
}

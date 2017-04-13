<?php


class MobileController extends Controller
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
				'actions'=>array('admin', 'delete', 'create', 'update', 'index', 'index_a', 'view', 'updatetime', 'showkohteet', 'yhteenveto', 'kyhteenveto', 'yhteenveto_m', 'historia', 'poistaKohde', 'total_suunniteltu', 'total_toteutu', 'total_luettu', 'kesto', 'index_ajax', 'raportit', 'uusirivi', 'palkkataulukko', 'tidfromtomatkat', 'tidfromtoSL', 'tidfromtoSPL', 'tyobykohde', 'asiakas_hyvaksyminen', 'kohdebytekija' ,'kyhteenveto_tuntemattomat', 'laskutettu', 'lahetys_asiakkaalle', 'get_tyovuorot_day', 'on_olemassa', 'luetut_toteutuneet_ero_pdf', 'vuosilomat_pdf', 'check_paallekkainMobile', 'tyoajan_seuranta'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}




	public function isEtuntiAdmin() {

		if(isset(Yii::app()->user->adminID))
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

	public function actionGet_tyovuorot_day($id)
	{
		$model = Tyovuoroot::model()->findbypk($id);
		if(isset($model->pvm))
		{
			$return = array(
				'week'=>date("W", strtotime($model->pvm)),
				'year'=>date("Y", strtotime($model->pvm)),
			);
			echo json_encode($return);
		}
	}

	public function actionRaportit()
	{

function num($val){
    if($val > 0)
	return  number_format((float)$val/3600, 2, '.', '');
}

	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		function allSess(){


			if(Yii::app()->request->getPost('kohteet') == 'kaikki')
			unset(Yii::app()->session['kohteet']);
			if(Yii::app()->request->getPost('kohteet') and Yii::app()->request->getPost('kohteet') != 'kaikki'){
			Yii::app()->session['kohteet'] = Yii::app()->request->getPost('kohteet');
			}

			if(Yii::app()->request->getPost('from'))
			Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));
	
			if(Yii::app()->request->getPost('to'))
			Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));

			if(isset($_POST['ilman']))
			{
			  foreach($_POST['ilman'] as $val){
				if($val == 'Lounastauko')
				Yii::app()->session['Lounastauko'] = 10;
	
				if($val == 'MATKA')
				Yii::app()->session['MATKA'] = 2;
			  }
			}

		}


		function allCrit($criteria){

			$tids = array();
			if(false != Yii::app()->request->getPost('tekija') and is_array(Yii::app()->request->getPost('tekija')) )
			{
				$tids = "tid='".implode("' OR tid='", Yii::app()->request->getPost('tekija'))."'";
			}

			if(Yii::app()->request->getPost('tekija') != 'kaikki')
	        	$criteria->addCondition ($tids);

			if(isset(Yii::app()->session['kohteet']) and Yii::app()->session['kohteet'] != 'kaikki')
	        	$criteria->addCondition (" kohdenID = '".Yii::app()->session['kohteet']."'");

			if(isset($_POST['siivousPaaSivulla']) and !empty($_POST['siivousPaaSivulla']))
	        	$criteria->addCondition (" kohdenID IN ( SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".$_POST['siivousPaaSivulla']."%' ) ");

			if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        	$criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

			if(Yii::app()->session['Lounastauko'])
			$criteria->addCondition (" status != '10' ");
		
			if(Yii::app()->session['MATKA'])
			$criteria->addCondition (" status != '2' ");
		}



		if(Yii::app()->request->getPost('method'))
		{

		  unset(Yii::app()->session['Lounastauko']);
		  unset(Yii::app()->session['MATKA']);

		// <-- Luetut
		  if(Yii::app()->request->getPost('method') == 'luetut')
		  {

			allSess();

		       	$criteria = new CDbCriteria();
			$criteria->select = " aloitan,loppui,tid,tekijan_nimi,kohde_kannasta,viesti,sairaus ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " 
				aloitan!='' and loppui!='' 
			";

			allCrit($criteria);

			$model = Mobile::model()->findAll($criteria); 
	
			if(isset($_POST['luoPDF']))
			{

			define('PHPDOCX_INCLUDE_PATH', (dirname(Yii::app()->basePath)).'/protected/vendors/phpdocx');
			spl_autoload_unregister(array('YiiBase','autoload'));
			require_once PHPDOCX_INCLUDE_PATH.'/lib/pdf/dompdf_config.inc.php';
			//require_once PHPDOCX_INCLUDE_PATH.'/classes/TransformDocAdv.inc';
			require_once PHPDOCX_INCLUDE_PATH.'/classes/CreateDocx.inc';
			spl_autoload_register(array('AutoLoader','load'));
			spl_autoload_register(array('YiiBase', 'autoload'));

			$html = $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Luetut'),true);

			if (!file_exists(Yii::app()->basePath."/../temp")) {
			 	mkdir(Yii::app()->basePath."/../temp", 0777, true);
			}
			$tiedosto = time().'_temp';
			$temp_tiedosto = (dirname(Yii::app()->basePath)).'/temp/'.$tiedosto;
			$docx = new CreateDocx();
			$docx->embedHTML($html);
			$docx->createDocx( $temp_tiedosto );


			if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' )
			{
			$transform = new TransformDocAdvLibreOffice();
			$transform->transformDocument('temp/'.$tiedosto.'.docx', 'temp/'.$tiedosto.'.pdf');
			}
			unlink('temp/'.$tiedosto.'.docx');

			if (file_exists(Yii::app()->basePath.'/../temp'.$tiedosto.'.pdf'))
			{
			header('Content-type: application/pdf');
			readfile('temp/'.$tiedosto.'.pdf');
			unlink('temp/'.$tiedosto.'.pdf');
			}

exit;

			        $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en', 'true', 'UTF-8', array(3,10,5,10));
				$html2pdf->setDefaultFont('Arial');
			        $html2pdf->WriteHTML($html);
			        $html2pdf->Output();
				//$this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Luetut'));
			        exit;
			}

			if(isset($_POST['luoExcel']))
			{
			        $html = $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Luetut'),true);
				preg_match_all('/<div class=\"tb\">(.*?)<\/div>/s',$html,$match);
				$this->htmlToXls($match[0][0], 'luetut');
			        exit;
			}

			if(isset($_POST['luoPrintSivu']))
			{
				$html = '';
			        $html .= $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Luetut'),true);
				echo $html;
			        exit;
			}

		  }
		//  Luetut -->

		// <-- Toteutuneet
		  if(Yii::app()->request->getPost('method') == 'toteutuneet')
		  {

			allSess();

			$model = array();

			/* lu */
		       	$criteria = new CDbCriteria();
			$criteria->select = " time,aloitan,loppui,tid,tekijan_nimi,kohde_kannasta,viesti,sairaus ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " aloitan!='' and loppui!='' AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";

			allCrit($criteria);

			$lu = Mobile::model()->findAll($criteria);
  			foreach($lu as $data){
				$model[strtotime($data->aloitan)] = $data;
			}

			/* tot */
		       	$criteria = new CDbCriteria();
			$criteria->select = " time,aloitan,loppui,tekijan_nimi,kohde_kannasta,sairaus ";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " aloitan!='' and loppui!='' ";

			allCrit($criteria);
			$tot = array();
			$tot = Toteutuneet::model()->findAll($criteria); 

  			foreach($tot as $data){
				$model[strtotime($data->aloitan)] = $data;
			}

			if(count($model) > 0)
			ksort($model);


			if(isset($_POST['luoPDF']))
			{
			        $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en', 'true', 'UTF-8', array(3,10,5,10));
				$html2pdf->setDefaultFont('Arial');
			        $html2pdf->WriteHTML($this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Hyväksytyt'),true));
			        $html2pdf->Output();
			        exit;
			}

			if(isset($_POST['luoExcel']))
			{
			        $html = $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Hyväksytyt'),true);
				preg_match_all('/<div class=\"tb\">(.*?)<\/div>/s',$html,$match);
				$this->htmlToXls($match[0][0], 'toteutuneet');
			        exit;
			}

			if(isset($_POST['luoPrintSivu']))
			{
				$html = '';
			        $html .= $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Hyväksytyt'),true);
				echo $html;
			        exit;
			}

		  }
		//  Toteutuneet -->

		// <-- Toteutuneen ja suunnitellun työn erot
		  if(Yii::app()->request->getPost('method') == 'LuetutToteutuneetEro')
		  {

		       	$criteria = new CDbCriteria();
		       	$criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m.%d'),( SELECT tekijan_nimi FROM sivex_ttekijat WHERE t.tid=id ) ";
		       	$criteria->group = " CONCAT(pvm,kohde,tid) ";
		       	$criteria->select = " 
				( SELECT osoite FROM sivex_kohdet WHERE t.kohde=id ) as osoite, 
				( SELECT kaupunki FROM sivex_kohdet WHERE t.kohde=id ) as kaupunki, 
				( SELECT tekijan_nimi FROM sivex_ttekijat WHERE t.tid=id ) as tekijan_nimi, 

				SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(CONCAT(pvm, loppu), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
				DATE_FORMAT(STR_TO_DATE(CONCAT(pvm, alku), '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as suunnittellut,
				t.* 
			";

			if(isset($_POST['from']) and isset($_POST['to']))
			{

			$site = Yii::app()->createController('Site');
			$eilasketa = $site[0]->eiLasketa();

	        		$criteria->addCondition ("
					DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
					BETWEEN '".date("Y-m-d", strtotime($_POST['from']))."' AND '".date("Y-m-d", strtotime($_POST['to']))."' 
					AND $eilasketa
					AND kohde!=''
				");

			}


			$tids = array();
			if(false != Yii::app()->request->getPost('tekija') and is_array(Yii::app()->request->getPost('tekija')) )
			{
				$tids = "tid='".implode("' OR tid='", Yii::app()->request->getPost('tekija'))."'";
	        		$criteria->addCondition ($tids);
			}


			if(isset($_POST['kohteet']) and  $_POST['kohteet'] != 'kaikki')
	        	$criteria->addCondition (" kohde = '".$_POST['kohteet']."'");

			if(isset($_POST['siivousPaaSivulla']) and !empty($_POST['siivousPaaSivulla']))
	        	$criteria->addCondition (" kohde IN ( SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".$_POST['siivousPaaSivulla']."%' ) ");

			$model = Tyovuoroot::model()->findAll($criteria); 



			if(isset($_POST['luoPDF']))
			{
			        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en', 'true', 'UTF-8', array(3,10,5,10));
				$html2pdf->setDefaultFont('Arial');
			        $html2pdf->WriteHTML($this->renderPartial('luetut_toteutuneet_ero_pdf', array('model' => $model, 'from'=>$_POST['from'],'to'=>$_POST['to']),true));
			        $html2pdf->Output();
			        exit;
			}

			if(isset($_POST['luoExcel']))
			{
			        $html = $this->renderPartial('luetut_toteutuneet_ero_pdf', array('model' => $model, 'from'=>$_POST['from'],'to'=>$_POST['to']),true);
				preg_match_all('/<div class=\"tb\">(.*?)<\/div>/s',$html,$match);
				$this->htmlToXls($match[0][0], 'LuetutToteutuneetEro');
			        exit;
			}

			if(isset($_POST['luoPrintSivu']))
			{
				$html = '';
			        $html .= $this->renderPartial('luetut_toteutuneet_ero_pdf', array('model' => $model, 'from'=>$_POST['from'],'to'=>$_POST['to']),true);
				echo $html;
			        exit;
			}

		  }
		// Toteutuneen ja suunnitellun työn erot -->

		// <-- lomat Ja Poissaolot
		  if(Yii::app()->request->getPost('method') == 'lomatJaPoissaolot')
		  {
		       	$criteria = new CDbCriteria();
			$criteria->order = "(SELECT tekijan_nimi FROM sivex_ttekijat WHERE t.tid=id),status";
			$criteria->group = "tid,status";
		       	$criteria->select = " COUNT(status) as kpl, (SELECT tekijan_nimi FROM sivex_ttekijat WHERE t.tid=id) as tekijan_nimi, t.*";

			if(isset($_POST['from']) and isset($_POST['to']))
			{

	        		$criteria->addCondition ("
					DATE_FORMAT(STR_TO_DATE(pvm, '%Y-%m-%d'), '%Y-%m-%d') 
					BETWEEN '".date("Y-m-d", strtotime($_POST['from']))."' AND '".date("Y-m-d", strtotime($_POST['to']))."' 
				");

			}

			if(isset($_POST['tekija']) and  $_POST['tekija'] != 'kaikki')
	        	$criteria->addCondition (" tid = '".$_POST['tekija']."'");


			if(isset($_POST['status']) and !empty($_POST['status']))
			{
				$impl = "status LIKE '%". implode("/%' OR status LIKE '%", $_POST['status'])."/%'";
	        		$criteria->addCondition ($impl);
			}

			$model = Vuosilomat::model()->findAll($criteria); 


			if(isset($_POST['luoPDF']))
			{
			        $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
				$html2pdf->setDefaultFont('Arial');
			        $html2pdf->WriteHTML($this->renderPartial('vuosilomat_pdf', array('model' => $model),true));
			        $html2pdf->Output();
			        exit;
			}

			if(isset($_POST['luoExcel']))
			{
			        $html = $this->renderPartial('vuosilomat_pdf', array('model' => $model),true);
				preg_match_all('/<div class=\"tb\">(.*?)<\/div>/s',$html,$match);
				$this->htmlToXls($match[0][0], 'lomatJaPoissaolot');
			        exit;
			}

			if(isset($_POST['luoPrintSivu']))
			{
				$html = '';
			        $html .= $this->renderPartial('vuosilomat_pdf', array('model' => $model),true);
				echo $html;
			        exit;
			}

		  }
		//  lomat Ja Poissaolot -->


		} else {

			$this->render('raportit');

		}

	}

	protected function htmlToXls($html, $nimike)
	{

			Yii::import('ext.phpexcel.PHPExcel',true);
			$tmpfile = 'temp.html';
			file_put_contents($tmpfile, mb_convert_encoding($html, 'ISO-8859-1', 'UTF-8'));
			
			$inputFileType = 'HTML';
			$inputFileName = $tmpfile;
			$outputFileType = 'Excel5';
			$outputFileName = 'myExcelFile.xlsx';
			
			$objPHPExcelReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objPHPExcelReader->load($inputFileName);
		
			$objPHPExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,$outputFileType);

			header('Content-type: application/vnd.ms-excel;');
			header('Content-Disposition: attachment; filename="'.$nimike.'.xls"');
			$objPHPExcelWriter->save('php://output');
			unlink($tmpfile);
	}

	public function actionTotal_suunniteltu($id,$kohde_tid,$from,$to)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$this->renderPartial('suunniteltu',array(
			'id'=>$id,
			'kohde_tid'=>$kohde_tid,
			'from'=>$from,
			'to'=>$to,
		));

	}

	public function actionTotal_luettu($tid)
	{

		$this->renderPartial('total_luettu',array(
			'tid'=>$tid,
		));

	}

	public function actionTotal_toteutu($tid)
	{

		$this->renderPartial('total_toteutu',array(
			'tid'=>$tid,
		));

	}

	public function actionIndex_ajax()
	{

		$model = Mobile::model()->find("id!='' order by id DESC");
		if(isset($_POST['setRivi']))
		$this->renderPartial('_view', array('data' => $model));
		else
		echo $model->id;

	}

	public function actionKesto($id)
	{
	
	function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

		$k = '';
		$model = $this->loadModel($id);

		  $model->loppui = date("d.m.Y H:i",strtotime($model->loppui));
		  $model->aloitan = date("d.m.Y H:i",strtotime($model->aloitan));

		$k = strtotime($model->loppui)-strtotime($model->aloitan);
		echo sprint($k);
	}


	public function actionHistoria($id,$tilanne,$kohde_kannasta,$aloitan,$loppui,$tekijan_nimi)
	{

/*
		$this->renderPartial('historia',array(
			'id'=>$id,
			'tilanne'=>$tilanne,
			'uusikohde'=>$uusikohde,
			'uusialoitus'=>$uusialoitus,
			'uusilopetus'=>$uusilopetus,
		));
*/
	}

	public function actionUpdatetime()
	{

		$model 		= $this->loadModel($_POST['id']);

 		$kohde_kannasta	= $model->kohde_kannasta;
 		$aloitan 	= $model->aloitan;
 		$loppui	 	= $model->loppui;
 		$tekijan_nimi	= $model->tekijan_nimi;

		if(isset($_POST['status']))
		$model->status =  (int)$_POST['status'];


		if($_POST['request'] == 'aloitan'){
			$model->aloitan = date("d.m.Y H:i:s",strtotime($_POST['value']));
		}
		if($_POST['request'] == 'loppui'){
			$model->loppui = date("d.m.Y H:i:s",strtotime($_POST['value']));
		}
		if($_POST['request'] == 'kohde_kannasta'){
			$model->kohde_kannasta = $_POST['value'];
		}


		if($model->save()){

			// <-- Kirjoitetaan historia luettut tietokantaan
			$this->renderPartial('//mobile/historia',array(
			'id'=>$model->id,
			'tilanne'=>"Luetut",
			'kohde_kannasta'=>array('vanha'=>$kohde_kannasta, 'uusi'=>$model->kohde_kannasta),
			'aloitan'=>array('vanha'=>$aloitan, 'uusi'=>$model->aloitan),
			'loppui'=>array('vanha'=>$loppui, 'uusi'=>$model->loppui),
			'tekijan_nimi'=>array('vanha'=>$tekijan_nimi, 'uusi'=>$model->tekijan_nimi),
			));
			// Kirjoitetaan historia luettut tietokantaan -->

			echo $_POST['request']."//".date("H:i",strtotime($model->aloitan))."//".date("H:i",strtotime($model->loppui));
		}

	}

	public function actionShowkohteet()
	{
		$as=new Kohteet;
		$bd = CHtml::activeDropDownList($as, 'id',
		CHtml::listData(Kohteet::model()->findAll(array("order"=>"osoite")), 'id', 'osoite'),   
		    array('empty'=>'Muokka', "class"=>"kohdenvaihto btn btn-default") 
		);


		$bd .= '<input type="hidden" id="sainkohdenID" value="'.$_POST['thisID'].'">';
		echo json_encode($bd);

	}


	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	public function actionUusirivi()
	{
		$model=new Mobile;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Mobile']))
		{
			$k = Kohteet::model()->findbypk($_POST['Mobile']['kohdenID']);
			$model->attributes=$_POST['Mobile'];
			$model->time = date("Y-m-d H:i:s",strtotime($_POST['Mobile']['aloitan']));
			$model->kohde_kannasta=$k->osoite;
			$model->admin=1;

			if($model->save()){
			   $did = date("Ymd",strtotime($model->aloitan));
			   echo $did."_".$model->tid;
			   exit;
			}

		}

		$this->renderPartial('_uusirivi',array(
			'model'=>$model,
		));
	
	}


	public function actionOn_olemassa()
	{
		$model=new Mobile;
		$isLine = '';

		if(isset($_POST))
		{
			$model->attributes=$_POST;

			$criteria = new CDBCriteria;
			$criteria->condition = "
				DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')='".date("Y-m-d H:i", strtotime($model->aloitan))."'
				AND DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')='".date("Y-m-d H:i", strtotime($model->loppui))."'
				AND tid='".$model->tid."'
			";
			$check = Mobile::model()->find($criteria);

			if(isset($check->id))
			{
				$isLine = Yii::t('main', 'Tämä aika on jo olemassa ID:'). $check->id . "\n";
			}

		}

		echo json_encode($isLine);
		exit;
	}

	public function actionCreate()
	{
		$model=new Mobile;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);




		if(isset($_POST['Mobile']))
		{
			$isLine =  false;
			$model->attributes=$_POST['Mobile'];

			if(empty($_POST['Mobile']['loppui']) and $_POST['Mobile']['status'] == 3)
			$model->status=1;

			$criteria = new CDBCriteria;
			$criteria->condition = "
				DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')='".date("Y-m-d H:i", strtotime($model->aloitan))."'
				AND DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')='".date("Y-m-d H:i", strtotime($model->loppui))."'
				AND tid='".$model->tid."'
				AND kohdenID='".$model->kohdenID."'
				AND status='".$model->status."'
			";
			$check = Mobile::model()->find($criteria);
			if(isset($check->id))
			{
				$isLine = true;
			}

			if($isLine == false)
			$model->save();

			echo $isLine;
			exit;
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
	//print_r($_POST);
	//exit;

		$model=$this->loadModel($id);


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['fromMob'])){
			$_POST['Mobile']=$_POST;
		}

		if(isset($_POST['Mobile']))
		{

	 		$aloitan 	= $model->aloitan;
	 		$loppui	 	= $model->loppui;
	 		$kohde_kannasta	= $model->kohde_kannasta;
	 		$tekijan_nimi 	= $model->tekijan_nimi;

			$model->attributes=$_POST['Mobile'];

			if(isset($_POST['Mobile']['status']) and empty($_POST['Mobile']['loppui']) and $_POST['Mobile']['status'] == 3)
			$model->status=1;

			if($model->save())
			{

				// <-- Kirjoitetaan historia luettut tietokantaan
				$this->renderPartial('//mobile/historia',array(
				'id'=>$model->id,
				'tilanne'=>"Luetut",
				'kohde_kannasta'=>array('vanha'=>$kohde_kannasta, 'uusi'=>$model->kohde_kannasta),
				'aloitan'=>array('vanha'=>$aloitan, 'uusi'=>$model->aloitan),
				'loppui'=>array('vanha'=>$loppui, 'uusi'=>$model->loppui),
				'tekijan_nimi'=>array('vanha'=>$tekijan_nimi, 'uusi'=>$model->tekijan_nimi),
				));
				// Kirjoitetaan historia luettut tietokantaan -->

				$this->redirect(array('index'));

			} else {

			   	var_dump($model->getErrors());
			   	exit;

			}

		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionPoistaKohde()
	{
		$this->loadModel($_POST['id'])->delete();
		Toteutuneet::model()->deleteAll(" kid='".$_POST['id']."' ");
	}




	public function actionLaskutettu()
	{


		if(isset($_POST['ajax']) and isset($_POST['id']))
		{
 			if(isset($_POST['tot']) and $_POST['tot'] == '1')
			  Toteutuneet::model()->updatebypk($_POST['id'], array('laskutettu'=>$_POST['las']));
			else
			  Mobile::model()->updatebypk($_POST['id'], array('laskutettu'=>$_POST['las']));

			  echo $_POST['id']." ".$_POST['las'];
			  exit;
		}

		if(isset($_POST['submit']))
		{

		if(Yii::app()->request->getPost('etsi_kohteet'))
		Yii::app()->session['etsi_kohteet'] = Yii::app()->request->getPost('etsi_kohteet');

		if(Yii::app()->request->getPost('fromP'))
		Yii::app()->session['fromP'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('fromP')));
	
		if(Yii::app()->request->getPost('toP'))
		Yii::app()->session['toP'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('toP')));

		if(Yii::app()->request->getPost('tekijaPaaSivulla'))
		Yii::app()->session['tekijaPaaSivulla'] = Yii::app()->request->getPost('tekijaPaaSivulla');

		if(Yii::app()->request->getPost('siivousPaaSivulla'))
		Yii::app()->session['siivousPaaSivulla'] = Yii::app()->request->getPost('siivousPaaSivulla');

		if(Yii::app()->request->getPost('yrityksen_nimi'))
		Yii::app()->session['yrityksen_nimi'] = Yii::app()->request->getPost('yrityksen_nimi');

		if(Yii::app()->request->getPost('laskutettu'))
		Yii::app()->session['laskutettu'] = Yii::app()->request->getPost('laskutettu');



		if(Yii::app()->request->getPost('etsi_kohteet') == '') unset(Yii::app()->session['etsi_kohteet']);
		if(Yii::app()->request->getPost('fromP') == '') unset(Yii::app()->session['fromP']);
		if(Yii::app()->request->getPost('toP') == '') unset(Yii::app()->session['toP']);

		if(Yii::app()->request->getPost('tekijaPaaSivulla') == '' or Yii::app()->request->getPost('tekijaPaaSivulla') == 'kaikki') 
			unset(Yii::app()->session['tekijaPaaSivulla']);

		if(Yii::app()->request->getPost('siivousPaaSivulla') == '') unset(Yii::app()->session['siivousPaaSivulla']);
		if(Yii::app()->request->getPost('yrityksen_nimi') == '') unset(Yii::app()->session['yrityksen_nimi']);
		if(Yii::app()->request->getPost('laskutettu') == '') unset(Yii::app()->session['laskutettu']);
	
		}



       		$criteria = new CDbCriteria();
        	$criteria->order = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < DATE_ADD(NOW(), interval 4 hour) AND status IN (1,2,10) AND loppui='' DESC, 
		time and status IN (1,2,10) AND loppui='' DESC, 
		time DESC ";

	        $criteria->condition = " admin!=1 AND status=3 ";

		if(isset(Yii::app()->session['tekijaPaaSivulla']))
	        $criteria->addCondition (" tid = '".Yii::app()->session['tekijaPaaSivulla']."' ");

		if(isset(Yii::app()->session['siivousPaaSivulla']))
	        $criteria->addCondition (" kohdenID IN ( SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".Yii::app()->session['siivousPaaSivulla']."%' ) ");


		if(isset(Yii::app()->session['yrityksen_nimi']) and !empty(Yii::app()->session['yrityksen_nimi']))
		{
	        $criteria->addCondition ("  
			kohdenID IN ( 
			SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
				( SELECT id FROM asiakkaat 
					WHERE yrityksen_nimi LIKE '%".Yii::app()->session['yrityksen_nimi']."%' OR yhteyshenkilo LIKE '%".Yii::app()->session['yrityksen_nimi']."%'
				)
			)
		");
		}

		if(isset(Yii::app()->session['etsi_kohteet']))
	        $criteria->addCondition (" kohde_kannasta LIKE '%".Yii::app()->session['etsi_kohteet']."%' ");

		if(isset(Yii::app()->session['fromP']) and isset(Yii::app()->session['toP']))
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date("Y-m-d", strtotime(Yii::app()->session['fromP']))."' AND '".date("Y-m-d", strtotime(Yii::app()->session['toP']))."' ");

		if(isset(Yii::app()->session['laskutettu']) and Yii::app()->session['laskutettu'] == '1')
	        $criteria->addCondition (" laskutettu = '1' ");

		if(isset(Yii::app()->session['laskutettu']) and Yii::app()->session['laskutettu'] == '3')
	        $criteria->addCondition (" laskutettu = '0' ");


		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;

		$this->render('laskutettu', array('dataProvider' => $dataProvider));
	}

	public function actionIndex()
	{


		if(Yii::app()->request->getPost('tekijaPaaSivulla') == 'kaikki')
		unset(Yii::app()->session['tekijaPaaSivulla']);
		if(Yii::app()->request->getPost('tekijaPaaSivulla') and Yii::app()->request->getPost('tekijaPaaSivulla') != 'kaikki'){
		Yii::app()->session['tekijaPaaSivulla'] = Yii::app()->request->getPost('tekijaPaaSivulla');
		}

		if(isset($_POST['mob_hae']) and Yii::app()->request->getPost('kohde_kannasta') == '')
		unset(Yii::app()->session['kohde_kannasta']);
		if(isset($_POST['mob_hae']) and Yii::app()->request->getPost('kohde_kannasta')){
		Yii::app()->session['kohde_kannasta'] = Yii::app()->request->getPost('kohde_kannasta');
		}

		if(isset($_POST['fromP']) and empty($_POST['fromP']))
		unset(Yii::app()->session['fromP']);

		if(isset($_POST['toP']) and empty($_POST['toP']))
		unset(Yii::app()->session['toP']);

		if(isset($_POST['tunni_status']) and $_POST['tunni_status'] == 'kaikki')
		unset(Yii::app()->session['tunni_status']);

		if(isset($_POST['yrityksen_nimi']) and empty($_POST['yrityksen_nimi']))
			unset(Yii::app()->session['yrityksen_nimi']);
		else if(isset($_POST['yrityksen_nimi']) and !empty($_POST['yrityksen_nimi']))
			Yii::app()->session['yrityksen_nimi'] = Yii::app()->request->getPost('yrityksen_nimi');

		if(isset($_POST['siivousPaaSivulla']) and empty($_POST['siivousPaaSivulla']))
			unset(Yii::app()->session['siivousPaaSivulla']);
		else if(isset($_POST['siivousPaaSivulla']) and !empty($_POST['siivousPaaSivulla']))
			Yii::app()->session['siivousPaaSivulla'] = Yii::app()->request->getPost('siivousPaaSivulla');

		if(isset($_POST['tyontekijanRyhma']) and empty($_POST['tyontekijanRyhma']))
			unset(Yii::app()->session['tyontekijanRyhma']);
		else if(isset($_POST['tyontekijanRyhma']) and !empty($_POST['tyontekijanRyhma']))
			Yii::app()->session['tyontekijanRyhma'] = Yii::app()->request->getPost('tyontekijanRyhma');



		if(Yii::app()->request->getPost('fromP'))
		Yii::app()->session['fromP'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('fromP')));

		if(Yii::app()->request->getPost('toP'))
		Yii::app()->session['toP'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('toP')));

		if(Yii::app()->request->getPost('tunni_status') and Yii::app()->request->getPost('tunni_status') != 'kaikki')
		Yii::app()->session['tunni_status'] = Yii::app()->request->getPost('tunni_status');

       		$criteria = new CDbCriteria();


/*
$criteria->order =
"    	  case 
            when loppui='' then 1
            when aloitan  then 2
	  else 100 
    	  end  DESC
";
time <= date_sub(NOW(), interval 3 hour) AND status IN (1,2,10) AND loppui='' DESC, 
*/
        	$criteria->order = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < DATE_ADD(NOW(), interval 4 hour) AND status IN (1,2,10) AND loppui='' DESC, 
		time and status IN (1,2,10) AND loppui='' DESC, 
		time DESC ";

	        $criteria->condition = " admin!=1 ";

		if(Yii::app()->session['tekijaPaaSivulla'])
	        $criteria->addCondition (" tid = '".Yii::app()->session['tekijaPaaSivulla']."' ");
		if(Yii::app()->session['kohde_kannasta'])
	        $criteria->addCondition (" kohde_kannasta LIKE '%".Yii::app()->session['kohde_kannasta']."%' ");
		if(Yii::app()->session['fromP'] and Yii::app()->session['toP'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['fromP']."' AND '".Yii::app()->session['toP']."' ");
		if(Yii::app()->session['tunni_status'])
	        $criteria->addCondition (" status = '".Yii::app()->session['tunni_status']."' ");

		if(isset(Yii::app()->session['yrityksen_nimi']))
		{
	        $criteria->addCondition ("  
			kohdenID IN ( 
			SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
				( SELECT id FROM asiakkaat 
					WHERE yrityksen_nimi LIKE '%".Yii::app()->session['yrityksen_nimi']."%' OR yhteyshenkilo LIKE '%".Yii::app()->session['yrityksen_nimi']."%'
				)
			)
		");
		}

		if(isset(Yii::app()->session['siivousPaaSivulla']))
	        $criteria->addCondition (" kohdenID IN ( SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".Yii::app()->session['siivousPaaSivulla']."%' ) ");


		if(isset(Yii::app()->session['tyontekijanRyhma']))
		{
	        $criteria->addCondition ("  
			tid IN ( 
			SELECT id FROM sivex_ttekijat WHERE tyoryhma LIKE '%".Yii::app()->session['tyontekijanRyhma']."%'
			)
		");
		}





		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;

		if(isset($_POST['index_ajax']))
		{
			echo $this->renderPartial('index_a', array('dataProvider' => $dataProvider));
			exit;
		} else {
			echo $this->render('index', array('dataProvider' => $dataProvider));
		}

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Mobile('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Mobile']))
			$model->attributes=$_GET['Mobile'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Mobile the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Mobile::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Mobile $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mobile-form')


		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



	public function ilta($al,$lop){

		$totalIlta = 0;


	  	if(
			strtotime($al[0]." ".$al[1]) > strtotime($al[0]." 18:00")
			and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 23:00")
			and $al[0] == $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;
		}

	  	if(
			strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 18:00")
			and strtotime($lop[0]." ".$lop[1]) < strtotime($lop[0]." 23:00")
			and strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 18:00")
			and $al[0] == $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." 18:00");
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;
		}

	  	if(
		  	strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 18:00")
			and strtotime($lop[0]." ".$lop[1]) >= strtotime($lop[0]." 23:00")
			and $al[0] == $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." 18:00");
	   	  $strLop0 = strtotime($lop[0]." 23:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;
		}

	  	if(
			strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 18:00")
			and strtotime($lop[0]." ".$lop[1]) > strtotime($lop[0]." 23:00") // 10.03.2017
			and $al[0] == $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." 23:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;
		}

	  	if(
			strtotime($al[0]." ".$al[1]) > strtotime($al[0]." 18:00")
			and strtotime($al[0]." ".$al[1]) < strtotime($al[0]." 23:00")
			and $al[0] != $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($al[0]." 23:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;

		}

	  	if(
			strtotime($al[0]." ".$al[1]) < strtotime($al[0]." 18:00")
			and $al[0] != $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." 18:00");
	   	  $strLop0 = strtotime($al[0]." 23:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalIlta += $str;
		}


		return $totalIlta;
	}


	public function yo($al,$lop){

		$totalYo = 0;

		//echo $al[0].' '.$al[1].' - '.$lop[0].' '.$lop[1].'<br>';

	  	if(strtotime($al[0]." ".$al[1]) > strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 06:00")
		)
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) > strtotime($lop[0]." 23:00")
		and $al[0] == $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." 23:00");
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) > strtotime($lop[0]." 06:00")
		and $al[0] != $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." 23:00");
	   	  $strLop0 = strtotime($lop[0]." 06:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}


	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 06:00")
		and $al[0] != $lop[0]
		)
		{
	   	  $strAl0 = strtotime($al[0]." 23:00");
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) > strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) > strtotime($lop[0]." 06:00"))
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." 06:00");

	 	  $str = ($strLop0-$strAl0);
		  //echo 'bb '. $al[0]." ".$al[1].' '.$lop[0]." ".$lop[1].' == '.$this->sprint($str);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 06:00")
		and strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 00:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($al[0]." 06:00")
		)
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  //$strLop0 = strtotime($al[0]." 06:00");
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 06:00")
		and strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 00:00")
		and strtotime($lop[0]." ".$lop[1]) > strtotime($al[0]." 06:00")
		)
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($al[0]." 06:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}



		if($totalYo > 0)
		return $totalYo;
	}

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}


	public function actionTidfromtomatkat($from,$to,$tid)
	{
	/*
		$this->renderPartial('palkkataulukko', array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$tid
		));
	*/
	}

	public function TidfromtoStatus($from,$to,$tid,$status)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$result = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND status='".$status."'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)

		";


		$lu = Mobile::model()->find($criteria);


       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND status='".$status."'
		";


		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->l_tunnit))
		$result = $lu->l_tunnit;

		if(isset($tot->l_tunnit))
		$result = $result+$tot->l_tunnit;


		return $result;
	}


	public function TidfromtoSairaus($from,$to,$tid,$sairaus)
	{

		if($sairaus == 'SPL') 	$sairaus = 1; // Palkaton
		if($sairaus == 'SL') 	$sairaus = 2; // Palkallinen
		if($sairaus == 'LS') 	$sairaus = 3; // Lapsen sairaus


		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$result = '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND sairaus='".$sairaus."'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)

		";


		$lu = Mobile::model()->find($criteria);


       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND sairaus='".$sairaus."'
		";


		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->l_tunnit))
		$result = $lu->l_tunnit;

		if(isset($tot->l_tunnit))
		$result = $result+$tot->l_tunnit;


		return $result;
	}

/*
	protected function TidfromtoSL($from,$to,$tid)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$result = '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND sairaus='2'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
		";


		$lu = Mobile::model()->find($criteria);


       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND sairaus='2'
		";


		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->l_tunnit))
		$result = $lu->l_tunnit;

		if(isset($tot->l_tunnit))
		$result = $result+$tot->l_tunnit;


		return $result;
	}


	protected function TidfromtoLS($from,$to,$tid)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$result = '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND tid='".$tid."'

			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND sairaus='3'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)

		";


		$lu = Mobile::model()->find($criteria);


       		$criteria = new CDbCriteria();
        	$criteria->select = "

			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

	        $criteria->condition = " 

			aloitan!='' AND loppui!=''
			AND tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'

			AND sairaus='3'
		";


		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->l_tunnit))
		$result = $lu->l_tunnit;

		if(isset($tot->l_tunnit))
		$result = $result+$tot->l_tunnit;


		return $result;
	}



	protected function TidfromtoSPL($from,$to,$tid)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$result = '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "COUNT(*) as count";

	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND sairaus='1'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
		";

		$lu = Mobile::model()->find($criteria);

       		$criteria = new CDbCriteria();
        	$criteria->select = "COUNT(*) as count";

	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND sairaus='1'
		";

		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->count))
		$result = $lu->count;

		if(isset($tot->count))
		$result = $result+$tot->count;

		return $result;
	}
*/

	protected function TidfromtoVuosilomaPalkkatauluko($from,$to,$tid,$tila)
	{

		$from 	= date("Y-m-d", strtotime($from));
		$to 	= date("Y-m-d", strtotime($to));

		$result = 0;
       		$criteria = new CDbCriteria();
	        $criteria->condition = "
			DATE(pvm) 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND status LIKE '%".$tila."//%'
			AND hyvaksytty=1
		";

		$tv = Vuosilomat::model()->findAll($criteria);

		return count($tv);
	}

	protected function TidPvmVuosiloma($pvm,$tid,$tila)
	{

		$pvm 	= date("Y-m-d", strtotime($pvm));

		$result = 0;
       		$criteria = new CDbCriteria();
	        $criteria->condition = "
			DATE(pvm) = '".$pvm."' 
			AND tid='".$tid."'
			AND status LIKE '%".$tila."//%'
			AND hyvaksytty=1
		";
		$vl = Vuosilomat::model()->find($criteria);

		$arr = array('count'=>0);
		if(isset($vl->id))
			$arr = array('id'=>$vl->id,'hyvaksytty'=>$vl->hyvaksytty,'count'=>1);

		return $arr;
	}


	public function actionPalkkataulukko()
	{


		//unset(Yii::app()->session['Tekija']);
		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

		$from = date("d.m.Y",strtotime("first day of this month"));
		$to = date("d.m.Y");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}
		

       		$criteria = new CDbCriteria();
		$criteria->select = " id,tekijan_nimi ";

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

        	$criteria->condition = " aktiivinen=1 "; 

		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

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

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_palkkataulukko', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('palkkataulukko', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ));
		}
	}

	protected function TP($tid,$from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

       		$criteria = new CDbCriteria();
        	$criteria->select = "id";
        	$criteria->group = "DATE(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d'))";

	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND sairaus=''
			AND aloitan!=loppui
		";

		$model = Mobile::model()->findAll($criteria);
		return count($model);

	}

	public function actionYhteenveto()
	{


		//unset(Yii::app()->session['Tekija']);
		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

		if(isset($_POST['yhtvetoform']))
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

/*
		if(Yii::app()->request->getPost('from'))
		Yii::app()->session['from'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('from')));

		if(Yii::app()->request->getPost('to'))
		Yii::app()->session['to'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('to')));
*/		

		$from = date("d.m.Y");
		$to = date("d.m.Y");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}


       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit,tid
		";

        	$criteria->order = "tekijan_nimi"; //"SUBSTR(LTRIM(tekijan_nimi), LOCATE(' ',LTRIM(tekijan_nimi)))"
        	$criteria->group = 'tid';
        	$criteria->condition = " 
			aloitan !='' and loppui !='' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date('Y-m-d',strtotime($from))."' AND '".date('Y-m-d',strtotime($to))."' 
		";



		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

	        $criteria->addCondition ('tid IN ('.$ids.') ');
		}

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");



		/*
		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			'pagination'=>false
		));
		*/
		$model = Mobile::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_yhteenveto', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ),true));

	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('yhteenveto', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ));
		}
	}


	protected function luMatka($criteria,$tid,$from,$to){

        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";


        	$criteria->condition = "  

			status = '2' and tid = '".$tid."' and aloitan !='' and loppui !='' 
		";

		if(!empty($from) and !empty($to))
		{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

	        $criteria->addCondition (" 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		");
		}

	        $criteria->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");

		return $criteria;
	}

	protected function totMatka($criteria,$tid,$from,$to){

        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = "  
			status = '2' and tid = '".$tid."' and aloitan !='' and loppui !='' 
		";

		if(!empty($from) and !empty($to))
		{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

	        $criteria->addCondition (" 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		");
		}

	        //$criteria->addCondition (" kid IN (SELECT id FROM sivexkuitti) ");

		return $criteria;
	}

	public function actionYhteenveto_m()
	{


		//unset(Yii::app()->session['Tekija']);
		if(Yii::app()->request->getPost('Tekija'))
		Yii::app()->session['Tekija'] = Yii::app()->request->getPost('Tekija');

		$from = date("d.m.Y");
		$to = date("d.m.Y");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}
		

       		$criteria = new CDbCriteria();
        	$criteria->order = "tekijan_nimi"; //"SUBSTR(LTRIM(tekijan_nimi), LOCATE(' ',LTRIM(tekijan_nimi)))"
		if(Yii::app()->session['Tekija']){
		  if(count(Yii::app()->session['Tekija']) > 1)
		    $ids = implode(",",Yii::app()->session['Tekija']);
		  else
		    $ids = Yii::app()->session['Tekija'][0];

	        $criteria->addCondition ('id IN ('.$ids.') ');
		}


		$model = Tyontekijat::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_yhteenveto_m', array(
			'model' => $model,
			'from' => $from,
			'to' => $to,	
		  ),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('yhteenveto_m', array(
			'model' => $model,
			'from' => $from,
			'to' => $to,
		  ));
		}
	}

	protected function yhtSUUNN($from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$site = Yii::app()->createController('Site');
		$eilasketa = $site[0]->eiLasketa();



       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";

        	$criteria->condition = " 
			loppu!='' and alku!='' 
			AND $eilasketa
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";

		$model = Tyovuoroot::model()->find($criteria);
		return $model->l_tunnit;
	}


	protected function totKpl($criteria,$kohdenID,$from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

        	$criteria->select = " COUNT(*) as count	";

        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";
		if($kohdenID != 'kaikki')
	        $criteria->addCondition (" kohdenID = '".$kohdenID."' ");

		$criteria->group = "kohdenID"; 


		return $criteria;
	}


	protected function totLu($criteria,$kohdenID,$from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND kohdenID ='".$kohdenID."' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";

		return $criteria;
	}


	protected function YhteensaLuMatka($tid,$from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));


		$return = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = " 
			tid='".$tid."'
			AND loppui!='' and aloitan!='' 
			AND status=2
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";

		$lu = Mobile::model()->find($criteria);

		if( isset($lu->l_tunnit) and $lu->l_tunnit > 0 )
			$return = $lu->l_tunnit;

		return $return;
	}


	protected function totLuYhteensa($criteria,$status,$from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));



		if($status != 2 and $status != 10)
		$kid = " AND kohdenID !='' ";
		else
		$kid = '';

        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='$status'
			$kid 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
		";


		return $criteria;
	}


	protected function yhtLU($from,$to){

		$from = date("Y-m-d", strtotime($from));

		$to = date("Y-m-d", strtotime($to));

       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,3,$from,$to);
		$l = Mobile::model()->find($cr1);

		$lu = $l->l_tunnit;

	return $lu;

	}

	protected function yhtTOT($from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,3,$from,$to);
		$cr1->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
		$tt = Mobile::model()->find($cr1);


       		$cr2 = new CDbCriteria();
		$this->totLuYhteensa($cr2,3,$from,$to);
		$tt2 = Toteutuneet::model()->find($cr2);
		
		$tot = $tt->l_tunnit+$tt2->l_tunnit;

	return $tot;

	}



	protected function yhtLUmatka($from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,2,$from,$to);
		$l = Mobile::model()->find($cr1);

		$lu = $l->l_tunnit;

	return $lu;

	}

	protected function yhtTOTmatka($from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

       		$cr1 = new CDbCriteria();
		$this->totLuYhteensa($cr1,2,$from,$to);
		$cr1->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
		$tt = Mobile::model()->find($cr1);

       		$cr2 = new CDbCriteria();
		$this->totLuYhteensa($cr2,2,$from,$to);
		$tt2 = Toteutuneet::model()->find($cr2);
		
		$tot = $tt->l_tunnit+$tt2->l_tunnit;

	return $tot;

	}

	public function actionTyobykohde($kohdenID,$from,$to)
	{
		$body 	= '<table class="table table-bordered sortable">';
		$from 	= date("Y-m-d", strtotime($from));
		$to 	= date("Y-m-d", strtotime($to));

		$lu = array();
		$ids = array();

		$fromTo = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ";

       		$criteria = new CDbCriteria();
        	$criteria->order = "kohde_kannasta";
        	$criteria->condition = "
			id NOT IN (select kid from sivexkuitti_repaired) 
			AND status='3'
			AND sairaus!=1
			AND kohdenID='".$kohdenID."'
			AND $fromTo
		";

		$model = Mobile::model()->findAll($criteria);
		foreach($model as $d){
			$kesto = 0;

		  	$d->loppui 	= date("d.m.Y H:i",strtotime($d->loppui));
		  	$d->aloitan 	= date("d.m.Y H:i",strtotime($d->aloitan));
			$kesto 		= strtotime($d->loppui)-strtotime($d->aloitan);

			$lu[] = $this->etuSukunimi($d->tid)."//".date("d.m.Y",strtotime($d->aloitan))."//".$kesto."//mobile_".$d->id."//".$d->asiakas_hyvaksy."//".date("H:i",strtotime($d->aloitan))."//".date("H:i",strtotime($d->loppui))."//////".$d->sairaus.'//'.strtotime($d->aloitan);
		}


       		$criteria = new CDbCriteria();
        	$criteria->order = "kohde_kannasta";
        	$criteria->condition = "
			status='3'
			AND sairaus!=1
			AND kohdenID='".$kohdenID."'
			AND $fromTo
		";

		$model = Toteutuneet::model()->findAll($criteria);
		foreach($model as $d){
			$kesto = 0;

		  	$d->loppui 	= date("d.m.Y H:i",strtotime($d->loppui));
		  	$d->aloitan 	= date("d.m.Y H:i",strtotime($d->aloitan));
		  	$kesto 		= strtotime($d->loppui)-strtotime($d->aloitan);

			$lu[] = $this->etuSukunimi($d->tid)."//".date("d.m.Y",strtotime($d->aloitan))."//".$kesto."//toteutu_".$d->id."//".$d->asiakas_hyvaksy."//".date("H:i",strtotime($d->aloitan))."//".date("H:i",strtotime($d->loppui))."//".$d->osoite."//".$d->tietoja."//".$d->sairaus.'//'.strtotime($d->aloitan);
		}

		//if(count($lu) > 0)
		//ksort($lu);

			$body .= 
			'<thead>
			 <tr>
			  <th>Pvm</th>
			  <th data-defaultsort="asc">Aloitus</th>
			  <th>Lopetus</th>
			  <th>Kesto</th>
			  <th>Työntekijä</th>
			  <th>Tietoja</th>
			 </tr>
			 </thead>
			 <tbody>';


		foreach($lu as $k=>$v)
		{
			$explV = explode("//",$v);

			$asiakas_hyvaksy = '';
			if(isset($explV[4]) and !empty($explV[4]) and isset($_GET['asiakkalle']) and $_GET['asiakkalle'] == 1)
			{
			  $exp = explode("_", $explV[4]);
			    if(isset($exp[0]) and $exp[0] == 0)
				$asiakas_hyvaksy = '<b class="fa fa-share pull-right text-warning"></b>';
			    if(isset($exp[0]) and $exp[0] == 1)
				$asiakas_hyvaksy = '<b class="glyphicon glyphicon-ok pull-right text-success"></b>';
			    if(isset($exp[0]) and $exp[0] == 2)
				$asiakas_hyvaksy = '<b class="glyphicon glyphicon-warning-sign pull-right text-danger"></b>';
			}

			if(isset($explV[0]) and isset($explV[1]) and isset($explV[2]))
			{

			$kertaosoite = '';
			if(isset($explV[7]) and !empty($explV[7]))
			$kertaosoite = ' ('.trim($explV[7]).') ';

			$tietoja = '';
			if(isset($explV[7]) and !empty($explV[8]))
			$tietoja = '<br><p>'.trim($explV[8]).'</p>';

			$spl = $this->sairausMerkki($explV[9]);

			$body .= 
			'<tr>
			  <td>'.$explV[1].'</td>
			  <td data-value="'.$explV[10].'">'.$explV[5].'</td>
			  <td>'.$explV[6].'</td>
			  <td>'.$this->sprint($explV[2]).' '.$asiakas_hyvaksy.'</td>
			  <td>'.$explV[0].$kertaosoite.$spl.'</td>
			  <td>'.$tietoja.'</td>
			</tr>';
			}
			if(isset($explV[3]))
			$ids[] = $explV[3];
		}

		$body 	.= '</tbody></table>';

		if(isset($_GET['asiakkalle']) and $_GET['asiakkalle'] == 1)
		{
		$body .= '<br>';
		$body .= '<div class="pull-right">';
		$body .= '<form action="asiakas_hyvaksyminen" method="POST">';
		$body .= '<input type="hidden" name="fromPosti" value="'.$from.'">';
		$body .= '<input type="hidden" name="toPosti" value="'.$to.'">';
		$body .= '<input type="hidden" name="ids" value="'.implode(",",$ids).'">';
		$body .= '<input type="hidden" name="kohdenID" value="'.$kohdenID.'">';
		$body .= '<input type="submit" class="btn btn-sm btn-success" value="'.Yii::t('main','Lähetä asiakkaalle hyväksyttäväksi').'">';
		$body .= '</form>';
		$body .= '</div>';
		}



		echo json_encode($body);
	}



	public function actionKyhteenveto_tuntemattomat()
	{

		if(Yii::app()->request->getPost('kohteet') == 'kaikki')
		unset(Yii::app()->session['kohteet']);

		if(Yii::app()->request->getPost('kohteet') and Yii::app()->request->getPost('kohteet') != 'kaikki')
		Yii::app()->session['kohteet'] = Yii::app()->request->getPost('kohteet');

		$from = date("d.m.Y");
		$to = date("d.m.Y");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}



       		$criteria = new CDbCriteria();
        	$criteria->select = "
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,kohde_kannasta,tekijan_nimi,aloitan,loppui
		";
        	$criteria->order = "kohde_kannasta";
        	$criteria->condition = "
			aloitan!='' AND loppui!=''
			AND kohdenID=''
			AND status='3'
			AND id NOT IN (select kid from sivexkuitti_repaired) 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($from))."' AND '".date('Y-m-d', strtotime($to))."' 			
		";


		$model = Mobile::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_kyhteenveto', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('kyhteenveto_tuntemattomat', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ));
		}
	}



	public function actionLahetys_asiakkaalle()
	{

		$from = date("Y-m-d");
		$to = date("Y-m-d");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}


       		$criteria = new CDbCriteria();
        	$criteria->select = "kohdenID,kohde_kannasta";
        	$criteria->order = "kohde_kannasta";
        	$criteria->group = "kohdenID";
        	$criteria->condition = "
			id NOT IN (select kid from sivexkuitti_repaired) 
			AND status='3'
			AND sairaus!=1
			AND kohdenID!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($from))."' AND '".date('Y-m-d', strtotime($to))."' 
		";

		if(isset($_POST['osoite']) and !empty($_POST['osoite'])){
		$criteria->addCondition  (" kohde_kannasta LIKE '%".$_POST['osoite']."%' ");
		}

		$model = Mobile::model()->findAll($criteria);
		$lu = array();
		foreach($model as $d){
			$lu[$d->kohde_kannasta] = $d->kohdenID;
		}



       		$criteria = new CDbCriteria();
        	$criteria->select = "kohdenID,kohde_kannasta";
        	$criteria->order = "kohde_kannasta";
        	$criteria->group = "kohdenID";


        	$criteria->condition = "
			status='3'
			AND sairaus!=1
			AND kohdenID!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' 
		";

		if(isset($_POST['osoite']) and !empty($_POST['osoite'])){
		$criteria->addCondition  (" kohde_kannasta LIKE '%".$_POST['osoite']."%' ");
		}

		$model = Toteutuneet::model()->findAll($criteria);
		foreach($model as $d){
			$lu[$d->kohde_kannasta] = $d->kohdenID;
		}

		if(count($lu) >0)
		ksort($lu);
	

		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('lahetys_asiakkaalle', array(
			'lu' => $lu,
			'from' => $from,
			'to' => $to
		  ));
		
	}


	public function actionKyhteenveto()
	{

/*

		if(Yii::app()->request->getPost('kohteet') == 'kaikki')
		unset(Yii::app()->session['kohteet']);

		if(Yii::app()->request->getPost('kohteet') and Yii::app()->request->getPost('kohteet') != 'kaikki')
		Yii::app()->session['kohteet'] = Yii::app()->request->getPost('kohteet');
		
		if(Yii::app()->request->getPost('mitkatKohteet'))
		Yii::app()->session['mitkatKohteet'] = Yii::app()->request->getPost('mitkatKohteet');
*/

		$from = date("d.m.Y");
		$to = date("d.m.Y");

		if(isset($_POST['from']) and isset($_POST['to'])){
		$from 	= $_POST['from'];
		$to 	= $_POST['to'];
		}



       		$criteria = new CDbCriteria();
        	$criteria->select = "kohdenID";
        	$criteria->order = "kohde_kannasta";
        	$criteria->group = "kohdenID";
        	$criteria->condition = "
			id NOT IN (select kid from sivexkuitti_repaired) 
			AND status='3' 
			AND sairaus!=1
			AND kohdenID!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($from))."' AND '".date('Y-m-d', strtotime($to))."' 
		";

		if(isset($_POST['osoite']) and !empty($_POST['osoite'])){
		$criteria->addCondition  (" kohde_kannasta LIKE '%".$_POST['osoite']."%' ");
		}


		$model = Mobile::model()->findAll($criteria);
		$lu = array();
		foreach($model as $d){
			$osoite = '';
			$k = Kohteet::model()->findBypk($d->kohdenID);
			if(isset($k->id))
			$osoite = $k->osoite;

			$lu[$osoite] = $d->kohdenID;
		}



       		$criteria = new CDbCriteria();
        	$criteria->select = "kohdenID";
        	$criteria->order = "kohde_kannasta";
        	$criteria->group = "kohdenID";

        	$criteria->condition = "
			status='3'
			AND sairaus!=1
			AND kohdenID!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($from))."' AND '".date('Y-m-d', strtotime($to))."' 
		";

		if(isset($_POST['osoite']) and !empty($_POST['osoite'])){
		$criteria->addCondition  (" kohde_kannasta LIKE '%".$_POST['osoite']."%' ");
		}

		$model = Toteutuneet::model()->findAll($criteria);
		foreach($model as $d){
			$osoite = '';
			$k = Kohteet::model()->findBypk($d->kohdenID);
			if(isset($k->id))
			$osoite = $k->osoite;
			$lu[$osoite] = $d->kohdenID;
		}

		if(count($lu) >0)
		ksort($lu);
	

		if(Yii::app()->request->getPost('tulosta'))
		{

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_kyhteenveto', array(
			'lu' => $lu,
			'from' => $from,
			'to' => $to
		  ),true));
	          $html2pdf->Output();
		} else {
		  //$dataProvider->pagination->pageSize = 50;
		  $this->render('kyhteenveto', array(
			'lu' => $lu,
			'from' => $from,
			'to' => $to
		  ));
		}
	}

	public function actionAsiakas_hyvaksyminen()
	{

		if(Yii::app()->request->getPost('laheta'))
		{

		  $length = 50;
		  $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);


	  	$model=new AsiakasHyvaksynta;
	  	$model->attributes=$_POST;
	  	$model->kirjen_body=$_POST['kirjen_body'];
	  	$model->code=$code;

	  	if($model->save())
		{



	$ids = explode(",",$_POST['ids']);
	foreach($ids as $val)
	{
	    $explVal = explode("_", $val);
	    if(isset($explVal[1]))
	    {

		if($explVal[0] == 'mobile') 
		{
		   Mobile::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'0_'.date("d.m.Y")));
		   //echo $explVal[1].'<br>';
		}

		if($explVal[0] == 'toteutu')
		{
		   Toteutuneet::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'0_'.date("d.m.Y")));
		   //echo $explVal[1].'<br>';
		}

	    }

	}


		  $message = json_decode($_POST['kirjen_body']);
		  $message .= '
			<br>
			<center>
			<a href="http://etunti.fi/index.php/site/hyvaksy?id='.$model->id.'&code='.$model->code.'&domain='.Yii::app()->user->domain.'">'.Yii::t('main','Hyväksy').'</a> &nbsp;&nbsp;&nbsp;
			<a href="http://etunti.fi/index.php/site/hylkaa?id='.$model->id.'&code='.$model->code.'&domain='.Yii::app()->user->domain.'">'.Yii::t('main','Hylkää').'</a>
			</center>

		  ';		 

	          $mail = new YiiMailer();
		  //$mail->clearLayout();//if layout is already set in config
		  $mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
		  $mail->setTo($_POST['sahkoposti']);
		  $mail->setSubject($_POST['otsikko']);
		  $mail->setBody($message);
	
		     if($mail->send()){

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $_POST['sahkoposti'];
							$log->email_subject	= $_POST['otsikko'];
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

		     		$this->redirect(array('kyhteenveto'));
		     } else {
				echo Yii::t('main','Sähköpostissa on vika');
		     }

		}
		if(!$model->save()){
		   var_dump($model->getErrors());
		}


		} else {
		  $this->render('asiakas_hyvaksyminen');
		}

	}

	public function toteutuneet($tid,$pvm,$status,$kohdenID)
	{
		$pvm = date("Y-m-d", strtotime($pvm));
		if($kohdenID !== null and $status == 3) $kohdenID = " AND kohdenID='".$kohdenID."' "; else $kohdenID = "";
		if($status !== null) $status = " AND status='".$status."' "; else $status = "";

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";
        	$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!='' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')='".$pvm."'
			$status $kohdenID 
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
			$status $kohdenID 
			AND sairaus!=1
		";
		$toteutuneet = Toteutuneet::model()->find($criteria);


		return $luetut->l_tunnit+$toteutuneet->l_tunnit;

	}


	public function toteutu($tid,$sivu,$from,$to)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

	
		$total_l 	= 0;
		$total_t 	= 0;
		$totalIlta 	= 0;
		$totalYo 	= 0;
		$totalSu	= 0;
		$total	 	= 0;
		$al		= '';
		$lop		= '';

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
		";

		if($sivu == 'palkkataulukko')
	        	$criteria->addCondition (" status = '3' ");


		if($sivu == 'yhteenveto')
		{
			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");
		}


	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		    $totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    if(date('N', strtotime($al[0])) == 7)
		    $totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
		";

		if($sivu == 'palkkataulukko')
	        	$criteria->addCondition (" status = '3' ");


		if($sivu == 'yhteenveto')
		{
			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");
		}

	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		    $totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    if(date('N', strtotime($al[0])) == 7)
		    $totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}

		$kaikki = array($total_l,$totalIlta,$totalYo,$totalSu);

		return $kaikki;

	}



	public function matkaIlta($tid,$from,$to)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$totalIlta 	= 0;


       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND status = '2'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";



		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));

		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);

		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND status = '2'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";


		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);

		}


		return $totalIlta;

	}



	public function lounaat($tid,$from,$to)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$total	= 0;


       		$criteria = new CDbCriteria();
        	$criteria->select = "

		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui

		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND status = '10'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";



		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		    $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));
		    $total += (strtotime($l->loppui)-strtotime($l->aloitan));
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND status = '10'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'

		";


		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		    $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));
		    $total += (strtotime($l->loppui)-strtotime($l->aloitan));
		}


		return $total;

	}


	public function actionKohdebytekija($tid,$from,$to)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

       		$criteria = new CDbCriteria();
        	$criteria->select = "COUNT(*) as count,
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit,
			kohde_kannasta
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!=''
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";
        	$criteria->group = "kohde_kannasta";

			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");

		$lu = Mobile::model()->findAll($criteria);

		    $return = array();
		    $ks = array();
		    $c = array();
		    $sum = 0;

		foreach($lu as $t)
		{
		    $kesto = '';
		    $kesto = $t->l_tunnit;
		    $sum += $t->l_tunnit;

		    if(!isset($ks[$t->kohde_kannasta])) { $ks[$t->kohde_kannasta] = 0; }
		    $ks[$t->kohde_kannasta] += $t->l_tunnit;

		    if(!isset($c[$t->kohde_kannasta])) { $c[$t->kohde_kannasta] = 0; }
		    $c[$t->kohde_kannasta] += $t->count;

		    $return[$t->kohde_kannasta] = array($t->kohde_kannasta, $t->count, null);
		}


       		$criteria = new CDbCriteria();
        	$criteria->select = "COUNT(*) as count, 
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as t_tunnit,
			kohde_kannasta
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		";
        	$criteria->group = "kohde_kannasta";

			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		    $return2 = array();

		foreach($tot as $t)
		{
		    $kesto = '';
		    $kesto = $t->t_tunnit;
		    $sum += $t->t_tunnit;

		    if(!isset($ks[$t->kohde_kannasta])) { $ks[$t->kohde_kannasta] = 0; }
		    $ks[$t->kohde_kannasta] += $t->t_tunnit;

		    if(!isset($c[$t->kohde_kannasta])) { $c[$t->kohde_kannasta] = 0; }
		    $c[$t->kohde_kannasta] += $t->count;

		    $return[$t->kohde_kannasta] = array($t->kohde_kannasta, $t->count, 'muokattu');
		}

		$model = $return;
		//ksort($return);
		//array_sum($ks);

		foreach($model as $k=>$result)
		{

		    $muokattu = '';
		    if($result[2] == 'muokattu')
		    $muokattu = 'text-danger';

			echo 
			'<div class="row">
			   <div class="col-sm-6 text-right '.$muokattu.'">'.$result[0].'</div>
			   <div class="col-sm-6">kesto: <b> '.$this->sprint($ks[$k]).' ('.$this->num($ks[$k]).')</b>, kerta: '.$c[$k].'</div>
			</div>';

		}

		echo '<h3 class="pull-right">'.Yii::t('main','Yhteensä').' '.$this->sprint($sum).' ('.$this->num($sum).')</h3>';

	}



	protected function sairausMerkki($val)
	{
	     $spl = '';
	  if($val == '1')
	     $spl = '<span style="color:red" class="small"> (SPL) </span>';
	  elseif($val == '2')
	     $spl = '<span style="color:red" class="small"> (SL) </span>';
	  elseif($val == '3')
	     $spl = '<span style="color:red" class="small"> (LS) </span>';
	
	  return $spl;
	}


	public function pyhapaivat($tid,$from,$to,$m)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

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

			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
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
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
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

	public function actionCheck_paallekkainMobile()
	{
		$count	= 0;
		$tid	= $_POST['tid'];
		$pvm	= $_POST['pvm'];
		$alku	= date("Y-m-d H:i:s", strtotime($_POST['alku']));
		$loppu	= date("Y-m-d H:i:s", strtotime($_POST['loppu']));
		

		$criteria=new CDbCriteria;
		$criteria->condition="
			tid='".$tid."'
			AND (
				DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i:%s') BETWEEN '".$alku."' AND '".$loppu."'
				OR DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i:%s') BETWEEN '".$alku."' AND '".$loppu."'
			)
		";
		$model = Mobile::model()->findAll($criteria);
		$count = count($model);
		echo json_encode($count);
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}


	protected function rivit($data, $kesto, $viesti, $spl){

	$r = '';
	$r .=  '<tr>';
	$r .= '<td style="width:11%">'.date("d.m.Y",strtotime($data->aloitan)).'</td>';
	$r .= '<td style="width:10%">'.$this->etuSukunimi($data->tid).$spl.'</td>';
	$r .= '<td style="width:18%">'.$data->kohde_kannasta.'</td>';
	$r .= '<td style="width:10%">'.date("H:i",strtotime($data->aloitan)).'</td>';
	$r .= '<td style="width:10%">'.date("H:i",strtotime($data->loppui)).'</td>';
	$r .= '<td style="width:10%">'.sprint($kesto).'</td>'; //<br><b>('.num($kesto).')</b>
	$r .= '<td style="width:25%">'.$viesti.'</td>';
	$r .= '</tr>';
	return $r;
	}


	public function actionTyoajan_seuranta()
	{
		$tid = '';
		$year = '';
		if(isset($_POST['tid']))
		{
			$t = Tyontekijat::model()->findByPk($_POST['tid']);
			if(isset($t->id))
			$tid = $t->id;
			if(isset($_POST['vuosi']))
			$year = $_POST['vuosi'];
		}

		if(Yii::app()->request->getPost('tulosta'))
		{
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tyoajan_seuranta', array(
			'tid'=>$tid,
			'year'=>$year,
		  ),true));
	          $html2pdf->Output();
		} else {
		$this->render('tyoajan_seuranta',array(
			'tid'=>$tid,
			'year'=>$year,
		));
		}

	}


	public function onkoRiviHyvaksytty($id)
	{

		$result = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit
		";

	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND id='".$id."'
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND hyvaksytty!=''
		";


		$lu = Mobile::model()->find($criteria);


       		$criteria = new CDbCriteria();
        	$criteria->select = "
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit
		";

	        $criteria->condition = " 
			kid='".$id."'
			AND hyvaksytty!=''
		";
		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->l_tunnit))
		$result = $lu->l_tunnit;

		if(isset($tot->l_tunnit))
		$result = $result+$tot->l_tunnit;


		return $result;
	}


}

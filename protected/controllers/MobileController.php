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
				'actions'=>array('admin', 'delete', 'create', 'update', 'index', 'index_a',
					'view', 'updatetime', 'showkohteet', 'yhteenveto', 'kyhteenveto', 
					'yhteenveto_m', 'historia', 'poistaKohde', 'total_suunniteltu', 
					'total_toteutu', 'total_luettu', 'kesto', 'index_ajax', 
					'raportit', 'uusirivi', 'palkkataulukko', 'tidfromtomatkat', 
					'tidfromtoSL', 'tidfromtoSPL', 'tyobykohde', 'asiakas_hyvaksyminen', 
					'kohdebytekija' ,'kyhteenveto_tuntemattomat', 'laskutettu', 
					'lahetys_asiakkaalle', 'get_tyovuorot_day', 'on_olemassa', 
					'luetut_toteutuneet_ero_pdf', 'vuosilomat_pdf', 
					'check_paallekkainMobile', 'tyoajan_seuranta', 'raportit_taulu', 
					'tulostus', 'hyvaksymattomat', 'ayhteenveto', 'ayhteenvetoyht',
					'palkkataulukkopost', 'selectedemployees', 'closestshift'),
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

		if(!isset(Yii::app()->user->adminStatus))
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


	public function actionTulostus()
	{
		if(isset($_POST['html_content']) and isset($_POST['ext']))
		{
			(isset($_POST['header']))? $header = $_POST['header'] : $header = '';
			$this->transformHtmlTo($header, $_POST['html_content'], $_POST['ext']);
			exit;
		}
		echo json_encode('false');
		exit;
	}

	public function transformHtmlTo($header, $html_content, $ext)
	{

		// Tämä funktio vaaditaan uudempi kun 16.0 versio pandoc ja apt-get install xvfb
		// wget https://github.com/jgm/pandoc/releases/download/1.17.0.2/pandoc-1.17.0.2-1-amd64.deb

		if (!file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain )) {
		 	mkdir( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain, 0777, true );
		}
		$tiedosto = 'temp_raporti_'.str_replace(" ", "_", Yii::app()->user->nimi);
                if(isset($_POST['fileName']) and isset($_POST['from']) and isset($_POST['to']))
		{
			$tiedosto=$_POST['fileName'].'_'.date("d.m.Y", strtotime($_POST['from'])).'-'.date("d.m.Y", strtotime($_POST['to']));
		}
  		
		$path = 'tiedostot/temp/'.Yii::app()->user->domain.'/';

		// <-- Poistetaan edelliset
		if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.html' ))
		{
		 	unlink($path.$tiedosto.'.html');
		}
		if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.xls' ))
		{
		 	unlink($path.$tiedosto.'.xls');
		}
		if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.doc' ))
		{
		 	unlink($path.$tiedosto.'.doc');
		}
		if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.pdf' ))
		{
			unlink($path.$tiedosto.'.pdf');
		}
		//     Poistetaan edelliset -->

		$asetukset=Asetukset::model()->findByPk(1);

		$c = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		$c .= '<style>'.file_get_contents(Yii::app()->basePath.'/../css/raportit_table2.css').'</style>';
		$c .= '</head><body>';


		if($ext == 'pdf' or $ext == 'doc')
		{
			$img = $asetukset->logon_polkku;
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($img);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

			$c .= '<table id="ylataulu">
			 <tr><td>
			  <img src="'.$base64.'" height="'.$asetukset->logon_korkeus.'">
			 </td><td align="right">
			    '.$header.'
			 </td>
			 </tr>
			</table>';
		}

		$c .= preg_replace("/(?=\>\s+\n|\n)+(\s+)/", '', $html_content);
		$c .= '</body>';
		$c .= '</html>';
		//echo $c;
		//exit;

		file_put_contents($path.$tiedosto.'.html', $c);

		$files_return = array();

		if($ext == 'pdf')
		{
			//echo 'xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf';
			//exit;
			exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf', $output, $return); //--orientation Landscape --title "Titulo: do PDF"
			if($output)
			{
			    if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.pdf' ))
			    {

				header("Content-Length: " . filesize ( $path.$tiedosto.'.pdf' ) ); 
		                header("Content-type: application/pdf"); 
		                header("Content-disposition: attachment; filename=".basename($path.$tiedosto.'.pdf'));
		                readfile($path.$tiedosto.'.pdf');
				exit;

			    }
			}
		}

		if($ext == 'doc')
		{
			exec('pandoc -s '.$path.$tiedosto.'.html -o '.$path.$tiedosto.'.doc', $output, $return);
		        if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.doc' ))
			{
				header("Content-Length: " . filesize ( $path.$tiedosto.'.doc' ) ); 
		                header("Content-type: application/vnd.ms-word"); 
		                header("Content-disposition: attachment; filename=".basename($path.$tiedosto.'.doc'));
		                header('Expires: 0');
		                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		                readfile($path.$tiedosto.'.doc');
				exit;
			}
		}

		if($ext == 'xls')
		{
			exec('pandoc -s '.$path.$tiedosto.'.html -o '.$path.$tiedosto.'.xls', $output, $return);
		        if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.xls' ))
			{
				header("Content-Length: " . filesize ( $path.$tiedosto.'.xls' ) ); 
		                header("Content-type: application/vnd.ms-excel;"); 
		                header("Content-disposition: attachment; filename=".basename($path.$tiedosto.'.xls'));
		                header('Expires: 0');
		                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		                readfile($path.$tiedosto.'.xls');
				exit;
			}
		}

		return false;

	}

	public function actionGet_tyovuorot_day($id)
	{
		$return = [];
		$tyovuorot = Yii::app()->createController('Tyovuoroot');
		$get_id 	= $tyovuorot[0]->this_id($id);
		if(isset($get_id['pvm'])){
			$return = array(
				'week'=>date("W", strtotime($get_id['pvm'])),
				'year'=>date("Y", strtotime($get_id['pvm'])),
				'tid'=>$get_id['tid'],
			);
			echo json_encode($return);
			exit;
		} else {
			$return['error'] = true;
		}
		echo json_encode($return);
		exit;
	}

	public function actionRaportit()
	{
		$asetukset = Asetukset::model()->findByPk(1);
		$tyovuorot = Yii::app()->createController('Tyovuoroot');

		// <-- Order tyontekijat
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

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
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " 
				aloitan!='' and loppui!='' 
				AND deleted=0
			";

			allCrit($criteria);

			$model = Mobile::model()->findAll($criteria); 
	
			if(isset($_POST['luoPDF']))
			{

				$content = '<link rel="stylesheet" type="text/css" href="../../css/raportit_table2.css">';
				$content .= $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Luetut'), true);
				//echo $content;
				//$this->transformContentTo($content, 'pdf');

				$header = '';
				$this->transformHtmlTo($header, $content, 'pdf');
			        exit;
			}

			if(isset($_POST['luoExcel']))
			{
			        $html = $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Luetut'),true);
				//preg_match_all('/<div class=\"tb\">(.*?)<\/div>/s',$html,$match);
				//$this->htmlToXls($match[0][0], 'luetut');
				$header = '';
				$this->transformHtmlTo($header, $html, 'xls');
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
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " 
				aloitan!='' and loppui!='' 
				AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) 
				AND deleted=0
			";

			allCrit($criteria);

			$lu = Mobile::model()->findAll($criteria);
  			foreach($lu as $data){
				if( isset($_POST['pvm_or_tid']) and $_POST['pvm_or_tid'] == 'tid' ){
					$model[$data->tekijan_nimi.strtotime($data->aloitan)] = $data;
				} else {
					$model[strtotime($data->aloitan)] = $data;
				}
			}

			/* tot */
		       	$criteria = new CDbCriteria();
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " 
				aloitan!='' and loppui!='' 
				AND deleted=0
			";

			allCrit($criteria);
			$tot = array();
			$tot = Toteutuneet::model()->findAll($criteria); 

  			foreach($tot as $data){
				if( isset($_POST['pvm_or_tid']) and $_POST['pvm_or_tid'] == 'tid' ){
					$model[$data->tekijan_nimi.strtotime($data->aloitan)] = $data;
				} else {
					$model[strtotime($data->aloitan)] = $data;
				}
			}

			if(count($model) > 0){
				ksort($model);
				//asort($model);
			}


			if(isset($_POST['luoPDF']))
			{
				/*
			        $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en', 'true', 'UTF-8', array(3,10,5,10));
				$html2pdf->setDefaultFont('Arial');
			        $html2pdf->WriteHTML($this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Hyväksytyt'),true));
			        $html2pdf->Output();
				*/
				$content = '<link rel="stylesheet" type="text/css" href="../../css/raportit_table2.css">';
				$content .= $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Hyväksytyt'), true);
				//$this->transformContentTo($content, 'pdf');
				$header = '';
				$this->transformHtmlTo($header, $content, 'pdf');
			        exit;
			}

			if(isset($_POST['luoExcel']))
			{
			        $html = $this->renderPartial('raportit_pdf_l', array('model' => $model, 'tyyppi' => 'Hyväksytyt'),true);
				//preg_match_all('/<div class=\"tb\">(.*?)<\/div>/s',$html,$match);
				//$this->htmlToXls($match[0][0], 'toteutuneet');
				$header = '';
				$this->transformHtmlTo($header, $html, 'xls');
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
		if(Yii::app()->request->getPost('method') == 'LuetutToteutuneetEro'){
			$from 	= date("Y-m-d", strtotime($_POST['from']));
			$to 	= date("Y-m-d", strtotime($_POST['to']));
			$haku_criteria 	= [];
			$tids 		= [];
			if(false != Yii::app()->request->getPost('tekija') and is_array(Yii::app()->request->getPost('tekija')) )
				$tids = Yii::app()->request->getPost('tekija');

			if(isset($_POST['kohteet']) and  $_POST['kohteet'] != 'kaikki')
		        	$haku_criteria[] = "kohde = '".$_POST['kohteet']."'";

			if(isset($_POST['siivousPaaSivulla']) and !empty($_POST['siivousPaaSivulla']))
		        	$haku_criteria[] = " kohde IN ( SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".$_POST['siivousPaaSivulla']."%' ) ";

			$dataAll 	= $tyovuorot[0]->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, ['data']);
			$tids_after 	= [];
			$newarr 	= [];
			foreach($dataAll as $k => $arr){
				$data 	= $arr['data'];
				if( isset($data->tt) and $data->status == 3 or $data->status == 0 ){
					$tt 	= $data->tt;
					$pvm 	= date("Ymd", strtotime($data->pvm));
					$tn	= trim($tt->$tt_order_1.' '.$tt->$tt_order_2);
					if(!isset($newarr[$pvm][$tn][$data->tid][$data->osoite]))
						$newarr[$pvm][$tn][$data->tid][$data->osoite] = 0;
					$newarr[$pvm][$tn][$data->tid][$data->osoite] += strtotime($data->loppu)-strtotime($data->alku);
					$tids_after[$data->tid] = $data->tid;
				}
			}
			ksort($newarr);
			$hyv_tyotunnit_all = $this->TidfromtoMobiiliAll($from, $to, $tids_after, [3], 3, false, 0, true, null, null);
			/*
			echo '<pre>';
			print_r( $newarr );
			echo '</pre>';
			exit;
			*/
			if(isset($_POST['luoPDF'])){
				$content = $this->renderPartial('luetut_toteutuneet_ero_pdf', ['dataAll' => $newarr, 'from'=>$_POST['from'],'to'=>$_POST['to'], 'hyv_tyotunnit_all' => $hyv_tyotunnit_all],true);
				$header = '';
				$this->transformHtmlTo($header, $content, 'pdf');
			        exit;
			}
			if(isset($_POST['luoExcel'])){
			        $html = $this->renderPartial('luetut_toteutuneet_ero_pdf', ['dataAll' => $newarr, 'from'=>$_POST['from'],'to'=>$_POST['to'], 'hyv_tyotunnit_all' => $hyv_tyotunnit_all],true);
				$header = '';
				$this->transformHtmlTo($header, $html, 'xls');
			        exit;
			}
			if(isset($_POST['luoPrintSivu'])){
				$html = '';
			        $html .= $this->renderPartial('luetut_toteutuneet_ero_pdf', ['dataAll' => $newarr, 'from'=>$_POST['from'],'to'=>$_POST['to'], 'hyv_tyotunnit_all' => $hyv_tyotunnit_all],true);
				echo $html;
			        exit;
			}

		}
		// Toteutuneen ja suunnitellun työn erot -->

		// <-- lomat Ja Poissaolot
		  if(Yii::app()->request->getPost('method') == 'lomatJaPoissaolot')
		  {
			$from 	= date("Y-m-d", strtotime($_POST['from']));
			$to 	= date("Y-m-d", strtotime($_POST['to']));
			$haku_criteria 	= [];
			$tids 		= [];
			if(false != Yii::app()->request->getPost('tekija') and is_array(Yii::app()->request->getPost('tekija')) )
				$tids = Yii::app()->request->getPost('tekija');

			if(isset($_POST['status']) and !empty($_POST['status'])){
				$impl = "tyoajanlaatu LIKE '%(". implode(")%' OR tyoajanlaatu LIKE '%(", $_POST['status']).")%'";
	        		$haku_criteria = $impl;
			}

			$dataAll 	= $tyovuorot[0]->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, ['data']);
			$newarr 	= [];
			foreach($dataAll as $k => $arr){
				$data 	= $arr['data'];
				if($data->status == 11){
					$tt 	= $data->tt;
					$pvm 	= date("Ymd", strtotime($data->pvm));
					$tn	= trim($tt->$tt_order_1.' '.$tt->$tt_order_2);
					if(!isset($newarr[$pvm][$tn][$data->tid][$data->tyoajanlaatu]))
						$newarr[$pvm][$tn][$data->tid][$data->tyoajanlaatu] = 0;
					$newarr[$pvm][$tn][$data->tid][$data->tyoajanlaatu] += 1;
				}
			}
			ksort($newarr);
			/*
			echo '<pre>';
			print_r( $newarr );
			echo '</pre>';
			exit;
			*/

			if(isset($_POST['luoPDF'])){
				$content = $this->renderPartial('vuosilomat_pdf', array('dataAll' => $newarr),true);
				$header = '';
				$this->transformHtmlTo($header, $content, 'pdf');
			        exit;
			}

			if(isset($_POST['luoExcel'])){
			        $html = $this->renderPartial('vuosilomat_pdf', array('dataAll' => $newarr),true);
				$header = '';
				$this->transformHtmlTo($header, $html, 'xls');
			        exit;
			}

			if(isset($_POST['luoPrintSivu'])){
				$html = '';
			        $html .= $this->renderPartial('vuosilomat_pdf', array('dataAll' => $newarr),true);
				echo $html;
			        exit;
			}

		  }
		//  lomat Ja Poissaolot -->


		} else {

			$this->render('raportit');

		}

	}

	protected function transformContentTo($content, $ext)
	{

			$tiedosto = 'temp_raporti_'.Yii::app()->getSession()->getSessionId();
			define('PHPDOCX_INCLUDE_PATH', (dirname(Yii::app()->basePath)).'/protected/vendors/phpdocx');
			spl_autoload_unregister(array('YiiBase','autoload'));
			require_once PHPDOCX_INCLUDE_PATH.'/lib/pdf/dompdf_config.inc.php';
			//require_once PHPDOCX_INCLUDE_PATH.'/classes/TransformDocAdv.inc';
			require_once PHPDOCX_INCLUDE_PATH.'/classes/CreateDocx.inc';
			spl_autoload_register(array('AutoLoader','load'));
			spl_autoload_register(array('YiiBase', 'autoload'));

			$path = 'tiedostot/temp/'.$tiedosto;
			$html = file_put_contents($path.'.html', $content);

			$transform = new TransformDocAdvLibreOffice();
			$transform->transformDocument($path.'.html', $path.'.'.$ext);


    			$filename = $path.'.'.$ext;

			$fileinfo = pathinfo($filename);
			$sendname = $fileinfo['filename'] . '.' . strtoupper($fileinfo['extension']);

			header('Content-Type: application/pdf');
			header("Content-Disposition: attachment; filename=\"$sendname\"");
			header('Content-Length: ' . filesize($filename));
			readfile($filename);

			unlink($path.'.html');
			unlink($path.'.'.$ext);
	}

	protected function htmlToXls($html, $nimike)
	{

			libxml_use_internal_errors(true);
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
			exit;
	}

	protected function htmlToXlsSaveToTempeDico($html, $nimike)
	{

			libxml_use_internal_errors(true);
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
			$path = 'tmp/'.Yii::app()->user->domain.'/'.$nimike.'.xls';
			$objPHPExcelWriter->save($path);
			unlink($tmpfile);
			return true;
	}

	protected function htmlToPDFSaveToTempeDico($html, $nimike)
	{
			define('PHPDOCX_INCLUDE_PATH', (dirname(Yii::app()->basePath)).'/protected/vendors/phpdocx');
			spl_autoload_unregister(array('YiiBase','autoload'));
			require_once PHPDOCX_INCLUDE_PATH.'/lib/pdf/dompdf_config.inc.php';
			//require_once PHPDOCX_INCLUDE_PATH.'/classes/TransformDocAdv.inc';
			require_once PHPDOCX_INCLUDE_PATH.'/classes/CreateDocx.inc';
			spl_autoload_register(array('AutoLoader','load'));
			spl_autoload_register(array('YiiBase', 'autoload'));

			$path = 'tmp/'.Yii::app()->user->domain.'/'.$nimike;
			$html = file_put_contents($path.'.html', $html);

			$transform = new TransformDocAdvLibreOffice();
			$transform->transformDocument($path.'.html', $path.'.pdf');


    			$filename = $path.'.'.$ext;

			$fileinfo = pathinfo($filename);
			$sendname = $fileinfo['filename'] . '.' . strtoupper($fileinfo['extension']);
			unlink($path.'.html');
			return true;
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


	public function actionRaportit_taulu()
	{

		$kohde_id = 0;
		$mob_or_tv = '';
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Luetut')
			$mob_or_tv = 'mob';
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksynta')
			$mob_or_tv = 'mob';
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksytyt')
			$mob_or_tv = 'mob';
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Suunnitellut')
			$mob_or_tv = 'tv';

		$from = date("d.m.Y", strtotime('first day of this month'));
		$to = date("d.m.Y");

		if(isset($_GET['from']))
			$from = date("d.m.Y", strtotime($_GET['from']));
		if(isset($_GET['to']))
			$to = date("d.m.Y", strtotime($_GET['to']));

		$criteria = new CDBCriteria;
		if( $mob_or_tv == 'mob' ){
			$criteria->condition = " 
			id IN ( SELECT tid FROM sivexkuitti	
				WHERE DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
				BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."' 
				)
			";
		}

		if(isset($_GET['tekijaPaaSivulla'])){
			$impl = implode(",", $_GET['tekijaPaaSivulla']);
	        	$criteria->addCondition (" id IN ($impl) ");
		} else {
	        	$criteria->addCondition (" id=0 ");
		}

		if(isset($_GET['osoite']) and !empty($_GET['osoite'])){
			$k = Kohteet::model()->find(" osoite='".$_GET['osoite']."' ");
			if(isset($k->id)){ $kohde_id = $k->id; }
		}


		$model = Tyontekijat::model()->findAll($criteria);

		$tids = (isset($_GET['tekijaPaaSivulla']))?array_values($_GET['tekijaPaaSivulla']):[];
		$dataAll = [];
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Suunnitellut'){
			$tyovuorot = Yii::app()->createController('Tyovuoroot');
			$haku_criteria = [];
			if($kohde_id > 0){ $haku_criteria[] = " kohde='".$kohde_id."' "; }
			$dataAll = $tyovuorot[0]->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, ['data', 'tv_kesto']);
		}

		$this->render('raportit_taulu', array(
			'model' => $model,
			'from' => $from,
			'to' => $to,
			'kohde_id' => $kohde_id,
			'mob_or_tv' => $mob_or_tv,
			'dataAll' => $dataAll,
		));


	}

	protected function tidFromTo_luetut($tid, $from, $to, $kohde_id)
	{

			$model = array();

			/* lu */
		       	$criteria = new CDbCriteria();
        		$criteria->select = "
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit, t.*
			";

			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " aloitan!='' and loppui!='' 
				AND tid='".$tid."'
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
				BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			";

			if($kohde_id > 0){ $criteria->addCondition (" kohdenID='".$kohde_id."' "); }

			$lu = Mobile::model()->findAll($criteria);
  			foreach($lu as $data){
				$model[strtotime($data->aloitan)] = $data;
			}

			if(count($model) > 0)
			ksort($model);

		return $model;
	}


	protected function tidFromTo_toteutuneet($tid, $from, $to, $kohde_id, $type)
	{

			$model = array();

			/* lu */
		       	$criteria = new CDbCriteria();
        		$criteria->select = "
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit, t.*
			";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " aloitan!='' and loppui!='' 
				AND tid='".$tid."'
				AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) 
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
				BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
				AND deleted=0
			";
			if($kohde_id > 0){ $criteria->addCondition (" kohdenID='".$kohde_id."' "); }
			if($type == 'Hyvaksytyt'){ $criteria->addCondition (" hyvaksytty!='' "); }

			$lu = Mobile::model()->findAll($criteria);
  			foreach($lu as $data){
				$model[strtotime($data->aloitan)] = $data;
			}

			/* tot */
		       	$criteria = new CDbCriteria();
        		$criteria->select = "
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit, t.*
			";
			$criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') ASC ";
			$criteria->condition = " aloitan!='' and loppui!='' 
				AND tid='".$tid."'
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
				BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
				AND deleted=0
			";
			if($kohde_id > 0){ $criteria->addCondition (" kohdenID='".$kohde_id."' "); }
			if($type == 'Hyvaksytyt'){ $criteria->addCondition (" hyvaksytty!='' "); }

			$tot = array();
			$tot = Toteutuneet::model()->findAll($criteria); 

  			foreach($tot as $data){
				$model[strtotime($data->aloitan)] = $data;
			}

			if(count($model) > 0)
			ksort($model);

		return $model;
	}

	public function actionTotal_luettu($tid)
	{

		$this->renderPartial('total_luettu',array(
			'tid'=>$tid,
		));

	}

	public function actionHyvaksymattomat()
	{
		$lista = array();

		$from = date("Y-m-d", strtotime("-1 year"));
		$to = date("Y-m-d");
		if( isset($_GET['from']) and !empty($_GET['from']) and isset($_GET['to']) and !empty($_GET['to']) ){
		        $from = date("Y-m-d", strtotime($_GET['from']));
			$to = date("Y-m-d", strtotime($_GET['to'])); 
		}

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND status='3'
			AND sairaus!=1
			AND hyvaksytty=''
			AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND deleted=0
		";
		if( isset($_GET['osoite']) and !empty($_GET['osoite']) ){
		        $criteria->addCondition(" kohde_kannasta LIKE '%".$_GET['osoite']."%' "); 
		}
		if( isset($_GET['tid']) and !empty($_GET['tid']) and $_GET['tid'] != 'kaikki' ){
		        $criteria->addCondition(" tid='".$_GET['tid']."' "); 
		}
		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi']))
		{
	        $criteria->addCondition ("  
			kohdenID IN ( 
			SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
				( SELECT id FROM asiakkaat 
					WHERE yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR CONCAT(etunimi , ' ' , sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%'
				)
			)
		");
		}
		$dataProviderLu=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

       		$criteria = new CDbCriteria();
	        $criteria->order = " DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') DESC ";
	        $criteria->condition = " 
			aloitan!='' AND loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."'
			AND status='3'
			AND sairaus!=1
			AND hyvaksytty=''
		";
		if( isset($_GET['osoite']) and !empty($_GET['osoite']) ){
		        $criteria->addCondition(" kohde_kannasta LIKE '%".$_GET['osoite']."%' "); 
		}
		if( isset($_GET['tid']) and !empty($_GET['tid']) and $_GET['tid'] != 'kaikki' ){
		        $criteria->addCondition(" tid='".$_GET['tid']."' "); 
		}
		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi']))
		{
	        $criteria->addCondition ("  
			kohdenID IN ( 
			SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
				( SELECT id FROM asiakkaat 
					WHERE yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR CONCAT(etunimi , ' ' , sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%'
				)
			)
		");
		}
		$dataProviderTot=new CActiveDataProvider('Toteutuneet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$lista = $dataProviderLu;
		if( is_array($dataProviderTot) and count($dataProviderTot) > 0 ){ $lista = array_merge($dataProviderLu, $dataProviderTot); }



		$lista->pagination->pageSize = 50;

		$this->render('hyvaksymattomat', array('dataProvider' => $lista));

	}

	public function actionTotal_toteutu($tid)
	{

		$this->renderPartial('total_toteutu',array(
			'tid'=>$tid,
		));

	}

	public function actionIndex_ajax()
	{
		if(!isset($_SESSION['domain']))
		{
			die('Error domain');
			exit;
		}

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";
	        //$criteria->condition = " ";
		$model = Mobile::model()->find($criteria);
		if(isset($model->id) and isset($_POST['setRivi'])){
			$this->renderPartial('_view', array('data' => $model));
		} elseif(isset($model->id) and !isset($_POST['setRivi'])) {
			echo $model->id;
		}
		exit;
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
		$vanha_attr 	= $model->attributes;

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

				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Mobile';
				$name_log 	= 'Tunnit';
				$status_log 	= 'Update by admin';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

			// <-- Kirjoitetaan historia luettut tietokantaan
			/*
			$this->renderPartial('//mobile/historia',array(
			'id'=>$model->id,
			'tilanne'=>"Luetut",
			'kohde_kannasta'=>array('vanha'=>$kohde_kannasta, 'uusi'=>$model->kohde_kannasta),
			'aloitan'=>array('vanha'=>$aloitan, 'uusi'=>$model->aloitan),
			'loppui'=>array('vanha'=>$loppui, 'uusi'=>$model->loppui),
			'tekijan_nimi'=>array('vanha'=>$tekijan_nimi, 'uusi'=>$model->tekijan_nimi),
			));
			*/
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
			if( isset($k->id) ) { $model->kohde_kannasta=$k->osoite; } 
			$model->admin=1;
			// there's no seconds in the format when submitting through uusirivi,
			// the UI actually disallows it.
			$oldFormat = "d.m.Y H:i";
			$newFormat = "Y-m-d H:i:s";
			
			$startDate = DateTime::createFromFormat($oldFormat, $model->aloitan);
			$endDate = DateTime::createFromFormat($oldFormat, $model->loppui);
			// also create v2 versions of hours entries
			$hour = new Hours();
			$hour->property_id = $model->kohdenID;
			$hour->starting_time = $startDate->format($newFormat);
			$hour->ending_time = $endDate->format($newFormat);
			$hour->worker_id = $model->tid;
			$hour->status = $model->status;
			$hour->shift_id = $model->tv_id;
			$hour->calculateDurations();

			$hour->save();

			$salaryHour = new SalaryHours();
			$salaryHour->attributes = $hour->attributes;
			unset($salaryHour->id);
			$salaryHour->hours_id = $hour->id;
			$salaryHour->approved = 0;
			$salaryHour->version = 1;

			$invoiceHour = new InvoiceHours();
			$invoiceHour->attributes = $salaryHour->attributes;
			$invoiceHour->invoiced = 0;
			
			$salaryHour->save();
			$invoiceHour->save();

			$model->hours_id = $hour->id;
			$model->salary_id = $salaryHour->id;
			$model->invoice_id = $invoiceHour->id;

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

	/**
	 * Returns the closest workshift it can find when the request
	 * has employee_id, property_id and starting_timestamp defined.
	 * starting_timestamp is a date time string in DD.MM.YYYY HH:mm:ss
	 * or DD.MM.YYYY HH:mm:ss format.
	 * employee_id should be an ID of a valid employee (Tyontekijat model)
	 * property_id should be an ID of a valid property (Kohdet model)
	 * 
	 * Returns either a shift (Tyovuoroot) object or null.
	 */
	public function actionClosestshift()
	{
		$req = Yii::app()->request;
		$status = $req->getQuery("status");
		// exit early if status is anything but 3 (work)
		if($status != 3) {
			echo null;
			return;
		}
		$start = $req->getQuery("starting_timestamp");
		$startDate = DateTime::createFromFormat("d.m.Y H:i", $start);
		if($startDate === false) {
			$startDate = DateTime::createFromFormat("d.m.Y H:i:s", $start);
		}
		$employeeId = $req->getQuery("employee_id");
		$propertyId = $req->getQuery("property_id");
		if($startDate !== false && $employeeId && $propertyId) {
			$closestShift = Tyovuoroot::findClosestShift($startDate->format("Y-m-d H:i:s"), $propertyId, $employeeId,);
			if($closestShift) {
				echo CJavaScript::jsonEncode($closestShift);
			}
		}
		echo null;
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
				AND deleted=0
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
				AND deleted=0
			";
			$check = Mobile::model()->find($criteria);
			if(isset($check->id))
			{
				$isLine = true;
			}

			if($isLine == false)
			{
				// create v2 hours
				$hour = new Hours();
				$hour = $hour->copyFromToteutuneetOrMobile($model);
				$hour->save();

				$salaryHour = new SalaryHours();
				$salaryHour = $salaryHour->copyFromToteutuneetOrMobile($model);
				$salaryHour->hours_id = $hour->id;

				$invoiceHour = new InvoiceHours();
				$invoiceHour = $invoiceHour->copyFromToteutuneetOrMobile($model);
				$salaryHour->hours_id = $hour->id;

				
				$salaryHour->save();
				$invoiceHour->save();

				$model->hours_id = $hour->id;
				$model->salary_id = $salaryHour->id;
				$model->invoice_id = $invoiceHour->id;

				if($model->save())
				{
				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Mobile';
				$name_log 	= 'Tunnit';
				$status_log 	= 'Create by admin';
	
					$old_values = null;
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->
				}
			}

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

			$vanha_attr = $model->attributes;
			$model->attributes=$_POST['Mobile'];


			if(isset($_POST['Mobile']['status']) and empty($_POST['Mobile']['loppui']) and $_POST['Mobile']['status'] == 3)
			$model->status=1;

			if($model->save())
			{

				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Mobile';
				$name_log 	= 'Tunnit';
				$status_log 	= 'Update by admin';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->


				// <-- Kirjoitetaan historia luettut tietokantaan
				/*
				$this->renderPartial('//mobile/historia',array(
				'id'=>$model->id,
				'tilanne'=>"Luetut",
				'kohde_kannasta'=>array('vanha'=>$kohde_kannasta, 'uusi'=>$model->kohde_kannasta),
				'aloitan'=>array('vanha'=>$aloitan, 'uusi'=>$model->aloitan),
				'loppui'=>array('vanha'=>$loppui, 'uusi'=>$model->loppui),
				'tekijan_nimi'=>array('vanha'=>$tekijan_nimi, 'uusi'=>$model->tekijan_nimi),
				));
				*/
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
		$model = $this->loadModel($id);
		$model->delete();
		// delete v2 hours
		if(isset($model->salary_id)) {
			$salary = SalaryHours::model()->findByPk($model->salary_id);
			if($salary) {
				$salary->delete();
				$salary->deleteOldVersions($salary);
			}
		}
		if(isset($model->invoice_id)) {
			$invoice = InvoiceHours::model()->findAllByPk($model->invoice_id);
			if($invoice) {
				$invoice->delete();
				$invoice->deleteOldVersions($invoice);
			}
		}


		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionPoistaKohde()
	{
		$mobile = $this->loadModel($_POST['id']);
		$mobile->delete();
		// delete v2 hours
		if(isset($mobile->salary_id)) {
			$salary = SalaryHours::model()->findByPk($mobile->salary_id);
			if($salary) {
				$salary->delete();
				$salary->deleteOldVersions($salary);
			}
		}
		if(isset($mobile->invoice_id)) {
			$invoice = InvoiceHours::model()->findByPk($mobile->invoice_id);
			if($invoice) {
				$invoice->delete();
				$invoice->deleteOldVersions($invoice);
			}
		}
		//Toteutuneet::model()->deleteAll(" kid='".$_POST['id']."' ");
		// delete v2 hours bound to Toteutuneet
		$id = $_POST["id"];
		$toteutuneetArr = Toteutuneet::model()->findAll(" kid=$id ");
		foreach($toteutuneetArr as $toteutunut) {
			$toteutunut->delete();

			if(isset($toteutunut->salary_id)) {
				$salary = SalaryHours::model()->findByPk($toteutunut->salary_id);
				if($salary) {
					$salary->delete();
					$salary->deleteOldVersions($salary);
				}
			}
			if(isset($toteutunut->invoice_id)) {
				$invoice = InvoiceHours::model()->findByPk($toteutunut->invoice_id);
				if($invoice) {
					$invoice->delete();
					$invoice->deleteOldVersions($invoice);
				}
			}
		}
	}




	public function actionLaskutettu()
	{


		if(isset($_POST['ajax']) and isset($_POST['id']))
		{
 			if(isset($_POST['tot']) and $_POST['tot'] == '1')
 			{
				$tot_table = Toteutuneet::model()->find("kid='".$_POST['id']."'");
				if(isset($tot_table->id))
				{
					Toteutuneet::model()->updatebypk($tot_table->id, array('laskutettu'=>$_POST['las']));
					$mob_table = Mobile::model()->findbypk($tot_table->kid);
					if( isset($mob_table->id) ){
						Mobile::model()->updatebypk($mob_table->id, array('laskutettu'=>$_POST['las']));
					}
				}				

			} else {
			  Mobile::model()->updatebypk($_POST['id'], array('laskutettu'=>$_POST['las']));
			}

			  echo $_POST['id']." ".$_POST['las'];
			  exit;
		}

   		$criteria = new CDbCriteria();
    	$criteria->order = " 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < DATE_ADD(NOW(), interval 4 hour) AND status=3 AND loppui='' DESC, 
			time and status=3 AND loppui='' DESC, 
			time DESC ";

		$criteria->condition = " 
			deleted=0 AND status=3
		";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->addCondition (" kohdenID IN ( SELECT id FROM sivex_kohdet WHERE tyoryhma IN ($ids) ) ");
		}
		//    Tyoryhmat -->

		if(isset($_GET['tekijaPaaSivulla']) and !empty($_GET['tekijaPaaSivulla']) and $_GET['tekijaPaaSivulla'] != 'kaikki')
	        $criteria->addCondition (" tid = '".$_GET['tekijaPaaSivulla']."' ");

		if(isset($_GET['siivousPaaSivulla']) and !empty($_GET['siivousPaaSivulla']))
	        $criteria->addCondition (" kohdenID IN ( SELECT id FROM sivex_kohdet WHERE siivous LIKE '%".$_GET['siivousPaaSivulla']."%' ) ");


		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi']))
		{
	        $criteria->addCondition ("  
			kohdenID IN ( 
			SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
				( SELECT id FROM asiakkaat 
					WHERE yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR CONCAT(etunimi , ' ' , sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%'
				)
			)
		");
		}
		if(isset($_GET['asiakasryhma']) and !empty(trim($_GET['asiakasryhma']))){
	        $criteria->addCondition ("  
			kohdenID IN ( 
			SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
				( SELECT id FROM asiakkaat 
					WHERE ryhma LIKE '%".$_GET['asiakasryhma']."%'
				)
			)
		");
		}

		if(isset($_GET['osoite']) and !empty($_GET['osoite'])){
	        $criteria->addCondition (" kohde_kannasta LIKE '%".$_GET['osoite']."%' ");
		}

		// <-- From to HAKU
		if(isset($_GET['fromP']) and !empty($_GET['fromP']) and isset($_GET['toP']) and !empty($_GET['toP'])){
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date("Y-m-d", strtotime($_GET['fromP']))."' AND '".date("Y-m-d", strtotime($_GET['toP']))."' ");
		} elseif(isset($_GET['fromP']) and empty($_GET['fromP']) and isset($_GET['toP']) and !empty($_GET['toP'])){
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') <= '".date("Y-m-d", strtotime($_GET['toP']))."' ");
		} elseif(isset($_GET['fromP']) and !empty($_GET['fromP']) and isset($_GET['toP']) and empty($_GET['toP'])){
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') >= '".date("Y-m-d", strtotime($_GET['fromP']))."' ");
		}
		//    From to HAKU -->

		if(isset($_GET['laskutettu']) and $_GET['laskutettu'] == '1')
	        $criteria->addCondition (" laskutettu=1 ");

		if(isset($_GET['laskutettu']) and $_GET['laskutettu'] == '3')
	        $criteria->addCondition (" laskutettu=0 ");


		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;

		$this->render('laskutettu', array('dataProvider' => $dataProvider));
	}

	public function actionIndex()
	{

		if( isset($_POST['geterittelyt'])){
			$mobile = Mobile::model()->findByPk($_POST['mob_id']);
			$tv = Tyovuoroot::model()->findByPk($_POST['tv_id']);
	 		if(is_array(json_decode($tv->tyo_erittelyt, true))){
			$body = '';
			 foreach(json_decode($tv->tyo_erittelyt, true) as $k => $v){
			 $body .= '<div class="row">
			  <div class="col-sm-11">';
			   if( isset($mobile->id) ){
			    $body .= '<input type="text" name="Tyovuoroot[tyo_erittelyt][]" class="form-control input-sm" value="'.$v.'" readonly>';
			   } else {
			    $body .= '<input type="text" name="Tyovuoroot[tyo_erittelyt][]" class="form-control input-sm" value="'.$v.'">';
			   }
			    $body .= '</div><div class="col-sm-1 text-right">';

			   if( isset($mobile->id) and is_array(json_decode($mobile->tyo_erittelyt, true)) and in_array($k, json_decode($mobile->tyo_erittelyt, true)) ){
			    $body .= '<span class="text-success fa fa-check"></span>';
			   }
			  $body .= '</div></div>';
			 }
			}
			echo json_encode($body);
			exit;
		}

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

		if(isset($_POST['osoite']) and empty($_POST['osoite']))
			unset(Yii::app()->session['osoite']);
		else if(isset($_POST['osoite']) and !empty($_POST['osoite']))
			Yii::app()->session['osoite'] = Yii::app()->request->getPost('osoite');

		if(!isset(Yii::app()->session['fromP']))
			Yii::app()->session['fromP'] = date("Y-m-d",strtotime("-1 month"));
		if(!isset(Yii::app()->session['toP']))
			Yii::app()->session['toP'] = date("Y-m-d");
			
		if(Yii::app()->request->getPost('fromP'))
		Yii::app()->session['fromP'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('fromP')));

		if(Yii::app()->request->getPost('toP'))
		Yii::app()->session['toP'] = date("Y-m-d",strtotime(Yii::app()->request->getPost('toP')));

		if(Yii::app()->request->getPost('tunni_status') and Yii::app()->request->getPost('tunni_status') != 'kaikki')
		Yii::app()->session['tunni_status'] = Yii::app()->request->getPost('tunni_status');

		// set approved search param into session
		if(Yii::app()->request->getPost("approved")) {
			Yii::app()->session["approved"] = Yii::app()->request->getPost("approved");
		}

   		$criteria = new CDbCriteria();
    	$criteria->order = " 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') < DATE_ADD(NOW(), interval 4 hour) AND status IN (1,2,10) AND loppui='' DESC, 
			time and status IN (1,2,10) AND loppui='' DESC, 
			time DESC ";

			    $criteria->condition = " 
				deleted=0
		";

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
        		$criteria->addCondition (" tid IN ($ids)");
		}
		//    Tyoryhmat -->

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
					WHERE yrityksen_nimi LIKE '%".Yii::app()->session['yrityksen_nimi']."%' OR CONCAT(etunimi,' ',sukunimi) LIKE '%".Yii::app()->session['yrityksen_nimi']."%'
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

		if(isset(Yii::app()->session['osoite']))
	        $criteria->addCondition (" kohde_kannasta LIKE '%".Yii::app()->session['osoite']."%' ");
		
		if(isset(Yii::app()->session["approved"])) {
			$approved = Yii::app()->session["approved"];
			// 1 = approved
			// 2 = not approved
			if($approved == 1) {
				$criteria->addCondition(" hyvaksytty != '' ");
			} else if($approved == 2) {
				$criteria->addCondition(" hyvaksytty = '' ");
			}
		} 


		$dataProvider=new CActiveDataProvider('Mobile', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;

		// <-- Tyovuorot
		$pvms = [];
		$tids = [];
		foreach($dataProvider->getData() as $item){
			$pvms[strtotime($item->aloitan)] = strtotime($item->aloitan);
			$tids[$item->tid] = $item->tid;
		}
		$haku_criteria 	= " peruutettu='0' OR peruutettu IS NULL";
		$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
		$tv_arr 	= $tyovuorot[0]->tv_arr(date("Y-m-d", min($pvms)), date("Y-m-d", max($pvms)), $tids, $haku_criteria, true, []);
		//     Tyovuorot -->

		// <-- Check Laskutetut
		$start    	= (new DateTime(Yii::app()->session['fromP'].' 00:00:00'));
		$end      	= (new DateTime(Yii::app()->session['toP'].' 23:59:59'));
		$interval 	= DateInterval::createFromDateString('1 month');
		$period   	= new DatePeriod($start, $interval, $end);
		$kks 		= [];
		$laskutetut_ids = [];

		foreach ($period as $dt)
		{
			$kks[$dt->format("m.Y")] = 'la_'.$dt->format("m.Y").'_';
		}
		if(count($kks) > 0)
		{
			$query 			= "etunti_tunniste LIKE '".implode("%' OR etunti_tunniste LIKE '", $kks)."%'";
			$laskutetut_ids = Lasku::LaskutetutIDs('mobiili_id', $query);
		}
		//  Check Laskutetut -->

		if(isset($_POST['index_ajax']))
		{
			echo $this->renderPartial('index_a', array('dataProvider' => $dataProvider, 'tv_arr' => $tv_arr, 'laskutetut_ids' => $laskutetut_ids));
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
		$al_day =  date("d", strtotime($al[0]));
		$al_hours =  strtotime($al[1]);
		$lop_day =  date("d", strtotime($lop[0]));
		$lop_hours =  strtotime($lop[1]);

	  	if( $al_day == $lop_day	and $lop_hours > $al_hours) {
	  		if( $al_hours <= strtotime("18:00") and $lop_hours > strtotime("18:00") ) {
				$str_al = strtotime("18:00");
  				$str_lop = $lop_hours;
				$sum = ($str_lop-$str_al);
				if( $sum > 18000 ){ $sum = 18000; } // 5h
 				$totalIlta += $sum;
			}
	  		if( $al_hours > strtotime("18:00") and $al_hours < strtotime("23:00") and $lop_hours <= strtotime("23:00")) {
				$str_al = $al_hours;
  				$str_lop = $lop_hours;
				$sum = ($str_lop-$str_al);
 				$totalIlta += $sum;
			}
	  		if( $al_hours > strtotime("18:00") and $al_hours < strtotime("23:00") and $lop_hours > strtotime("23:00")) {
				$str_al = $al_hours;
  				$str_lop = strtotime("23:00");
				$sum = ($str_lop-$str_al);
 				$totalIlta += $sum;
			}
		}


		return $totalIlta;
	}


	public function yo($al,$lop){

		$totalYo = 0;
		$al_day =  date("d", strtotime($al[0]." ".$al[1]));
		$lop_day =  date("d", strtotime($lop[0]." ".$lop[1]));

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
		and $lop_day == $al_day
		)
		{
	   	  $strAl0 = strtotime($al[0]." 23:00");
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) > strtotime($lop[0]." 06:00")
		and $lop_day > $al_day
		)
		{
	   	  $strAl0 = strtotime($al[0]." 23:00");
	   	  $strLop0 = strtotime($lop[0]." 06:00");

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}


	  	if(strtotime($al[0]." ".$al[1]) <= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 06:00")
		and $lop_day > $al_day
		)
		{
	   	  $strAl0 = strtotime($al[0]." 23:00");
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if(strtotime($al[0]." ".$al[1]) >= strtotime($al[0]." 23:00")
		and strtotime($lop[0]." ".$lop[1]) <= strtotime($lop[0]." 23:59")
		and $lop_day == $al_day
		)
		{
	   	  $strAl0 = strtotime($al[0]." ".$al[1]);
	   	  $strLop0 = strtotime($lop[0]." ".$lop[1]);

	 	  $str = ($strLop0-$strAl0);
	      	  $totalYo += $str;
		}

	  	if( strtotime($al[0]." ".$al[1]) > strtotime($al[0]." 23:00") 
		and strtotime($lop[0]." ".$lop[1]) > strtotime($lop[0]." 06:00")
		and $lop_day > $al_day
		)
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

/*
	public function actionTidfromtomatkat($from,$to,$tid)
	{
	
		$this->renderPartial('palkkataulukko', array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$tid
		));
	
	}
*/
	public function AsiakasPvmLuTotSuunArray($asiakas_id, $from, $to, $tilanne)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		if($tilanne == 'luetut' or $tilanne == 'toteutuneet'){

	       		$criteria = new CDbCriteria();
		        $criteria->condition = " 
				aloitan!='' AND loppui!=''
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to'
				AND status='3'
				AND deleted=0
			";
			if( $asiakas_id > 0 ){
			        $criteria->addCondition (" 
					kohdenID IN(
						SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas_id."'
					)
				");
			}
			if($tilanne == 'toteutuneet')
				$criteria->addCondition(" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");

			$luetut = Mobile::model()->findAll($criteria);

       			$criteria = new CDbCriteria();
		        $criteria->condition = " 
				aloitan!='' AND loppui!=''
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to'
				AND status='3'
				AND deleted=0
			";
			if( $asiakas_id > 0 ){
			        $criteria->addCondition (" 
					kohdenID IN(
						SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas_id."'
					)
				");
			}
			$toteutuneet = Toteutuneet::model()->findAll($criteria);
		}

		if($tilanne == 'suunnitelut'){

			$tids 		= [];
			$with		= ['data','kohteet'];

			$haku_criteria = [];
			if( $asiakas_id > 0 ){
				$haku_criteria[] = "
					kohde IN(
						SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas_id."'
					)
				";
			}
			$haku_criteria[] = "
				status='3'
				AND (peruutettu=0 OR peruutettu IS NULL)
			";

			$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
			$dataAll 	= $tyovuorot[0]->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, $with);
			$suunnitelut 	= $dataAll;

			/*
			echo '<pre>';
			print_r( $dataAll );
			echo '</pre>';
			exit;
			*/
		}

		if( $tilanne == 'suunnitelut'){
			$result = $suunnitelut; 
		}
		if( $tilanne == 'luetut'){
			$result = $luetut; 
		}
		if( $tilanne == 'toteutuneet'){
			$result = array_merge($luetut, $toteutuneet); 
		}
		return $result;
	}

	public function AsiakasPvmLuTotSuunArrayYht($asiakas_id, $from, $to, $tilanne)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		if($tilanne == 'luetut' or $tilanne == 'hyvaksynta'  or $tilanne == 'hyvaksytyt'){

	       		$criteria = new CDbCriteria();
		        $criteria->condition = " 
				aloitan!='' AND loppui!=''
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to'
				AND status='3'
				AND deleted=0
			";
			if( $asiakas_id > 0 ){
			        $criteria->addCondition (" 
					kohdenID IN(
						SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas_id."'
					)
				");
			}
			if($tilanne == 'hyvaksynta' or $tilanne == 'hyvaksytyt')
				$criteria->addCondition(" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
			if($tilanne == 'hyvaksytyt')
				$criteria->addCondition (" hyvaksytty!='' AND laskutetaan=1 ");

			$luetut = Mobile::model()->findAll($criteria);

       			$criteria = new CDbCriteria();
		        $criteria->condition = " 
				aloitan!='' AND loppui!=''
				AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to'
				AND status='3'
				AND deleted=0
			";
			if($tilanne == 'hyvaksytyt')
				$criteria->addCondition (" hyvaksytty!='' AND laskutetaan=1 ");

			if( $asiakas_id > 0 ){
			        $criteria->addCondition (" 
					kohdenID IN(
						SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas_id."'
					)
				");
			}
			$toteutuneet = Toteutuneet::model()->findAll($criteria);
		}

		if($tilanne == 'suunnitelut'){

			$tids 		= [];
			$with		= ['data','kohteet'];

			$haku_criteria = [];
			if( $asiakas_id > 0 ){
				$haku_criteria[] = "
					kohde IN(
						SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas_id."'
					)
				";
			}
			$haku_criteria[] = "
				status='3'
				AND (peruutettu=0 OR peruutettu IS NULL)
			";

			$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
			$dataAll 	= $tyovuorot[0]->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, $with);
			$suunnitelut 	= $dataAll;
		}

		$asiakkaat_arr = [];
		if( $tilanne == 'suunnitelut'){
			$result = $suunnitelut;
			foreach($result as $got){
				$r = $got['data'];
				if(isset($r->kohteet->asiakkaat))
					$asiakkaat_arr[$r->kohteet->asiakkaat->id][] = strtotime($r->loppu)-strtotime($r->alku);
			}
		}
		if( $tilanne == 'luetut'){
			$result = $luetut;
			foreach($result as $r){
				if(isset($r->kohteet->asiakkaat))
					$asiakkaat_arr[$r->kohteet->asiakkaat->id][] = strtotime($r->loppui)-strtotime($r->aloitan);
			}
		}
		if( $tilanne == 'hyvaksynta' or $tilanne == 'hyvaksytyt'){
			$result = array_merge($luetut, $toteutuneet);
			foreach($result as $r){
				if(isset($r->kohteet->asiakkaat))
					$asiakkaat_arr[$r->kohteet->asiakkaat->id][] = strtotime($r->loppui)-strtotime($r->aloitan);
			}
		}

		return $asiakkaat_arr;
	}

	/**
	 * Get day/evening/night work hours for tids.
	 * @param mixed $tids Array of tids, or single tid in string or int format.
	 * @param int $time Lookup time, 0 = day, 1 = evening, 2 = nighttime, 3 = sunday, 4 = pyhapaivat
	 */
	public function TidfromtoMobiiliAll($from, $to, $tids, $status=[], $hyvaksytty='', $palkanlaskentaan=false, $time=0, $by_aloitan=false, $kohde=null, $asiakas=null, $withKohdenID=false)
	{
		$set = [];
		if (is_array($tids)) {
			foreach($tids as $tid)
				if(!$by_aloitan)
					$set[$tid] = 0;

			$tids = implode(", ", $tids);
		} else {
			if(!$by_aloitan)
				$set[$tids] = 0;
		}

		// <-- Pyhapaivat
		$pyhapaivat = [];
		$pyhapaivat_str = '';
		if($time==4){
			$asetukset = AsetuksetForAll::model()->findbypk(1);
			$p_explode = explode("\n", $asetukset->viralliset_pyhapaivat);
			$p_explode = array_map('trim', $p_explode); // clear spaces
			$p_explode = array_map('rtrim', $p_explode); // clear spaces

			$begin = date ("d.m.Y", strtotime($from));
			$end   = date ("d.m.Y", strtotime($to));
			// check every date in the search range for holiday
			$datesToConsider = [];
			while(strtotime($begin) <= strtotime($end)) {
				$datesToConsider[] = $begin;
				$begin = date("d.m.Y", strtotime("+1 day", strtotime($begin)));
			}
			foreach($datesToConsider as $dddd) {
				if(in_array($dddd, $p_explode)) {
					// SQL query below expects Y-m-d dates
					$pyhapaivat[] = date("Y-m-d", strtotime($dddd));
				}
			}
			if( count($pyhapaivat) > 0 )
				$pyhapaivat_str = "(DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))='".implode("' OR DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))='", $pyhapaivat)."')";
		}
		//     Pyhapaivat -->

		// <-- Erikoislauantai
		$erikoislauantai = [];
		$erikoislauantai_str = '';
		if($time==5){
			$asetukset = AsetuksetForAll::model()->findbypk(1);
			$p_explode = explode("\n", $asetukset->erikoislauantai);
			$p_explode = array_map('trim', $p_explode); // clear spaces
			$p_explode = array_map('rtrim', $p_explode); // clear spaces

			$begin = date ("d.m.Y", strtotime($from));
			$end   = date ("d.m.Y", strtotime($to));
			while (strtotime($begin) <= strtotime($end)) {
                		if(in_array($begin, $p_explode)){
					$erikoislauantai[] = date ("Y-m-d", strtotime($begin));
				}
                		$begin = date ("d.m.Y", strtotime("+1 day", strtotime($begin)));
			}
			if( count($erikoislauantai) > 0 )
				$erikoislauantai_str = "(DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))='".implode("' OR DATE(STR_TO_DATE(aloitan, '%d.%m.%Y'))='", $erikoislauantai)."')";
		}
		//     Erikoislauantai -->

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$status = "status='".implode("' OR status='", $status)."'";
		$criteria = new CDbCriteria();
		if($by_aloitan)
			$criteria->group = "DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')), tid";
		elseif($withKohdenID)
			$criteria->group = "tid, kohdenID, aloitan"; // tid, kohdenID
		else
			$criteria->group = "tid";

		// Helper function to avoid duplicate code (doesn't handle 'hyvaksytty' as it differs)
		$buildCriteria = function (CDbCriteria &$criteria) use ($from, $to, $tids, $status, $palkanlaskentaan, $time, $by_aloitan, $pyhapaivat_str, $erikoislauantai_str, $kohde, $asiakas, $withKohdenID) {
			if($by_aloitan)
				$criteria->group = "DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')), tid";
			elseif($withKohdenID)
				$criteria->group = "tid, kohdenID, aloitan"; // tid, kohdenID
			else
				$criteria->group = "tid";
			// Select statements
			switch ($time) {
				case 0:
					$criteria->select = "
						tid, aloitan, loppui, kohdenID, kohde_kannasta, SUM(TIME_TO_SEC(TIMEDIFF(
							STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'),
							STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i')
						))) as l_tunnit";
					break;
				case 1:
					// Note: 18000 at end of query is equal to TIME_TO_SEC(TIMEDIFF('23:00:00', '18:00:00'))
					$criteria->select = "tid, aloitan, loppui, kohdenID, kohde_kannasta, SUM(CASE
						WHEN
							TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')) >= '18:00:00'
						THEN CASE
							WHEN
								(TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')) < '23:00:00') &&
								(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) > '23:00:00' ||
								DATE(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) != DATE(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')))
							THEN
								TIME_TO_SEC(TIMEDIFF('23:00:00', TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
							WHEN
								TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) > '18:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
							ELSE
								0
							END
						ELSE CASE
							WHEN
								TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) > '23:00:00' ||
								DATE(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) != DATE(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))
							THEN
								18000
							WHEN
								TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) > '18:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), '18:00:00'))
							ELSE
								0
							END
						END) AS l_tunnit";
					break;
				case 2:
					$criteria->select = "tid, aloitan, loppui, kohdenID, kohde_kannasta, SUM(CASE
						WHEN
							DATE(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) = DATE(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))
						THEN CASE
							WHEN
								TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) <= '06:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
							WHEN
								TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) > '23:00:00'
							THEN CASE
								WHEN
									TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')) <= '06:00:00'
								THEN
									TIME_TO_SEC(TIMEDIFF('06:00:00', TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')))) +
									TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), '23:00:00'))
								WHEN
									TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')) > '23:00:00'
								THEN
									TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
								ELSE
									TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), '23:00:00'))
								END
							WHEN
								TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')) <= '06:00:00'
							THEN
								TIME_TO_SEC(TIMEDIFF('06:00:00', TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
							ELSE
								0
							END
						ELSE CASE
							WHEN
								TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) <= '06:00:00'
							THEN CASE
								WHEN
									TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')) <= '23:00:00'
								THEN
									TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), '00:00:00')) + 3600
								ELSE CASE
									WHEN
										DATE(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) > DATE(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))
									THEN
										TIME_TO_SEC(TIMEDIFF('23:59:59', TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))+1+
										TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), '00:00:00'))
									ELSE
										TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
									END
								END
							ELSE CASE
								WHEN
									TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')) <= '23:00:00'
								THEN
									25200 /* TIME_TO_SEC('06:00:00') + TIME_TO_SEC('01:00:00') */
								ELSE
									TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')), '23:00:00')) + 21600
								END
							END
						END) AS l_tunnit";
					break;
				case 3:
					$criteria->select = "tid, aloitan, loppui, kohdenID, kohde_kannasta, SUM(CASE
						WHEN
							DAYOFWEEK(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')) = 1
						THEN CASE
							WHEN
								DAYOFWEEK(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) = 1
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
							ELSE
								TIME_TO_SEC(TIMEDIFF('23:59:00', TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')))) + 60
							END
						WHEN
							DAYOFWEEK(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) = 1
						THEN
							TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), '00:01')) + 60
						ELSE
							0
						END) AS l_tunnit";
					break;
				case 4:
				if( !empty($pyhapaivat_str) ){
					$criteria->select = "tid, aloitan, loppui, kohdenID, kohde_kannasta, SUM(CASE
						WHEN
							$pyhapaivat_str
						THEN CASE
							WHEN
								DATE(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) = DATE(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
							ELSE
								TIME_TO_SEC(TIMEDIFF('23:59:00', TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')))) + 60
							END
						ELSE
							0
						END) AS l_tunnit";
				}
					break;
				case 5:
				if( !empty($erikoislauantai_str) ){
					$criteria->select = "tid, aloitan, loppui, kohdenID, kohde_kannasta, SUM(CASE
						WHEN
							$erikoislauantai_str
						THEN CASE
							WHEN
								DATE(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')) = DATE(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))
							THEN
								TIME_TO_SEC(TIMEDIFF(TIME(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s')), TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'))))
							ELSE
								TIME_TO_SEC(TIMEDIFF('23:59:00', TIME(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s')))) + 60
							END
						ELSE
							0
						END) AS l_tunnit";
				}
					break;
			}

			// Conditions
			$criteria->condition = "
				aloitan!='' AND loppui!=''
				AND tid IN ($tids)
				AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) BETWEEN '$from' AND '$to'
				AND deleted=0";
			if ($kohde > 0) $criteria->addCondition("kohdenID='".$kohde."'");
			if ($asiakas > 0) $criteria->addCondition("kohdenID IN(SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas."')");
			if ($status) $criteria->addCondition($status);
			if ($palkanlaskentaan) $criteria->addCondition("palkanlaskentaan=1");
		};

		// Build CDbCriteria
		$buildCriteria($criteria);

		// Add conditions based on 'hyvaksytty' (not added by buildCriteria().)
		switch ($hyvaksytty) {
				// Case 3 falls through due to no break statement, this is intentional.
			case 3:
				$criteria->addCondition("hyvaksytty!=''");
			case '':
			case 2:
				$criteria->addCondition("id NOT IN (SELECT kid FROM sivexkuitti_repaired)");
		}

		// Exec query

		$lu = Mobile::model()->findAll($criteria);

		// Build second CDbCriteria for sivexkuitti_repaired (toteutuneet) if $hyvaksytty != 1
		$tot = [];
		if ($hyvaksytty != 1) {
			$criteria = new CDbCriteria();
			$buildCriteria($criteria);
			if ($hyvaksytty == 3)
				$criteria->addCondition("hyvaksytty!=''");
			$tot = Toteutuneet::model()->findAll($criteria);
		}

		// Build final array for results, in form of tid => tunnit.
		if($by_aloitan){
			foreach ($lu as $l){
				$set[date("Y-m-d", strtotime($l->aloitan))][$l->tid] = $l->l_tunnit;
			}
			foreach ($tot as $t){
				if(!isset($set[date("Y-m-d", strtotime($t->aloitan))][$t->tid])){
					$set[date("Y-m-d", strtotime($t->aloitan))][$t->tid] = $t->l_tunnit;
				} else {
					$set[date("Y-m-d", strtotime($t->aloitan))][$t->tid] += $t->l_tunnit;
				}
			}
		} else {
			if($withKohdenID)
			{
				$set = [];

				foreach ($lu as $l)
					$set[] = ['l_tunnit' => $l->l_tunnit, 'attributes' => $l->attributes];
						
				foreach ($tot as $l)
					$set[] = ['l_tunnit' => $l->l_tunnit, 'attributes' => $l->attributes];
				/*
				foreach ($lu as $l)
					if(!isset($set[$l->tid][$l->kohdenID]))
						$set[$l->tid][$l->kohdenID] = $l->l_tunnit;
					else
						$set[$l->tid][$l->kohdenID] += $l->l_tunnit;
						
				foreach ($tot as $l)
					if(!isset($set[$l->tid][$l->kohdenID]))
						$set[$l->tid][$l->kohdenID] = $l->l_tunnit;
					else
						$set[$l->tid][$l->kohdenID] += $l->l_tunnit;
				*/
					
			} else {
				foreach ($lu as $l)
					$set[$l->tid] += $l->l_tunnit;
				foreach ($tot as $t)
					$set[$t->tid] += $t->l_tunnit;
			}
		}
		return $set;
	}
/*
	public function TidfromtoSairausTunnit($from,$to,$tid,$sairaus)
	{
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$return = 0;
       		$criteria = new CDbCriteria();
		$criteria->select = " SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit ";
	        $criteria->condition = " 
			tid='".$tid."'
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
			AND tyoajanlaatu LIKE '%(".$sairaus.")%'
		";
		$vl = Tyovuoroot::model()->find($criteria);

		if(isset($vl->l_tunnit)){ $return = $vl->l_tunnit; }
		return $return;
	}
*/
/*
	public function TidfromtoSairausTP($from,$to,$tid,$sairaus)
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
*/
	protected function TidfromtoVuosilomaBetween($from, $to, $tids, $tila, $by_pvm=false)
	{
		$set 		= [];
		$from 		= date("Y-m-d", strtotime($from));
		$to 		= date("Y-m-d", strtotime($to));
		$haku_criteria	= ["tyoajanlaatu LIKE '%(".$tila.")%'"];
		$with		= ['data'];
		$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
		$dataAll 	= $tyovuorot[0]->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, $with);
		foreach($dataAll as $arr){
			$item = $arr['data'];
			if($by_pvm){
				if(!isset($set[$arr['this_pvm']][$arr['this_tid']]))
					$set[$arr['this_pvm']][$arr['this_tid']] = 1;
				else
					$set[$arr['this_pvm']][$arr['this_tid']] += 1;
			} else {
				if(!isset($set[$arr['this_tid']]))
					$set[$arr['this_tid']] = 1;
				else
					$set[$arr['this_tid']] += 1;
			}
		}
		return $set;
	}

	/**
	 * A faster version of TidfromtoVuosilomaBetween which queries these special
	 * 'tyoajanlaatu' shifts in bulk. Doesn't support the "by_pvm" thing which was
	 * in the original function, because I didn't find any case where it's used.
	 * With some quick testing this method was about 2 times faster.
	 */
	protected function TidfromtoVuosilomaBetweenFast($from, $to, $tids) 
	{
		// we will be discarding any other key found
		$set = [
			"SL" => [],
			"SPL" => [],
			"LS" => [],
			"VL" => [],
			"VKL" => [],
			"AP" => [],
			"PV" => [],
			"LSK" => [],
			"PP" => []
		];
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));
		$haku_criteria = ["tyoajanlaatu != ''"];
		$with = ['data'];
		$tyovuorot = Yii::app()->createController('Tyovuoroot');
		$dataAll = $tyovuorot[0]->FromToSuunnitellutAll($from, $to, $tids, $haku_criteria, $with);

		foreach($dataAll as $shiftArr) {
			$typeKey = $this->shiftTypeKey($shiftArr["data"]);
			// if there isn't a key defined for this type in $set, we wont count it.
			if(array_key_exists($typeKey, $set)) {
				if(!isset($set[$typeKey][$shiftArr["this_tid"]])) {
					$set[$typeKey][$shiftArr["this_tid"]] = 1;
				} else {
					$set[$typeKey][$shiftArr["this_tid"]] += 1; 
				}
			}
		}
		return $set;
	}

	private function shiftTypeKey($shift) 
	{
		$str = $shift["tyoajanlaatu"];
		$str = substr($str, 1);
		$split = explode(")", $str);
		return $split[0];
	}
/*
	public function TidPvmVuosiloma($pvm,$tid,$tila)
	{
		$pvm 	= date("Y-m-d", strtotime($pvm));
		$result = 0;
       		$criteria = new CDbCriteria();
	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = '".$pvm."' 
			AND tid='".$tid."'
			AND tyoajanlaatu LIKE '%(".$tila.")%'
		";
		$vl = Tyovuoroot::model()->find($criteria);

		$arr = array('count'=>0);
		if(isset($vl->id)){ $arr = array('count'=>1); }
		return $arr;
	}
*/

	/**
	 * The purpose of this action is to go around the URL
	 * limitation that some companies could hit with the normal
	 * actionPalkkataulukko. When selecting all employees from the dropdown
	 * list, all of the employee IDs would get appended to the URL,
	 * which could go over the URL character limit.
	 * 
	 * This action goes around the limitation by accepting
	 * a POST request, which should have the employee IDs
	 * defined in the POST body. 
	 */
	public function actionPalkkataulukkoPost() {
		$req = Yii::app()->request;

		// get work groups (työryhmät) from params, default to empty arr
		$workGroups = $req->getPost("workGroups", []);
		if(is_string($workGroups)) {
			$workGroups = [];
		}
		// get employees (tyontekijat) from params, default to empty arr
		$employees = $req->getPost("employees", []);
		if(is_string($employees)) {
			$employees = [];
		}
		

		// build query
		$criteria = new CDbCriteria();
    	$criteria->select = " id, tekijan_nimi, sukunimi, tyoryhma";

		// set first and lastname order based on domain settings
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		// only look for active employees
		$criteria->condition = " aktiivinen=1 "; 
		// add defined employee IDs to query, if any
		if(!empty($employees)){
			$ids = implode(",", $employees);
			$criteria->addCondition ('id IN ('.$ids.') ');
		}

		$model = Tyontekijat::model()->findAll($criteria);

		// Työryhmät: trim non-matching cleaners from results.
		if(!empty($workGroups)) {
			$trs = array_column(Valikkoot::model()->findAllByPk($workGroups), 'value', 'id');
			if (in_array(0, $workGroups))
				$trs[0] = 'Tyoryhmättömät';
		
			// If 0 ("Työryhmättömät") is not selected, and the model has no value
			// in tyoryhma attribute, remove the model from results. Otherwise,
			// remove model if it's not in any of the selected $trs.
			for ($i = 0; $i < count($model); $i++) {
				$tr = json_decode($model[$i]->tyoryhma ?? ''); // NULL if empty.
				if ((empty($tr)) ? !isset($trs[0]) : empty(array_intersect($tr, $trs)))
				unset($model[$i]);
			}
		}
		// reduce the array of employee objects to just their IDs
		$workerIds = [];
		foreach($model as $employee) {
			$workerIds[] = $employee->id;
		}

		Yii::app()->session["palkkataulukko_employeeIds"] = $workerIds;
		// since we're setting the IDs to the session we'll just return something here
		// so the caller knows it's a success
		echo json_encode(["ok" => "ok"]);

	}

	/**
	 * This action is called from palkkataulukko.php to automatically
	 * select all employees that belong to selected workgroups (työryhmä).
	 * 
	 * The action returns an array of unique active employee IDs.
	 */
	public function actionSelectedEmployees() {
		$workGroupIds = Yii::app()->request->getParam("workGroups", []);
		$workGroups = array_column(Valikkoot::model()->findAllByPk($workGroupIds), "value", "value");
		// check if "Työryhmättömät" is checked,
		// and if it is, add it to the $workGroups map
		if(in_array(0, $workGroupIds)) {
			$workGroups[0] = 0;
		}
		// build query
		$criteria = new CDbCriteria();
		$criteria->select= " id, tyoryhma";
		$criteria->condition = " aktiivinen=1 ";

		$employees = Tyontekijat::model()->findAll($criteria);

		$employeeIds = [];
		// filter employees based on selected work groups
		// "työryhmättömät" requires special handling
		foreach($employees as $employee) {

			$employeeGroups = json_decode($employee->tyoryhma, true) ?? [];
			// "työryhmättömät" handling, if an employee has no
			// workgroups defined and "työryhmättömät" is selected
			// we can assume we want this employee selected.
			if(empty($employeeGroups) && isset($workGroups[0])) {
				$employeeIds[] = $employee->id;
			}
			// match employees work groups with selected work groups,
			// if an employee has even 1 of the workgroups, add it to the
			// selected list.
			foreach($employeeGroups as $empGroup) {
				if(isset($workGroups[$empGroup])) {
					$employeeIds[] = $employee->id;
				}
			}
		}
		// return unique employee ids
		echo json_encode(array_values(array_unique($employeeIds)));
		
	}

	public function actionPalkkataulukko()
	{
		// if "yhtveto" is not set in the URL, that means the user is just navigating
		// to this page for the first time. in that case we'll reset 
		// the sessions selected employee IDs
		if(!isset($_GET["yhtveto"])) {
			unset(Yii::app()->session["palkkataulukko_employeeIds"]);
		}

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

		// default from and to dates of the current month
		$from = date("d.m.Y",strtotime("first day of this month"));
		$to = date("d.m.Y");

		if(isset($_GET['from']) and !empty($_GET['from'])){ $from = $_GET['from']; }
    	if(isset($_GET['to']) and !empty($_GET['to'])){ $to = $_GET['to']; }

    	$criteria = new CDbCriteria();
    	$criteria->select = " id, tekijan_nimi, sukunimi, tyoryhma, using_framework_agreement";

		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

		// Display all / only active workers.
		if(!isset($_GET['kaikki_tyontekijat'])){
			$criteria->condition = " aktiivinen=1 ";
		}

		// Tekija no longer has a value, but the original system used it to conditionally render
		// in palkkautaulukko.php, so I decided to leave it here
		if( isset($_GET['Tekija']) and 
			isset(Yii::app()->session["palkkataulukko_employeeIds"]) ){
			$ids = "";
			// get employee IDs from the session instead of the URL
			$sModels = Yii::app()->session["palkkataulukko_employeeIds"];
	
			foreach($sModels as $employeeId) {
				$ids .= $employeeId . ",";
			}
			if(!empty($ids)) {
				$ids = substr($ids, 0, -1);
			}
			
			$criteria->addCondition ('id IN ('.$ids.') ');
		}

    	$model = Tyontekijat::model()->findAll($criteria);
		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		// Työryhmät: trim non-matching cleaners from results.
		if (!empty($tyoryhmat = $_GET['tyoryhmat'] ?? [])) {

			$trs = array_column(Valikkoot::model()->findAllByPk($tyoryhmat), 'value', 'id');

			if (in_array(0, $tyoryhmat))
				$trs[0] = 'Tyoryhmättömät';

			// If 0 ("Työryhmättömät") is not selected, and the model has no value
			// in tyoryhma attribute, remove the model from results. Otherwise,
			// remove model if it's not in any of the selected $trs.
			for ($i = 0; $i < count($model); $i++) {
				$tr = json_decode($model[$i]->tyoryhma ?? ''); // NULL if empty.
				if ((empty($tr)) ? !isset($trs[0]) : empty(array_intersect($tr, $trs)))
				unset($model[$i]);
			}
		}


		$this->render('palkkataulukko', array(
      		'model' => $model,
      		'tyoryhmat' => $trs ?? [],
			'from' => $from,
			'to' => $to,
			'tt_order_1' => $tt_order_1,
			'tt_order_2' => $tt_order_2
		));
	}

	protected function TP($tid,$from,$to){

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

       		$criteria = new CDbCriteria();
        	$criteria->select = "id, aloitan";
        	$criteria->group = "DATE(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d'))";
	        $criteria->condition = "
			id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND sairaus!=1
			AND aloitan!=loppui
			AND deleted=0
			AND palkanlaskentaan=1
			AND hyvaksytty!=''
		";
		$lu = Mobile::model()->findAll($criteria);

       		$criteria = new CDbCriteria();
        	$criteria->select = "id, aloitan";
        	$criteria->group = "DATE(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d'))";
	        $criteria->condition = "
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".$from."' AND '".$to."' 
			AND tid='".$tid."'
			AND sairaus!=1
			AND aloitan!=loppui
			AND deleted=0
			AND palkanlaskentaan=1
			AND hyvaksytty!=''
		";
		$tot = Toteutuneet::model()->findAll($criteria);
		$lista = array_merge($lu, $tot);
		$arr = array();
		foreach($lista as $item){
			$arr[date("Ymd", strtotime($item->aloitan))] = $item->id;
		}
		return count($arr);

	}

	protected function TPBetweenMobAll($from,$to,$tids,$palkanlaskentaan=false){

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
        	$criteria->select = "tid, aloitan";
        	$criteria->group = "DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')), tid";
	        $criteria->condition = "
			id NOT IN (SELECT kid FROM sivexkuitti_repaired)
			AND DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) BETWEEN '".$from."' AND '".$to."' 

			AND deleted=0
			AND hyvaksytty!=''
		";
		if ($palkanlaskentaan) $criteria->addCondition("palkanlaskentaan=1");
		$lu = Mobile::model()->findAll($criteria);

       		$criteria = new CDbCriteria();
        	$criteria->select = "tid, aloitan";
        	$criteria->group = "DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')), tid";
	        $criteria->condition = "
			DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) BETWEEN '".$from."' AND '".$to."' 

			AND deleted=0
			AND hyvaksytty!=''
		";
		//			AND tid IN ($tids)
		if ($palkanlaskentaan) $criteria->addCondition("palkanlaskentaan=1");
		$tot = Toteutuneet::model()->findAll($criteria);

		foreach($lu as $item){
			$set[date("Y-m-d", strtotime($item->aloitan))][$item->tid] = 1;
		}
		foreach($tot as $item){
			$set[date("Y-m-d", strtotime($item->aloitan))][$item->tid] = 1;
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

	public function actionYhteenveto()
	{

		if(isset($_GET['yhtvetoform'])){
			unset(Yii::app()->session['Lounastauko']);
			unset(Yii::app()->session['MATKA']);
		}

		$from 	= date("d.m.Y");
		$to 	= date("d.m.Y");

		if(isset($_GET['from']) and isset($_GET['to'])){
			$from 	= $_GET['from'];
			$to 	= $_GET['to'];
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
			AND deleted=0
		";
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksytyt')
			$criteria->addCondition ("hyvaksytty!=''");
			
		if(isset($_GET['Tekija']) and count($_GET['Tekija']) > 0){
			$ids = implode(",",$_GET['Tekija']);
			$criteria->addCondition ('tid IN ('.$ids.') ');
		}
		if(isset($_GET['ilman'])){
			foreach($_GET['ilman'] as $val){
				if($val == 'Lounastauko')
					$criteria->addCondition (" status != '10' ");

				if($val == 'MATKA')
					$criteria->addCondition (" status != '2' ");
			}
		}

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
		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->
        	$criteria->condition = " aktiivinen=1 ";

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
        		$criteria->addCondition (" id IN ($ids)");
		}
		//    Tyoryhmat -->

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
			AND deleted=0
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
			AND deleted=0
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
		$body 	= '<div class="table-responsive"><table class="table table-bordered sortable">';
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
			AND kohdenID='".$kohdenID."'
			AND $fromTo
			AND deleted=0
		";

		$model = Mobile::model()->findAll($criteria);
		foreach($model as $d){
			$kesto = 0;

		  	$d->loppui 	= date("d.m.Y H:i",strtotime($d->loppui));
		  	$d->aloitan 	= date("d.m.Y H:i",strtotime($d->aloitan));
			$kesto 		= strtotime($d->loppui)-strtotime($d->aloitan);

			$lu[] = $this->etuSukunimi($d->tid)."//".date("d.m.Y",strtotime($d->aloitan))."//".$kesto."//mobile_".$d->id."//".$d->asiakas_hyvaksy."//".date("H:i",strtotime($d->aloitan))."//".date("H:i",strtotime($d->loppui))."//////".$d->sairaus.'//'.strtotime($d->aloitan).'//'.strtotime($d->viesti);
		}


       		$criteria = new CDbCriteria();
        	$criteria->order = "kohde_kannasta";
        	$criteria->condition = "
			status='3'
			AND kohdenID='".$kohdenID."'
			AND $fromTo
			AND deleted=0
		";

		$model = Toteutuneet::model()->findAll($criteria);
		foreach($model as $d){
			$kesto = 0;

		  	$d->loppui 	= date("d.m.Y H:i",strtotime($d->loppui));
		  	$d->aloitan 	= date("d.m.Y H:i",strtotime($d->aloitan));
		  	$kesto 		= strtotime($d->loppui)-strtotime($d->aloitan);

			$lu[] = $this->etuSukunimi($d->tid)."//".date("d.m.Y",strtotime($d->aloitan))."//".$kesto."//toteutu_".$d->id."//".$d->asiakas_hyvaksy."//".date("H:i",strtotime($d->aloitan))."//".date("H:i",strtotime($d->loppui))."//".$d->osoite."//".$d->tietoja."//".$d->sairaus.'//'.strtotime($d->aloitan).'//'.strtotime($d->viesti);
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
			  <th>Viesti</th>
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
			  <td>'.$explV[8].'</td>
			</tr>';
			}
			if(isset($explV[3]))
			$ids[] = $explV[3];
		}

		$body 	.= '</tbody></table></div>';

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

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
        		$criteria->addCondition (" tid IN ($ids)");
		}
		//    Tyoryhmat -->

		$model = Mobile::model()->findAll($criteria);

		if(Yii::app()->request->getPost('tulosta'))
		{
/*
	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($this->renderPartial('tulosta_kyhteenveto', array(
			'model' => $model,
			'from' => $from,
			'to' => $to
		  ),true));
	          $html2pdf->Output();
*/
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
			AND deleted=0
		";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->addCondition (" kohdenID IN ( SELECT id FROM sivex_kohdet WHERE tyoryhma IN ($ids) ) ");
		}
		//    Tyoryhmat -->


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
			AND deleted=0
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

	public function actionAyhteenveto()
	{

		$from = date("d.m.Y");
		$to = date("d.m.Y");

		if(isset($_GET['from']) and isset($_GET['to'])){
		$from 	= $_GET['from'];
		$to 	= $_GET['to'];
		}

		$criteria = new CDbCriteria();
		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi'])){
			$criteria->condition = " 
				yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR CONCAT(etunimi , ' ' , sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%'
			";
		}
		if(isset($_GET['asiakas_id'])){
			$criteria->condition = " 
				id='".$_GET['asiakas_id']."'
			";
		}
		if((isset($_GET['yrityksen_nimi'])  and !empty($_GET['yrityksen_nimi'])) or isset($_GET['asiakas_id'])){
			$asiakas = Asiakkaat::model()->find($criteria);
		}

		$this->render('ayhteenveto', array(
			'from' => $from,
			'to' => $to,
			'asiakas_id' => (isset($asiakas->id))?$asiakas->id:'',
			'asiakas' => (isset($asiakas->id))?$asiakas:'',
		));
	}

	public function actionAyhteenvetoyht()
	{

		$from 	= date("d.m.Y");
		$to 	= date("d.m.Y");

		if(isset($_GET['from']) and isset($_GET['to'])){
			$from 	= $_GET['from'];
			$to 	= $_GET['to'];
		}

		$criteria = new CDbCriteria();
/*
		$criteria->addCondition(" 
			id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id IN(SELECT kohdenID FROM sivexkuitti WHERE 
				DATE(STR_TO_DATE(aloitan, '%d.%m.%Y')) 
				BETWEEN '".date("Y-m-d", strtotime($from))."' AND '".date("Y-m-d", strtotime($to))."'
				AND status='3'
				AND deleted=0
			))
		");
*/
		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi'])){
			$criteria->addCondition(" 
				yrityksen_nimi='".$_GET['yrityksen_nimi']."' OR etunimi='".$_GET['yrityksen_nimi']."' OR sukunimi='".$_GET['yrityksen_nimi']."'
			");
		}
		if(isset($_GET['asiakas_id'])){
			$criteria->condition = " 
				id='".$_GET['asiakas_id']."'
			";
		}

		$asiakkaat = Asiakkaat::model()->findAll($criteria);
		$sort = [];
		foreach($asiakkaat as $asiakas)
			$sort[$asiakas->Fullname] = $asiakas;

		ksort($sort);

		$this->render('ayhteenvetoyht', array(
			'from' => $from,
			'to' => $to,
			'asiakkaat' => $sort,
			'asiakas_id' => (isset($_GET['asiakas_id']))? $_GET['asiakas_id']: '',
		));
	}


	public function actionKyhteenveto()
	{

		$from = date("d.m.Y");
		$to = date("d.m.Y");

		if(isset($_GET['from']) and isset($_GET['to'])){
		$from 	= $_GET['from'];
		$to 	= $_GET['to'];
		}

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			COUNT(*) as count, kohdenID,
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";
        	//$criteria->order = "kohde_kannasta";
        	$criteria->group = "kohdenID";
        	$criteria->condition = "
			id NOT IN (select kid from sivexkuitti_repaired) 
			AND status='3' 
			AND kohdenID!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($from))."' AND '".date('Y-m-d', strtotime($to))."' 
			AND deleted=0
		";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->addCondition (" kohdenID IN ( SELECT id FROM sivex_kohdet WHERE tyoryhma IN ($ids) ) ");
		}
		//    Tyoryhmat -->

		if(isset($_GET['osoite']) and !empty($_GET['osoite'])){
			$k = Kohteet::model()->findAll(" osoite LIKE '%".$_GET['osoite']."%' ");
			$osoiteet = [];
			foreach($k as $item)
				$osoiteet[$item->id] = $item->id;

			if( count($osoiteet) > 0 ){
				$impl = "kohdenID='" . implode("' OR kohdenID='", $osoiteet)."'";
				$criteria->addCondition  ($impl);
			}
		}

		$model = Mobile::model()->findAll($criteria);
		$lu = array();
		foreach($model as $d){
			if(isset($d->kohteet->id)){
				$lu[$d->kohteet->osoite] = [
					'asiakas' => (isset($d->kohteet->asiakkaat)? $d->kohteet->asiakkaat->Fullname : ''),
					'kohdenID' => $d->kohdenID,
					'l_tunnit' => $d->l_tunnit,
					'count' => $d->count
				];
			}
		}

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			COUNT(*) as count, kohdenID,
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";
        	//$criteria->order = "kohde_kannasta";
        	$criteria->group = "kohdenID";
        	$criteria->condition = "
			status='3'
			AND kohdenID!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".date('Y-m-d', strtotime($from))."' AND '".date('Y-m-d', strtotime($to))."' 
			AND deleted=0
		";

		if(isset($_GET['osoite']) and !empty($_GET['osoite'])){
			$k = Kohteet::model()->findAll(" osoite LIKE '%".$_GET['osoite']."%' ");
			$osoiteet = [];
			foreach($k as $item)
				$osoiteet[$item->id] = $item->id;

			if( count($osoiteet) > 0 ){
				$impl = "kohdenID='" . implode("' OR kohdenID='", $osoiteet)."'";
				$criteria->addCondition  ($impl);
			}
		}

		$model = Toteutuneet::model()->findAll($criteria);
		foreach($model as $d){
			if(isset($d->kohteet->id)){
				if( isset($lu[$d->kohteet->osoite]) ){ 
					$d->l_tunnit += $lu[$d->kohteet->osoite]['l_tunnit'];
					$d->count += $lu[$d->kohteet->osoite]['count'];
				}
				$lu[$d->kohteet->osoite] = [
						'asiakas' => (isset($d->kohteet->asiakkaat)? $d->kohteet->asiakkaat->Fullname : ''),
						'kohdenID' => $d->kohdenID,
						'l_tunnit' => $d->l_tunnit,
						'count' => $d->count
				];
			}
		}

		if(count($lu) >0){ ksort($lu); }

		if(isset($_GET['tulosta'])){
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
			<a href="'.Yii::app()->request->hostInfo.'/index.php/site/hyvaksy?id='.$model->id.'&code='.$model->code.'&domain='.Yii::app()->user->domain.'">'.Yii::t('main','Hyväksy').'</a> &nbsp;&nbsp;&nbsp;
			<a href="'.Yii::app()->request->hostInfo.'/index.php/site/hylkaa?id='.$model->id.'&code='.$model->code.'&domain='.Yii::app()->user->domain.'">'.Yii::t('main','Hylkää').'</a>
			</center>

		  ';		 

		  $ft = FirmanTiedot::model()->findByPk(1);
	          $mail = new YiiMailer();
		  //$mail->clearLayout();//if layout is already set in config
		  $mail->setFrom('no-reply@etunti.fi');
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
			$status $kohdenID 
			AND deleted=0
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
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND sairaus!=1
			AND deleted=0
		";

		if( !isset($_GET['lu_tai_tot']) or (isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 2) or (isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 3) ){
			$criteria->addCondition (" id NOT IN(select kid from sivexkuitti_repaired) ");
		}

		if(isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 3) {
			$criteria->addCondition (" hyvaksytty!='' ");
		}
		if(isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksytyt') {
			$criteria->addCondition (" hyvaksytty!='' ");
		}
		
		if($sivu == 'palkkataulukko'){ 	$criteria->addCondition (" status = '3' AND palkanlaskentaan=1 "); }

		if($sivu == 'yhteenveto')
		{
			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");
		}
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");
		$lu = Mobile::model()->find($criteria);
		if(isset($lu->l_tunnit)){
			$total_l = $lu->l_tunnit;
		}
/*
		foreach($lu as $l)
		{

		  $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		  $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    //$totalIlta += $this->ilta($al,$lop);
		    //$totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    //if(date('N', strtotime($al[0])) == 7)
		    //$totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}
*/
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit 
		";
        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND sairaus!=1
			AND deleted=0
		";
		if(isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 3) {
			$criteria->addCondition (" hyvaksytty!='' ");
		}
		if(isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksytyt') {
			$criteria->addCondition (" hyvaksytty!='' ");
		}
		
		if($sivu == 'palkkataulukko'){ $criteria->addCondition (" status = '3' AND palkanlaskentaan=1 "); }
		if($sivu == 'yhteenveto')
		{
			if(Yii::app()->session['Lounastauko'])
		        $criteria->addCondition (" status != '10' ");
	
			if(Yii::app()->session['MATKA'])
		        $criteria->addCondition (" status != '2' ");
		}

	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."' ");

		if( !isset($_GET['lu_tai_tot']) or (isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 2) or (isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 3) ){
		$tot = Toteutuneet::model()->find($criteria);
		if(isset($tot->l_tunnit)){
			$total_l += $tot->l_tunnit;
		}
/*
		foreach($tot as $l)
		{
				$l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
				$l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));
				$l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
				$al = explode(" ",$l->aloitan);
				$lop = explode(" ",$l->loppui);
				$total_l += $l->l_tunnit;
		}
*/
		}

		$kaikki = array($total_l,$totalIlta,$totalYo,$totalSu);

		return $kaikki;

	}


/*
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
			AND sairaus!=1
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND deleted=0
			AND hyvaksytty!=''
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


       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$tid."' and aloitan !='' and loppui !='' 
			AND status = '2'
			AND sairaus!=1
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND deleted=0
			AND hyvaksytty!=''
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
*/


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
			AND sairaus!=1
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND deleted=0
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
			AND sairaus!=1
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND deleted=0
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


	public function actionKohdebytekija($tid, $from, $to, $kohde_kannasta, $status, $row_id, $raporti_tyyppi)
	{

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$criteria = new CDbCriteria();
		if($row_id == null){
			$criteria->select = "COUNT(*) as count,
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit,
			kohde_kannasta, kohdenID, tid, status, id
			";
		}
		$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!=''
			AND id NOT IN(select kid from sivexkuitti_repaired)
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND deleted=0
		";
		if($raporti_tyyppi == 'Hyvaksytyt'){ $criteria->addCondition (" hyvaksytty!='' "); }
		
		if($row_id != null and $status == 3)
			$criteria->addCondition("kohde_kannasta='$kohde_kannasta'");
		elseif($row_id != null and $status != 0)
			$criteria->addCondition("status=$status");
		elseif($row_id == null)
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
			if($t->status == 2)
				$t->kohde_kannasta = 'MATKA';
			if($t->status == 10)
				$t->kohde_kannasta = 'Lounastauko';
				
		    $kesto = '';
		    $kesto = $t->l_tunnit;
		    $sum += $t->l_tunnit;

		    if(!isset($ks[$t->kohde_kannasta])) { $ks[$t->kohde_kannasta] = 0; }
		    $ks[$t->kohde_kannasta] += $t->l_tunnit;

		    if(!isset($c[$t->kohde_kannasta])) { $c[$t->kohde_kannasta] = 0; }
		    $c[$t->kohde_kannasta] += $t->count;

			if($row_id == null){
				$return[$t->kohde_kannasta] = [
						'row_id' => $t->id.strtotime($t->aloitan),
						'tid' => $t->tid,
						'kohdenID' => $t->kohdenID,
						'status' => $t->status,
						'kohde_kannasta' => $t->kohde_kannasta,
						'kustannuspaikka_nro' => (isset($t->kohteet->kustannuspaikka_nro))? $t->kohteet->kustannuspaikka_nro : '',
						'count' => $t->count,
						'muokattu' => null
				];
		    } else {
				$return[] = [
						'pvm' => date("d.m.Y", strtotime($t->aloitan)),
						'aloitan' => date("H:i", strtotime($t->aloitan)),
						'loppui' => date("H:i", strtotime($t->loppui)),
						'kesto' => strtotime($t->loppui)-strtotime($t->aloitan),
				];
		    }
		}


   		$criteria = new CDbCriteria();
   		if($row_id == null){
			$criteria->select = "COUNT(*) as count, 
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as t_tunnit,
			kohde_kannasta, kohdenID, tid, status, id
			";
		}
    	$criteria->condition = "  
			tid = '".$tid."' and aloitan!='' and loppui!=''
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND deleted=0
		";
		if($raporti_tyyppi == 'Hyvaksytyt'){ $criteria->addCondition (" hyvaksytty!='' "); }

		if($row_id != null and $status == 3)
			$criteria->addCondition("kohde_kannasta='$kohde_kannasta'");
		elseif($row_id != null and $status != 0)
			$criteria->addCondition("status=$status");
		elseif($row_id == null)
			$criteria->group = "kohde_kannasta";

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		    $return2 = array();

		foreach($tot as $t)
		{
			if($t->status == 2)
				$t->kohde_kannasta = 'MATKA';
			if($t->status == 10)
				$t->kohde_kannasta = 'Lounastauko';
				
		    $kesto = '';
		    $kesto = $t->t_tunnit;
		    $sum += $t->t_tunnit;

		    if(!isset($ks[$t->kohde_kannasta])) { $ks[$t->kohde_kannasta] = 0; }
		    $ks[$t->kohde_kannasta] += $t->t_tunnit;

		    if(!isset($c[$t->kohde_kannasta])) { $c[$t->kohde_kannasta] = 0; }
		    $c[$t->kohde_kannasta] += $t->count;

			if($row_id == null){
				$return[$t->kohde_kannasta] = [
						'row_id' => $t->id.strtotime($t->aloitan),
						'tid' => $t->tid,
						'kohdenID' => $t->kohdenID,
						'status' => $t->status,
						'kohde_kannasta' => $t->kohde_kannasta,
						'kustannuspaikka_nro' => (isset($t->kohteet->kustannuspaikka_nro))? $t->kohteet->kustannuspaikka_nro : '',
						'count' => $t->count,
						'muokattu' => 'muokattu'
				];
		    } else {
				$return[] = [
						'pvm' => date("d.m.Y", strtotime($t->aloitan)),
						'aloitan' => date("H:i", strtotime($t->aloitan)),
						'loppui' => date("H:i", strtotime($t->loppui)),
						'kesto' => strtotime($t->loppui)-strtotime($t->aloitan),
				];
		    }
		}

		//ksort($return);
		//array_sum($ks);
		$i = 0;

		if($row_id == null){
			echo '<table class="table table-bordered">';
			echo '
				<tr>
					<th></th>
					<th>Kohde</th>
					<th>Kustannuspaikka</th>
					<th>Kesto</th>
					<th>Kerta</th>
				</tr>
			';
		}
		foreach($return as $k=>$result)
		{
			$i++;
		    $muokattu = '';
		    if(isset($result['muokattu']) and $result['muokattu'] == 'muokattu')
		    	$muokattu = 'text-danger';
		    	
			if(isset($result['row_id']) and $row_id == null){
				echo 
				'<tr>
					<td width="1" class="text-center">
						<span style="vertical-align: center;" class="link showKukaSub text-danger" row_id="'.$result['row_id'].'" status="'.$result['status'].'" kohde="'.$result['kohdenID'].'" tid="'.$result['tid'].'" kohde_kannasta="'.$result['kohde_kannasta'].'"><i class="fa fa-2x fa-caret-square-o-down"></i></span>
					</td>
					<td>
					   <span class="col-sm-6 text-right '.$muokattu.'">'.$result['kohde_kannasta'].'</span>
					</td>
					<td>'.$result['kustannuspaikka_nro'].'</td>
					<td>'.$this->sprint($ks[$k]).' ('.$this->num($ks[$k]).')</td>
					<td>'.$c[$k].'</td>
				</tr>';
			} elseif(isset($result['pvm']) and $row_id != null) {
				echo 
				'<tr>
					<td></td>
					<td>'.$result['pvm'].'</td>
					<td>'.$result['aloitan'].'</td>
					<td>'.$result['loppui'].'</td>
					<td>'.$this->sprint($result['kesto']).'</td>
				</tr>';
			}
		}
		if($row_id == null){
			echo '<tr>
				<td>'.Yii::t('main','Yhteensä').'</td>
				<td><b>'.$this->sprint($sum).' ('.$this->num($sum).')</b></td>
			</tr>';
			echo '</table>';
		}
		exit;
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
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
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
			AND deleted=0
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
	$r .= '<td style="width:18%">'.(($data->status == 3)?$data->kohde_kannasta:'').(($data->status == 10)?'LOUNASTAUKO':'').(($data->status == 2)?'MATKA':'').'</td>';
	$r .= '<td style="width:10%">'.date("H:i",strtotime($data->aloitan)).'</td>';
	$r .= '<td style="width:10%">'.date("H:i",strtotime($data->loppui)).'</td>';
	$r .= '<td style="width:10%">'.$this->sprint($kesto).'</td>'; //<br><b>('.num($kesto).')</b>
	$r .= '<td style="width:10%">'.$this->num($kesto).'</td>'; //<br><b>('.num($kesto).')</b>
	$r .= '<td style="width:10%">'.$this->statusMuutosNimeksi($data->status).'</td>';
	$r .= '</tr>';
	return $r;
	}

	protected function statusMuutosNimeksi($nro){

		$r = '';
		if($nro == 3)
			$r = Yii::t('main', 'Työ');
		elseif($nro == 2)
			$r = Yii::t('main', 'Matka');
		elseif($nro == 10)
			$r = Yii::t('main', 'Lounastauko');
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
			AND deleted=0
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
			AND deleted=0
		";
		$tot = Toteutuneet::model()->find($criteria);

		if(isset($lu->l_tunnit))
		$result = $lu->l_tunnit;

		if(isset($tot->l_tunnit))
		$result = $result+$tot->l_tunnit;


		return $result;
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

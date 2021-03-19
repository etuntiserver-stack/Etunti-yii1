<?php

class AsetuksetController extends Controller
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
				'actions'=>array('update', 'yrityksentiedot', 'oikeudet', 'rekisteriseloste', 'tiedostot', 'createbackup', 'procountor_auth'),
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

	public function actionCreatebackup($domain)
	{
		echo $domain;
		exec("php " . Yii::app()->basePath . "/cosbackup.php -d$domain", $output);
		print_r($output);
		//$this->redirect(array('update', 'id' => 1));
		exit;
	}

	public function actionOikeudet()
	{
	// <-- Oikeudet
	   $checkOikeus = "asetukset_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->
		if(isset($_POST['oikeudet']))
		{
			$as = Asetukset::model()->updatebypk(1,array('oikeudet' => json_encode($_POST['oikeudet'])));
			exit;
		} else {

			$this->render('oikeudet');
		}
	}

	public function actionTiedostot($id)
	{

		$model = $this->loadModel($id);

		if(isset($_POST['uploaded_t']))
		{

		  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
		  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
		  }

		  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
		  $uploadfile = $uploaddir . basename($model->id.'_'.$_FILES['file']['name']);
		  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		     //echo "";
		  } 
		}


		if(isset($_POST['uploaded_onlinevarausehdot']))
		{

		  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
		  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
		  }

		  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
		  $temp = explode(".", $_FILES["file"]["name"]);
		  $uploadfile = $uploaddir . basename('onlinevarausehdot.'.end($temp));
		  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		     $this->redirect(array('tiedostot', 'id' => 1));
		  } 
		}

		if(isset($_POST['uploaded_toimitusehdot']))
		{
		
		  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
		  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
		  }

		  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
		  $temp = explode(".", $_FILES["file"]["name"]);
		  $uploadfile = $uploaddir . basename('toimitusehdot.'.end($temp));
		  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		     $this->redirect(array('tiedostot', 'id' => 1));
		  } 
		}

		if(isset($_POST['uploaded_Konevuokraus_toimitusehdot']))
		{

		  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
		  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
		  }

		  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
		  $temp = explode(".", $_FILES["file"]["name"]);
		  if(end($temp) == 'pdf')
		  {
			$uploadfile = $uploaddir . basename('Konevuokraus_toimitusehdot.'.end($temp));
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
		     $this->redirect(array('tiedostot', 'id' => 1));

		  } else {
	
			Yii::app()->user->setFlash('danger', "Lataaminen ei onnistunut, odottelaan PDF");
		     $this->redirect(array('tiedostot', 'id' => 1));
		  }
		}

		if(isset($_POST['uploaded_tietosuojaseloste']))
		{

		  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
		  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
		  }

		  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
		  $temp = explode(".", $_FILES["file"]["name"]);
		  if(end($temp) == 'pdf')
		  {
			$uploadfile = $uploaddir . basename('Tietosuojaseloste.'.end($temp));
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
		     $this->redirect(array('tiedostot', 'id' => 1));

		  } else {
	
			Yii::app()->user->setFlash('danger', "Lataaminen ei onnistunut, odottelaan PDF");
		     $this->redirect(array('tiedostot', 'id' => 1));
		  }
		}

		if(isset($_POST['uploaded_edico_kayttoehdot']))
		{
		
		  $path = Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain;
		  if (!file_exists($path)) {
		  	mkdir($path, 0777, true);
		  }

		  $path_html = Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain.'/eDico_html';
		  if (!file_exists($path_html)) {
		  	mkdir($path_html, 0777, true);
		  }

		  $uploaddir = $path.'/';
		  $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		  $bname = 'eDico_kayttoehdot';
		  if($ext == 'pdf')
		  {
			$uploadfile = $uploaddir . basename($bname.'.pdf');
			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
			{
				$exec = 'pdftohtml -c -s -noframes '.$path.'/'.$bname.'.pdf '.$path_html.'/'.$bname.'.html';
				exec($exec.' 2>&1', $output, $return);
				if (file_exists($path_html.'/'.$bname.'.html')) {
				  	$html_content = file_get_contents($path_html.'/'.$bname.'.html');
				  	$html_content = str_replace("background image", "", $html_content);
				  	$html_content = str_replace("body bgcolor=\"#A0A0A0\"", "body bgcolor=\"#FFFFFF\"", $html_content);
					$html_content = preg_replace("/<img[^>]+\>/i", "", $html_content);
				  	$html_content = str_replace("p {margin: 0; padding: 0;}", "", $html_content);

					//$html_content = strip_tags($html_content, '<style>');
					$html_content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html_content);
					$html_content = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $html_content);
					if(file_put_contents($path.'/'.basename($bname.'.html'), $html_content))
		  				exec('rm -rf '.$path_html);
				}
		     $this->redirect(array('tiedostot', 'id' => 1));
			}

		  } else {

			Yii::app()->user->setFlash('danger', "Lataaminen ei onnistunut, odottelaan PDF");
			//$this->redirect(array('update', 'id'=>1));
		  }
		}


		if(isset($_POST['uploaded_logo']))
		{

		  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
		  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
		  }

		  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
		  $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		  $uploadfile = $uploaddir . basename('systemlogo.'.$ext);
		  if($ext != 'jpg' and $ext != 'png')
		  {
			Yii::app()->user->setFlash('danger', "Lataaminen ei onnistunut, odottelaan JPG tai PNG.");
			$this->redirect(array('tiedostot','id'=>$model->id));
			exit;
		  }

		  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		     $this->redirect(array('tiedostot', 'id' => 1));
		  } 
		}

		if(isset($_POST['uploaded_ov_tauste']))
		{

		  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain.'/pub')) {
		  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain.'/pub', 0777, true);
		  }

		  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/pub/';
		  $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		  $uploadfile = $uploaddir . basename('ov_tauste.'.$ext);
		  if($ext != 'jpg')
		  {
			Yii::app()->user->setFlash('danger', "Lataaminen ei onnistunut, odottelaan JPG tai PNG.");
			$this->redirect(array('tiedostot','id'=>$model->id));
			exit;
		  }

		  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		  	file_put_contents(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain.'/pub/.htaccess', 'allow from all');  
		  	$this->redirect(array('tiedostot', 'id' => 1));
		  } 
		}


		if(isset($_POST['poistaTamaTiedosto'])){
			unlink($_POST['poistaTamaTiedosto']);
			exit;
		}

		$this->render('tiedostot',array(
			'model'=>$model,
		));
	}

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	public function actionRekisteriseloste()
	{

		if(isset($_POST['rekisteriseloste']))
		{
			$as = Asetukset::model()->updatebypk(1,array('rekisteriseloste' => json_encode($_POST['rekisteriseloste'])));
			exit;
		} else {
			$rt = Asetukset::model()->findbypk(1);
			$rekisteriseloste = json_decode($rt->rekisteriseloste);
			$this->render('rekisteriseloste',array(
				'rekisteriseloste'=>$rekisteriseloste
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
		$model=new Asetukset;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Asetukset']))
		{
			$model->attributes=$_POST['Asetukset'];
			if(isset($_POST['Asetukset']['netvisor_mita_lahetetaan']))
			$model->netvisor_mita_lahetetaan=json_encode($_POST['Asetukset']['netvisor_mita_lahetetaan']);

			if(isset($_POST['Asetukset']['edico_muut_kulut']))
				$model->edico_muut_kulut=json_encode($_POST['Asetukset']['edico_muut_kulut']);
			else
				$model->edico_muut_kulut='';

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
	public function actionYrityksentiedot($id)
	{

	// <-- Oikeudet
	   $checkOikeus = "yrityksentiedot_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' ");
		$tasot = explode(",", $domainit->paketti);


		// <-- Uusi taso ota kayttoon
		if(isset($_POST['taso']))
		{
			array_push($tasot, $_POST['taso']);
			sort($tasot);
			$paketti = implode(",", $tasot);
			Domainit::model()->updateByPk($domainit->id, array('paketti' => $paketti));

			echo json_encode($tasot);
			exit;
		}
		//   Uusi taso ota kayttoon -->


		$model=$this->loadModel($id);
		$f = FirmanTiedot::model()->findbypk(1);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FirmanTiedot']))
		{
			$f->attributes=$_POST['FirmanTiedot'];
			if($f->save())
			{
				Yii::app()->user->setFlash('success', "Tiedot tallennettu.");
				$this->redirect(array('yrityksentiedot','id'=>$model->id));
			}
		}

		$this->render('yrityksentiedot',array(
			'f'=>$f,
			'tasot' => $tasot,
			'domainit' => $domainit,
			'asetukset' => $model
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
	   $checkOikeus = "asetukset_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=$this->loadModel($id);
		if(isset($_POST['Asetukset']))
		{

			$vanha_attr = $model->attributes;
			$model->attributes=$_POST['Asetukset'];
			if(isset($_POST['Asetukset']['netvisor_mita_lahetetaan'])){
				$model->netvisor_mita_lahetetaan=json_encode($_POST['Asetukset']['netvisor_mita_lahetetaan']);
			}
			if(isset($_POST['Asetukset']['edico_muut_kulut'])){
				$model->edico_muut_kulut=json_encode($_POST['Asetukset']['edico_muut_kulut']);
			} else {
				$model->edico_muut_kulut='';
 			}
			if(isset($_POST['Asetukset']['asiakas_ryhma'])){
				$model->asiakas_ryhma = json_encode($_POST['Asetukset']['asiakas_ryhma']);
			} else {
				$model->asiakas_ryhma="";
			}
			if(isset($_POST['Asetukset']['asiakas_pakkoliset'])){
				$model->asiakas_pakkoliset = json_encode($_POST['Asetukset']['asiakas_pakkoliset']);
			} else {
				$model->asiakas_pakkoliset="";
			}
			if($model->save())
			{

				// <-- Check TRUST
				if($model->palvelu_tyyppi == 2 and empty($model->trust_cid) and empty($model->trust_cid))
				{
					Yii::app()->user->setFlash('danger', "Sinulla ei ole asetuksissa määritettynä TRUST-tunnuksia.
Jos yritykselläsi ei ole Ropo Capital Oy:n kanssa sopimusta tunnuksista, lähetä viesti osoitteeseen tuki@etunti.fi ja autamme sopimuksen syntymisessä.");
				}
				//    Check TRUST -->

				// <-- kirjautumistunnus
				if(isset($_POST['Asetukset']['kirjautumistunnus']) and !empty($_POST['Asetukset']['kirjautumistunnus']))
				{
			       		$criteria = new CDbCriteria();
			       		$criteria->condition = " 
						domain!='".Yii::app()->user->domain."' 
						AND kirjautumistunnus!='' AND kirjautumistunnus='".$_POST['Asetukset']['kirjautumistunnus']."'
					";
					$domainit_all = Domainit::model()->findAll($criteria);

					if(isset($domainit_all[0]))
					{
						Yii::app()->user->setFlash('danger', "Tämä kirjautumistunnus on varattu.");
					} else {
						$domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' ");
						Domainit::model()->updateByPk($domainit->id, array('kirjautumistunnus' => $_POST['Asetukset']['kirjautumistunnus']));
					}
				}
				//     kirjautumistunnus -->

				// <-- LOG
				$model_log 	= 'Asetukset';
				$name_log 	= 'Asetukset';
				$status_log 	= 'Update';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

				$this->redirect(array('update','id'=>$model->id));
			}
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
		$dataProvider=new CActiveDataProvider('Asetukset');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Asetukset('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Asetukset']))
			$model->attributes=$_GET['Asetukset'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Asetukset the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Asetukset::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Asetukset $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='asetukset-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function oikeudenOtsikot()
	{
		$criteria = new CDbCriteria();
		$criteria->order = "id=1 DESC,for_delete";
		$model = OikeusRyhmat::model()->findAll($criteria);
		foreach($model as $data)
		$otsiko[$data->id] = ['nimike' => $data->nimike, 'for_delete' => $data->for_delete];
		return $otsiko;
	}

	protected function pakettiMuutos($pakettit)
	{
		$tasot = explode(",", $pakettit);
		$return = array();
		if(in_array(1, $tasot) and in_array(2, $tasot))
		$return[] = 'eTyö';
		if(in_array(3, $tasot))
		$return[] = 'eLasku';
		if(in_array(4, $tasot))
		$return[] = 'eOnline';
		if(in_array(5, $tasot))
		$return[] = 'eDico';

		return implode(", ", $return);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}

	/**
	 * Authorize Procountor in this environment. Procountor login page returns to
	 * this action, providing the authorization code that will be traded for an
	 * access token and a refresh token.
	 *
	 * @param int $code
	 * Authorization code.
	 * @param mixed $state
	 * Custom state set by this app.
	 */
	public function actionProcountor_auth($code, $state = null)
	{
		$context = ['model' => $this->loadModel(1)];

		// Login success message is now displayed under the login button.
		if (empty($code)) {
			$context['procountor_auth_success'] = false;
			// 	Yii::app()->user->setFlash('danger', 'Virheellinen pyyntö (vastaanotettu kirjautumistunnus on tyhjä).');
			$context['procountor_auth_message'] = 'Virheellinen pyyntö (vastaanotettu kirjautumistunnus on tyhjä).';
		} elseif (Yii::createComponent('Procountor')->authorize($code)) {
			$context['procountor_auth_success'] = true;
			$context['procountor_auth_message'] = 'Procountor kirjautuminen onnistui.';
			// 	Yii::app()->user->setFlash('success', 'Procountor kirjautuminen onnistui.');
		} else {
			$context['procountor_auth_success'] = false;
			$context['procountor_auth_message'] = 'Procountor kirjautuminen epäonnistui.';
			// 	Yii::app()->user->setFlash('danger', 'Procountor kirjautuminen epäonnistui.');
		}

		// Redirect back to the settings page.
		$this->render('update', $context);
	}
}

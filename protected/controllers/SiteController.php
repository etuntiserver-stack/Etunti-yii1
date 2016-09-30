<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
/*
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
*/
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('etunnin_asiakkaat', 'update_etunnin_asiakas', 'etunnin_asiakas_kk', 'laheta_et_kirje', 'etunnin_asiakas_kk_laskuri'),
                		'expression'=>"Yii::app()->controller->isDigisten()",
			),
			array('allow', 
				'actions'=>array( 'header', 'footer', 'lomake_tarjouspyynto', 'lomake_testiryhma', 'ajankohtaista', 'asiakkaat', 'yritys', 'yhteystiedot', 'lomake_lataailmainen', 'uusi_kommento', 'crontab', 'logout'),
				'users'=>array('*'),
			),
			array('allow', 
				'actions'=>array('etusivu','ohjesivu','etusivu_esimerki', 'change_color', 'valiko', 'valiko_ajax', 'kohderyhma', 'ohjevideot', 'mobemu', 'etusivu_ajax', 'ulkonaky'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', 
				'actions'=>array('index','test','hyvaksy','hylkaa'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function isDigisten() {

		if(isset(Yii::app()->user->adminID) and Yii::app()->user->domain == 'digisten')
		{
		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	        if($m->id == Yii::app()->user->adminID)
	            return true;
		} else {
	            return false;
		}
	}

	public function isEtuntiAdmin() {

		if(isset(Yii::app()->user->adminID))
		{
		$m = Administrators::model()->findbypk(Yii::app()->user->adminID);
	        if($m->id == Yii::app()->user->adminID)
	            return true;
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
                } elseif (isset(Yii::app()->user->asiakas)) {
                        Yii::app()->theme = 'customer';
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();
        }



	public function actionUlkonaky()
	{
		$ad = Administrators::model()->findByPk(Yii::app()->user->adminID);
		$result = '';
		if(isset($_POST['vaihdo']))
		{
			if( $_POST['vaihdo'] == 'sidebarSkin' )
			{

				$ulkonaky = json_decode($ad->ulkonaky, true);
				$ulkonaky['sidebarSkin'] = $_POST['sidebarSkin'];

				$result = json_encode($ulkonaky);
				Administrators::model()->updateByPk($ad->id, array('ulkonaky'=>$result));
			
			}

			if( $_POST['vaihdo'] == 'headerSkin' )
			{

				$ulkonaky = json_decode($ad->ulkonaky, true);
				$ulkonaky['headerSkin'] = $_POST['headerSkin'];

				$result = json_encode($ulkonaky);
				Administrators::model()->updateByPk($ad->id, array('ulkonaky'=>$result));
			
			}
		}

		if(isset($_POST['getSkins']))
		{
			$result = $ad->ulkonaky;
		}

		if(isset($_POST['clearStorage']))
		{
			Administrators::model()->updateByPk($ad->id, array('ulkonaky'=>''));
		}

		echo $result;
	}


	public function actionEtusivu_ajax()
	{
		if(isset($_POST['suoritus']))
		{
		$suoritus = $_POST['suoritus'];
		$this->renderPartial('etusivu_ajax',array(
			'suoritus'=>$suoritus,
		));
		}
	}

	public function actionCrontab($pass)
	{
                Yii::app()->theme = 'classic';
		$this->renderPartial('crontab',array(
			'pass'=>$pass,
		));
	}


	public function actionLaheta_et_kirje()
	{

		if(isset($_POST['Domainit']))
		{


			$message = str_replace("\n", "<br>", $_POST['Domainit']['viesti']);
			foreach($_POST['Domainit']['sahkoposti'] as $sahkoposti)
			{
			
	
			$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($sahkoposti);
			$mail->setSubject(Yii::t('main', 'ETUNTI.FI'));
			$mail->setBody($message);


			}

				$this->redirect(array('etunnin_asiakkaat'));
		}

		$model= new Domainit;
		$this->render('laheta_et_kirje',array(
			'model'=>$model,
		));
	}


	public function actionEtunnin_asiakas_kk_laskuri($id)
	{
		$model=Domainit::model()->findbypk($id);

		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$model->domain;

		$this->render('etunnin_asiakas_kk_laskuri',array(
			'id'=>$id,
			'model'=>$model,
		));
	}


	public function actionEtunnin_asiakas_kk($id)
	{
		$model=Domainit::model()->findbypk($id);

		Yii::app()->db1->setActive(false);
		Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname='.$model->domain;

		$this->render('etunnin_asiakas_kk',array(
			'id'=>$id,
			'model'=>$model,
		));
	}

	public function actionUpdate_etunnin_asiakas($id)
	{
		$model=Domainit::model()->findbypk($id);

		if(isset($_POST['Domainit']))
		{
			$model->attributes=$_POST['Domainit'];
			if(isset($_POST['tasot'])) $model->paketti = implode(",",$_POST['tasot']);
			if($model->save())
				$this->redirect(array('etunnin_asiakkaat'));
		}

		$this->render('update_etunnin_asiakas',array(
			'model'=>$model,
		));
	}

	public function actionEtunnin_asiakkaat()
	{
       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";
	        $criteria->condition = " domain!='defdb' ";

		if(isset($_POST['domain_nimi']) and !empty($_POST['domain_nimi']))
	        $criteria->addCondition (" domain LIKE '%".$_POST['domain_nimi']."%' ");

		$dataProvider=new CActiveDataProvider('Domainit', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('etunnin_asiakkaat', array('dataProvider' => $dataProvider));
	}


	public function actionAjankohtaista()
	{
		Yii::app()->theme = 'classic';
		$this->render('ajankohtaista');
	}
	public function actionOhjevideot()
	{
		$this->render('ohjevideot');
	}
	public function actionAsiakkaat()
	{
		$this->render('asiakkaat');
	}
	public function actionYritys()
	{
		Yii::app()->theme = 'classic';
		$this->render('yritys');
	}
	public function actionYhteystiedot()
	{
		Yii::app()->theme = 'classic';
		$this->render('yhteystiedot');
	}
	public function actionLomake_lataailmainen()
	{
		Yii::app()->theme = 'classic';
		$this->render('lomake_lataailmainen');
	}
	public function actionUusi_kommento()
	{
		if(isset($_POST))
		{
			$model = new BlogComments;
			$model->blog_id=$_POST['blog_id'];
			$model->nimimerkki=$_POST['nimimerkki'];
			$model->teksti=$_POST['teksti'];
			if($model->save())
			echo 'ok';
		}
		exit;
	}





	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
	}


	public function actionKohderyhma()
	{

		$data = array();

	 	if(isset($_POST) and (!empty($_POST['ryhma']) or !empty($_POST['myyja'])))
		{
       			$criteria = new CDbCriteria();

			if(!empty($_POST['ryhma']))
       			$criteria->addCondition ( " ryhma='".trim($_POST['ryhma'])."' " );

			if(!empty($_POST['myyja']))
       			$criteria->addCondition ( " myyja='".trim($_POST['myyja'])."' " );







			$a = Asiakkaat::model()->findAll($criteria);
			foreach($a as $asiakas)
			{
				$m = Administrators::model()->findbypk($asiakas->myyja);
				if(isset($m->adm_nimi)) $myyja = $m->adm_nimi; else $myyja = '';

				$data[] = array(
					'Asiakkaat', 
					$asiakas->yrityksen_nimi, 
					$asiakas->yhteyshenkilo, 
					$asiakas->osoite,
					$asiakas->kaupunki,
					$asiakas->puhelin,
					$asiakas->sahkoposti,
					$myyja,
				);
			}

       			$criteria = new CDbCriteria();

			if(!empty($_POST['ryhma']))
       			$criteria->addCondition ( " ryhma='".trim($_POST['ryhma'])."' " );

			if(!empty($_POST['myyja']))
       			$criteria->addCondition ( " myyja='".trim($_POST['myyja'])."' " );

			$y = Yhteystiedot::model()->findAll($criteria);
			foreach($y as $asiakas)
			{
				$m = Administrators::model()->findbypk($asiakas->myyja);
				if(isset($m->adm_nimi)) $myyja = $m->adm_nimi; else $myyja = '';

				$data[] = array(
					'Yhteystiedot', 
					$asiakas->yrityksen_nimi, 
					$asiakas->yhteyshenkilo, 
					$asiakas->osoite,
					$asiakas->postitoimipaikka,
					$asiakas->puhelin,
					$asiakas->sahkoposti,
					$myyja,
				);
			}

		}



		$this->render('kohderyhma', array('data'=>$data));
	}

	public function actionValiko()
	{

	
$mod = '
	<input type="hidden" id="select_type" value="'.$_POST['select_type'].'">
	<div id="result"></div>';


$mod .= '
<script type="text/javascript">
$(document).ready(function(){


        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko_ajax",
           type: "POST",
           data: { "select_type" : $("#select_type").val() },
           success: function(data){
		//console.log(data);
		$("#result").html(data);
           }
        });

});
</script>';
			
		echo json_encode($mod);
	}

	public function actionValiko_ajax()
	{
		$this->renderPartial('valiko_ajax');
	}


	public function actionChange_color()
	{
		//Yii::app()->user->setState('myBgColors', $_POST['myBgColors']);
	}

	public function actionEtusivu()
	{

		if(isset($_POST['currentBody']))
		Yii::app()->user->setState('currentBody',$_POST['currentBody']);
		
		if(isset($_GET['theme']))
		{
		  Yii::app()->user->setState('user_theme',$_GET['theme']);
		  $this->redirect('/index.php/site/etusivu');
		}
		$this->render('etusivu');

	}

	public function actionLomake_testiryhma()
	{
		$this->render('lomake_testiryhma');
	}

	public function actionLomake_tarjouspyynto()
	{
		$this->renderPartial('lomake_tarjouspyynto');
	}

	public function actionEtusivu_esimerki()
	{
		$this->render('etusivu_esimerki');
	}

	public function actionOhjesivu()
	{
		$this->render('ohjesivu');
	}

	public function actionTest()
	{
	/*
        # Example from HTML2PDF wiki: Send PDF by email
        $content_PDF = $html2pdf->Output('', EYiiPdf::OUTPUT_TO_STRING);
        require_once(dirname(__FILE__).'/pjmail/pjmail.class.php');
        $mail = new PJmail();
        $mail->setAllFrom('webmaster@my_site.net', "My personal site");
        $mail->addrecipient('mail_user@my_site.net');
        $mail->addsubject("Example sending PDF");
        $mail->text = "This is an example of sending a PDF file";
        $mail->addbinattachement("my_document.pdf", $content_PDF);
        $res = $mail->sendmail();
	*/

        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $html2pdf->WriteHTML($this->renderPartial('test', compact('model'),true));
        $html2pdf->Output();


	}

	public function actionHyvaksy($id,$code,$domain)
	{

		Yii::app()->theme = 'classic';

       		$criteria = new CDbCriteria();
       		$criteria->condition = " status=1 AND id='".$id."' AND code='".trim($code)."' ";
		$model = AsiakasHyvaksynta::model()->find($criteria);

		if(isset($model->id))
		{

		$ids = explode(",",$model->ids);
		foreach($ids as $val)
		{
		    $explVal = explode("_", $val);
		    if(isset($explVal[1]))
		    {
			if($explVal[0] == 'mobile') 
			   Mobile::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'1_'.date("d.m.Y")));
	
			if($explVal[0] == 'toteutu')
			   Toteutuneet::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'1_'.date("d.m.Y")));
		    }
	
		}
	

		$this->render('hyvaksy', array(
			'asia' => true,
		));


		AsiakasHyvaksynta::model()->updatebypk($model->id, array('code'=>'','status'=>3));

		} else {

		$this->render('hyvaksy', array(
			'asia' => false,
		));

		}



	}

	public function actionHylkaa($id,$code,$domain)
	{

		Yii::app()->theme = 'classic';

		$this->render('hylkaa', array(
			'id' => $id,
			'code' => $code,
			'domain' => $domain,
		));
	}

	public function actionMobemu()
	{
		$this->render('mobemu');
	}

	public function actionIndex()
	{

		//if(isset(Yii::app()->user->adminID))
			$this->render('index');
		//else
			//$this->render('start');

	}


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


	public function toteutuThisMonth($k)
	{
		$month = $k;
		$total_l = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			aloitan !='' and loppui !='' and status ='3'
			AND EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d'))  = '".$month."'
			AND id NOT IN(select kid from sivexkuitti_repaired)
		";

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{

		    $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		    $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $total_l += $l->l_tunnit;
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			aloitan !='' and loppui !='' and status ='3'
			AND EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d'))  = '".$month."'
		";

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{

		    $l->loppui = date("d.m.Y H:i",strtotime($l->loppui));
		    $l->aloitan = date("d.m.Y H:i",strtotime($l->aloitan));

		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $total_l += $l->l_tunnit;
		}


		return $total_l;

	}


	public function toteutuThisMonthByCity($k,$city)
	{
		$month = $k;
		$total_l = 0;

       		$criteria = new CDbCriteria();
		$criteria->with=array('kohteet');
        	$criteria->select = " COUNT(*) as count";
        	$criteria->group = " kohdenID ";
        	$criteria->condition = "  
			aloitan !='' and loppui !='' and status ='3'
			AND EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d'))  = '".$month."'
			AND t.id NOT IN(select kid from sivexkuitti_repaired)
			AND kohteet.kaupunki LIKE '%".$city."%'
		";

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $total_l += $l->count;
		}

		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
		$criteria->with=array('kohteet');
        	$criteria->select = " COUNT(*) as count";
        	$criteria->group = " kohdenID ";
        	$criteria->condition = "  
			aloitan !='' and loppui !='' and status ='3'
			AND EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d'))  = '".$month."'
			AND kohteet.kaupunki LIKE '%".$city."%'
		";

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $total_l += $l->count;
		}


		return $total_l;

	}



	public function tilatTanaan($tila)
	{
		$total_l = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count";
        	$criteria->group = " status ";
        	$criteria->condition = "  
			aloitan !='' and loppui !='' and status ='".$tila."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')  = CURDATE()
			AND t.id NOT IN(select kid from sivexkuitti_repaired)
		";

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $total_l += $l->count;
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count";
        	$criteria->group = " status ";
        	$criteria->condition = "  
			aloitan !='' and loppui !='' and status ='".$tila."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')  = CURDATE()
		";

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $total_l += $l->count;
		}


		return $total_l;
	}

	public function parasSiivojaTanaan()
	{
		$total_l = array();

       		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count,tekijan_nimi";
        	$criteria->order = " tekijan_nimi ";
        	$criteria->group = " tid ";
        	$criteria->condition = "  
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')  = CURDATE()
			AND t.id NOT IN(select kid from sivexkuitti_repaired)
		";

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $total_l[] = array($l->tekijan_nimi,(int)$l->count);
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count,tekijan_nimi";
        	$criteria->order = " COUNT(*) LIMIT 4 ";
        	$criteria->group = " tid ";
        	$criteria->condition = "  
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')  = CURDATE()
		";

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $total_l[] = array($l->tekijan_nimi,(int)$l->count);
		}


		return $total_l;

	}


	protected function oikeudet($id,$sivu)
	{
		$return = '';


/*
 		$return .= CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$id), 
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));

	   
	     	$return .= '
		<script type="text/javascript">
		$(document).ready(function(){
		   $(":input").prop("disabled", true);	
		});
		</script>';
*/
	   

		echo $return;
	}


	public function checkOikeus($pyynto)
	{
	   $return = '';
	   $asetukset = Asetukset::model()->findbypk(1);
	   $oikeudet = $asetukset->oikeudet;
	   if (!preg_match("/".$pyynto."/i", $oikeudet) and Yii::app()->user->username != 'admin') {

	      	$return = '
		<link href="'.Yii::app()->request->baseUrl.'/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<br>
		<div class="col-sm-6 col-sm-offset-3">
		 <center>
		  <div class="alert alert-warning">
			<h2>Sinulla ei ole tarvittavia oikeuksia!</h2>
			<p>"admin" tunnuksella saa vaihda oikeuksia asetuksessa</p>
		  </div>
		 </center>
		</div>';

		echo $return;
		exit;
	   }

		echo $return;
	}



	public function checkOikeusFields($pyynto)
	{

	   $asetukset = Asetukset::model()->findbypk(1);
	   $oikeudet = $asetukset->oikeudet;
	   if (!preg_match("/".$pyynto."/i", $oikeudet) and Yii::app()->user->username != 'admin') {
	   	$return = 0;
	   } else {
	   	$return = 1;
	   }

		return $return;
	}

	public function eiLasketa()
	{
		$return = " 
		(tyoajanmerkinta NOT LIKE '%Ei lasketa%' AND tyoajanmerkinta NOT LIKE '%Varallaolo%')
		";

		return $return;
	}

	public function eiLasketaSubStr($val)
	{

		$return = false;
		if (strpos($val, 'Ei lasketa') !== false or strpos($val, 'Varallaolo') !== false) {
		    $return = true;
		}
		return $return;
	}

	public function moduliMuutos($m)
	{
		$return = "";
		$ex = explode(",", $m);
		foreach($ex as $e)
		{
			$t = Tasot::model()->find(" taso='".$e."' ");
			if(isset($t->id))
			$return .= '<b>'.$t->nimetys.':</b> '.$t->kuvaus."<br>";
		}

		echo $return;
	}


	public function netvisorYhteys()
	{

	   $return = array();
	   $a = Asetukset::model()->findbypk(1);
	   $fm = FirmanTiedot::model()->findbypk(1);

	   if($a->netvisor_kaytto == 1)
	   {

		$url		= "http://integrationdemo.netvisor.fi"; 
		$host 		= 'integrationdemo.netvisor.fi';

		$sender 	= $fm->tyonantaja;
		$customerId	= $a->netvisor_customer_id;
		$partnerId	= $a->netvisor_partner_id;
		$timestamp	=  date("Y-m-d H:i:s");
		$language	= 'FI';
		$organisationIdentifier	= $a->netvisor_organisation_identifier;
		$transactionIdentifier	= rand(0,10000000);
		$userKey 	= $a->netvisor_userkey;
		$partnerKey	= $a->netvisor_partnerkey;

		$return = array($url,$host,$sender,$customerId,$partnerId,$timestamp,$language,$organisationIdentifier,$transactionIdentifier,$userKey, $partnerKey);

	   }

		return $return;

	}

}

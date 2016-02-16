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

	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('lomake_tarjouspyynto', 'lomake_testiryhma'),
				'users'=>array('*'),
			),
			array('allow', 
				'actions'=>array('etusivu','ohjesivu','etusivu_esimerki', 'change_color', 'valiko', 'valiko_ajax'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', 
				'actions'=>array('index','test','hyvaksy','hylkaa'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('mobemu'),
				'users'=>array('@'),
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

	protected function sprint($val){
	    if($val > 0)
		return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
	}

	protected function num($val){
	    if($val > 0)
		return  number_format((float)$val/3600, 2, '.', '');
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
		console.log(data);
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

		$this->render('hyvaksy', array(
			'model' => $model,
		));
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

	   if(Yii::app()->user->adminStatus == 1 and $sivu != 'noDelete'){

 		$return .= CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$id), 
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));

	   } elseif(Yii::app()->user->adminStatus == 2){

	   } elseif(Yii::app()->user->adminStatus == 3){
	   
	     $return .= '
		<script type="text/javascript">
		$(document).ready(function(){
		   $(":input").prop("disabled", true);	
		});
		</script>';

	   }

		echo $return;
	}



}

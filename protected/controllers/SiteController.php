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
				'actions'=>array('index','test','hyvaksy','hylkaa','uploadfromphone'),
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

	public function actionUploadfromphone($dom)
	{
	
	  if (!file_exists(Yii::app()->basePath."/../img/uploadedfromphone/".$dom)) {
	  	mkdir(Yii::app()->basePath."/../img/uploadedfromphone/".$dom, 0777, true);
	  }
	
		$kohdenID = $_POST['kohdenID'];

		function saveImage($base64img,$dom,$kohde,$tekija){
		    define('UPLOAD_DIR', Yii::app()->basePath."/../img/uploadedfromphone/".$dom."/");
		    $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
		    $data = base64_decode($base64img);
		    $file = UPLOAD_DIR . $kohde.'_'.$tekija.'_'.time().'.jpg';
		    
		    if(file_put_contents($file, $data))
			echo 'saveOk';
		    else
			echo 'saveError';
		}

	  	$model = Kohteet::model()->findbypk($kohdenID);

	   	$criteria = new CDbCriteria();
	    	$criteria->condition = "  
			salasana!='' 
			AND tekijan_email = '".$_POST['email']."' 
			AND salasana = '".$_POST['salasana']."' 
	    	";
            	$ttekija = Tyontekijat::model()->find($criteria);

	  	if(isset($model->id) and isset($ttekija->id) and isset($_POST['img'])){
			saveImage(utf8_decode($_POST['img']),$dom,$model->id,$ttekija->id);
	     	} else {
			echo 'Ei onnistu!';
	     	}
		//print_r($_POST);
		echo '<br>vastaus etunti.fi';
		exit; //exit

	}

	public function actionHyvaksy($id,$code,$domain)
	{
		$model = AsiakasHyvaksynta::model()->find(" id='".$id."' and code='".$code."' ");

		$this->render('hyvaksy', array(
			'model' => $model,
		));
	}

	public function actionHylkaa($id,$code,$domain)
	{

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
}

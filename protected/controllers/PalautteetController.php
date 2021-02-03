<?php

class PalautteetController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view', 'lahetetty', 'vastaus'),
                		'expression'=>"Yii::app()->controller->isAsiakas() or Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create'),
                		'expression'=>"Yii::app()->controller->isAsiakas()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','view','admin','delete','update'),
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
		} else {
	            return false;
		}
	}

	public function isAsiakas() 
	{
		if(isset(Yii::app()->user->asiakas))
		{
		$m = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
	        if($m->id == Yii::app()->user->asiakas)
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
                } else {
                        Yii::app()->theme = 'classic';
                }
                parent::init();
        }


	public function Vastaus($id, $post)
	{
		Yii::app()->theme = 'customer';
		$model = new Palautteet;


		if(isset($post['Palautteet']))
		{
			$p = Palautteet::model()->findbypk($id);

			$model->attributes=$post['Palautteet'];
			$model->otsikko=$p->otsikko;
			$model->asiakas_id=$p->asiakas_id;


			$nimi = '';
			$as = Asiakkaat::model()->findbypk($p->asiakas_id);
			$firma = FirmanTiedot::model()->findbypk(1);

			if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi))
			$nimi = $as->yrityksen_nimi;
			elseif(isset($as->yhteyshenkilo) and !empty($as->yhteyshenkilo))
			$nimi = $as->yhteyshenkilo;

			if(isset(Yii::app()->user->asiakas))
				$model->teksti = '<b>'.$nimi.'</b>: '.$model->teksti;
			elseif(isset(Yii::app()->user->nimi))
				$model->teksti = '<b>'.Yii::app()->user->nimi.'</b>: '.$model->teksti;

			if($model->save())
			{


				$ft = FirmanTiedot::model()->findbypk(1);

				$message = Yii::t('main', 'Asiakas').': '.$nimi.'<br>';
				$message .= Yii::t('main', 'Keskustelu ID:').': '.$model->keskustelu_id.'<br>';
				$message .= Yii::t('main', 'Palaute:').': '.$model->teksti;

				if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
				{
				$subject = Yii::t('main', 'Palaute'). ': '.$nimi;
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($ft->sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $ft->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				}

				if(isset($as->sahkoposti) and !empty($as->sahkoposti))
				{
				$subject = Yii::t('main', 'Palaute'). ': '.$nimi;
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($as->sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $as->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				}

				//$this->redirect(array('lahetetty','asiakas_id'=>$as->id));
			}
		}

/*
		$this->render('vastaus',array(
			'model'=>$model,
			'keskustelu_id'=>$id,
		));
*/
	}

	public function actionLahetetty($asiakas_id)
	{
		Yii::app()->theme = 'customer';
		$this->render('lahetetty', array('asiakas_id'=>$asiakas_id));
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

	public function UusiPalaute($mod, $post)
	{

		$as = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
		$ft = FirmanTiedot::model()->findbypk(1);
							
		$nimi = '';
		if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi))
		$nimi = $as->yrityksen_nimi;
		elseif(isset($as->yhteyshenkilo) and !empty($as->yhteyshenkilo))
		$nimi = $as->yhteyshenkilo;

		$mod->attributes=$post['Palautteet'];
		$mod->teksti = '<div><b>'.$nimi.'</b>: '.$mod->teksti.'<br><div class="aika">'.date('d.m.Y H:i').'</div></div>';
		if($mod->save())
		{
			Palautteet::model()->updatebypk($mod->id, array('keskustelu_id'=>$mod->id));

			// <-- push notify
			$asetukset = Asetukset::model()->findbypk(1);
			$push_teksti = '';
			if( $mod->emoji_tila == 1 ){ $push_teksti = $asetukset->palautteet_autovastaus_hyva; }
			if( $mod->emoji_tila == 3 ){ $push_teksti = $asetukset->palautteet_autovastaus_huono; }
			Domainit::sendGCMeDico($as->id, Yii::t('main', 'Palautteen vastaus'), $push_teksti, null);
			//     push notify -->

			$message = Yii::t('main', 'Asiakas').': '.$nimi.'<br>';
			$message .= Yii::t('main', 'Keskustelu ID').': '.$mod->id.'<br>';
			$message .= Yii::t('main', 'Kohde').': '.$mod->viimeinen_tyo.'<br>';
			$message .= Yii::t('main', 'Palaute').': '.$mod->teksti;
		
			if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
			{
				$sp_arr = explode(",", $ft->sahkoposti);

				$subject = Yii::t('main', 'Palaute'). ': '.$nimi;
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo(array_values($sp_arr));
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

				// <-- LOG
				$log=new Log;
				$log->log_category 	= 1; // 1-email
				$log->email_to 		= $ft->sahkoposti;
				$log->email_subject	= $subject;
				$log->email_message	= json_encode($message);
				$log->save();
				//     LOG -->

			}
				return true;
		} else {
			return $mod->getErrors();
		}
	}


	public function actionCreate()
	{
                Yii::app()->theme = 'customer';
		$model=new Palautteet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Palautteet']))
		{

			$as = Asiakkaat::model()->findbypk(Yii::app()->user->asiakas);
			$ft = FirmanTiedot::model()->findbypk(1);
				
			$nimi = '';
			if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi))
			$nimi = $as->yrityksen_nimi;
			elseif(isset($as->yhteyshenkilo) and !empty($as->yhteyshenkilo))
			$nimi = $as->yhteyshenkilo;

			$model->attributes = $_POST['Palautteet'];
			$model->teksti = '<b>'.$nimi.'</b>: '.$model->teksti;
			if($model->save())
			{
				Palautteet::model()->updatebypk($model->id, array('keskustelu_id'=>$model->id));
			

				$message = Yii::t('main', 'Asiakas').': '.$nimi.'<br>';
				$message .= Yii::t('main', 'Keskustelu ID:').': '.$model->id.'<br>';
				$message .= Yii::t('main', 'Palaute:').': '.$model->teksti;

				if(isset($ft->sahkoposti) and !empty($ft->sahkoposti))
				{
				$subject = Yii::t('main', 'Palaute'). ': '.$nimi;
				$mail = new YiiMailer();
				$mail->setFrom('no-reply@etunti.fi');
				$mail->setTo($ft->sahkoposti);
				$mail->setSubject($subject);
				$mail->setBody($message);
				$mail->send();

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $ft->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

				}

				$this->redirect(array('lahetetty','asiakas_id'=>$as->id));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Palautteet']))
		{
			$model->attributes=$_POST['Palautteet'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		Palautteet::model()->deleteAll(" keskustelu_id='".$id."' ");
		Palautteet::model()->deleteByPk($id);

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
/*
	// <-- Oikeudet
	   $checkOikeus = "kohteet_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->
*/

		if(isset($_GET['suljeJuttelu']))
		{
			Palautteet::model()->updatebypk($_GET['suljeJuttelu'], array('status'=>3));
			$this->redirect(array('index'));
		}

		if(isset($_POST['palaute_id']))
		{
	   		$asiakkaat = Yii::app()->createController('Asiakkaat');
			$asiakkaat[0]->palautteetVastaus($_POST);
		}


       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";
		$criteria->condition = "
			keskustelu_id=id
		";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->addCondition (" asiakas_id IN ( SELECT id FROM asiakkaat WHERE tyoryhma IN ($ids) ) ");
		}
		//    Tyoryhmat -->

		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi']))
		{
			$asiakaat = Asiakkaat::model()->findAll(" yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR yhteyshenkilo LIKE '%".$_GET['yrityksen_nimi']."%' ");
			$as_id = array();
			foreach($asiakaat as $itm)
				$as_id[] = $itm->id;
			$ids =  "asiakas_id=".implode(" OR asiakas_id=", array_values($as_id));
			if(count($as_id) == 0)
			$criteria->addCondition("asiakas_id=0");
			else
			$criteria->addCondition($ids);

		}

		$from = date("d.m.Y", strtotime("-1 month"));
		$to = date("d.m.Y");

		if(isset($_GET['from']) and isset($_GET['to'])){
		$from 	= date("Y-m-d",strtotime($_GET['from']));
		$to 	= date("Y-m-d",strtotime($_GET['to']));
		}

	        $criteria->addCondition (" DATE(time) BETWEEN '".date("Y-m-d",strtotime($from))."' AND '".date("Y-m-d",strtotime($to))."' ");

		$dataProvider=new CActiveDataProvider('Palautteet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 30;
		$this->render('index', array(
			'dataProvider' => $dataProvider,
			'from' => $from,
			'to' => $to,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Palautteet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Palautteet']))
			$model->attributes=$_GET['Palautteet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Palautteet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Palautteet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Palautteet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='palautteet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

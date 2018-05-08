<?php

class TyonkuvausController extends Controller
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
				'actions'=>array('index','view', 'create','update', 'admin', 'delete', 'pdf'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
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

		if(isset(Yii::app()->user->adminID) and in_array('5',$tas))
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionPdf($id, $open_status)
	{
		$this->PdfOpener($id, $open_status);
	}

	public function PdfOpener($id, $open_status)
	{
		$asetukset=Asetukset::model()->findByPk(1);
		$ft = FirmanTiedot::model()->findByPk(1);
		$tk = Tyonkuvaus::model()->findByPk($id);
		$k = Kohteet::model()->findByPk($tk->kohde_id);
		(isset($k->id))? $kohde = $k->osoite:$kohde = '';
	   	$tarjoukset = Yii::app()->createController('CrmTarjoukset');
	   	$html = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		$html .= '<style>'.file_get_contents(Yii::app()->basePath.'/../css/raportit_table2.css').'</style>';
		$html .= '</head><body>';
		$html .= '
			<table id="ylataulu" class="table">
			 <tr><td>
			  	<img src="'.$asetukset->logon_polkku.'" height="'.$asetukset->logon_korkeus.'">
			 </td><td align="right">
				'.$ft->tyonantaja.'
			 </td>
			 </tr>
			</table>
			<hr>';

		$html .= '<p><h2>'.Yii::t('main', 'Työnkuvaus').'</h2> '.date("d.m.Y H:i", strtotime($tk->time)).' '.$kohde.'</p><br>';
	   	$html .= $tarjoukset[0]->get_tyonkuvaus($id);
		$html .= '</body></html>';

		$basePath = Yii::app()->basePath.'/../tmp/'.Yii::app()->user->domain.'/';
		$path = 'tmp/'.Yii::app()->user->domain.'/';

		if (!file_exists( $basePath )) {
		 	mkdir( $basePath, 0777, true );
		}

		// <-- Tiedoston nimi
		$tiedosto = 'Tyonkuvaus';
		if(isset($tk->id))
		{
			$site = Yii::app()->createController('Site');
  			$tiedosto = $site[0]->tiedostonNimiAsiakasKohdeAika($tiedosto, $tk->asiakas_id, $tk->kohde_id, $tk->time);
		}
		//     Tiedoston nimi -->


		file_put_contents($path.'/'.$tiedosto.'.html', $html);
		$output = exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.$tiedosto.'.html '.$path.$tiedosto.'.pdf 2>&1'); 
		if ($open_status == 'openPDF' and file_exists( $path.$tiedosto.'.pdf' ))
		{

			header("Content-Length: " . filesize ( $path.$tiedosto.'.pdf' ) ); 
		        header("Content-type: application/pdf"); 
		        header("Content-disposition: attachment; filename=".basename($path.$tiedosto.'.pdf'));
		        readfile($path.$tiedosto.'.pdf');
			unlink($path.$tiedosto.'.html');
			unlink($path.$tiedosto.'.pdf');
			exit;

		} elseif ($open_status == 'getFile' and file_exists( $path.$tiedosto.'.pdf' )){
			unlink($path.$tiedosto.'.html');
			return $path.$tiedosto.'.pdf';
		} else {
			return $output;
		}

	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Tyonkuvaus;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tyonkuvaus']))
		{
			$model->attributes=$_POST['Tyonkuvaus'];
			if($model->save())
			{

				foreach($_POST['TyonkuvausRivit']['tilat'] as $key=>$items)
				{
					$tilat = $items;
					if(isset($_POST['TyonkuvausRivit']['tyotehtava'][$key]))
					{
						$tyontehtavat = array();
						foreach($_POST['TyonkuvausRivit']['tyotehtava'][$key] as $k2=>$i2)
						{
							$tyontehtavat[$k2] = array(
								'tyotehtava'=>$i2, 
								'vkopvm' => $_POST['TyonkuvausRivit']['vkopvm'][$key][$k2],
								'vkovali' => $_POST['TyonkuvausRivit']['vkovali'][$key][$k2]
							);
						}
					}

					$kommenti = '';
					if(isset($_POST['TyonkuvausRivit']['kommenti'][$key]))
					{
						$kommenti = $_POST['TyonkuvausRivit']['kommenti'][$key];
					}


					$tk_rivit = new TyonkuvausRivit;
					$tk_rivit->tyonkuvaus_id = $model->id;
					$tk_rivit->tilat = json_encode($tilat);
					$tk_rivit->tyontehtavat = json_encode($tyontehtavat);
					$tk_rivit->kommenti = $kommenti;
					$tk_rivit->save();

					/*
					echo '<pre>';

					print_r($laatutasot);
					echo '</pre>';
					echo '<hr>';
					*/
				}
			}
			//exit;
				$this->redirect(array('index'));
			
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

		if(isset($_POST['Tyonkuvaus']))
		{
			$model->attributes=$_POST['Tyonkuvaus'];

			if(isset($_POST['TyonkuvausRivit']['tilat']))
			{

				// <-- Poistetaan edelliset
				TyonkuvausRivit::model()->deleteAll("tyonkuvaus_id='".$id."'");

				foreach($_POST['TyonkuvausRivit']['tilat'] as $key=>$items)
				{
					$tilat = $items;
					if(isset($_POST['TyonkuvausRivit']['tyotehtava'][$key]))
					{
						$tyontehtavat = array();
						foreach($_POST['TyonkuvausRivit']['tyotehtava'][$key] as $k2=>$i2)
						{

						    if(isset($_POST['TyonkuvausRivit']['vkopvm'][$key][$k2]) and isset($_POST['TyonkuvausRivit']['vkovali'][$key][$k2]))
						    {
							$tyontehtavat[$k2] = array(
								'tyotehtava'=>$i2, 
								'vkopvm' => $_POST['TyonkuvausRivit']['vkopvm'][$key][$k2],
								'vkovali' => $_POST['TyonkuvausRivit']['vkovali'][$key][$k2]
							);
						    }
						}
					}


					$kommenti = '';
					if(isset($_POST['TyonkuvausRivit']['kommenti'][$key]))
					{
						$kommenti = $_POST['TyonkuvausRivit']['kommenti'][$key];
					}


					$tk_rivit = new TyonkuvausRivit;
					$tk_rivit->tyonkuvaus_id = $model->id;
					$tk_rivit->tilat = json_encode($tilat);
					$tk_rivit->tyontehtavat = json_encode($tyontehtavat);
					$tk_rivit->kommenti = $kommenti;
					$tk_rivit->save();

					/*
					echo '<pre>';
					print_r($laatutasot);
					echo '</pre>';
					echo '<hr>';
					*/
				}


			}
			//exit;


			if($model->save())
				$this->redirect(array('index'));
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
		TyonkuvausRivit::model()->deleteAll("tyonkuvaus_id='".$id."'");

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

		$criteria = new CDbcriteria;
		$criteria->order = " id DESC ";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " asiakas_id IN ( SELECT id FROM asiakkaat WHERE tyoryhma IN ($ids) ) ";
		}
		//    Tyoryhmat -->

		$dataProvider=new CActiveDataProvider('Tyonkuvaus',array(
			'criteria'=>$criteria,
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tyonkuvaus('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tyonkuvaus']))
			$model->attributes=$_GET['Tyonkuvaus'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tyonkuvaus the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tyonkuvaus::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tyonkuvaus $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tyonkuvaus-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

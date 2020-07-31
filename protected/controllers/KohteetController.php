<?php

class KohteetController extends Controller
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
			array('allow', 
				'actions'=>array('asiakas_tila', 'asiakas_kohteet'),
                		'expression'=>"Yii::app()->controller->isAsiakas()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update','index', 'view','osoite', 'autotaytaminen', 'createfromasiakas', 'googlemap', 'googlemap_k', 'massamuokkaus', 'omasiistijat_ajax'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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


	public function actionAsiakas_kohteet()
	{

       		$criteria = new CDbCriteria();
	        $criteria->condition = "  asiakas_id='".Yii::app()->user->asiakas."' ";

		$dataProvider=new CActiveDataProvider('Kohteet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 50;
		$this->render('asiakas_kohteet', array('dataProvider' => $dataProvider));
	}


	public function actionAsiakas_tila($id)
	{

		$model=$this->loadModel($id);

	        if($model->asiakas_id == Yii::app()->user->asiakas)
		{

		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
			if($model->save())
				$this->redirect(array('asiakas_tila','id'=>$model->id));
		}


			$this->render('update', array('model'=>$model));

		} else {
	        	return false;
		}


	}

	public function actionGooglemap()
	{
		if(isset($_GET['nomenu'])){
		$this->renderPartial('googlemap');
		} else {
		$this->render('googlemap');
		}
	}

	public function actionGooglemap_k()
	{

       		$criteria = new CDbCriteria();

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		if(isset($_GET['tila']) and $_GET['tila'] == 'avoimet')
		{
		$criteria->addCondition("
			id IN 
			( 
			SELECT kohdenID FROM sivexkuitti 
			WHERE status=1
			)
		");
		} elseif(isset($_GET['tila']) and $_GET['tila'] == 'toteutetut') {
		$criteria->addCondition("
			id IN 
			( 
			SELECT kohdenID FROM sivexkuitti 
			WHERE DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() 
			AND status=3
			)
		");
		} elseif(isset($_GET['tila']) and $_GET['tila'] == 'kaikki') {
		$criteria->addCondition("
			id IN 
			( 
			SELECT kohdenID FROM sivexkuitti 
			WHERE DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() 
			)
		");
		} elseif(isset($_GET['tila']) and $_GET['tila'] == 'aktiiviset_asiakkaat') {
		$criteria->addCondition("
			asiakas_id IN
			( 
			SELECT id FROM asiakkaat WHERE aktiivinen=1
			)
		");
		} 

		$model=Kohteet::model()->findAll($criteria);

		$this->renderPartial('googlemap_k',array(
			'model'=>$model,
		));
	}

	public function actionAutotaytaminen($id)
	{
		$m=Asiakkaat::model()->findbypk($id);

		$ryhma = 0;
		if($m->ryhma != 0)
		{
		$l = Valikkoot::model()->findbypk($m->ryhma);
		$ryhma = $l['id']."-".$l['value'];
		}

		echo $m->yhteyshenkilo."//".$m->kaupunki."//".$m->postinumero."//".$m->sahkoposti."//".$m->puhelin."//".$ryhma;
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionOsoite($osoite)
	{
		$model = Kohteet::model()->find(" osoite = '".$osoite."' ");
		echo $model['id'];
	}

	public function actionCreatefromasiakas($id)
	{
		$model=new Kohteet;
		$asiakas=Asiakkaat::model()->findbypk($id);
		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt);
			} else {
				$model->tyo_erittelyt = '';
			}
			if($model->save())
			{

				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Kohteet';
				$name_log 	= 'Kohde';
				$status_log 	= 'Create';
	
					$old_values = null;
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->

				Yii::app()->user->setFlash('success', "Tallennettu.");
				$this->redirect(array('index'));
			}
		}

		$this->render('createfromasiakas',array(
			'model'=>$model,
			'asiakas'=>$asiakas,
		));
	}

	public function actionMassamuokkaus()
	{
	// <-- Oikeudet
	   $checkOikeus = "kohteet_4_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			aktiivinen=1
		";
		$k_all = Kohteet::model()->findAll($criteria);

		if(isset($_POST['Kohteet']))
		{
		   $post = array();
		   foreach($_POST['Kohteet'] as $k => $v){
			if(!empty($v) and isset($_POST['Check'][$k])){ $post[$k] = $v; }
		   }

		   foreach($k_all as $model){
			$vanha_attr = $model->attributes;
			$model->attributes=$post;
			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt);
			} else {
				$model->tyo_erittelyt = '';
			}
			if($model->save())
			{
				// <-- LOG
				$model_log 	= 'Kohteet';
				$name_log 	= 'Kohde';
				$status_log 	= 'Massamuokkaus';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			}
		    }
		    Yii::app()->user->setFlash('success', "Valmis.");
		    $this->redirect(array('index'));
		}
		$model = new Kohteet;
		$this->render('massamuokkaus',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{

	// <-- Oikeudet
	   $checkOikeus = "kohteet_1_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

		$model=new Kohteet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Kohteet']))
		{
			$model->attributes=$_POST['Kohteet'];
			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt);
			} else {
				$model->tyo_erittelyt = '';
			}
			if($model->save())
			{

				// <-- LOG
				if( isset($model->id) )
				{
				$model_log 	= 'Kohteet';
				$name_log 	= 'Kohde';
				$status_log 	= 'Create';
	
					$old_values = null;
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				}
				//     LOG -->


				// <-- Koordinatiit
				$model=$this->loadModel($model->id);
			        $latAuto = '';
			        $lngAuto = '';
			    	$coordinates = $this->getlatlong($model->osoite);
				if(isset($coordinates->results[0]->geometry->location->lat))
			        $latAuto = $coordinates->results[0]->geometry->location->lat.',';
				if(isset($coordinates->results[0]->geometry->location->lng))
			        $lngAuto = $coordinates->results[0]->geometry->location->lng;
			
				if( isset($model->id) and empty($model->gps_sijainti) and !empty($latAuto.$lngAuto))
					Kohteet::model()->updateBypk($model->id, array('gps_sijainti' => $latAuto.$lngAuto));
				// Koordinatiit -->

				Yii::app()->user->setFlash('success', "Tallennettu.");
				$this->redirect(array('index'));
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
		$checkOikeus = "kohteet_2_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		$site[0]->checkOikeus($checkOikeus);
		//  Oikeudet -->

		$model=$this->loadModel($id);

		// <-- FILES
		if(isset($_POST['uploaded_t'])){
			Asetukset::model()->uploadFile(
				Yii::app()->user->domain, 
				'kohteet', 
				$model->id.'_'.$_FILES['file']['name']
			);
		}
		if(isset($_POST['poistaTamaTiedosto'])){
			unlink($_POST['poistaTamaTiedosto']);
			exit;
		}
		//     FILES -->

		// <-- Koordinatiit
	        $latAuto = '';
	        $lngAuto = '';
	    	$coordinates = $this->getlatlong($model->osoite);
		if($coordinates and isset($coordinates->results[0]->geometry->location->lat))
	        $latAuto = $coordinates->results[0]->geometry->location->lat.',';
		if($coordinates and isset($coordinates->results[0]->geometry->location->lng))
	        $lngAuto = $coordinates->results[0]->geometry->location->lng;
	
		if( isset($model->id) and empty($model->gps_sijainti) and !empty($latAuto) and !empty($lngAuto))
			Kohteet::model()->updateBypk($model->id, array('gps_sijainti' => $latAuto.$lngAuto));
		// Koordinatiit -->


		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Kohteet']))
		{

			$vanha_attr = $model->attributes;
			$model->attributes=$_POST['Kohteet'];
			if( is_array($model->tyo_erittelyt) and count($model->tyo_erittelyt) > 0 ){
				$model->tyo_erittelyt = json_encode($model->tyo_erittelyt, JSON_FORCE_OBJECT);
			} else {
				$model->tyo_erittelyt = '';
			}
			if($model->save())
			{

				// <-- LOG
				$model_log 	= 'Kohteet';
				$name_log 	= 'Kohde';
				$status_log 	= 'Update';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

				Yii::app()->user->setFlash('success', "Tallennettu.");
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	protected function getlatlong($address)
	{

		$asetuksetForAll = AsetuksetForAll::model()->findByPk(1);
		if(isset($asetuksetForAll->googlemaps_apikey) and !empty($asetuksetForAll->googlemaps_apikey))
		{
	        	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true&key='.$asetuksetForAll->googlemaps_apikey;
		        $json = @file_get_contents($url);
		        $data = json_decode($json);
		        if (isset($data->status) and $data->status == "OK")
			{
			        return $data;
			} else {
				return false;
			}
		}

	            return false;
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{

	// <-- Oikeudet
	   $checkOikeus = "kohteet_3_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->


		$k_d = Kohteet::model()->findbypk($id);

			if(isset($k_d->id))
			{
				// <-- LOG
				$model_log 	= 'Kohteet';
				$name_log 	= 'Kohde';
				$status_log 	= 'Delete';
	
					$old_values = json_encode($k_d->attributes);
					$new_values = null;
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			}

		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{

		if(isset($_POST['kohteetPerSivu']))
		{
			Yii::app()->user->setState('kohteetPerSivu', $_POST['kohteetPerSivu']);
			echo json_encode($_POST['kohteetPerSivu']);
			exit;
		}

	// <-- Oikeudet
	   $checkOikeus = "kohteet_0_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $site[0]->checkOikeus($checkOikeus);
	//  Oikeudet -->

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//    Tyoryhmat -->

		if(isset($_POST['osoite']) and !empty($_POST['osoite']))
	        $criteria->addCondition (" osoite LIKE '%".$_POST['osoite']."%' ");

		if(isset($_POST['aktiivinen']) and $_POST['aktiivinen'] == 1)
	        $criteria->addCondition (" aktiivinen=1 ");


		if(isset($_POST['etu_suku_nimet']) and !empty(trim($_POST['etu_suku_nimet'])))
	        $criteria->addCondition (" etu_suku_nimet LIKE '%".$_POST['etu_suku_nimet']."%' ");

		if(isset($_POST['tag_id']) and !empty(trim($_POST['tag_id'])))
	        $criteria->addCondition (" tag_id LIKE '%".$_POST['tag_id']."%' ");

		if(isset($_POST['avain']) and !empty(trim($_POST['avain'])))
	        $criteria->addCondition (" avain LIKE '%".$_POST['avain']."%' ");

		if(isset($_POST['email']) and !empty(trim($_POST['email'])))
	        $criteria->addCondition (" email LIKE '%".$_POST['email']."%' ");

		if(isset($_POST['yrityksen_nimi']) and !empty(trim($_POST['yrityksen_nimi'])))
		{
	        	$criteria->addCondition (" 
				asiakas_id IN (
					SELECT id FROM asiakkaat 
					WHERE yrityksen_nimi LIKE '%".$_POST['yrityksen_nimi']."%' OR yhteyshenkilo LIKE '%".$_POST['yrityksen_nimi']."%'
				)		
			");
		}


		$dataProvider=new CActiveDataProvider('Kohteet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$perSivu = 50;
		if(isset(Yii::app()->user->kohteetPerSivu))
		$perSivu = Yii::app()->user->kohteetPerSivu;

		$dataProvider->pagination->pageSize = $perSivu;

		$this->render('index', array('dataProvider' => $dataProvider, 'perSivu' => $perSivu));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Kohteet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Kohteet']))
			$model->attributes=$_GET['Kohteet'];

		$this->render('admin',array(
			'model'=>$model,
		));
  }

  /**
   * Get list of workers that have approved shifts/cycles in a target location.
   *
   * Calls KohteetController::omasiistijat() with relevant data.
   *
   * @param int $id
   * ID of the location (kohde).
   *
   * @param bool $force_refresh
   * If true, cache results are ignored and data is force refreshed.
   *
   * @return null
   * Outputs results as a JSON array of IDs.
   */
  public function actionOmasiistijat_ajax($id = null, $force_refresh = false)
  {
    // Get possible POST value for ID.
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
      $id = (int)$_POST['id'];
    }

    if (empty($id) || !is_numeric($id)) {

      // Invalid/empty ID; output empty array (TODO: log).
      echo '[]'; // json_encode([]) (empty array).
    } else {

      // Get results array and output as JSON for the view.
      $force_refresh = ($force_refresh || ($_POST['force_refresh'] ?? '') == 1);
      $omasiistijat = $this->omasiistijat($id, $force_refresh);

      // Convert objects into arrays.
      array_walk($omasiistijat, function(&$item) {
        if (is_object($item)) {
          $item = get_object_vars($item);
        }
      });

      echo json_encode($this->omasiistijat($id, $force_refresh));
    }
  }

  /**
   * Get list of workers that have approved shifts/cycles in a target location.
   *
   * Tässä laajennettuna SQL haku joka suoritetaan myöhemmin, jolla haetaan
   * työntekijät joilla on hyväksyttyjä tunteja kyseisessä kohteessa.
   *
   * Haetaan tiedot niiltä työntekijöiltä, jotka koskee hakua. Alempana tehtävä
   * ID rajaus rajaa työntekijät vain niihin, joilla on hyväksyttyjä tunteja
   * kyseisessä kohteessa.
   *
   * SET @kohde_id = 2654;
   * SELECT id, tekijan_nimi, sukunimi
   * FROM sivex_ttekijat
   *
   * // Vain aktiiviset työntekijät (1:aktiivinen, 2:passiivinen, 3:epäaktiivinen)
   * WHERE aktiivinen = 1
   *
   * // Rajataan työntekijät vain niihin, joilla on allaolevan haun perusteella hyväksyttyjä tunteja kohteessa.
   * AND id IN (
   *   // Haetaan työntekijä ID lista kohteen hyväksytyistä tunneista.
   *   SELECT tid FROM (
   *     SELECT tid FROM sivexkuitti
   *     WHERE kohdenID = @kohde_id AND hyvaksytty != ''
   *     UNION ALL
   *     SELECT tid FROM sivexkuitti_repaired
   *     WHERE kohdenID = @kohde_id AND hyvaksytty != ''
   *   ) t
   *   // Groupataan, jotta päällekkäiset ID:t katoavat (jokainen tt vain kerran listalla)
   *   GROUP BY tid
   * );
   *
   * @param int $id
   * ID of the location (kohde).
   *
   * @param bool $force_refresh
   * If true, cache results are ignored and data is force refreshed.
   *
   * @return array
   * Array of IDs of matching workers.
   */
  public function omasiistijat($id, $force_refresh = false)
  {
    // Require valid ID.
    if (empty($id) || !is_numeric($id)) {
      //throw new \Exception('Kohteen ID ei annettu omasiistijälistaa varten.');
      return [];
    }

    /**** CACHING ****/
    $cache_id = sprintf("%s_omasiistijat_%s", Yii::app()->user->domain, $id);
    $workers = Yii::app()->cache->get($cache_id);

    // If cached results JSON object is empty, or force_refresh parameter is
    // provided, get fresh results and save cached results with random expire
    // duration of between 10 and 20 minutes, to stagger refreshes.
    if (empty($workers)) {

      /** @var CDbConnection */
      $connection = Yii::app()->db1;

      // Get list of workers that have been to this target.
      // See function documentation for explanation.
      $workers = $connection->createCommand("
        SELECT id, tekijan_nimi, sukunimi
          FROM sivex_ttekijat
          WHERE aktiivinen = 1
          AND id IN
          (
            SELECT tid FROM
            (
              SELECT tid
                FROM sivexkuitti
                WHERE kohdenID = :kohde_id
                AND hyvaksytty != ''

              UNION ALL

              SELECT tid
                FROM sivexkuitti_repaired
                WHERE kohdenID = :kohde_id
                AND hyvaksytty != ''
            ) t
            GROUP BY tid
        )")
        ->bindValue(':kohde_id', $id)
        ->queryAll(true);

      // Refresh between 10 and 20 minutes to stagger refreshes between results.
      Yii::app()->cache->set($cache_id, $workers, rand(600, 1200));
    }

    return $workers;
  }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Kohteet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Kohteet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	/**
	 * Performs the AJAX validation.
	 * @param Kohteet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='kohteet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


    	protected function asiakasMuutos($data,$row)
	{ 
		$return = '';

		    $a = Asiakkaat::model()->findbypk($data->asiakas_id);
		    if(isset($a->yrityksen_nimi) and !empty($a->yrityksen_nimi))
		    $return = $a->yrityksen_nimi;
		    elseif(isset($a->yhteyshenkilo) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
		    $return = $a->yhteyshenkilo;
		    else
		    $return = $data->asiakas_id;

		    if(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'henkilo')
		    $return = '<b class="text-warning">Yhteyshenkilö</b><br>'.$return;
		    elseif(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'yritys')
		    $return = '<b class="text-success">Yritys</b><br>'.$return;

            	return $return;
	}

    	protected function asiakasMuutosTheme($as)
	{ 
		$return = '';

		    $a = Asiakkaat::model()->findbypk($as);
		    if(isset($a->yrityksen_nimi) and !empty($a->yrityksen_nimi))
		    $return = $a->yrityksen_nimi;
		    elseif(isset($a->yhteyshenkilo) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
		    $return = $a->yhteyshenkilo;
		    else
		    $return = $as;

		    if(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'henkilo')
		    $return = '<b class="text-warning">Yhteyshenkilö</b><br>'.$return;
		    elseif(isset($a->tyyppi) and !empty($a->tyyppi) and $a->tyyppi == 'yritys')
		    $return = '<b class="text-success">Yritys</b><br>'.$return;

            	return $return;
	}

    	protected function onkoKuva($data,$row)
	{ 
		$return = '';

		foreach(array_reverse(glob(Yii::app()->basePath."/../img/uploadedfromphone/".Yii::app()->user->domain."/".$data->id."_*.*")) as $file) 
		{
			if(!empty($file))
			{
			$return = '<i class="fa fa-camera"></i>';
			break;
			}
		}
            	return $return;
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

        protected function TyoryhmaName($id){
		$return = '';
		if( !empty($id) ){
		   $v = Valikkoot::model()->findByPk($id);
		   if( isset($v->value) ){ $return = $v->value; }
		}
                return $return;
        }

}

<?php

class AvaimetController extends Controller
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
				'actions'=>array('index','view', 'avaimet_tyontekijalle', 'employeekeys'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
                		'expression'=>"Yii::app()->controller->isEtuntiAdmin()",
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Avaimet;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Avaimet']))
		{
			$model->attributes=$_POST['Avaimet'];
			if($model->save())
			{

				// <-- LOG
				$model_log 	= 'Avaimet';
				$name_log 	= 'Avaimet';
				$status_log 	= 'Create';
	
					$old_values = null;
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//

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
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Avaimet']))
		{
			$vanha_attr = $model->attributes;
			$model->attributes=$_POST['Avaimet'];
			if($model->save())
			{

				// <-- LOG
				$model_log 	= 'Avaimet';
				$name_log 	= 'Avaimet';
				$status_log 	= 'Update';
	
					$old_values = json_encode($vanha_attr);
					$new_values = json_encode($model->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->

				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function sijaintiText($id){
		$arr = [1 => 'Toimistolla', 2 => 'Palautettu asiakkaalle', 3 => 'Työntekijällä'];
		$text = '';
		$avain = Avaimet::model()->findByPk($id);
		if( isset($avain->id) ){
			if($avain->sijainti_omatekstti == 0 and isset($arr[$avain->sijainti]))
				$text = $arr[$avain->sijainti];
			if($avain->sijainti_omatekstti == 1)
				$text = $avain->sijainti;
		}
		return $text;
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{

		$del_model = Avaimet::model()->findbypk($id);
		if(isset($del_model->id))
		{
			// <-- LOG
			$model_log 	= 'Avaimet';
			$name_log 	= 'Avaimet';
			$status_log 	= 'Delete';
				$old_values = json_encode($del_model->attributes);
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
		if(isset($_POST['avaimet']))
		{
			foreach($_POST['avaimet'] as $avain_id)
			{

				$avaimet_old = Avaimet::model()->findByPk($avain_id);
				Avaimet::model()->updateByPk($avain_id, array('sijainti_omatekstti' => $_POST['sijainti_omatekstti'], 'sijainti' => $_POST['sijainti'], 'tid' => $_POST['tyontekija']));
				$avaimet_new = Avaimet::model()->findByPk($avain_id);

				// <-- LOG
				$model_log 	= 'Avaimet';
				$name_log 	= 'Avaimet';
				$status_log 	= 'Move';
	
					$old_values = json_encode($avaimet_old->attributes);
					$new_values = json_encode($avaimet_new->attributes);
					$site = Yii::app()->createController('Site');
					$criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
				//     LOG -->
			}
		}
		if(isset($_POST['asiakkaatPerSivu']))
		{
			Yii::app()->user->setState('asiakkaatPerSivu', $_POST['asiakkaatPerSivu']);
			echo json_encode($_POST['asiakkaatPerSivu']);
			exit;
		}

       		$criteria = new CDbCriteria();

		// <-- Tyoryhmat
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " kohde IN ( SELECT id FROM sivex_kohdet WHERE tyoryhma IN ($ids)) ";
		}
		//    Tyoryhmat -->


		if(isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi']))
		{

	        $criteria->addCondition (" 
			kohde IN ( SELECT id FROM sivex_kohdet
				WHERE asiakas_id IN ( SELECT id FROM asiakkaat
					WHERE yrityksen_nimi LIKE '%".$_GET['yrityksen_nimi']."%' OR etunimi LIKE '%".$_GET['yrityksen_nimi']."%' 
					OR sukunimi LIKE '%".$_GET['yrityksen_nimi']."%'
					OR CONCAT(etunimi, ' ', sukunimi) LIKE '%".$_GET['yrityksen_nimi']."%'
				)
			)
		");
		}

		if(isset($_GET['osoite']) and !empty($_GET['osoite']))
		{
	        $criteria->addCondition (" 
			kohde IN ( SELECT id FROM sivex_kohdet
				WHERE osoite LIKE '%".$_GET['osoite']."%'
			)
		");
		}
		if(isset($_GET['avainnumero']) and !empty($_GET['avainnumero']))
		{
	        $criteria->addCondition (" avainnumero LIKE '%".$_GET['avainnumero']."%' ");
		}
		if(isset($_GET['tekijan_nimi']) and !empty($_GET['tekijan_nimi']))
		{
	        $criteria->addCondition (" tid IN 
			(
			SELECT id FROM sivex_ttekijat WHERE CONCAT(tekijan_nimi, ' ', sukunimi)  LIKE '%".$_GET['tekijan_nimi']."%' 
			)"
		);
		}
		$dataProvider=new CActiveDataProvider('Avaimet', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$perSivu = 50;
		if(isset(Yii::app()->user->asiakkaatPerSivu))
		$perSivu = Yii::app()->user->asiakkaatPerSivu;

		$dataProvider->pagination->pageSize = $perSivu;

		$this->render('index', array(
			'dataProvider' => $dataProvider, 
			'perSivu' => $perSivu,
		));
	}

  public function actionAvaimet_tyontekijalle()
  {
    // Don't search unless some criteria are provided, in order to not lock up
    // the whole system when the page is initially opened, loading EVERYTHING.
    // Flag as TRUE to indicate a search once a criteria is processed.
    $search = false;

    // Check for actions to be performed.
    if (isset($_POST['avaimet'])) {
      foreach ($_POST['avaimet'] as $avain_id) {
        $avaimet_old = Avaimet::model()->findByPk($avain_id);
        Avaimet::model()->updateByPk($avain_id, array('sijainti' => $_POST['sijainti'], 'tid' => $_POST['tyontekija']));
        $avaimet_new = Avaimet::model()->findByPk($avain_id);

        // <-- LOG
        $model_log   = 'Avaimet';
        $name_log   = 'Avaimet';
        $status_log   = 'Move';

        $old_values = json_encode($avaimet_old->attributes);
        $new_values = json_encode($avaimet_new->attributes);
        $site = Yii::app()->createController('Site');
        $criteria = $site[0]->initPostLoger($model_log, $name_log, $status_log, $old_values, $new_values);
        //     LOG -->
      }
    }

    if (isset($_POST['asiakkaatPerSivu'])) {
      Yii::app()->user->setState('asiakkaatPerSivu', $_POST['asiakkaatPerSivu']);
      echo json_encode($_POST['asiakkaatPerSivu']);
      exit;
    }

    // Set default criteria in case a search is performed.
    $from = date("Y-m-d");
    $to = date("Y-m-d", strtotime($from . ' +1 month'));
    $perSivu = 50;
    $haku_criteria = [];


    // Search criteria: Dates (päivämäärät)
    if (isset($_GET['from']) and isset($_GET['to'])) {

      // Update dates based on user choice.
      $from = date("Y-m-d", strtotime($_GET['from']));
      $to = date("Y-m-d", strtotime($_GET['to']));

      // Tell the action to perform the search.
      $search = true;
    }

    // Search criteria: Customer (asiakas)
    if (isset($_GET['yrityksen_nimi']) and !empty($_GET['yrityksen_nimi'])) {
      // Add criteria for the specified customer.
      $haku_criteria[] = "
        kohde IN (
          SELECT id FROM sivex_kohdet WHERE asiakas_id IN (
            SELECT id FROM asiakkaat WHERE
              yrityksen_nimi LIKE '%{$_GET['yrityksen_nimi']}%'
              OR etunimi LIKE '%{$_GET['yrityksen_nimi']}%'
              OR sukunimi LIKE '%{$_GET['yrityksen_nimi']}%'
			  OR CONCAT(etunimi, ' ', sukunimi) LIKE '%{$_GET['yrityksen_nimi']}%'
			)
        )
      ";

      // Tell the action to perform the search.
      $search = true;
    }

    // Search criteria: Address (kohde)
    if (isset($_GET['osoite']) and !empty($_GET['osoite'])) {

      // Add criteria for the specified address.
      $haku_criteria[] = "kohde IN (SELECT id FROM sivex_kohdet WHERE osoite LIKE '%{$_GET['osoite']}%')";

      // Tell the action to perform the search.
      $search = true;
    }

    // Search criteria: Worker (työntekijä)
    if (isset($_GET['tekijan_nimi']) and !empty($_GET['tekijan_nimi'])) {

      // Add criteria for the specified worker.
      $haku_criteria[] = "tid IN (SELECT id FROM sivex_ttekijat WHERE CONCAT(tekijan_nimi, ' ', sukunimi) LIKE '%{$_GET['tekijan_nimi']}%')";

      // Tell the action to perform the search.
      $search = true;
    }

    // Only search when criteria are passed from the form by the user.
    // Otherwise, render search form without initial results.
    if (true === $search) {

      // Perform search.
      $tyovuorot = Yii::app()->createController('Tyovuoroot')[0];
      $dataAll = $tyovuorot->FromToSuunnitellutAll($from, $to, [], $haku_criteria, ['data']);

      // Render results and search form.
      $this->render('avaimet_tyontekijalle', array(
        'dataAll' => $dataAll,
        'perSivu' => $perSivu,
        'from' => $from,
        'to' => $to,
      ));
    } else {

      // Render search form without any results.
      $this->render('avaimet_tyontekijalle', array(
        'dataAll' => [],
        'perSivu' => $perSivu,
        'from' => $from,
        'to' => $to,
      ));
    }
  }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Avaimet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Avaimet']))
			$model->attributes=$_GET['Avaimet'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Avaimet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Avaimet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Avaimet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='avaimet-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	protected function etuSukunimi($tid)
	{
	   $site = Yii::app()->createController('Site');
	   return $site[0]->etuSukunimi($tid);
	}

	public function actionEmployeeKeys()
	{
		$req = Yii::app()->request;

		// if query string is empty, just show the page without results
		if(empty($req->queryString)) {
			return $this->render("employee_keys", [
				"keys" => [], 
				"shiftNeedMap" => [],
				"from" => date("Y-m-d"), 
				"to" => date("Y-m-d", strtotime("+1 week")),
				"showTransferOnly" => 1,
				"showClientKeys" => 0,
				"selectedEmployees" => [],
				"selectedWorkgroups" => [],
				"employees" => [],
				"employeeKeys" => [],
				"queryEmployeeIds" => [],
			]);
		}

		$from = $req->getQuery("from");
		$to = $req->getQuery("to");

		if(!$from) {
			$from = date("d.m.Y");
		}
		if(!$to) {
			$to = date("d.m.Y", strtotime("+1 month"));
		}

		$from = date("Y-m-d", strtotime($from));
		$to = date("Y-m-d", strtotime($to));

		$showTransferOnly = $req->getQuery("show-transfer-only");
		if($showTransferOnly === null) {
			// on by default
			$showTransferOnly = 1;
		}
		
		$showClientKeys = $req->getQuery("show-client-keys");
		$showClientKeys = $showClientKeys ? true : false;
		
		$employeeIds = $req->getQuery("employees", []);
		$employeeIds = array_map(function($empId) {
			return intval($empId);
		}, $employeeIds);

		$workGroupIds = $req->getQuery("work-groups", []);
		$workGroupIds = array_map(function($groupId) {
			return intval($groupId);
		}, $workGroupIds);

		// work types (työajanmerkintä) that should not be listed
		$disallowedTypes = ["Ei lasketa"];

		// get work group names
		$crit = new CDbCriteria();
		$crit->addInCondition("id", $workGroupIds);
		$workGroupNames = Valikkoot::model()->findAll($crit);
		$workGroupNames = array_map(function($workgroup) {
			return $workgroup->value;
		}, $workGroupNames);
		
		// fetch all employees, and filter those that belong
		// to $workGroupNames
		$activeEmployees = Tyontekijat::model()->findAll("aktiivinen=1");
		$filteredEmployeeIds = [];
		$employeeMap = [];
		foreach($activeEmployees as $emp) {
			$employeeMap[$emp->id] = $emp;
			$employeesGroups = json_decode($emp->tyoryhma, true) ?? [];
			if(count($employeesGroups) === 0) {
				continue;
			}
			foreach($workGroupNames as $wgName) {
				if(in_array($wgName, $employeesGroups)) {
					$filteredEmployeeIds[] = $emp->id;
				}
			}
		}
		
		$queryEmployeeIds = array_unique(array_merge($employeeIds, $filteredEmployeeIds));

		// keys extracted from shifts (those that the selected employees might need)
		$keys = [];

		$crit = new CDbCriteria();
		if(count($queryEmployeeIds) > 0) {
			$crit->addInCondition("t.tid", $queryEmployeeIds);
		}
		$crit->addBetweenCondition("STR_TO_DATE(pvm, '%d.%m.%Y')", $from, $to);
		$crit->addCondition("peruutettu=0 OR peruutettu IS NULL");
		$crit->addCondition("t.status=3");
		$shifts = Tyovuoroot::model()
			->with("avaimet")
			->findAll($crit);

		$shiftNeedMap = [];
		// a list of employee IDs that do not match the key holder
		$wrongEmployeeIds = [];

		// extract keys
		foreach($shifts as $shift) {
			$type = $shift->tyoajanmerkinta;
			$is_type_ok = true;
			foreach($disallowedTypes as $disallowedType) {
				if(strpos($disallowedType, $type) !== false) {
					$is_type_ok = false;
					break;
				}
			}
			// skip if type isn't for normal work
			if(!$is_type_ok) {
				continue;
			}
			if(isset($shift->avaimet)) {
				foreach($shift->avaimet as $key) {
					$keys[$key->id] = $key;
					// skip if client has the key and we're not including those
					if($showClientKeys === false && $this->clientHasKey($key)) {
						continue;
					}
					if($key->tid != $shift->tid) {
						$wrongEmployeeIds[] = $key->tid;
						if(isset($shiftNeedMap[$shift->tid])) {
							$shiftNeedMap[$shift->tid][] = ["shift" => $shift, "key" => $key];
						} else {
							$shiftNeedMap[$shift->tid] = [["shift" => $shift, "key" => $key]];
						}
					}
				}
			}
		}

		$crit = new CDbCriteria();
		if(count($queryEmployeeIds) > 0) {
			$ids = implode(",", $queryEmployeeIds);
			$q = "t.tyopaari LIKE '%\"".implode("\"%' OR t.tyopaari LIKE '%\"", $queryEmployeeIds)."\"%' ";
			$crit->addCondition("t.tid IN ($ids) OR ($q)");
		}

		// FromToSuunnitellutAll uses this method to search for repeating shifts,
		// seems to work.
		$crit->addCondition("STR_TO_DATE(pfrom, '%d.%m.%Y') <= '$to'");
		$crit->addCondition("STR_TO_DATE(pto, '%d.%m.%Y') >= '$from'");
		$crit->addCondition("t.status=3");

		$repeatingShifts = ToistuvatTyovuorot::model()
			->with("avaimet")
			->findAll($crit);

		$filteredRepeatingShifts = [];

		// filter out repeating shifts that don't have a shift during the searched time
		// this is copy-pasted (with slight modifications) from TyovuorootControllers tv_arr function
		// which should calculate "virtual shifts"
		foreach($repeatingShifts as $repeating) {

			$poistettu_pvms = [];
			if( !empty($repeating->new_poistettu_pvm) ){
				foreach(json_decode($repeating->new_poistettu_pvm, true) as $key => $val) {
					if(isset($val["pvm"])) {
						$poistettu_pvms[$val["pvm"]] = $val["pvm"];
					}
				}
			}

			$startday 	= date("Y-m-d", strtotime($repeating->pfrom));
			$startday_ts	= strtotime($startday);
			$haku_from_ts	= strtotime($from);
			$haku_to_ts = strtotime($to);
			$stopday 	= date("Y-m-d", strtotime($repeating->pto));
			// kuukausiperusteinen toistuvuus (2026-09)
			if ($repeating->isMonthlyRepeat()) {
				foreach ($repeating->getMonthlyOccurrenceDates($from, $to) as $this_pvm) {
					if(!isset($poistettu_pvms[$this_pvm])) {
						$virtualShift = clone($repeating); $virtualShift->pvm = $this_pvm; $filteredRepeatingShifts[] = $virtualShift;
					}
				}
				continue;
			}

			$date = new \DateTime($startday, new DateTimeZone('Europe/Helsinki'));
			$date->modify('this week monday');
			$date_end = (new \DateTime($stopday, new DateTimeZone('Europe/Helsinki')))->getTimestamp();

			while($date->getTimestamp() <= $date_end) {
				foreach(json_decode($repeating->viikko_paivat, true) as $viikko_paiva) {
					$paiva = new \DateTime($date->format('Y-m-d'), new DateTimeZone('Europe/Helsinki'));
					$paiva->modify("+" . ($viikko_paiva - 1) . "day");
					$this_pvm = $paiva->format('d.m.Y');
					if ( (strtotime($this_pvm) < $startday_ts) or (strtotime($this_pvm) < $haku_from_ts) )
						continue;
					if ((false !== $haku_to_ts && strtotime($this_pvm) > $haku_to_ts) or strtotime($this_pvm) > strtotime($stopday)){
						break 2;
					}
					// create a virtual shift if the date isn't defined in
					// $poistettu_pvms
					if(!isset($poistettu_pvms[$this_pvm])) {
						// if we end up here I think we should have a virtual shift
						$virtualShift = clone($repeating);
						$virtualShift->pvm = $this_pvm;
						$filteredRepeatingShifts[] = $virtualShift;
					}
				}
				$date->modify("+{$repeating->viikkoja}week");
			}
		}
		

		// extract keys
		foreach($filteredRepeatingShifts as $shift) {
			if(isset($shift->avaimet)) {
				foreach($shift->avaimet as $key) {
					// skip if client has the key and we're not including those
					if($showClientKeys === false && $this->clientHasKey($key)) {
						continue;
					}
					$shiftEmployeeIds = $this->repeatShiftEmployeeIds($shift, $queryEmployeeIds);
					$keys[$key->id] = $key;
					if(!in_array($key->tid, $shiftEmployeeIds)) {
						$wrongEmployeeIds[] = $key->tid;
						foreach($shiftEmployeeIds as $empId) {
							if(isset($shiftNeedMap[$empId])) {
								$shiftNeedMap[$empId][] = ["shift" => $shift, "key" => $key];
							} else {
								$shiftNeedMap[$empId] = [["shift" => $shift, "key" => $key]];
							}
						}
					}
				}
			}
		}

		// keys that the selected employees currently have
		$employeeKeys = [];
		if(!$showTransferOnly) {
			// fetch all keys for employees
			$crit = new CDbCriteria();
			$crit->addInCondition("tid", $queryEmployeeIds);
			$allKeys = Avaimet::model()->findAll($crit);
			foreach($allKeys as $key) {
				if(isset($employeeKeys[$key->tid])) {
					$employeeKeys[$key->tid][] = $key;
				} else {
					$employeeKeys[$key->tid] = [$key];
				}
			}
		}
		
		$this->render("employee_keys", [
			"keys" => $keys, 
			"shiftNeedMap" => $shiftNeedMap,
			"from" => $from, 
			"to" => $to,
			"showTransferOnly" => $showTransferOnly,
			"showClientKeys" => $showClientKeys,
			"selectedEmployees" => $employeeIds,
			"selectedWorkgroups" => $workGroupIds,
			"employees" => $employeeMap,
			"employeeKeys" => $employeeKeys,
			"queryEmployeeIds" => $queryEmployeeIds,
		]);
	}

	/**
	 * Returns a boolean indicating if a key has been returned
	 * back to a client. The location custom text (sijainti_omatekstti being 1)
	 * is interpreted on a best effort basis.
	 */
	private function clientHasKey(Avaimet $key) {
		$location = $key->sijainti;
		// sijainti_omatekstti should be 0
		// when sijainti is "2" (which is office, chosen from a list of options)
		if($location == 2) {
			return true;
		} else if($key->sijainti_omatekstti == 1) {
			// trying to look for common phrases that people write
			// into the custom field, like "palautettu asiakkaalle"
			// or just "asiakkaalla"
			$keywords = ["asiakkaalla", "asiakkaalle"];
			foreach($keywords as $keyword) {
				$w = strtoupper($keyword);
				$l = strtoupper($location);
				if(strpos($l, $w) !== false) {
					return true;
				}
			}
			
		}
		return false;
	}

	/**
	 * Returns the 'tid' and 'tyopaari' (employee IDs) fields in an array of a ToistuvatTyovuorot object.
	 * You can pass an Array as the second parameter to filter out some of those IDs from the result.
	 */
	private function repeatShiftEmployeeIds(ToistuvatTyovuorot $shift, Array $intersect = null) {
		$ids = [];
		$ids[] = $shift->tid;

		// parse work pair IDs
		$pair = json_decode($shift->tyopaari, true) ?? [];
		foreach($pair as $p) {
			$ids[] = intval($p);
		}

		if($intersect) {
			return array_intersect($intersect, $ids);
		}
		return $ids;
	}
}

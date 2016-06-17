<?php
	header("Access-Control-Allow-Origin: *");



//echo $_SERVER['HTTP_X_USERNAME'];
//var_dump($_GET);

class ApiController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    Const APPLICATION_ID = 'ASCCPE';
 
    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    /**
     * @return array action filters
     */
    public function filters()
    {
            return array();
    }
 




public function actionCheck_admin($dom)

    {

    switch($_GET['model'])
    {
        case 'mob':

	    if(isset($_POST['tunnus']) and isset($_POST['salasana']))
	    {
	   	$criteria = new CDbCriteria();
	    	$criteria->condition = " adm_login='".$_POST['tunnus']."' AND adm_salasana='".md5($_POST['salasana'])."' ";
		$model = Administrators::model()->find($criteria);
		if(isset($model->id))
       	    	 $this->_sendResponse(200, CJSON::encode('loginOk'));
		else
       	    	 $this->_sendResponse(200, CJSON::encode('loginError'));
	    }

            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }


}



public function actionAdminkalut($dom)

    {

	    Yii::app()->user->setState('domain', $dom);

    switch($_GET['model'])
    {
        case 'mob':

	    Yii::app()->theme = 'etunti';

	    if(isset($_POST['luoAsiakas']))
	    {
		$model = new Asiakkaat;
		$lomake = $this->renderPartial('//asiakkaat/_form', array('model'=>$model), true);
       	    	$this->_sendResponse(200, CJSON::encode($lomake));
		exit;
	    }

	    if(isset($_POST['Asiakkaat']))
	    {
		$model = new Asiakkaat;
		$model->attributes = $_POST['Asiakkaat'];
		if($model->save())
       	    	 $this->_sendResponse(200, CJSON::encode($model->id));
		else
       	    	 $this->_sendResponse(200, CJSON::encode('saveError'));
		exit;
	    }

	    if(isset($_POST['luoKohde']))
	    {
		$model = new Kohteet;
		$lomake = $this->renderPartial('//kohteet/_form', array('model'=>$model), true);
       	    	$this->_sendResponse(200, CJSON::encode($lomake));
		exit;
	    }

	    if(isset($_POST['Kohteet']))
	    {
		$model = new Kohteet;
		$model->attributes = $_POST['Kohteet'];
		if($model->save())
       	    	 $this->_sendResponse(200, CJSON::encode($model->id));
		else
       	    	 $this->_sendResponse(200, CJSON::encode('saveError'));
		exit;
	    }


            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }


}





public function actionLang($dom)

    {

    switch($_GET['model'])
    {
        case 'mob':

		$lang = array();

	    if(isset($_POST['lang']))
	    {
	  	$_SESSION['lang'] = $_POST['lang'];
		$lang = array(

		/* Index */		
		'TYO' => Yii::t('app', 'TYÖ'),
		'MATKA' => Yii::t('app', 'MATKA'),
		'LOUNAS' => Yii::t('app', 'LOUNAS'),
		'ALOITA' => Yii::t('app', 'ALOITA'),
		'LOPETA' => Yii::t('app', 'LOPETA'),
		'osoite' => Yii::t('app', 'Osoite'),
		'lyhyt_viesti' => Yii::t('app', 'lyhyt_viesti'),

		/* Viestinta */
		'LangViestinta' => Yii::t('app', 'Viestintä'),
		'olenEksynyt' => Yii::t('app', 'Lähetä GPS tiedot'),
		'LangUusiViesti' => Yii::t('app', 'Uusi viesti'),
		'LangViesti' => Yii::t('app', 'Viesti'),
		'lahetaToimistoon' => Yii::t('app', 'Lähetä viesti toimistoon'),

		/* Asetukset */
		'tyontekijan_sahkoposti' => Yii::t('app', 'Työntekijän sähköposti'),
		'tyontekijan_salasana' => Yii::t('app', 'Työntekijän salasana'),
		'tallenna' => Yii::t('app', 'Tallenna'),
		'Valitse_kieli' => Yii::t('app', 'Valitse kieli'),
		'Asetukset' => Yii::t('app', 'Asetukset'),
		'tallennaKieli' => Yii::t('app', 'Tallenna kieli'),

		/* Kamera */
		'LangKuvienLahettaminen' => Yii::t('app', 'Kuvien lähettäminen'),
		'Valitse_osoite' => Yii::t('app', 'Valitse osoite'),
		'fromCamera' => Yii::t('app', 'Ota kuva kameralla'),
		'fromLibrary' => Yii::t('app', 'Tuo kuva kirjastosta'),
		'fromAlbum' => Yii::t('app', 'Tuo kuva galleriasta'),

		/* Etunti admin */
		'admin_login' => Yii::t('app', 'Järjestelmänvalvoja tunnus'),
		'admin_password' => Yii::t('app', 'Järjestelmänvalvoja salasana'),

		);
	    }

        	$this->_sendResponse(200, CJSON::encode($lang));



            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }


}




public function actionTiedosto($dom)

    {

    switch($_GET['model'])
    {
        case 'mob':

	  	if (!file_exists(Yii::app()->basePath."/../img/uploadedfromphone/".$dom)) {
		  	mkdir(Yii::app()->basePath."/../img/uploadedfromphone/".$dom, 0777, true);
		}


	   	$criteria = new CDbCriteria();
	    	$criteria->condition = "  
			salasana!='' 
			AND tekijan_email = '".$_POST['email']."' 
			AND salasana = '".$_POST['salasana']."' 
	    	";
            	$ttekija = Tyontekijat::model()->find($criteria);

	  	if(isset($ttekija->id)){

    	 	if (move_uploaded_file($_FILES['file']['tmp_name'], Yii::app()->basePath."/../img/uploadedfromphone/".$dom."/".$_POST['kohdenID']."_".$ttekija->id."_".date("YmdHi").".jpg")) 
		{


		$firma = FirmanTiedot::model()->findbypk(1);
		if(isset($firma->sahkoposti) and !empty($firma->sahkoposti))
		{
		$k = Kohteet::model()->findbypk($_POST['kohdenID']);
		$message = Yii::t('main', 'Hei. Valokuva on saapunut kohteista: ').$k->osoite;
		mail($firma->sahkoposti,Yii::t('main', 'Uusi valokuva kohteista. Lähettäjä: '). ' '.$ttekija->tekijan_nimi,$message);
		}

        	$this->_sendResponse(200, "saveOK");


    		} else {

        	$this->_sendResponse(200, "saveError");

    		}


	     	} else {

        	$this->_sendResponse(200, "Ei onnistu!");

	     	}


            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }


}



public function actionImei($dom)
{

//$this->_checkAuth();

    switch($_GET['model'])
    {
        // Get an instance of the respective model
        case 'mob':


		    function sprint($val)
 		    {
			if($val > 0)
		    return sprintf('%02d:%02d', $val/3600, ($val % 3600)/60);
		    }


	    if(isset($_POST['lang']))
	  	$_SESSION['lang'] = $_POST['lang'];

	    if(isset($_POST['imei']) and !isset($_POST['salasana']))
	    {
	    $criteria = new CDbCriteria();
	    $criteria->condition = " imei!='' AND imei = '".$_POST['imei']."' ";
            $ttekija = Tyontekijat::model()->find($criteria);

	     	if(empty($ttekija->id))
	     	{
                  $this->_sendResponse(200, "imeiError//Virhellinen imei koodi//".$_POST['imei']);
	         exit;
	     	}

	    }

	    if(isset($_POST['email']) and isset($_POST['salasana']))
	    {
	    $criteria = new CDbCriteria();
	    $criteria->condition = " 
			salasana!='' 
			AND tekijan_email = '".$_POST['email']."' 
			AND salasana = '".$_POST['salasana']."' 
	    ";
            $ttekija = Tyontekijat::model()->find($criteria);

	    	if(empty($ttekija->id))
	    	{
              	   $this->_sendResponse(200, "eiLoytyTekija//Työntekijää ei löydy");
	         exit;
	    	}

	    	if(isset($ttekija->id) and !empty($ttekija->id))
	    	{
		   if(isset($_POST['gcm_reg_id']) and !empty($_POST['gcm_reg_id']) and $ttekija->gcm_reg_id != $_POST['gcm_reg_id'])
	   		Tyontekijat::model()->updatebypk($ttekija->id, array('gcm_reg_id'=>$_POST['gcm_reg_id']));

		   if(isset($_POST['gcm_reg_id']))
			unset($_POST['gcm_reg_id']);

		}

	    }



    	if(!isset($ttekija->id))
	{
              	   $this->_sendResponse(200, "eiLoytyTekija//Työntekijää ei löydy");
	         exit;
	}


	    if(isset($_POST['check'])){

	        if($_POST['check'] == 'sendLocation'){

		      Tyontekijat::model()->updatebypk($ttekija->id, array('position'=>$_POST['my_location']."//".date("d.m.Y H:i")));

		      $this->_sendResponse(200, $ttekija->id."//".date("d.m.Y H:i")."//".$_POST['my_location']);
		exit;
	        }


	        if($_POST['check'] == 'getObjbyTag'){

		  if(isset($_POST['tag']) and $_POST['tag'] != '000000')
		  {
		    $kohteet = Kohteet::model()->find(" tag_id='".$_POST['tag']."' ");

		    if(isset($kohteet['osoite']) and !empty($kohteet['osoite']))
		    {
		      $get_osoite = $kohteet['osoite'];
		      $kohdenID = $kohteet['id'];
		    } else {
		      $this->_sendResponse(200, "Tuntematon TAG//".$_POST['tag']."//error");
		    }
		  } 
		      $this->_sendResponse(200, $get_osoite."//".$kohdenID."//ok");
		exit;
	        }

	        if($_POST['check'] == 'tehty'){

		    $criteria = new CDbCriteria();
		    $criteria->order = " id DESC ";
		    $criteria->condition = " 
			tid = '".$ttekija->id."' 
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime("first day of this month"))."' AND '".date("Y-m-d")."' 
			AND loppui!=''
		    ";
	            $mob = Mob::model()->findAll($criteria);

		    if(empty($mob))
		    {
		    $this->_sendResponse(200, 'ei tuloksia');
		    exit;
		    }


		    $sel = '<h2>'.Yii::t('app', 'Tänään tekemasi työt').'</h2>';
		    foreach($mob as $val)
		    {
		    $kesto = '00:00';
		    if(!empty($val->loppui))
		    $kesto = (strtotime($val->loppui)-strtotime($val->aloitan));

		      $sel .= '<div class="well">
				  '.Yii::t('app', 'Päivämäärä').': <b>'.date("d.m.Y",strtotime($val->aloitan)).'</b><br> 
				  '.Yii::t('app', 'Klo').': '.$val->aloitan.'-'.$val->loppui.'<br> 
				  <h4>'.Yii::t('app', 'Osoite').': '.$val->kohde_kannasta.'</h4>
				  <hr>
				  <h3>'.Yii::t('app', 'Kesto').': '.sprint($kesto).'</h3>
				</div>';
		    }

		    $this->_sendResponse(200, $sel);
		exit;
	        }


	        if($_POST['check'] == 'getTyovuorotToday'){

		    $site = Yii::app()->createController('Site');
		    $eilasketa = $site[0]->eiLasketa();

		    $criteria = new CDbCriteria();
		    $criteria->order = " DATE_FORMAT(STR_TO_DATE(alku, '%H.%i'), '%H.%i') ASC ";
		    $criteria->condition = " 
				tid = '".$ttekija->id."' 
				and DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() 
				AND $eilasketa
		    ";

	            $tvuoro = Tyovuoroot::model()->findAll($criteria);

		    if(!isset($tvuoro[0]))
		    {
		    //$this->_sendResponse(200, 'ei tuloksia');
		    exit;
		    }

		    $sel = '';
		    $sel .= '<select id="list" class="form-control">';
		    $sel .= '<option>'.Yii::t('app','Valitse kohde työvuorosta').'</option>';
		    foreach($tvuoro as $val){
			$k = Kohteet::model()->findbypk($val->kohde);
			if(isset($k->osoite))
		      	$sel .= '<option value="'.$k->id.'">'.$k->osoite.'</option>';
		    }
		    $sel .= '</select>';

		    $this->_sendResponse(200, $sel);
		exit;
	        }



	        if($_POST['check'] == 'tvuoro'){

		    $tas = Domainit::model()->find(" domain='".$dom."' ");
		    $p = array();
		    if(isset($tas->paketti)) 
		     	$p = explode(",",$tas->paketti);

		    if(!in_array('2',$p))
		    {
		    $this->_sendResponse(200, 'Osta lisäosa työvuorojenhallinta');
		    exit;
		    } 

		    $asetukset = Asetukset::model()->findbypk(1);

		    if(isset($asetukset->sovellus_tyovuorot) and $asetukset->sovellus_tyovuorot == '1')
		    $aikaVali = date('Y-m-d',strtotime('sunday this week'));
		    elseif(isset($asetukset->sovellus_tyovuorot) and $asetukset->sovellus_tyovuorot == '2')
		    $aikaVali = date('Y-m-d',strtotime('+7 day'));
		    elseif(isset($asetukset->sovellus_tyovuorot) and $asetukset->sovellus_tyovuorot == '3')
		    $aikaVali = date('Y-m-d',strtotime('+14 day'));
		    else
		    $aikaVali = date('Y-m-d',strtotime('sunday this week'));


		    $criteria = new CDbCriteria();
		    $criteria->order = " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'),alku ASC ";
		    $criteria->condition = " 
				tid = '".$ttekija->id."' 
				and DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
				BETWEEN CURDATE() AND '".$aikaVali."'
		    ";
	            $tvuoro = Tyovuoroot::model()->findAll($criteria);

		    if(empty($tvuoro))
		    {
		    $this->_sendResponse(200, 'ei tuloksia');
		    exit;
		    }

		    $sel = '<h2>'.Yii::t('app', 'Työvuorot').'</h2>';

		    foreach($tvuoro as $val)
		    {
		      $osoite = '';
		      $kohde = Kohteet::model()->findbypk($val->kohde);
		      if(isset($kohde['osoite']))
		      $osoite = $kohde['osoite'];

		      $alkLop = '';
		      if($val->alku > 0 and $val->loppu > 0)
		      $alkLop = $val->alku.'-'.$val->loppu.' ';

		      $color = '';
	 	      if(!empty($val->tyoajanlaatu) and empty($osoite))
	 	      {
			    $expl1 = explode("/",$val->tyoajanlaatu);
			    $color = (isset($expl1[1])) ? $expl1[1] : '';
			    $osoite = (isset($expl1[0])) ? $expl1[0] : '';
		      }

		      $nm = '';
		      if(isset($asetukset->show_name) and $asetukset->show_name == 1 and $kohde->etu_suku_nimet != '')
		      $nm = ', '.$kohde->etu_suku_nimet;
		      

		      $sel .= '<div class="well">
				  <b>'.$val->pvm.$nm.'</b><br>
				  <b><span class="text" style="color:'.$color.'">'.$alkLop.$osoite.'</span></b>';
		      if(!empty($val->tietoja))
		      {
		      $sel .= '
				  <hr>
				  <div class="text-small">'.$val->tietoja.'</div>';
		      }
		      $sel .= '
				</div>';
		    }

		    $this->_sendResponse(200, $sel);
		exit;
	        }

	        if($_POST['check'] == 'uusiviesti'){

		    $model = new Viestinta;
		    $model->admin = "tt_".$ttekija->id.",".$ttekija->tekijan_nimi;
		    $model->tekija = "toimisto";
	
		    $viesti = '';
		    if(isset($_POST['viesti']))
		    $viesti .= $_POST['viesti'];

		    $model->viesti = date("d.m H:i").", ".$ttekija->tekijan_nimi.": ".$viesti;

		    if($model->save())
		       $this->_sendResponse(200, "Viestisi vastaanotettu");
		    else
		       $this->_sendResponse(200, "Ei onnistuu");
		exit;
	        }


	        if($_POST['check'] == 'oleneksynyt'){

		    $model = new Viestinta;
		    $model->admin = "tt_".$ttekija->id.",".$ttekija->tekijan_nimi;
		    $model->tekija = "toimisto";
	
		    $viesti = '';
		    if(isset($_POST['viesti']))
		    $viesti .= $_POST['viesti'];

		    
		    if(isset($_POST['my_location']) and !empty($_POST['my_location']))
		    {
		    $viesti .= '<br> <a href="http://maps.google.com/maps?q='.str_replace("/",",",$_POST['my_location']).'&ll='.str_replace("/",",",$_POST['my_location']).'&z=17" target="_blank">KARTTA</a>';
		    }
		    

		    $model->viesti = date("d.m H:i").", ".$ttekija->tekijan_nimi.": ".$viesti;

		    if($model->save())
		       $this->_sendResponse(200, "Viestisi vastaanotettu");
		    else
		       $this->_sendResponse(200, "Ei onnistuu");
		exit;
	        }


	        if($_POST['check'] == 'checkviesti'){

		    $criteria = new CDbCriteria();
		    $criteria->order = " id DESC,status = '0' DESC LIMIT 20";
		    $criteria->condition = " tekija = '".$ttekija->id."' and status = '0' ";
	            $viestinta = Viestinta::model()->findAll($criteria);

		    if(empty($viestinta))
		    {
		    $this->_sendResponse(200, 'ei tuloksia');
		    exit;
		    }

		    $sel = 0;
		    foreach($viestinta as $count)
		    $sel += 1;

		    if($sel > 0){ 

				$on = '<br><div class="row">
		  		  <div class="col-sm-12">
				    <div class="well">
				      <div class="card-content black-text">
				        <center><a href="viestinta.html"><h2 class="glyphicon glyphicon-envelope form-group"></h2>&nbsp;&nbsp;&nbsp;<b>Sinulla on lukematon viesti</b></a></center>
				      </div>
				    </div>
				   </div>
				  </div>';

		    } else { 
  		      $on = '';
		    }

		    $this->_sendResponse(200, $sel."//".$on);
		exit;
	        }


	        if($_POST['check'] == 'viestinta'){

		    $criteria = new CDbCriteria();
		    $criteria->order = " id DESC,status = '0' LIMIT 20";
		    $criteria->condition = " tekija = '".$ttekija->id."' ";
	            $viestinta = Viestinta::model()->findAll($criteria);

		    if(empty($viestinta))
		    {
		    $this->_sendResponse(200, 'ei tuloksia');
		    exit;
		    }

		    $sel = '';
		    $cl = '';
		    $admin = '';

		    foreach($viestinta as $val)
		    {
		      if($val->status == '0')
  			$cl = ' <span class="btn btn-xs btn-success">Uusi</span>';
		      else
  			$cl = '';

		      $exAdmin = explode(",",$val->admin);
		      if(isset($exAdmin[1]))
  			$admin = $exAdmin[1];
		      else
  			$admin = $val->admin;

		     	$sel .= '<div class="well">
				  <p>'.$cl.' <b>'.Yii::t('app', 'Keskustelu').': '.$val->id.'</b></p>
				  <span class="text" id="text_'.$val->id.'">'.str_replace("\n","<br>",$val->viesti).'</span><br>';

				  if(isset($exAdmin[0]) and !empty($exAdmin[0])){
				  $sel .= '
				  <br>

            			    <div class="input-group">
			              <input type="text" class="form-control form-input" id="vastaus_'.$val->id.'">
			              <div class="input-group-btn">
			                <button class="viesti btn btn-primary btn-group" id="'.$val->id.'">'.Yii::t('app', 'vastaus').'</button>
			              </div>
			            </div>


			  	  </div>
				  ';
				  }

		    }

		    Viestinta::model()->updateAll(array('status'=>1),'tekija="'.$ttekija->id.'"');

		    $this->_sendResponse(200, $sel);
		exit;
	        }


	        if($_POST['check'] == 'vastaus' and isset($_POST['viestinID'])){

	            $viestinta = Viestinta::model()->findbypk($_POST['viestinID']);
		    $tekija = Tyontekijat::model()->findbypk($viestinta->tekija);


	            $viestinta->viesti = $viestinta->viesti."\n".date("d.m H:i").", ".$tekija->tekijan_nimi.": ".$_POST['vastText'];
	            $viestinta->save();
		    // lahetta sahkopostiin
		    $admin = '';
		    $sending = '';
		      $exAdmin = explode(",",$viestinta->admin);
		      if(isset($exAdmin[0]))
  			$admin = $exAdmin[0];

	            $adm = Administrators::model()->findbypk($admin);
		      if(isset($adm->adm_email) and !empty($adm->adm_email))
		      {

			
				$name='=?UTF-8?B?'.base64_encode($viestinta->id).'?=';
				$subject='=?UTF-8?B?'.base64_encode("Työntekijä ".$tekija->tekijan_nimi." vastaa").'?=';
				$headers="From: $tekija->tekijan_nimi <etunti@etunti.fi>\r\n".
					"Reply-To: no_replay@etunti.fi\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/html; charset=UTF-8";

				$message = '<h2>Keskustelun ID: '.$_POST['viestinID']."</h2><br>";
				$message .= str_replace("\n","<br>",$viestinta->viesti);


				if(mail($adm->adm_email,$subject,$message,$headers)){
				   $sending = 'ok';
				} else {
				   $this->_sendResponse(200, 'Mail send ERROR');
				}
		      }

		    $this->_sendResponse(200, $viestinta->viesti);
		exit;
	        }


	        if($_POST['check'] == 'osoitevaihto'){

		    $criteria = new CDbCriteria();
		    $criteria->order = " osoite ";
		    $criteria->condition = " osoite like '%".$_POST['thisKey']."%' ";
	            $kohteet = Kohteet::model()->findAll($criteria);

		    $sel = '<select id="list" class="form-control">';
		    $sel .= '<option id="valitseOsoite">'.Yii::t('app', 'Valitse osoite').'</option>';
		    foreach($kohteet as $val)
		      $sel .= '<option value="'.$val->id.'">'.$val->osoite.'</option>';
		    $sel .= '</select>';

		    $this->_sendResponse(200, $sel);
		exit;
	        }




		/*
		$loc = explode("/",$_POST['my_location']);
 		if(isset($loc[0]) and isset($loc[1]) and !empty($loc[0]) and !empty($loc[1]))
		{
		  $gps = $loc[0].",".$loc[1]; 
 
		  try {
		   $json_url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$gps.'&language=fi&sensor=true';
		   	if($json = file_get_contents($json_url))
		   	{
			  $obj = json_decode($json);
			  if(isset($obj->results[0]))
			  {
				$go = $obj->results[0]->formatted_address;

		  		$go = explode(",", $go);
		  		if(isset($go[0]))
		  		$get_osoite = $go[0];
			  }
			}

		   } catch(Exception $e) {


		   }
		   
		}
		*/

		if(isset($_POST['tag']) and $_POST['tag'] != '000000')
		{
		  $kohteet = Kohteet::model()->find(" tag_id='".$_POST['tag']."' ");

		    if(isset($kohteet['osoite']) and !empty($kohteet['osoite']))
		    {
		      $get_osoite = $kohteet['osoite'];
		      $kohdenID = $kohteet['id'];
		    } 
		} 



	 	 $tag = '';
		 if(isset($_POST['tag']))
	 	 $tag = $_POST['tag'];


	    	$criteria = new CDbCriteria();
	    	$criteria->order = " 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') DESC, id DESC ";
	    	$criteria->condition = " 
			tid = '".$ttekija->id."' 
	    	";

           	//$mobCheck = Mob::model()->find(" tid = '".$ttekija->id."' order by id DESC ");
           	$mobCheck = Mob::model()->find($criteria);
		$kohdenID = '';
		$get_osoite = '';


		  if(isset($mobCheck->id))
		  {
		    if($mobCheck->status == 2 and $mobCheck->loppui == '')
		      $mobCheck->status = 2.1;

		    if($mobCheck->status == 10 and $mobCheck->loppui == '')
		       $mobCheck->status = 10.1;

		    $nykyinenKesto = 0;
		    if(strtotime($mobCheck->aloitan) > 0)
		    {
		    $nykyinenKesto = time()-strtotime($mobCheck->aloitan);
		    $nykyinenKesto = sprint($nykyinenKesto);
		    }



                       $this->_sendResponse(200, $mobCheck->status."//".$mobCheck->kohde_kannasta."<br>".$nykyinenKesto."//".$mobCheck->aloitan."//".$mobCheck->loppui."//".$get_osoite."//".$ttekija->tekijan_nimi."//".$kohdenID."//".$tag);
		  } else {
                     $this->_sendResponse(200, "3//mull//null//null//".$get_osoite."//".$ttekija->tekijan_nimi."//".$kohdenID."//".$tag);
		  }


	     	exit;
	    }


	    $criteria = new CDbCriteria();
	    $criteria->order = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i') 
		AND status IN (1,2,10)
		AND loppui='' DESC, id DESC ";
	    $criteria->condition = " 
		tid = '".$ttekija->id."' 
		and loppui='' 
		AND status IN (1,2,10)
	    ";

            //$mob = Mob::model()->find(" tid = '".$ttekija->id."' and loppui='' order by id DESC ");
            $mob = Mob::model()->find($criteria);

	    if(isset($mob->id)){



		$kesto = '';

		  $ms = '';
		if($mob->status == 1)
		  $ms = 'Työ';
		if($mob->status == 2)
		  $ms = 'Matka';
		if($mob->status == 10)
		  $ms = 'Lounas';


	     if($mob->status == 1 and $_POST['status'] == 3)
	     {
                $mobupdate = Mob::model()->findbypk($mob->id);

		$explAsNum = explode("_",$mobupdate->asiakas_num);
		$explAsNumPost = explode("_",$_POST['asiakas_num']);

		if( isset($explAsNum[1]) and isset($explAsNumPost[1]) and $explAsNum[1] != $explAsNumPost[1] )

		{
                $this->_sendResponse(200, $ms."//".$mobupdate->id."//".$mobupdate->status."//".$mob->kohde_kannasta."//tagnumerror//null//update");
	        exit;
		}

                $mobupdate->loppui = date("d.m.Y H:i:s");
                $mobupdate->status = 3;

		$vanhaViesti = '';
		if($mobupdate->viesti != '')
		$vanhaViesti = $mobupdate->viesti."\n";
                $mobupdate->viesti = $vanhaViesti.$_POST['viesti'];

                $mobupdate->my_location = $mobupdate->my_location."**".$_POST['my_location'];
                $mobupdate->save();

		$kesto = sprint(strtotime($mobupdate->loppui)-strtotime($mobupdate->aloitan));

                $this->_sendResponse(200, $ms."//".$mobupdate->id."//".$mobupdate->status."//".$mob->kohde_kannasta."//null//".$kesto."//update");

	     } elseif($mob->status == 2 and $_POST['status'] == 2)
	     {
                $mobupdate = Mob::model()->findbypk($mob->id);
                $mobupdate->loppui = date("d.m.Y H:i:s");
                $mobupdate->status = 2;

		$vanhaViesti = '';
		if($mobupdate->viesti != '')
		$vanhaViesti = $mobupdate->viesti."\n";
                $mobupdate->viesti = $vanhaViesti.$_POST['kohde_kannasta']." - ".$_POST['viesti'];

                $mobupdate->my_location = $mobupdate->my_location."**".$_POST['my_location'];
                $mobupdate->save();

		$kesto = sprint(strtotime($mobupdate->loppui)-strtotime($mobupdate->aloitan));

                $this->_sendResponse(200, $ms."//".$mobupdate->id."//".$mobupdate->status."//".$mob->kohde_kannasta."//null//".$kesto."//update");

	     } elseif($mob->status == 10 and $_POST['status'] == 10)
	     {
                $mobupdate = Mob::model()->findbypk($mob->id);
                $mobupdate->loppui = date("d.m.Y H:i:s");
                $mobupdate->status = 10;
	
		$vanhaViesti = '';
		if($mobupdate->viesti != '')
		$vanhaViesti = $mobupdate->viesti."\n";
                $mobupdate->viesti = $vanhaViesti.$_POST['kohde_kannasta']." - ".$_POST['viesti'];

                $mobupdate->my_location = $mobupdate->my_location."**".$_POST['my_location'];
                $mobupdate->save();

		$kesto = sprint(strtotime($mobupdate->loppui)-strtotime($mobupdate->aloitan));

                $this->_sendResponse(200, $ms."//".$mobupdate->id."//".$mobupdate->status."//".$mob->kohde_kannasta."//null//".$kesto."//update");

	     } else {

                $this->_sendResponse(200, $ms." on avattu ID: ".$mob->id.", ".$mob->kohde_kannasta);

	     }
	      exit;
	    }


	    // uusi rivi
	    if(isset($ttekija->id) and !empty($_POST['aloitan']) and empty($_POST['loppui'])){


                $mobinsert = new Mob;
                $mobinsert->attributes = $_POST;

		if($_POST['status'] == 2)
		{
                $mobinsert->kohde_kannasta = 'MATKA';
                $mobinsert->viesti = $_POST['kohde_kannasta']." - ".$_POST['viesti'];
		}

		if($_POST['status'] == 10)
		{
                $mobinsert->kohde_kannasta = 'LOUNASTAUKO';
                $mobinsert->viesti = $_POST['kohde_kannasta']." - ".$_POST['viesti'];
		}

                $mobinsert->imei = $ttekija->imei;
                $mobinsert->tid = $ttekija->id;
                $mobinsert->tekijan_nimi = $ttekija->tekijan_nimi;
                $mobinsert->tietoja = $_POST['tietoja'];
                $mobinsert->aloitan = date("d.m.Y H:i:s");
                $mobinsert->save();

		// <-- Timer
		if(isset($mobinsert->kohdenID))
		{
		    $criteria = new CDbCriteria();
		    $criteria->order = "alku DESC"; 
		    $criteria->condition = " 
				tid = '".$ttekija->id."' and kohde = '".$mobinsert->kohdenID."'
				and DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
		    ";
		    $loppu = '';
		    $sekForSignal = '';
	            $tvuoro = Tyovuoroot::model()->find($criteria);
		    if(isset($tvuoro->id))
		    {
			$loppu = date("d.m.Y H:i",strtotime($tvuoro->pvm." ".$tvuoro->loppu));
			$sekForSignal = strtotime($tvuoro->pvm." ".$tvuoro->loppu)-time();
		    }

		}
		// Timer -->


                $this->_sendResponse(200, $mobinsert->id."//".$mobinsert->kohde_kannasta."//new//".$mobinsert->kohdenID."//".$loppu."//".$sekForSignal);

	    } else {
                $this->_sendResponse(200, "Kaikki on suljettu, ei ole mitään avoina");
	      exit;
	    }
            break;
        default:
            $this->_sendResponse(501, 
                sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
                Yii::app()->end();
    }

}



/*
    // Actions
    public function actionList()
    {


//$this->_checkAuth();
    // Get the respective model instance
    switch($_GET['model'])
    {
        case 'posts':
            $models = Mob::model()->findAll("id != '' order by id limit 10",array("select"=>"id"));
            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we get some results?
    if(empty($models)) {
        // No
        $this->_sendResponse(200, 
                sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
    } else {
        // Prepare response
        $rows = array();
        foreach($models as $model)
            $rows[] = $model->attributes;
        // Send the response
        $this->_sendResponse(200, CJSON::encode($rows));
    }

    }
*/


/*
public function actionView()
{
$this->_checkAuth();
    // Check if id was submitted via GET
    if(!isset($_GET['id']))
        $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
 
    switch($_GET['model'])
    {
        // Find respective model    
        case 'posts':
            $model = Mob::model()->findbypk($_GET['id']);
            break;
        default:
            $this->_sendResponse(501, sprintf(
                'Mode <b>view</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we find the requested model? If not, raise an error
    if(is_null($model)){
        $this->_sendResponse(404, 'No Item found with id '.$_GET['id']); //'No Item found with id '.$_GET['id']
    } else {
        $this->_sendResponse(200, CJSON::encode($model));
    }

}
*/


/*
public function actionImei()
{

//$this->_checkAuth();

    // Check if id was submitted via GET
    if(!isset($_GET['id']))
        $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
 
    switch($_GET['model'])
    {
        // Find respective model    
        case 'posts':
            $ttekija = Tyontekijat::model()->find(" imei = '".$_GET['id']."' ");
            $model = Mob::model()->find(" imei = '".$_GET['id']."' and loppui = '' ");
            break;
        default:
            $this->_sendResponse(501, sprintf(
                'Mode <b>view</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we find the requested model? If not, raise an error
    if(is_null($ttekija)){
        $this->_sendResponse(404, 'This imei not found'); //'No Item found with id '.$_GET['id']
    } else {

	if(!is_null($model)){
	  $id = $model->id;
	  $status = $model->status;

	} else {
	  $id = null;
	  $status = null;
	}

	$dataForSend = array("Kohde"=>$id,"Tyontekija"=>$ttekija->id,"Status"=>$status);
        $this->_sendResponse(200, CJSON::encode($dataForSend));
    }
}
*/

/*
public function actionCreate()
{

    switch($_GET['model'])
    {
        // Get an instance of the respective model
        case 'posts':
            $model = new Mob;                    
            break;
        default:
            $this->_sendResponse(501, 
                sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
                Yii::app()->end();
    }
    // Try to assign POST values to attributes
    foreach($_POST as $var=>$value) {
        // Does the model have this attribute? If not raise an error
        if($model->hasAttribute($var))
            $model->$var = $value;
        else
            $this->_sendResponse(500, 
                sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                $_GET['model']) );
    }
    // Try to save the model
    if($model->save())
        $this->_sendResponse(200, CJSON::encode($model));
    else {
        // Errors occurred
        $msg = "<h1>Error</h1>";
        $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
        $msg .= "<ul>";
        foreach($model->errors as $attribute=>$attr_errors) {
            $msg .= "<li>Attribute: $attribute</li>";
            $msg .= "<ul>";
            foreach($attr_errors as $attr_error)
                $msg .= "<li>$attr_error</li>";
            $msg .= "</ul>";
        }
        $msg .= "</ul>";
        $this->_sendResponse(500, $msg );
    }

}
*/

/*
public function actionUpdate()
{

    // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
    $json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
    $put_vars = CJSON::decode($json,true);  //true means use associative array
 
    switch($_GET['model'])
    {
        // Find respective model
        case 'posts':
            $model = Mob::model()->findByPk($_GET['id']);                    
            break;
        default:
            $this->_sendResponse(501, 
                sprintf( 'Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Did we find the requested model? If not, raise an error
    if($model === null)
        $this->_sendResponse(400, 
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                $_GET['model'], $_GET['id']) );
 
    // Try to assign PUT parameters to attributes
    foreach($put_vars as $var=>$value) {
        // Does model have this attribute? If not, raise an error
        if($model->hasAttribute($var))
            $model->$var = $value;
        else {
            $this->_sendResponse(500, 
                sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
                $var, $_GET['model']) );
        }
    }
    // Try to save the model
    if($model->save())
        $this->_sendResponse(200, CJSON::encode($model));
    else
        // prepare the error $msg
        // see actionCreate
        // ...
        $this->_sendResponse(500, $msg );

}
*/

/*
public function actionUpdaterow()
{
    // Check if id was submitted via GET
    if(!isset($_GET['id']))
        $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
 
    switch($_GET['model'])
    {
        // Find respective model    
        case 'posts':
            $model = Mob::model()->findbypk($_GET['id']);
            break;
        default:
            $this->_sendResponse(501, sprintf(
                'Mode <b>view</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }

    foreach($_POST as $var=>$value) {
        // Does the model have this attribute? If not raise an error
        if($model->hasAttribute($var))
            $model->$var = $value;
        else
            $this->_sendResponse(500, 
                sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                $_GET['model']) );
    }

    if($model->save())
        $this->_sendResponse(200, CJSON::encode($model));
    else
        // prepare the error $msg
        // see actionCreate
        // ...
        $this->_sendResponse(500, $msg );
}
*/

/*
public function actionDelete()
{

    switch($_GET['model'])
    {
        // Load the respective model
        case 'posts':
            $model = Mob::model()->findByPk($_GET['id']);                    
            break;
        default:
            $this->_sendResponse(501, 
                sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }
    // Was a model found? If not, raise an error
    if($model === null)
        $this->_sendResponse(400, 
                sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                $_GET['model'], $_GET['id']) );
 
    // Delete the model
    $num = $model->delete();
    if($num>0)
        $this->_sendResponse(200, $num);    //this is the only way to work with backbone
    else
        $this->_sendResponse(500, 
                sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                $_GET['model'], $_GET['id']) );

}
*/


private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
    // set the status
    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
    header($status_header);
    // and the content type
    header('Content-type: ' . $content_type);
 
    // pages with body are easy
    if($body != '')
    {
        // send the body
        echo $body;
    }
    // we need to create the body if none is passed
    else
    {
        // create some body messages
        $message = '';
 
        // this is purely optional, but makes the pages a little nicer to read
        // for your users.  Since you won't likely send a lot of different status codes,
        // this also shouldn't be too ponderous to maintain
        switch($status)
        {
            case 401:
                $message = 'You must be authorized to view this page.';
                break;
            case 404:
                $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                break;
            case 500:
                $message = 'The server encountered an error processing your request.';
                break;
            case 501:
                $message = 'The requested method is not implemented.';
                break;
        }
 
        // servers don't always have a signature turned on 
        // (this is an apache directive "ServerSignature On")
        $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
 
        // this should be templated in a real-world solution

        $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';
 
        echo $body;
    }
    Yii::app()->end();
}



private function _getStatusCodeMessage($status)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',

        501 => 'Not Implemented',
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
}


private function _checkAuth()
{


    if(!(isset($_GET['X_USERNAME']) and isset($_GET['X_PASSWORD']))) {
        // Error: Unauthorized
        $this->_sendResponse(401);
    }
    $username = $_GET['X_USERNAME'];
    $password = $_GET['X_PASSWORD'];
    // Find the user
    $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
    if($user===null) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Name is invalid');
    } else if(!$user->validatePassword($password)) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Password is invalid');
    }

/*
    // Check if we have the USERNAME and PASSWORD HTTP headers set?
    if(!(isset($_SERVER['HTTP_X_USERNAME']) and isset($_SERVER['HTTP_X_PASSWORD']))) {
        // Error: Unauthorized
        $this->_sendResponse(401);
    }
    $username = $_SERVER['HTTP_X_USERNAME'];
    $password = $_SERVER['HTTP_X_PASSWORD'];
    // Find the user
    $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
    if($user===null) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Name is invalid');
    } else if(!$user->validatePassword($password)) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Password is invalid');
    }
*/
}


}

?>

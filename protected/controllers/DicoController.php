<?php
	header("Access-Control-Allow-Origin: *");



//echo $_SERVER['HTTP_X_USERNAME'];
//var_dump($_GET);

class DicoController extends Controller
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
 




public function actionLogin($domain)
{

    switch($_GET['model'])
    {
        case 'asiakkaat':

		$return	= array();
		if($this->kirjautuminen($_POST['tunnus'], $_POST['salasana']) == true)
		{
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				sahkoposti='".$_POST['tunnus']."' 
				AND salasana='".$_POST['salasana']."'
				AND salasana!=''
			";
			$model=Asiakkaat::model()->find($criteria);
			if(isset($model->id))
			{
				$asiakasNimi = '';
				if(!empty($model->yrityksen_nimi))
				$asiakasNimi = $model->yrityksen_nimi;
				elseif(empty($model->yrityksen_nimi) and !empty($model->yhteyshenkilo))
				$asiakasNimi = $model->yhteyshenkilo;

				$domainit=Domainit::model()->find(" domain = '".$domain."' ");
				(isset($domainit->paketti))? $paketti = $domainit->paketti: $paketti = '';

				$return['loginOK'] = array('asiakasID'=>$model->id, 'asiakasNimi'=>$asiakasNimi, $_POST, 'paketti'=>$paketti);
			}
		}

		$this->_sendResponse(200, CJSON::encode($return));
		exit;

            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }

}

	public function actionCheck($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($_POST['tunnus'], $_POST['salasana']) == true)
		{
		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {
			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
				AND lahettaja='admin'
				AND asiakas_luettu=0
			";
			$pal=Palautteet::model()->find($criteria);
	
			if(isset($pal->id))
			{
				$this->_sendResponse(200, CJSON::encode($pal->keskustelu_id));
				exit;
			}

		   }
		}
		
		$this->_sendResponse(200, CJSON::encode($return));
		exit;
	}


	public function actionHistoria($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

				// <-- naytaVinkit
				if(isset($_POST['tyyppi']) and $_POST['tyyppi'] == 'naytaVinkit')
				{
			                Yii::app()->theme = 'customer';
					$mod = new VinkkiExtranet;
					if(isset($_POST['VinkkiExtranet']))
					{

						$token = $this->generateRandomString($length = 40);
						$mod->attributes=$_POST['VinkkiExtranet'];
						$mod->token=$token;

						if($mod->save())
						{

							// <-- Sahkoposti lahetys
							$firma = FirmanTiedot::model()->findbypk(1);
							$get_css = file_get_contents(Yii::app()->request->baseUrl.'css/email_send_table.css');


		if($model->tyyppi == 'yritys')
		$asiakas = $model->yrityksen_nimi;
		if($model->tyyppi == 'henkilo')
		$asiakas = $model->yhteyshenkilo;

		$message = '<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		    <title></title>
		    <style type="text/css">'.$get_css.'</style>
		</head>
		<body>';

		$message .= '<br>
		Hei,
		<p>Olen '.$firma->tyonantaja.':n tyytyväinen asiakas ja haluan suositella heidän palvelujaan sinulle.
		<br>Hyväksymällä tämän suosituksen, nimesi, puhelinnumerosi ja sähköpostiosoitteesi siirtyvät heidän asiakaskantaansa ja he ovat sinuun yhteydessä mahdollisista palveluista.
		<br>Hylkäämällä tämän suosituksen, tietosi eivät näy heillä. Mikäli et hyväksy/hylkää suositustani 14 vrk sisällä, tietojasi ei siirretä heille.</p>
		Terveisin, '.$asiakas;

		$message .= '<br>
		<center>
		<div id="outer">
		<a class="hyvaksy_button inner" href="http://'.$_SERVER['SERVER_NAME'].'/index.php/vinkkiExtranet/vastaus?domain='.$domain.'&asia=1&id='.$mod->id.'&token='.$token.'">
				<h2>'.Yii::t('main', 'Hyväksy').'</h2>
		</a>
		<a class="hylkaa_button inner" href="http://'.$_SERVER['SERVER_NAME'].'/index.php/vinkkiExtranet/vastaus?domain='.$domain.'&asia=0&id='.$mod->id.'&token='.$token.'">
				<h2>'.Yii::t('main', 'Hylkää').'</h2>
		</a>
		</div>
		</center>
		';
		$message .= '
		</center>
		</body>
		</html>';


							$subject='=?UTF-8?B?'.base64_encode($asiakas ." suosittele").'?=';
							$headers="From: ".$asiakas." <".$model->sahkoposti.">\r\n".
								"Reply-To: no_replay@etunti.fi\r\n".
								"MIME-Version: 1.0\r\n".
								"Content-type: text/html; charset=UTF-8";

							mail($mod->sahkoposti,$subject,$message,$headers);
							//     Sahkoposti lahetys -->

							// <-- LOG
							$log=new Log;
							$log->log_category 	= 1; // 1-email
							$log->email_to 		= $mod->sahkoposti;
							$log->email_subject	= $subject;
							$log->email_message	= json_encode($message);
							$log->save();
							//     LOG -->

							$this->_sendResponse(200, CJSON::encode(array('OK'=>Yii::t('main', 'Vinkki lähetetty.'))));
							exit;
						} else {
							$this->_sendResponse(200, CJSON::encode(array('Error'=>$mod->getErrors())));
							exit;
						}

					} else {
						$return .= $this->renderPartial('//vinkkiExtranet/_form', array('model'=>$mod), true);
					}
				}
				//  naytaVinkit -->

				// <-- naytaPalautteet
				if(isset($_POST['tyyppi']) and $_POST['tyyppi'] == 'naytaPalautteet')
				{
			                Yii::app()->theme = 'customer';
					$mod = new Palautteet;
					if(isset($_POST['Palautteet']))
					{

						$palauteResponse = $this->luo_palaute($mod, $_POST);

						if($palauteResponse)
						{
							$this->_sendResponse(200, CJSON::encode(array('OK'=>Yii::t('main', 'Palaute lähetetty.'))));
							exit;
						} else {
							$this->_sendResponse(200, CJSON::encode(array('Error'=>$palauteResponse)));
							exit;
						}

					} else {
						$return .= $this->renderPartial('//palautteet/_form', array('model'=>$mod), true);
					}

					// <-- Palaute vastaus
					if(isset($_POST['palaute_id']))
					{
						$return = $this->Send_vastaus($_POST);
						$this->_sendResponse(200, CJSON::encode(array('OK'=>Yii::t('main', 'Palaute vastaus lähetetty.'))));
						exit;
					}
					//     Palaute vastaus -->

				}
				//  naytaPalautteet -->


				Yii::app()->theme = 'etunti';

				$naytaMita = '';
				if(isset($_POST['tyyppi']) and !empty($_POST['tyyppi']))
				$naytaMita = $_POST['tyyppi'];

				$naytaId = '';
				if(isset($_POST['id']) and !empty($_POST['id']))
				{
					$naytaId = $_POST['id'];
					Palautteet::model()->updateAll(array('asiakas_luettu'=>1),'keskustelu_id="'.$_POST['id'].'"');
				}

				$return .= $this->renderPartial('//asiakkaat/asiakas_historia', 
				array(
					'model'=>$model,
					$naytaMita=>true,
					'id' => $naytaId,
					'kayttaja' => 'asiakas',
				)
				, true);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
	}


	protected function generateRandomString($length = 40) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function actionGetlaskupdf($domain, $id)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			$lasku = Yii::app()->createController('Lasku');
			$result = $lasku[0]->Lasku_pdf($id);
			$this->_sendResponse(200, $result);

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
	}

	protected function kirjautuminen($tunnus, $salasana)
	{

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			sahkoposti='".$tunnus."' 
			AND salasana='".$salasana."'
			AND salasana!=''
		";
		$model=Asiakkaat::model()->find($criteria);
		if(isset($model->id))
		{
			Yii::app()->user->setState('asiakas', $model->id);
			return true;
		} else {
			return false;
		}

	}

	protected function luo_palaute($mod, $post)
	{
		$palauteet = Yii::app()->createController('Palautteet');
		return $palauteet[0]->UusiPalaute($mod, $post);
	}

	protected function Send_vastaus($post)
	{
	   	$asiakkaat = Yii::app()->createController('Asiakkaat');
		$asiakkaat[0]->palautteetVastaus($post);
	}



	public function actionTarjoukset($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			$liite = '';
			$pdf_link = '';
			$nimike = '';
			if(isset($_POST['liite']))
			{
				$nimike = $_POST['liite'];
				$t = $_POST['liite'];
   				if(file_exists(Yii::app()->basePath."/../tiedostot/tarjoukset/".$domain."/".$t.".pdf"))
   				{

					if (!file_exists( Yii::app()->basePath.'/../tmp' )) {
					 	mkdir( Yii::app()->basePath.'/../tmp', 0777, true );
					}
					$rndm_str = $model->id.'_tarjous';
					$file = Yii::app()->basePath."/../tiedostot/tarjoukset/".$domain."/".$t.".pdf";
					$liite = Yii::app()->basePath.'/../tmp/'.$rndm_str.'.pdf';
	
					if (!copy($file, $liite)) {
					    	$this->_sendResponse(200, CJSON::encode('Copy error'));
						exit;
					} else {
						$pdf_link = Yii::app()->request->hostInfo .'/tmp/'.$rndm_str.'.pdf';
					}
				}
			}


			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
			";
			$m2 = CrmTarjoukset::model()->findAll($criteria);

			if(count($m2) > 0)
			{
			$lista = '<br><div class="lista">';
			foreach($m2 as $item)
			{
   				if(file_exists(Yii::app()->basePath."/../tiedostot/tarjoukset/".$domain."/".$item->liite.".pdf"))
   				{
					$lista .= '
					<div class="row link avaaPDF" liite="'.$item->liite.'">
					 <div class="col-sm-12">
					';
						$lista .= '<div class="alert bg-warning text-center"><h2>'.Yii::t('main', 'Tarjous').'</h2> '.date("d.m.Y H:i", strtotime($item->time)).'</div>';
					$lista .= '
					 </div>
					</div>
					';
				}

			}
			$lista .= '</div>';

				$return = array('lista'=>$lista, 'liite'=>$pdf_link, 'nimike'=>$nimike);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
				exit;
	}


	public function actionSopimukset($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			$liite = '';
			$pdf_link = '';
			$nimike = '';
			if(isset($_POST['liite']))
			{
				$nimike = $_POST['liite'];
				$t = $_POST['liite'];
   				if(file_exists(Yii::app()->basePath."/../tiedostot/sopimukset/".$domain."/".$t.".pdf"))
   				{
					if (!file_exists( Yii::app()->basePath.'/../tmp' )) {
					 	mkdir( Yii::app()->basePath.'/../tmp', 0777, true );
					}
					$rndm_str = $model->id.'_sopimus';
					$file = Yii::app()->basePath."/../tiedostot/sopimukset/".$domain."/".$t.".pdf";
					$liite = Yii::app()->basePath.'/../tmp/'.$rndm_str.'.pdf';
	
					if (!copy($file, $liite)) {
					    	$this->_sendResponse(200, CJSON::encode('Copy error'));
						exit;
					} else {
						$pdf_link = Yii::app()->request->hostInfo .'/tmp/'.$rndm_str.'.pdf';
					}
				}
			}


			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
			";
			$m2 = CrmSopimukset::model()->findAll($criteria);
			if(count($m2) > 0)
			{
			$lista = '<br><div class="lista">';
			foreach($m2 as $item)
			{
   				if(file_exists(Yii::app()->basePath."/../tiedostot/sopimukset/".$domain."/".$item->liite.".pdf"))
   				{
					$lista .= '
					<div class="row link avaaPDF" liite="'.$item->liite.'">
					 <div class="col-sm-12">
					';
						$lista .= '<div class="alert bg-warning text-center"><h2>'.Yii::t('main', 'Sopimus').'</h2> '.date("d.m.Y H:i", strtotime($item->time)).' '.$item->template.'</div>';
					$lista .= '
					 </div>
					</div>
					';
				}

			}
			$lista .= '</div>';

				$return = array('lista'=>$lista, 'liite'=>$pdf_link, 'nimike'=>$nimike);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
				exit;
	}


	public function actionTyonkuvaukset($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			$liite = '';
			$pdf_link = '';
			$nimike = '';
			if(isset($_POST['liite']))
			{
				$nimike = $_POST['liite'];
				$t = $_POST['liite'];

					if (!file_exists( Yii::app()->basePath.'/../tmp' )) {
					 	mkdir( Yii::app()->basePath.'/../tmp', 0777, true );
					}

			  		$tiedosto = $model->id.'_tyonkuvaus';
					$path = 'tmp';

					$tk = Tyonkuvaus::model()->findByPk($_POST['liite']);
					$k = Kohteet::model()->findByPk($tk->kohde_id);
					(isset($k->id))? $kohde = $k->osoite:$kohde = '';
	   				$tarjoukset = Yii::app()->createController('CrmTarjoukset');
	   				$html = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
					$html .= '<style>'.file_get_contents(Yii::app()->basePath.'/../css/raportit_table2.css').'</style>';
					$html .= '</head><body>';
					$html .= '<p><h2>'.Yii::t('main', 'Työnkuvaus').'</h2> '.date("d.m.Y H:i", strtotime($tk->time)).' '.$kohde.'</p><br>';
	   				$html .= $tarjoukset[0]->get_tyonkuvaus($_POST['liite']);
					$html .= '</body></html>';

					file_put_contents($path.'/'.$tiedosto.'.html', $html);
					exec('xvfb-run -a wkhtmltopdf --margin-bottom 10 --margin-top 10 '.$path.'/'.$tiedosto.'.html '.$path.'/'.$tiedosto.'.pdf', $output, $return); //--orientation Landscape --title "Titulo: do PDF"
					if($output)
					{
					    if (file_exists( Yii::app()->basePath.'/../'.$path.'/'.$tiedosto.'.pdf' ))
					    {
						$pdf_link = Yii::app()->request->hostInfo .'/'.$path.'/'.$tiedosto.'.pdf';
						unlink($path.'/'.$tiedosto.'.html');
					    }
					}

			}


			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
			";
			$m2 = Tyonkuvaus::model()->findAll($criteria);
			if(count($m2) > 0)
			{
			$lista = '<br><div class="lista">';
			foreach($m2 as $item)
			{
					$k = Kohteet::model()->findByPk($item->kohde_id);
					(isset($k->id))? $kohde = $k->osoite:$kohde = '';
					$lista .= '
					<div class="row link avaaPDF" liite="'.$item->id.'">
					 <div class="col-sm-12">
					';
						$lista .= '<div class="alert bg-warning text-center"><h2>'.Yii::t('main', 'Työnkuvaus').'</h2> '.date("d.m.Y H:i", strtotime($item->time)).' '.$kohde.'</div>';
					$lista .= '
					 </div>
					</div>
					';

			}
			$lista .= '</div>';

				$return = array('lista'=>$lista, 'liite'=>$pdf_link, 'nimike'=>$nimike);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			}

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
				exit;
	}




	public function actionMuuttiedostot($domain)
	{

		$return = '';
		if(isset($_POST['tunnus']) and $this->kirjautuminen($_POST['tunnus'], $_POST['salasana']) == true)
		{

		   $model=Asiakkaat::model()->findByPk($_POST['asiakasID']);
		   if(isset($model->id))
		   {

			$liite = '';
			$pdf_link = '';
			$nimike = '';
			if(isset($_POST['liite']))
			{

			}



			$lista = '<br><div class="lista">';
			foreach(array_reverse(glob('tiedostot/asiakkaat/'.$domain.'/'.$model->id.'_*.*')) as $file) 
			{
				$explNimi = explode("/",$file);

					$lista .= '
					<div class="row link avaaPDF" liite="'.$file.'">
					 <div class="col-sm-12">
					';
						$lista .= '<div class="alert bg-warning text-center"><h2>'.end($explNimi).'</div>';
					$lista .= '
					 </div>
					</div>
					';

			}
			$lista .= '</div>';

				$return = array('lista'=>$lista, 'liite'=>$pdf_link, 'nimike'=>$nimike);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;
			

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
				exit;
	}




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

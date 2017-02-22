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

				$return['loginOK'] = array('asiakasID'=>$model->id, 'asiakasNimi'=>$asiakasNimi, $_POST);
			}
		}

		$this->_sendResponse(200, CJSON::encode($return));

            break;
        default:
            // Model not implemented error
            $this->_sendResponse(501, sprintf(
                'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                $_GET['model']) );
            Yii::app()->end();
    }

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
						$mod->attributes=$_POST['VinkkiExtranet'];
						if($mod->save())
						{
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
					if(isset($_POST['PalautteetVastaus']['this_id']))
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

				$return .= $this->renderPartial('//asiakkaat/asiakas_historia', 
				array(
					'model'=>$model,
					$naytaMita=>true
				)
				, true);
				$this->_sendResponse(200, CJSON::encode($return));
				exit;

		   } // $model->id

		}

				$this->_sendResponse(200, CJSON::encode('Ei tuloksia'));
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

			$criteria=new CDbCriteria;
			$criteria->condition = " 
				asiakas_id='".$model->id."' 
			";
			$m2 = CrmTarjoukset::model()->findAll($criteria);
			$lista = '';
			foreach($m2 as $item)
			{
   				if(file_exists("tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$item->liite.".pdf"))
   				{
					$lista .= $item->liite;
				}
					$lista .= "tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$item->liite.".pdf";
			}

				$this->_sendResponse(200, CJSON::encode($lista));
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

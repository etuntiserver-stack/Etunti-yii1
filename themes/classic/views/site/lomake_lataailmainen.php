<?php
error_reporting(E_ALL ^ ( E_NOTICE | E_WARNING | E_DEPRECATED | E_STRICT));



class lomake_ilmainen {

var $kentat = array();
var $asetukset = array();
var $print_form = TRUE;

function __construct($formkentat, $formasetukset) {
	$this->kentat = $formkentat;
	$this->asetukset = $formasetukset;
}

public function kasittely()
{

	$vajaa = FALSE;
	$error = FALSE;
	$sendnimi = "{$this->asetukset['nimi']}_sendForm";
	if (isset($_POST[$sendnimi])) {

		foreach ($this->kentat as $key => $value){
			if($value['TYPE'] != 'submit'){
				$posti = $_POST['lomake'][$key];

				//tietoturvaa
				$posti = str_replace ( '<', '', $posti );
				$posti = str_replace ( '>', '', $posti );
				$posti = str_replace ( '\\', '', $posti );
				/* poistaa tagit esim <b> */
				$posti = strip_tags($posti);
				/* estää &auml; &lt; yms. */
				if (preg_match( "/^([&])+([#a-zA-Z0-9])+([;])*$/", $posti )){
				$vajaa = TRUE;
				}

				/* Oikea email */
				if ($this->kentat[$key]['TYPE']== 'email'){
					if(!preg_match( "/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/", $posti )) {
					$vajaa = TRUE;
					}
				}
				/* numerofield */
				if ($this->kentat[$key]['TYPE']== 'number'){
					if(!preg_match( "/^([^a-zA-Z])*$/", $posti )) {
					$vajaa = TRUE;
					}
				}
				/* pakollinen */
				if ($this->kentat[$key]['REQUIRED'] == true){
					if( empty($posti)) {
						$vajaa = TRUE;
					}
				}

				$this->kentat[$key]['VALUE'] = $posti; //yhdistää arrayt
			}
		}


		if ($vajaa != TRUE){
		$this->print_form = FALSE;
		$this->email();
		$this->csv_save();
		//echo "<div class='kiitos'><h2>{$this->asetukset['kiitos']}</h2></div>";

		} else {

			//echo "<div class='huom'>{$this->asetukset['error']}</div>";

		$this->print_form = FALSE;
		$this->email();
		$this->csv_save();
		//echo "<div class='kiitos'><h2>{$this->asetukset['kiitos']}</h2></div>";


		}
	}

}


public function csv_save()
{
	$osoiteluettelo = "";
	foreach ($this->kentat as $key => $value) {
		$osoiteluettelo .= ";".$this->kentat[$key]['VALUE'];
	}
	$osoiteluettelo = substr($osoiteluettelo, 1)."\r\n";

	$filenimi = "lomake_{$this->asetukset['nimi']}.csv";
	if (file_exists($filenimi)) {
		$fp = fopen($filenimi, 'a');
		fwrite($fp, $osoiteluettelo);
		fclose($fp);
	}
}

public function email()
{

	foreach ($this->asetukset['email'] as $key1 => $value1) {
		
		$headers = "From: {$value1['lahettaja']}\r\n" .
			    "Reply-To: {$value1['lahettaja']}" . "\r\n";
		
		//$headers .= "BCC: {$value1['kopio']}\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8\r\n";
		$headers .= "Content-language: FI\r\n";
		

		$style1 = "color:#3399CC; font-family: Arial,Helvetica; font-size:15px; line-height:22px";
		$style2 = "color:#000; font-family: Arial,Helvetica; font-size:15px; line-height:22px";
		$viesti = "
		<html>
		  <body>
		  <table style='padding-bottom:20px; padding-top:20px;' border='0' cellspacing='0' cellpadding='5'>\r\n";
		  $kentat = "";
		  $otsikko = $value1['otsikko'];
		  $viesti_kentat = "<tr><td valign='top'>{$value1['viesti']}</td></tr>\r\n";
		  foreach ($this->kentat as $key => $value) {
		  	if($this->kentat[$key]['TYPE'] != 'submit'){
				$kentat .= "<tr><td valign='top' align='right' style='$style1'>{$this->kentat[$key]['NAME']}</td> <td valign='top' style='$style2'>{$this->kentat[$key]['VALUE']}</td> </tr>\r\n";
				$nimi = "[{$this->kentat[$key]['NAME']}]";
				$viesti_kentat = str_replace($nimi, $this->kentat[$key]['VALUE'], $viesti_kentat);
				$otsikko = str_replace($nimi, $this->kentat[$key]['VALUE'], $otsikko);
		  	}
		  }
		  $kentat = "<table style='padding-bottom:20px; padding-top:20px;' border='0' cellspacing='0' cellpadding='5'>\r\n$kentat</table>";
		  $viesti_kentat = str_replace('[kentat]', $kentat, $viesti_kentat);

		$viesti .= $viesti_kentat;
		$viesti .= "</table>
		  </body>
		</html>";
		if(!empty($value1['osoite'])){
			mail ($value1['osoite'], $otsikko, $viesti, $headers);
		} else {
			echo "<h3>$otsikko</h3>";
			echo $viesti;
		}


		if(isset($this->kentat[2]['VALUE']) and !empty($this->kentat[2]['VALUE']))
		{

			$liite = Yii::app()->request->baseUrl."ylapalkki/Menestyvan_yrityksen_opas.pdf";

			$message = Yii::t('main', 'lataaIlmainenMessage');
			$mail = new YiiMailer();
			$mail->setFrom('info@etunti.fi', 'ETUNTI.FI');
			$mail->setTo($this->kentat[2]['VALUE']);
			$mail->setSubject(Yii::t('main', 'lataaIlmainenOtsikko'));
			$mail->setBody($message);
			$mail->setAttachment($liite);
			$mail->send();
		}

	}
}

public function form()
{
	$this->kasittely();
	print "<div id=\"{$this->asetukset['nimi']}_container\">";
	$this->tulosta_kentat();
	print "</div>";
}

public function tulosta_kentat()
{
	if ($this->print_form != FALSE) {

	$error = isset($this->asetukset['error']) ? $this->asetukset['error'] : "";
	$FORM_class = isset($this->asetukset['FORM_CLASS']) ? $this->asetukset['FORM_CLASS'] : "";
	$ajax = isset($this->asetukset['AJAX']) ? $this->asetukset['AJAX'] : FALSE;
	if ($ajax == TRUE) {
		$action = str_replace($_SERVER["DOCUMENT_ROOT"],'', $this->asetukset['URL'] );
		$FORM_class .= " ajax";
	}else{
		$action = "#{$this->asetukset['nimi']}_container";
	}


	print "<form method=\"post\" name=\"lomake\" action=\"".Yii::app()->request->baseUrl."/index.php/site/lomake_lataailmainen\" id=\"{$this->asetukset['nimi']}\" class=\"lomake $FORM_class\">";
	foreach ($this->kentat as $key => $value) {
			//yleiset
			$LABEL_class = isset($this->asetukset['LABEL_CLASS']) ? $this->asetukset['LABEL_CLASS'] : "";
			$FIELD_class = isset($this->asetukset['FIELD_CLASS']) ? $this->asetukset['FIELD_CLASS'] : "";
			$GROUP_class = isset($this->asetukset['GROUP_CLASS']) ? $this->asetukset['GROUP_CLASS'] : "";

			$required = ($value['REQUIRED'] == true) ? "required" : "";
			$asterix = ($value['REQUIRED'] == true) ? "<sup class='req'>*</sup>" : "";
			$maxlength = isset($value['MAXLENGTH']) ? "maxlength=\"{$value['MAXLENGTH']}\"" : "";
			$type = $value['TYPE'];
			$placeholder = isset($value['PLACEHOLDER']) ? "placeholder=\"{$value['PLACEHOLDER']}\"" : "";
			$INPUT_class = isset($value['INPUT_CLASS']) ? $value['INPUT_CLASS'] : "";

			//ylikirjoitetaan, jos kentässä on omat määritykset
			$GROUP_class = isset($value['GROUP_CLASS']) ? $value['GROUP_CLASS'] : $GROUP_class;
			$LABEL_class = isset($value['LABEL_CLASS']) ? $value['LABEL_CLASS'] : $LABEL_class;
			$FIELD_class = isset($value['FIELD_CLASS']) ? $value['FIELD_CLASS'] : $FIELD_class;

		  	print "<input type=\"hidden\" name=\"{$this->asetukset['nimi']}_sendForm\" value=\"1\">";
			switch ($value['TYPE']) {

				case 'textarea':
					print "<div class=\"$GROUP_class\"><label class=\"$LABEL_class\">{$value['NAME']}$asterix</label><div class=\"$FIELD_class\"><textarea class='txtareabox $required $INPUT_class' name='lomake[$key]' rows='4' cols='40'>{$value['VALUE']}</textarea></div></div>";
					break;

				case 'checkbox':
					print "<div class=\"$GROUP_class\"><label class=\"$LABEL_class\">{$value['NAME']}$asterix</label><div class=\"$FIELD_class\"><input type='checkbox' class='$required $type $INPUT_class' name='lomake[$key]' value='{$value['VALUE']}'></div></div>";
					break;

				case 'radio':
					print "<div class=\"$GROUP_class\"><label class=\"$LABEL_class\">{$value['NAME']}$asterix</label><div class=\"$FIELD_class\"><div class=\"$required $type\">";
					foreach ($value['VALUE'] as $keys => $values) {
						print "<input type='radio' class='$INPUT_class' name='lomake[$key]' value='$values'>$values ";
					}
					print "</div></div></div>";
					break;

				case 'submit':
					print "<div class=\"$FIELD_class\"><input type=\"submit\" name=\"submit\" value=\"{$value['VALUE']}\" class=\"button $INPUT_class\"></div>";
					break;

				case 'hidden':
					print "<input type=\"hidden\" class=\"$FIELD_class\" name=\"lomake[$key]\" value=\"{$value['VALUE']}\">";
					break;

				case 'select':
					print "<div class=\"$GROUP_class\"><label class=\"$LABEL_class\">{$value['NAME']}$asterix</label><div class=\"$FIELD_class\"><select class='selectbox $required $type $INPUT_class' name='lomake[$key]' >";

		    if($value['NAME'] != 'Tilaan uutiskirjeen')
                    print "<option value=\"\">-- Valitse --</option>";

                    foreach ($value['OPTIONS'] as $value) {
                    	print "<option value=\"$value\">$value</option>";
	                }
	                print "</select></div></div>";
					break;

				default:
					print "<div class=\"$GROUP_class\"><label class=\"$LABEL_class\">{$value['NAME']}$asterix</label><div class=\"$FIELD_class\"><input type='text' $maxlength class='txtbox $required $type $INPUT_class' name='lomake[$key]' value='{$value['VALUE']}' size='30' $placeholder></div></div>";
					break;
			}

		  }
		print "<div style=\"clear:both;\"></div>
		<p style=\"display:none;\" class=\"huom lomake\">$error</p>
		</form>";
	}
}

}









$asetukset = array(
    'nimi' => 'tarjouspyynto',
    'kiitos' => 'Kiitos',
    'error' => 'Huom! Tarkista, että täytit kaikki kentät oikein.',
    'AJAX' => TRUE,
    'LABEL_CLASS' => 'col-sm-5 control-label',
    'FIELD_CLASS' => 'col-sm-7',
    'GROUP_CLASS' => 'form-group',
    'FORM_CLASS' => 'form-horizontal',
    'URL' => __FILE__,
);
$asetukset['email'][] = array(
    'osoite' => 'info@etunti.fi',//veiko.poldkivi@etunti.fi
    'kopio' => '',
    'otsikko' => 'Oppaan lataus',
    'lahettaja' => 'info@etunti.fi',
    'viesti' => '[kentat]',
);
// $asetukset['email'][] = array(
//     'osoite' => '',
//     'kopio' => '',
//     'otsikko' => '',
//     'lahettaja' => '',
//     'viesti' => 'Nimi2: [Nimi]
//     ',
// );
$kentat = array();
/*
$kentat[] = array(
'NAME' => 'Modulit',
'TYPE' => 'hidden',
'FIELD_CLASS' => 'valitut_modulit',
);
*/
$kentat[] = array(
'NAME' => 'Nimi',
'REQUIRED' => true,
'TYPE' => 'text',
);
$kentat[] = array(
'NAME' => 'Yritys',
'REQUIRED' => true,
'TYPE' => 'text',
);
$kentat[] = array(
'NAME' => 'Sähköposti',
'REQUIRED' => true,
'TYPE' => 'email',
);
$kentat[] = array(
'NAME' => 'Puhelin',
'REQUIRED' => true,
'TYPE' => 'text',
);
$kentat[] = array(
'NAME' => 'Yrityksen toimiala',
'REQUIRED' => true,
'TYPE' => 'text',
'PLACEHOLDER' => '',
);
$kentat[] = array(
'NAME' => 'Työntekijöiden määrä',
'REQUIRED' => true,
'TYPE' => 'text',
);
$kentat[] = array(
'NAME' => 'Liikevaihto vuodessa',
'REQUIRED' => true,
'TYPE' => 'select',
'OPTIONS' => array("alle 500 000 €", "500 000 - 800 000 €", "800 000 - 1 000 000 €", "1 000 000 - 1 500 000 €", "1 500 000 € - 2 000 000 €", "Yli 2 000 000 €"),
);
$kentat[] = array(
'NAME' => 'Tilaan uutiskirjeen',
'REQUIRED' => true,
'TYPE' => 'select',
'OPTIONS' => array("Kyllä", "Ei"),
);

$kentat[] = array(
'VALUE' => 'Lähetä',
'TYPE' => 'submit',
'FIELD_CLASS' => 'col-sm-offset-9 col-sm-3',
'INPUT_CLASS' => 'btn btn-primary btn-block',
);

$tarjouspyynto = new lomake_ilmainen($kentat, $asetukset);
$tarjouspyynto->form();

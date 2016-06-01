<?php



/*
// Impostiamo il livello di errori da visualizzare
error_reporting (E_ALL|E_STRICT);

// Disattiviamo la cache WSDL
ini_set ('soap.wsdl_cache_enabled', 0);

// definiamo le credenziali scelte al momento della registrazione
define ('USERNAME', 'etunti');
define ('PASSWORD', 'Estrom2016!');

// SOAP WSDL endpoint
define ('ENDPOINT', 'https://api.livedocx.com/1.2/mailmerge.asmx?WSDL');

// Definiamo il timezone locale
date_default_timezone_set('Europe/Helsinki');

// Instanziamo l'oggetto SOAP e gli passiamo le credenziali sotto forma di array
$soap = new SoapClient(ENDPOINT);
$soap->LogIn(
array(
'username' => USERNAME,
'password' => PASSWORD
)
);

// Upload del file DOCX da convertire
$path = dirname(__FILE__) .'/../'.$_POST['polkku'];
$file_docx = $path.'.docx';
$data = file_get_contents($file_docx);
$soap->SetLocalTemplate(
array(
'template' => base64_encode($data),
'format' => 'docx'
  )
);

//Impostiamo il formato di output che vogliamo (pdf in questo caso) 
$result = $soap->RetrieveDocument(
array(
'format' => 'pdf'
  )
);
$data = $result->RetrieveDocumentResult;

print_r($soap);

//Impostazione e salvataggio del file PDF
$file_PDF = $path.'.pdf';
file_put_contents($file_PDF, base64_decode($data));

// Logout
$soap->LogOut();
unset($soap);



/*
require_once 'PhpWord/PhpWord.php';
require_once 'PhpWord/Autoloader.php';
require_once 'PhpWord/Common/XMLReader.php';
require_once 'PhpWord/Common/XMLWriter.php';
require_once 'PhpWord/Common/Text.php';

require_once 'PhpWord/Common/TranslatorAwareInterface.php';
require_once 'PhpWord/Common/ValidatorInterface.php';
require_once 'PhpWord/Common/AbstractValidator.php';
require_once 'PhpWord/Common/InArray.php';

PHPWord_Autoloader::register();

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$rendererName = \PhpOffice\PhpWord\Settings::PDF_RENDERER_TCPDF;
$rendererLibrary = 'tcpdf.php';
$rendererLibraryPath = dirname(__FILE__) .'/plugins/tcpdf/' . $rendererLibrary;
\PhpOffice\PhpWord\Settings::setPdfRenderer($rendererName,$rendererLibraryPath);

/*
$rendererName = \PhpOffice\PhpWord\Settings::PDF_RENDERER_MPDF;
$rendererLibrary = 'mpdf.php';
$rendererLibraryPath = dirname(__FILE__) .'/plugins/mpdf/' . $rendererLibrary;
\PhpOffice\PhpWord\Settings::setPdfRenderer($rendererName,$rendererLibraryPath);
*/
/* ei toimi
$rendererName = \PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF;
$rendererLibrary = 'dompdf.php';
$rendererLibraryPath = dirname(__FILE__) .'/plugins/dompdf/' . $rendererLibrary;
\PhpOffice\PhpWord\Settings::setPdfRenderer($rendererName,$rendererLibraryPath);
*/

/*
$path = dirname(__FILE__) .'/../'.$_POST['polkku'];
$phpWord = \PhpOffice\PhpWord\IOFactory::load($path.'.docx'); 
$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
$xmlWriter->save($path.'.pdf');  
*/
?>

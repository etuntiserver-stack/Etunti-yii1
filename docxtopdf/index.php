<?php


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


$path = dirname(__FILE__) .'/../'.$_POST['polkku'];
$phpWord = \PhpOffice\PhpWord\IOFactory::load($path.'.docx'); 
$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
$xmlWriter->save($path.'.pdf');  

?>

<?php

require_once 'PhpWord\PhpWord.php';
require_once 'PhpWord\Autoloader.php';
require_once 'PhpWord\Common\XMLReader.php';
require_once 'PhpWord\Common\XMLWriter.php';
require_once 'PhpWord\Common\Text.php';
PHPWord_Autoloader::register();


$phpWord = new \PhpOffice\PhpWord\PhpWord();

$rendererName = \PhpOffice\PhpWord\Settings::PDF_RENDERER_TCPDF;
$rendererLibrary = 'tcpdf.php';
$rendererLibraryPath = dirname(__FILE__) .'/plugins/tcpdf/' . $rendererLibrary;

\PhpOffice\PhpWord\Settings::setPdfRenderer($rendererName,$rendererLibraryPath);

$path = '../'.$_POST['polkku'];
$phpWord = \PhpOffice\PhpWord\IOFactory::load($path.'.docx'); 
$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
$xmlWriter->save($path.'.pdf');  


?>

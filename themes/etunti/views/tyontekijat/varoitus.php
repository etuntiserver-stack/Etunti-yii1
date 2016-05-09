<?php

Yii::import('ext.yiiword.YiiWord', true);
Yii::registerAutoloader(array('YiiWord', 'autoload'), true);

// Create a new PHPWord Object
$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('tiedostot/Template.docx');
$document->setValue('etunimi', 'paska');
//$document->setValue('Street', 'osoite joko');
$document->save('tiedostot/tulos.docx');
?>


<?php

Yii::import('ext.yiiword.YiiWord', true);
Yii::registerAutoloader(array('YiiWord', 'autoload'), true);

// Create a new PHPWord Object
$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('Template.docx');
$document->setValue('Name', 'paska');
//$document->setValue('Street', 'osoite joko');
$document->save('tulos.docx');
?>


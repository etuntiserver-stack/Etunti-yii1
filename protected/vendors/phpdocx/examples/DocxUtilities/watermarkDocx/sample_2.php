<?php
require_once '../../../classes/DocxUtilities.inc';

$docx = new DocxUtilities();
$source = '../../files/Text.docx';
$target = 'example_watermarkText.docx';
$docx->watermarkDocx($source, $target, $type = 'text', $options = array('text' => 'PHPDocX'));
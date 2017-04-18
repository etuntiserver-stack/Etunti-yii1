<?php
require_once '../../../classes/DocxUtilities.inc';

$docx = new DocxUtilities();
$source = '../../files/Text.docx';
$target = 'example_watermarkImage.docx';
$docx->watermarkDocx($source, $target, $type = 'image', $options = array('image' => '../../files/image.png', 'decolorate' => false));
<?php

require_once '../../../classes/DocxUtilities.inc';

$docx = new DocxUtilities();
$source = '../../files/Watermark.docx';
$target = 'example_unwatermark.docx';
$docx->watermarkRemove($source, $target);
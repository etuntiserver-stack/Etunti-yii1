<?php

require_once '../../../classes/DocxUtilities.inc';

$docx = new DocxUtilities();
$values = array (0,1,1);
$docx->parseCheckboxes('../../files/Checkbox.docx', 'example_parsecheckboxes.docx', $values);
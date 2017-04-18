<?php

require_once '../../../classes/DocxUtilities.inc';

$newDocx = new DocxUtilities();
$newDocx->setLineNumbering('../../files/second.docx', 'example_setLineNumbering.docx', array('start' => 25));
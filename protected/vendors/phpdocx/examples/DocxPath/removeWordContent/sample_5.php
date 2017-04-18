<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/headings.docx');

$referenceNode = array(
    'type' => 'paragraph',
    'ocurrence' => 1,
    'attributes' => array('w:outlineLvl' => array('w:val' => 2)),
);

$docx->removeWordContent($referenceNode);

$docx->createDocx('example_removeWordContent_5');
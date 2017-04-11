<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/sections.docx');

$referenceNode = array(
    'type' => 'section',
);

$docx->removeWordContent($referenceNode);

$docx->createDocx('example_removeWordContent_8');
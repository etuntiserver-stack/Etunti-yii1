<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/lists.docx');

$referenceNode = array(
    'type' => 'paragraph',
    'ocurrence' => -1,
);

$docx->removeWordContent($referenceNode);

$docx->createDocx('example_removeWordContent_7');
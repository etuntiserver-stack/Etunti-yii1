<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/links.docx');

$referenceNode = array(
    'type' => 'paragraph',
    'ocurrence' => 2,
    'contains' => 'HYPERLINK',
);

$docx->removeWordContent($referenceNode);

$docx->createDocx('example_removeWordContent_6');
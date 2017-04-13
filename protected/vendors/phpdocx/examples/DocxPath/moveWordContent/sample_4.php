<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/sections.docx');

$referenceNodeFrom = array(
    'type' => 'paragraph',
    'ocurrence' => 3,
    'contains' => 'Lorem ipsum',
);

$referenceNodeTo = array(
    'type' => 'section',
    'ocurrence' => 1,
);

$docx->moveWordContent($referenceNodeFrom, $referenceNodeTo, 'before');

$docx->createDocx('example_moveWordContent_4');
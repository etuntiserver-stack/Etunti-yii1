<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/sections.docx');

$referenceToBeCloned = array(
    'type' => 'paragraph',
    'ocurrence' => 2,
    'contains' => 'Lorem ipsum',
);

$referenceNodeTo = array(
    'type' => 'section',
    'ocurrence' => 1,
);

$docx->cloneWordContent($referenceToBeCloned, $referenceNodeTo, 'before');

$docx->createDocx('example_cloneWordContent_4');
<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/tables.docx');

$referenceToBeCloned = array(
    'type' => 'paragraph',
    'parent' => '/w:tc/',
    'ocurrence' => 4,
);

$referenceNodeTo = array(
    'type' => 'paragraph',
    'parent' => '/w:tc/',
    'ocurrence' => 8,
);

$docx->cloneWordContent($referenceToBeCloned, $referenceNodeTo, 'after');

$docx->createDocx('example_cloneWordContent_5');
<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/links.docx');

$referenceNodeFrom = array(
    'type' => 'paragraph',
    'ocurrence' => 1,
    'contains' => 'HYPERLINK',
);

$referenceNodeTo = array(
    'type' => 'paragraph',
    'ocurrence' => 2,
    'contains' => 'HYPERLINK',
);

$docx->moveWordContent($referenceNodeFrom, $referenceNodeTo, 'after');

$docx->createDocx('example_moveWordContent_3');
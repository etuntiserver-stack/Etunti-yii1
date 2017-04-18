<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/DOCXPathTemplate.docx');

$referenceNodeFrom = array(
    'type' => 'table',
    'ocurrence' => 1,
);

$referenceNodeTo = array(
    'type' => 'chart',
    'ocurrence' => 1,
);

$docx->moveWordContent($referenceNodeFrom, $referenceNodeTo, 'before');

$docx->createDocx('example_moveWordContent_6');
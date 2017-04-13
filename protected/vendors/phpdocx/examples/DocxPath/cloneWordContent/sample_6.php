<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/DOCXPathTemplate.docx');

$referenceToBeCloned = array(
    'type' => 'table',
    'ocurrence' => 1,
);

$referenceNodeTo = array(
    'type' => 'chart',
    'ocurrence' => 1,
);

$docx->cloneWordContent($referenceToBeCloned, $referenceNodeTo, 'before');

$docx->createDocx('example_cloneWordContent_6');
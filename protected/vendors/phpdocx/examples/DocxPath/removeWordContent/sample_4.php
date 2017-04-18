<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/charts.docx');

$referenceNode = array(
    'type' => 'chart',
    'ocurrence' => 2,
);

$docx->removeWordContent($referenceNode);

$docx->createDocx('example_removeWordContent_4');
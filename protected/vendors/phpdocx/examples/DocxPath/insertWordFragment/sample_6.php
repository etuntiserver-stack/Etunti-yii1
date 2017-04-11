<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/sections.docx');

$contentA = new WordFragment($docx, 'document');
$contentA->addText('New text at the beginning');
$referenceNode = array(
    'type' => '*',
    'ocurrence' => 1,
);
$docx->insertWordFragment($contentA, $referenceNode, 'before');

$contentB = new WordFragment($docx, 'document');
$contentB->addText('New text second page');
$referenceNode = array(
    'type' => 'section',
    'ocurrence' => 1,
);
$docx->insertWordFragment($contentB, $referenceNode, 'after');

$contentC = new WordFragment($docx, 'document');
$contentC->addText('New text first page');
$referenceNode = array(
    'type' => 'section',
    'ocurrence' => 1,
);
$docx->insertWordFragment($contentC, $referenceNode, 'before', true);

$contentD = new WordFragment($docx, 'document');
$contentD->addText('New text at the end');
$referenceNode = array(
    'type' => '*',
    'ocurrence' => -1,
);
$docx->insertWordFragment($contentD, $referenceNode, 'after');

$docx->createDocx('example_insertWordFragment_6');
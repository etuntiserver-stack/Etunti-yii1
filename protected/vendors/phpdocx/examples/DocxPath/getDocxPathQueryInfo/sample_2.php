<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/DOCXPathTemplate.docx');

$referenceNode = array(
    'type' => 'paragraph',
    'contains' => 'heading',
);

$queryInfo = $docx->getDocxPathQueryInfo($referenceNode);

for ($i = 1; $i <= $queryInfo['length']; $i++) {
    $content = new WordFragment($docx, 'document');

    $referenceNode = array(
        'type' => 'paragraph',
        'contains' => 'heading',
        'ocurrence' => $i,
    );

    $content->addText('New text', array('sz' => 18));

    $docx->insertWordFragment($content, $referenceNode, 'after');
}

$docx->createDocx('example_getDocxPathQueryInfo_2');
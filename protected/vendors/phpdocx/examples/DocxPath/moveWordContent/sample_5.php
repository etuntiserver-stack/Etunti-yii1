<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/tables.docx');

$referenceNodeFrom = array(
    'type' => 'paragraph',
    'parent' => '/w:tc/',
    'ocurrence' => 4,
);

$referenceNodeTo = array(
    'type' => 'paragraph',
    'parent' => '/w:tc/',
    'ocurrence' => 8,
);

$docx->moveWordContent($referenceNodeFrom, $referenceNodeTo, 'after');

$content = new WordFragment($docx, 'document');

$content->addText('New text to avoid empty cell');

$referenceNode = array(
    'parent' => '/w:tc/',
    'ocurrence' => 7,
);

$docx->insertWordFragment($content, $referenceNode, 'after');

$docx->createDocx('example_moveWordContent_5');
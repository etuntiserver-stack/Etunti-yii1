<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/docxpath/links.docx');

$content = new WordFragment($docx, 'document');

$content->addText('New text');
$content->addImage(array('src' => '../../img/image.png' , 'scaling' => 50));

$referenceNode = array(
	'type' => 'paragraph',
    'ocurrence' => 2,
    'contains' => 'HYPERLINK',
);

$docx->insertWordFragment($content, $referenceNode, 'before');

$docx->createDocx('example_insertWordFragment_4');
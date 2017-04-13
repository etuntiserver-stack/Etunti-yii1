<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocxFromTemplate('../../files/DOCXPathTemplate.docx');

$content = new WordFragment($docx, 'document');
$content->addText('New text');
$content->addImage(array('src' => '../../img/image.png' , 'scaling' => 50));
$valuesTable = array(
    array(
        'AAA',
        'BBB',
    ),
    array(
        'Text',
        'Text: More text',
    ),

);
$paramsTable = array(
    'border' => 'single',
    'tableAlign' => 'center',
    'borderWidth' => 10,
    'borderColor' => 'B70000',
    'textProperties' => array('bold' => true),
);
$content->addTable($valuesTable, $paramsTable);
$data = array(
    'legend' => array('Series 1', 'Series 2', 'Series 3'),
    'data 1' => array(10, 7, 5),
    'data 2' => array(20, 60, 3),
    'data 3' => array(50, 33, 7),
    'data 4' => array(25, 0, 14)
);
$paramsChart = array(
    'data' => $data,
    'type' => 'pie3DChart',
    'rotX' => 20,
    'rotY' => 20,
    'perspective' => 30,
    'color' => 2,
    'sizeX' => 10,
    'sizeY' => 5,
    'chartAlign' => 'center',
    'showPercent' => 1,
);
$content->addChart($paramsChart);
$linkOptions = array('url'=> 'http://www.google.es', 'color' => 'B70000', 'underline' => 'none');
$content->addLink('Link to Google in red color and not underlined', $linkOptions);
$itemList = array(
    'Line 1',
    'Line 2',
    'Line 3',
    'Line 4',
    'Line 5'
);
$content->addList($itemList, 1);

$html = '<h1 style="color: #b70000">An embedHTML() example.</h1>';
$content->embedHTML($html);

$referenceNode = array(
    'type' => 'paragraph',
    'ocurrence' => 1,
    'contains' => 'Another heading',
);

$docx->insertWordFragment($content, $referenceNode, 'after');

$docx->createDocx('example_insertWordFragment_9');
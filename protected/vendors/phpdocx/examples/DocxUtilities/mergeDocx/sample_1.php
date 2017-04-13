<?php

require_once '../../../classes/MultiMerge.inc';

$merge = new MultiMerge();
$merge->mergeDocx('../../files/Text.docx', array('../../files/second.docx', '../../files/SimpleExample.docx'), 'example_merge_docx.docx', array());
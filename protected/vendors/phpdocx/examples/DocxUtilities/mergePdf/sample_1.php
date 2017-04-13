<?php

require_once '../../../classes/MultiMerge.inc';

$merge = new MultiMerge();
$merge->mergePdf(array('../../files/Test.pdf', '../../files/Test2.pdf', '../../files/Test3.pdf'), 'example_merge_pdf.pdf');
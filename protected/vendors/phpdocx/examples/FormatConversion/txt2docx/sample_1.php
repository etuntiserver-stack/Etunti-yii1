<?php

require_once '../../../classes/CreateDocx.inc';

$docx = new CreateDocx();

$docx->txt2docx('../../files/Text.txt');

$docx->createDocx('example_txt2docx');
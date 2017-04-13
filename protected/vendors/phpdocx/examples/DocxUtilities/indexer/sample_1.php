<?php

require_once '../../../classes/Indexer.inc';

$indexer = new Indexer('../../files/indexer.docx');
$output = $indexer->getOutput();

print_r('body: ');
print_r($output['body']);

print_r('comments: ');
print_r($output['comments']);

print_r('endnotes: ');
print_r($output['endnotes']);

print_r('footers: ');
print_r($output['footers']);

print_r('footnotes: ');
print_r($output['footnotes']);

print_r('headers: ');
print_r($output['headers']);

//print_r('images: ');
//print_r($output['images']);

print_r('links: ');
print_r($output['links']);
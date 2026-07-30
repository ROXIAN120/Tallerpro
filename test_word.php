<?php
require_once __DIR__ . '/vendor/autoload.php';
$pw = new \PhpOffice\PhpWord\PhpWord();
$s = $pw->addSection();
$s->addText('Test documento');
$w = \PhpOffice\PhpWord\IOFactory::createWriter($pw, 'Word2007');
$w->save(__DIR__ . '/test_word.docx');
echo "OK - Size: " . filesize(__DIR__ . '/test_word.docx') . " bytes\n";

<?php
// We'll be outputting a PDF
header('Content-type: text/plain');

// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="aba_'.$_GET['aba'].'.txt"');

// The PDF source is in original.pdf
$filename="generated/aba_".$_GET['aba'].".txt";
readfile($filename);
?> 
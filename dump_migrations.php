<?php
$files = glob(__DIR__.'/database/migrations/*.php');
foreach($files as $f) {
    echo "--- " . basename($f) . " ---\n";
    echo file_get_contents($f) . "\n\n";
}

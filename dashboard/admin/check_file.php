<?php
$file_path = '/path/to/uploads/57/documents/FINALS_NET.doc';
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $file_path)) {
    echo "File exists: " . $_SERVER['DOCUMENT_ROOT'] . $file_path;
} else {
    echo "File does not exist: " . $_SERVER['DOCUMENT_ROOT'] . $file_path;
}
?>

<?php
// index.php

if (isset($_GET['file'])) {
    $filename = $_GET['file'];
    $filepath = "" . $filename;

    if (file_exists($filepath)) {
        echo "<pre>";
        echo htmlspecialchars(file_get_contents($filepath));
        echo "</pre>";
    } else {
        echo "File not found.";
    }
} else {
    echo "Try to find out the flag and Please provide a file parameter. Example: ?file=hello.txt";
}
?>
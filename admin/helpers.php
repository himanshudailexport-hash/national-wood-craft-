<?php
// helpers.php

function slugify($text) {
    // simple slug creation
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'n-a';
}

function unique_filename($original, $upload_dir) {
    $ext = pathinfo($original, PATHINFO_EXTENSION);
    $base = pathinfo($original, PATHINFO_FILENAME);
    $base = preg_replace('/[^A-Za-z0-9\-]/', '-', $base);
    $name = $base . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    return $name;
}

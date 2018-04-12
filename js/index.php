<?php
$requested_file = str_replace('/js/', '', $_SERVER['REQUEST_URI']);

if(strpos($requested_file, ',') !== false) {
    $requested_files = explode(',', $requested_file);
    $contents = [];
    $one_exist = false;
    foreach($requested_files as $requested_file) {
        $content = getFileContent($requested_file);
        $contents[$requested_file] = $content;
        if(!$one_exist && $content !== false) {
            $one_exist = true;
        }
    }
    if(!$one_exist) {
        set404();
    } else {
        header('Content-Type: application/javascript');
        foreach($contents as $filename => $content) {
            if($content !== false) {
                echo $content;
            } else {
                echo ' console.error("Requested file \'' . $filename . '\' was not found!"); ';
            }
        }
        exit;
    }
} else {
    $content = getFileContent($requested_file);
    if($content !== false) {
        header('Content-Type: application/javascript');
        echo $content;
    } else {
        set404();
    }
}

function getFileContent($filename) {
    if(file_exists(__DIR__ . '/' . $filename)) {
        return file_get_contents($filename);
    }

    return false;
}

function set404() {
    http_response_code(404);
    exit;
}
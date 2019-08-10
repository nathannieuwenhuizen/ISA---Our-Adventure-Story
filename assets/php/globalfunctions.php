<?php

function removeScriptTags($data) {
    return  strip_tags($data, '<p><h1><h2><h3><h4><h5><a><b><i>');
}
function removeAllTags($data) {
    return  strip_tags($data);
}

function GetIntervalRounded ($date) {
    $now = new DateTime();
    $current = new DateTime($date);
    $dif = date_diff($now, $current);
    $result =  $result = $dif->format('%s') . " seconds";

    if ($dif->format('%i') != 0) {
        $result = $dif->format('%i') . " minutes";
    }
    if ($dif->format('%h') != 0) {
        $result = $dif->format('%h') . " hours";
    }
    if ($dif->format('%d') != 0) {
        $result = $dif->format('%d') . " days";
    }
    if ($dif->format('%m') != 0) {
        $result = $dif->format('%m') . " months";
    }
    if ($dif->format('%y') != 0) {
        $result = $dif->format('%y') . " months";
    }
    return $result;
}
?>
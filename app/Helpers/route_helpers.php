<?php

function includeRouteFiles($directory) {
    $routeFiles = glob($directory . '/*.php');
    foreach ($routeFiles as $routeFile) {
        require_once $routeFile;
    }
    $subdirectories = glob($directory . '/*', GLOB_ONLYDIR);
    foreach ($subdirectories as $subdirectory) {
        includeRouteFiles($subdirectory);
    }
}
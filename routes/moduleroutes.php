<?php
    $moduleRoutes = glob(__DIR__.'/**/*.php');
    foreach ($moduleRoutes as $routeFile) {
        require $routeFile;
    }
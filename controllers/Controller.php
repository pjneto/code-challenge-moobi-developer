<?php

abstract class Controller {

    public static function base_url(string $path): string {
        $size = sizeof($_GET);
        return $size === 3 ? "../../"
                : ($size === 2 ? "../" : "") . $path; 
    }
}
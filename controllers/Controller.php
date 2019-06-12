<?php

require_once "utils/Consts.php";

abstract class Controller {

    private $postNames = [];

    function __construct(string ...$names) {
        $this->postNames = $names;
        if (!isset($_SESSION)){
            session_start();
        }
    }

    abstract function post(string $input): int;

    public function input_name(): string {
        foreach ($this->postNames as $input) {
            if (isset($_POST[$input])) {
                return $input;
            }
        }
        return "-";
    }

    public static function base_url(string $path): string {
        $size = sizeof($_GET);
        return $size === 3 ? "../../"
                : ($size === 2 ? "../" : "") . $path; 
    }
}
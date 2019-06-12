<?php 

interface IModel {
    
    function as_json(): string;

    function from_values(array $values);

    function invalid_values(): bool;
}
<?php

class ValidationErrorTemp
{
    private $arrayErrors;

    public function __construct($field, $message) {
        $this->arrayErrors = array(
            'field' => $field,
            'message' => $message
        );
    }

    public function getArrayErrors(): array
    {
        return $this->arrayErrors;
    }

    public function setArrayErrors($inputArray)
    {
        $this->arrayErrors = $inputArray;
    }

}
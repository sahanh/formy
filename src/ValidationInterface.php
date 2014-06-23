<?php
namespace SH\Formy;

interface ValidationInterface
{
    public function setRules(array $rules);

    public function setData(array $data);

    public function validate();
    
    public function getErrors();

    public function getElementErrors($name);
}
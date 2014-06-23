<?php
namespace SH\Formy;

interface ValidationInterface
{
    public function setRules(array $rules);

    public function setFieldset(Fieldset $fieldset);

    public function validate();
    
    public function getErrors();

    public function getElementErrors($name);
}
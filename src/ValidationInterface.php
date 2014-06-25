<?php
namespace SH\Formy;

interface ValidationInterface
{
    public function setForm(Form $form);

    /**
     * Validate the data
     * @return bool
     */
    public function validate();
    
    /**
     * Get all the errors
     * @return array
     */
    public function getErrors();

    /**
     * Get errors for a specific fieldset
     * @param  string $fieldset_name
     * @return array
     */
    public function getFieldsetErrors($fieldset_name);
}
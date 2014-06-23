<?php
namespace SH\Formy;

use SH\Formy\Form\InputResolver;

class Form
{
	/**
	 * Fieldsets
	 * @var array
	 */
	protected $fieldsets = [];

	/**
	 * Fieldset Validators
	 * @var array
	 */
	protected $validators;

	/**
	 * Input filter
	 * @var
	 */
	protected $filter;

	/**
	 * Populated data
	 * @var array
	 */
	protected $data;

	/**
	 * Form attributes
	 * @var array
	 */
	protected $attributes = [];

	public function addFieldset(Fieldset $fieldset)
	{
		$this->fieldsets[$fieldset->getName()] = $fieldset;
		return $this; 
	}

	public function getFieldsets()
	{
		return $this->fieldsets;
	}

	public function setFilter(Filterable $filter)
	{

	}

	public function setValidator($fieldset, ValidationInterface $validator)
	{
		$this->validators[$fieldset] = $validator;
		return $this;
	}

	public function getValidators()
	{
		return $this->validators;
	}

	public function validate()
	{
		$valid = true;

		//iterate through each validator
		foreach ($this->validators as $name => $validator) {

			$data = array_get($this->data, $name, array());
			$data = new InputResolver($data);

			//set data associated with the fieldset
			$validator->setData($data->resolve());

			if (!$validator->validate())
				$valid = false;

			if ($fieldset = array_get($this->fieldsets, $name)) {

				//foreach element in the fieldset
				foreach ($fieldset->getElements() as $element_name => $element) {
					//set error messages
					if ($messages = $validator->getElementErrors($element_name))
						$element->setMeta('errors', $messages);
				}

			}
		}

		return $valid;
	}

	public function setData($input_data)
	{
		$this->data = $input_data;

		foreach ($input_data as $fieldset => $data) {

			if (isset($this->fieldsets[$fieldset])) {
				$resolver = new InputResolver($data);
				$this->fieldsets[$fieldset]->populateData($resolver->resolve());
			}
		
		}

		return $this;
	}

	public function getAttribute($key)
	{
		return array_get($this->attributes, $key, false);
	}

	public function setAttribute($key, $value)
	{
		return $this->attributes[$key] = $value;
	}
}
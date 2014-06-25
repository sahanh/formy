<?php
namespace SH\Formy;

use SH\Formy\Form\InputResolver;
use SH\Formy\Exception\InvalidValidatorException;

class Form
{
	/**
	 * Fieldsets
	 * @var array
	 */
	protected $fieldsets = array();

	/**
	 * Validator
	 * @var ValidationInterface
	 */
	protected $validator;

	/**
	 * Input filter
	 * @var
	 */
	protected $filter;

	/**
	 * Populated data
	 * @var array
	 */
	protected $data = array();

	/**
	 * Form attributes
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Template to render
	 * @var Template
	 */
	protected $template;

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

	public function setValidator(ValidationInterface $validator)
	{
		$validator->setForm($this);
		
		$this->validator = $validator;
		return $this;
	}

	public function validate()
	{
		$data      = new InputResolver($this->data);
		$validator = $this->validator;

		if (!$validator)
			throw new InvalidValidatorException("No validator set");

		$validator->setData($data->resolve());

		if ($validator->validate())
			return true;


		foreach ($this->fieldsets as $fieldset_name => $fieldset) {
			
			$messages = $validator->getFieldsetErrors($fieldset_name);

			//foreach element in the fieldset
			foreach ($fieldset->getElements() as $element_name => $element) {
				//set error messages
				if ($element_errors = array_get($messages, $element_name))
					$element->setMeta('errors', $element_errors);
			}

		}
	}

	public function getData()
	{
		return $this->data;
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

	public function setTemplate(TemplateInterface $template)
	{
		$this->template = $template;
		return $this;
	}

	public function render()
	{
		$template = $this->template;
		$template->setForm($this);
		
		return $template->render();
	}
}
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

	public function getValidator()
	{
		return $this->validator;
	}

	public function setValidator(ValidationInterface $validator)
	{
		$validator->setForm($this);
		
		$this->validator = $validator;
		return $this;
	}

	public function validate()
	{
		if (!$this->validator)
			throw new InvalidValidatorException("No validator set");

		return $this->validator->validate();
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
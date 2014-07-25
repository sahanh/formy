<?php
namespace SH\Formy;

use SH\Formy\Form\InputResolver;
use SH\Formy\Exception\InvalidValidatorException;
use OutOfBoundsException;

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

	/**
	 * Add a fieldset
	 * @param Fieldset $fieldset
	 */
	public function addFieldset(Fieldset $fieldset)
	{
		$this->fieldsets[$fieldset->getName()] = $fieldset;
		return $this; 
	}

	/**
	 * Get fieldsets
	 * @return array
	 */
	public function getFieldsets()
	{
		return $this->fieldsets;
	}

	/**
	 * Get a fieldset by it's name
	 * @param  string $name
	 * @return Fieldset
	 */
	public function getFieldset($name)
	{
		if (!isset($this->fieldsets[$name]))
			throw new OutOfBoundsException("Fieldset {$name} does not exist");

		return $this->fieldsets[$name];
	}

	/**
	 * Get an element directly out of fieldset.
	 * Use dot notation <fieldset>.<element:name>
	 */
	public function getElement($name)
	{
		list($fieldset, $element) = explode('.', $name);
		return $this->getFieldset($fieldset)->getElement($element);
	}

	public function setFilter(Filterable $filter)
	{

	}

	/**
	 * Get a validator
	 * @return ValidationInterface
	 */
	public function getValidator()
	{
		return $this->validator;
	}

	/**
	 * Set a validator which implements ValidationInterface
	 * @param self
	 */
	public function setValidator(ValidationInterface $validator)
	{
		$validator->setForm($this);
		
		$this->validator = $validator;
		return $this;
	}

	/**
	 * Run a given validator on form data
	 * @return bool
	 */
	public function validate()
	{
		if (!$this->validator)
			throw new InvalidValidatorException("No validator set");

		return $this->validator->validate();
	}

	/**
	 * Get data set by setData
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Populate given data with elements of fieldset
	 * values are set to elements using setValue()
	 * @param array $data
	 * @return self
	 */
	public function setData(array $input_data)
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

	/**
	 * Get the entire attribute array
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Get attributes
	 * @param  string $key
	 * @return mixed
	 */
	public function getAttribute($key)
	{
		return array_get($this->attributes, $key, false);
	}

	/**
	 * Set attribute for the form
	 * @param string $key
	 * @param mixed $value
	 */
	public function setAttribute($key, $value)
	{
		return $this->attributes[$key] = $value;
	}

	/**
	 * Set template
	 * @param TemplateInterface $template
	 * @return  self
	 */
	public function setTemplate(TemplateInterface $template)
	{
		$this->template = $template;
		return $this;
	}

	/**
	 * Get assigned template, the form object will be set
	 * @return TemplateInterface
	 */
	public function getTemplate()
	{
		$template = $this->template;
		$template->setForm($this);
		return $template;
	}

	/**
	 * Render the form on template
	 * @return string
	 */
	public function render()
	{
		return $this->getTemplate()->render();
	}
}
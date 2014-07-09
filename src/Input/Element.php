<?php
namespace SH\Formy\Input;

use SH\Formy\Fieldset;

class Element
{
	protected $name;

	protected $type;

	protected $attributes = [];

	protected $meta       = [];

	protected $fieldset;

	public function __construct($name, $type, $attributes = [])
	{
		$this->name  = $name;
		$this->type  = $type;
		$this->setAttributes($attributes);
	}

	public function setFieldset(Fieldset $fieldset)
	{
		$this->fieldset = $fieldset;
		return $this;
	}

	public function getFieldset()
	{
		return $this->fieldset;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getName()
	{
		if ($this->fieldset) {
			$fieldset_name = $this->fieldset->getName();

			$keywords = preg_split("/[\[]/", $this->name);
			if (count($keywords) > 0) { //is array element
				//emails[name][sahan] => fieldset[emails][name][sahan]
				//emails[] => fieldset[emails][]
				return str_replace($keywords[0], "{$fieldset_name}[$keywords[0]]", $this->name);
			} else {
				return "{$fieldset_name}[{$this->name}]";
			}
		} else {
			return $this->name;
		}
		
	}

	public function setValue($value)
	{
		$this->setAttribute('value', $value);
		return $this;
	}

	public function getValue()
	{
		return $this->getAttribute('value');
	}

	public function setLabel($label)
	{
		$this->setMeta('label.name', $label);
		return $this;
	}

	public function getLabel()
	{
		return $this->getMeta('label.name');
	}

	public function getMeta($key)
	{
		return $this->getFromProperty('meta', $key);
	}

	public function setMeta($key, $value)
	{
		return $this->setToProperty('meta', $key, $value);
	}

	public function getMetas()
	{
		return $this->meta;
	}

	public function setMetas(array $meta)
	{
		return $this->addToProperty('meta', $meta);
	}

	public function getAttribute($key)
	{
		return $this->getFromProperty('attributes', $key);
	}

	public function setAttribute($key, $value)
	{
		return $this->setToProperty('attributes', $key, $value);
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function setAttributes(array $params)
	{
		return $this->addToProperty('attributes', $params);
	}

	/**
	 * Internal helper to get items from an array property
	 * @param  [type]  $prop [description]
	 * @param  boolean $key  [description]
	 * @return [type]        [description]
	 */
	protected function getFromProperty($prop, $key = false)
	{
		return array_get($this->{$prop}, $key);
	}

	protected function setToProperty($prop, $key, $value)
	{
		array_set($this->{$prop}, $key, $value);
		return $this;
	}

	protected function addToProperty($prop, $value)
	{
		$this->{$prop} = array_merge($this->{$prop}, $value);
		return $this;
	}
}
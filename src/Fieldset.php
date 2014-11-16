<?php
namespace SH\Formy;

use SH\Formy\Input\Element;
use OutOfRangeException;
use OutOfBoundsException;
use InvalidArgumentException;

class Fieldset
{
	protected $name;

	protected $elements;

    protected $is_parent;

	public function addElement(Element $element)
	{
        $name = $element->getName();

        if (isset($this->elements[$name]))
            throw new InvalidArgumentException("Duplicate element exists with [{$name}]");

        $element->setFieldset($this);
		$this->elements[$name] = $element;
        return $this;
	}

    public function getElement($name)
    {
        if (!isset($this->elements[$name]))
            throw new OutOfBoundsException("No element exists with the name [{$name}]");

        return $this->elements[$name];
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Populate data
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function populateData($data)
    {
        foreach ($data as $field => $value) {
            try {
                $this->getElement($field)->setValue($value);
            } catch (OutOfBoundsException $e) {
                
            }
        }

        return $this;
    }
}
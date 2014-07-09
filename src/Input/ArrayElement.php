<?php
namespace SH\Formy\Input;

use SH\Formy\Exception\InvalidValueException;

class ArrayElement extends Element
{
    protected $elements = [];

    public function setValue($values)
    {
        if (!is_array($values)) {
            $given = gettype($values);
            throw new InvalidValueException("The value should be an array of values {$given}");
        }

        foreach ($values as $value) {
            $this->elements[] = $this->makeElement($value);
        }
    }

    /**
     * Get elements
     * @return array
     */
    public function getElements()
    {
        if (empty($this->elements))
            $this->makeElement($value);

        return $this->elements;
    }

    /**
     * Make child element with base element spec
     * @param  mixed $value value for the element
     * @return Element
     */
    public function makeElement($value)
    {
        $element = new Element($this->name, $this->type, $this->attributes);
        $element->setMeta($this->meta);

        return $element;
    }
}
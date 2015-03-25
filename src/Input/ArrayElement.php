<?php
namespace SH\Formy\Input;

use SH\Formy\Exception\InvalidValueException;
use IteratorAggregate;
use ArrayIterator;
use Countable;

class ArrayElement extends Element implements IteratorAggregate, Countable
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
        return $this->elements;
    }

    /**
     * Make child element with base element spec
     * @param  mixed $value value for the element
     * @return Element
     */
    public function makeElement($value)
    {
        $element = new Element("{$this->name}[]", $this->type, $this->attributes);
        $element->setValue($value);

        if ($this->meta)
            $element->setMetas($this->meta);
        
        if ($this->fieldset)
            $element->setFieldset($this->fieldset);

        return $element;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->getElements());
    }

    public function count()
    {
        return count($this->getElements());
    }
}

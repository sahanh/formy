<?php
/**
 * Process input array correctly to form fields
 * The class will flatten multi demensional arrays
 * to work with element names
 * ie:- [name => [first => 'Sahan', last => 'HH']] to name[first], name[last]
 * ie:- [emails => ['email1', 'email2']] will remain intact (not assoc)
 */
namespace SH\Formy\Form;


class InputResolver
{
    protected $input = array();

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function resolve()
    {
        return $this->flatten($this->input);
    }

    protected function flatten($array, $prefix = null)
    {
        $result = array();
        
        foreach ($array as $key => $value) {
            
            if (!empty($prefix))
                $key = "[{$key}]";

            if($this->hasMoreFieldsToProcess($value)) {
                $result = $result + $this->flatten($value, $prefix.$key);
            } else {
                $result[$prefix.$key] = $value;
            }
        }

        return $result;
    }

    protected function hasMoreFieldsToProcess($input)
    {
        if (!is_array($input))
            return false;
        else //check if assoc
            return array_keys($input) !== range(0, count($input) - 1);
    }
}

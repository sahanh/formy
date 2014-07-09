<?php
use SH\Formy\Input\ArrayElement;

class ArrayElementTest extends \PHPUnit_Framework_Testcase
{
    protected $ele;

    public function setUp()
    {
        parent::setUp();

        $this->ele = new ArrayElement('email', 'text', []);
        $this->ele->setLabel('Email');
    }

    public function testBasicElement()
    {
        $this->assertEquals(0, count($this->ele->getElements()));
    }

    public function testValuePopulate()
    {
        $ele = $this->ele;
        $ele->setValue(['myemail@mydomain.com', 'another@another.com']);
        
        $elements   = $this->ele->getElements();
        $child_ele1 = $elements[0];
        $child_ele2 = $elements[1];

        $this->assertEquals(2, count($this->ele->getElements()));
        $this->assertEquals('Email', $child_ele1->getLabel());
        $this->assertEquals('myemail@mydomain.com', $child_ele1->getValue());
        $this->assertEquals('another@another.com', $child_ele2->getValue());
    }

    /**
     * @expectedException SH\Formy\Exception\InvalidValueException
     * @expectedExceptionMessage The value should be an array of values string
     */
    public function testInvalidValue()
    {
        $this->ele->setValue('email');
    }

    public function testIterator()
    {
        $ele = $this->ele;
        $ele->setValue(['myemail@mydomain.com', 'another@another.com']);

        $valid_count = 0;
        foreach ($ele as $element) {
            $valid_count++;
        }

        $this->assertEquals(2, $valid_count);
    }

    public function testCountable()
    {
        $ele = $this->ele;

        $this->assertEquals(0, count($ele));

        $ele->setValue(['myemail@mydomain.com', 'another@another.com']);

        $this->assertEquals(2, count($ele));
    }
}
<?php
use SH\Formy\Input\Element;
use Mockery as m;

class ElementTest extends \PHPUnit_Framework_Testcase
{
    public function testBasicElement()
    {
        $atts = ['class' => 'form-control', 'value' => 'sahan'];
        $e = new Element('name', 'text', $atts);
        $e->setLabel('First Name');

        $this->assertEquals('First Name', $e->getLabel());
        $this->assertEquals('text', $e->getType());
        $this->assertEquals('sahan', $e->getValue());
        $this->assertEquals('form-control', $e->getAttribute('class'));
        $this->assertEquals($atts, $e->getAttributes());
        $this->assertEquals(['label' => ['name' => 'First Name']], $e->getMetas());
    }

    public function testElementName()
    {
        $e = new Element('name', 'text', array());
        $e->setLabel('First Name');

        //stand-alone
        $this->assertEquals('name', $e->getName());
        
        //under fieldset
        $fs = m::mock('SH\Formy\Fieldset')
                ->shouldReceive('getName')
                ->times(1)
                ->andReturn('customers')
                ->getMock();

        $e->setFieldset($fs);
        $this->assertEquals('customers[name]', $e->getName());
    }

    public function tearDown()
    {
        m::close();
    }
}
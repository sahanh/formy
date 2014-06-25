<?php
use SH\Formy\Form;
use SH\Formy\Fieldset;
use SH\Formy\Input\Element;
use SH\Formy\Input\ArrayElement;

use Mockery as m;

class FormTest extends PHPUnit_Framework_Testcase
{
    public function testBasic()
    {
        $fs = new Fieldset;
        $fs->setName('customers');

        $fs->addElement(new Element('first_name', 'text'));
        $fs->addElement(new Element('last_name', 'text'));
        $fs->addElement(new Element('address', 'textarea'));

        $form = new Form;
        $form->addFieldset($fs);
        $form->setAttribute('action', 'create');
        $form->setAttribute('class', 'form-horizontal');

        $this->assertEquals(['customers' => $fs], $form->getFieldsets());
        $this->assertEquals($form->getAttribute('class'), 'form-horizontal');
        $this->assertEquals($form->getAttribute('action'), 'create');
    }

    public function testArrayDataPopulate()
    {
        $fs = new Fieldset;
        $fs->setName('customers');

        $fs->addElement(new Element('first_name', 'text'));
        $fs->addElement(new Element('phone[first]', 'text'));

        $data = array('first_name' => 'Sahan', 'phone' => array('first' => '123'));

        $form = new Form;
        $form->addFieldset($fs);
        $form->setData(array('customers' => $data));

        $this->assertEquals('123', $fs->getElement('phone[first]')->getValue());
        $this->assertEquals('Sahan', $fs->getElement('first_name')->getValue());
    }

    //test with data that not in fieldsets
    public function testMixedData()
    {
        $fs = new Fieldset;
        $fs->setName('customers');

        $fs->addElement(new Element('first_name', 'text'));
        $fs->addElement(new Element('phone[first]', 'text'));

        $data = array('first_name' => 'Sahan', 'phone' => array('first' => '123'));

        $form = new Form;
        $form->addFieldset($fs);
        $form->setData(array('customers' => $data, '_csrf' => 'token'));

        $this->assertEquals('123', $fs->getElement('phone[first]')->getValue());
        $this->assertEquals('Sahan', $fs->getElement('first_name')->getValue());
    }

    public function testRender()
    {
        $template = m::mock('SH\Formy\TemplateInterface')
                    ->shouldReceive('setForm')
                    ->times(1)
                    ->shouldReceive('render')
                    ->times(1)
                    ->getMock();

        $form     = new Form;
        $form->setTemplate($template);
        $form->render();
    }

    public function testValidation()
    {
        $val    = m::mock('SH\Formy\ValidationInterface')
                   ->shouldReceive('setForm')->times(1)                   
                   ->shouldReceive('validate')->times(1)                   
                   ->getMock();

        $form = new Form;
        $form->setValidator($val);
        $form->validate();

    }

    /**
     * @expectedException SH\Formy\Exception\InvalidValidatorException
     * @expectedExceptionMessage No validator set
     */
    public function testInvalidValidator()
    {
        $form = new Form;
        $form->validate();
    }

    public function tearDown()
    {
        m::close();
    }
}
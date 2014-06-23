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


    public function tearDown()
    {
        m::close();
    }
}
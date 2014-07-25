<?php
use SH\Formy\Fieldset;
use SH\Formy\Input\Element;

class FieldsetTest extends PHPUnit_Framework_Testcase
{
    public function testSample()
    {
        $fs = new Fieldset;
        $fs->setName('customers');

        $ele1 = new Element('first_name', 'text');
        $ele2 = new Element('last_name', 'text');
        $ele3 = new Element('address', 'textarea');

        $fs->addElement($ele1);
        $fs->addElement($ele2);
        $fs->addElement($ele3);

        $ele = $fs->getElement('first_name');

        $this->assertEquals('customers[first_name]', $ele->getName());
        $this->assertSame($fs, $ele->getFieldset());
        $this->assertSame(['first_name' => $ele1, 'last_name' => $ele2, 'address' => $ele3], $fs->getElements());
    }

    public function testDataPopulate()
    {
        $fs = new Fieldset;
        $fs->setName('customers');

        $ele1 = new Element('first_name', 'text');
        $ele2 = new Element('last_name', 'text');
        $ele3 = new Element('address', 'textarea');

        $fs->addElement($ele1);
        $fs->addElement($ele2);
        $fs->addElement($ele3);
        
        $fs->populateData(['first_name' => 'Sahan', 'last_name' => 'HH']);

        $this->assertEquals('Sahan', $fs->getElement('first_name')->getValue());
        $this->assertEquals('HH', $fs->getElement('last_name')->getValue());
    }

    /**
     * @expectedException OutOfBoundsException
     * @expectedMessage No element exists with the name [first_name]
     */
    public function testInvalidElement()
    {
        $fs = new Fieldset;
        $fs->setName('customers');
        $fs->getElement('first_name');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedMessage Duplicate element exists with [first_name]
     */
    public function testDuplicateElement()
    {
        $fs = new Fieldset;
        $fs->setName('customers');
        $fs->addElement(new Element('first_name', 'text'));
        $fs->addElement(new Element('first_name', 'text'));
    }
}
<?php
use SH\Formy\Form\InputResolver;

class FormInputResolverTest extends PHPUnit_Framework_Testcase
{
    public function testMultiDimensionalInput()
    {
        $input = array(
            'emails' => array('email1', 'email2'),
            'name'   => array('first' => 'Sahan', 'last' => 'HH'),
            'phone'  => array(
                'work' => array('am' => 123, 'pm' => 234),
                'home' => array(456, 789),
                'mars' => 909
            )
        );

        $expected = array(
            'emails'          => array('email1', 'email2'),
            'name[first]'     => 'Sahan',
            'name[last]'      => 'HH',
            'phone[work][am]' => 123,
            'phone[work][pm]' => 234,
            'phone[home]'     => array(456, 789),
            'phone[mars]'     => 909
        );

        $final = new InputResolver($input);
        $this->assertEquals($expected, $final->resolve());
    }

    public function testFlatInput()
    {
        $input = array(
            'first_name' => 'Sahan',
            'last_name'  => 'HH'
        );

        $final = new InputResolver($input);
        $this->assertEquals(array('first_name' => 'Sahan', 'last_name'  => 'HH'), $final->resolve());
    }
}
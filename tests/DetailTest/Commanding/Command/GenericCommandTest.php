<?php

namespace DetailTest\Commanding\Command;

use PHPUnit_Framework_TestCase as TestCase;

use Detail\Commanding\Command\GenericCommand;

class GenericCommandTest extends TestCase
{
    public function provideParams()
    {
        return array(
            array(
                'param' => 'param1',
                'accepted_param' => 'param1',
                'setter' => 'setParam1',
                'getter' => 'getParam1',
                'property' => 'param1',
            ),
            array(
                'param' => 'paramTwo',
                'accepted_param' => 'param_two',
                'setter' => 'setParamTwo',
                'getter' => 'getParamTwo',
                'property' => 'paramTwo',
            ),
            array(
                'param' => 'param_three',
                'accepted_param' => 'param_three',
                'setter' => 'setParamThree',
                'getter' => 'getParamThree',
                'property' => 'paramThree',
            ),
            array(
                'param' => 'param_Four',
                'accepted_param' => 'param_four',
                'setter' => 'setParamFour',
                'getter' => 'getParamFour',
                'property' => 'paramFour',
            ),
            array(
                'param' => '_param_five',
                'accepted_param' => 'param_five',
                'setter' => 'setParamFive',
                'getter' => 'getParamFive',
                'property' => 'paramFive',
            ),
            array(
                'param' => 'param_six_',
                'accepted_param' => 'param_six',
                'setter' => 'setParamSix',
                'getter' => 'getParamSix',
                'property' => 'paramSix',
            ),
            array(
                'param' => 'param__seven',
                'accepted_param' => 'param_seven',
                'setter' => 'setParamSeven',
                'getter' => 'getParamSeven',
                'property' => 'paramSeven',
            ),
            array(
                'param' => 'param__Eight',
                'accepted_param' => 'param_eight',
                'setter' => 'setParamEight',
                'getter' => 'getParamEight',
                'property' => 'paramEight',
            ),
            array(
                'param' => 'param_NINe',
                'accepted_param' => 'param_n_i_ne',
                'setter' => 'setParamNINe',
                'getter' => 'getParamNINe',
                'property' => 'paramNINe',
            ),
        );
    }

    public function provideInvalidProperties()
    {
        return array(
            array(
                'params' => array(
                    '_beginningWithUnderscore',
                ),
            ),
            array(
                'params' => array(
                    'underscore_in_the_middle',
                ),
            ),
            array(
                'params' => array(
                    'endingWithUnderscore_',
                ),
            ),
            array(
                'params' => array(
                    'param',
                ),
            ),
            array(
                'params' => array(
                    'params',
                ),
            ),
            array(
                'params' => array(
                    'fromArray',
                ),
            ),
        );
    }

    /**
     * @param string $param
     * @param string $acceptedParam
     * @param string $setter
     * @param string $getter
     * @param string $property
     * @dataProvider provideParams
     */
    public function testParamsCanBeSet($param, $acceptedParam, $setter, $getter, $property)
    {
        /** @var GenericCommand $command */
        $command = $this->getCommand(array($property));

        $this->assertTrue(is_array($command->getParams()));
        $this->assertCount(0, $command->getParams());

        $params = array($param => 1);
        $expectedParams = array($acceptedParam => 1);

        $command->setParams($params);

        $this->assertEquals($expectedParams, $command->getParams());

        $params[$param] = 2;
        $expectedParams[$acceptedParam] = 2;

        $command->setFromArray($params);

        $this->assertEquals($expectedParams, $command->toArray());

        $command->$setter(3);

        $this->assertEquals(3, $command->$getter());
    }

    /**
     * @param array $params
     * @dataProvider provideInvalidProperties
     */
    public function testInvalidPropertyLeadsToException(array $params)
    {
        $this->setExpectedException('Detail\Commanding\Exception\RuntimeException');

        $command = $this->getCommand($params);
        $command->setAnyParam();
    }

    public function testNonArrayLeadsToException()
    {
        $this->setExpectedException('Detail\Commanding\Exception\InvalidArgumentException');

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->setParams('not_an_array');
    }

    public function testInvalidGetterLeadsToException()
    {
        $this->setExpectedException('Detail\Commanding\Exception\BadMethodCallException');

        $command = $this->getCommand();
        $command->badMethod();
    }

    public function testNotGetterLeadsToException()
    {
        $this->setExpectedException('Detail\Commanding\Exception\BadMethodCallException');

        $command = $this->getCommand();
        $command->getUnsupportedParam();
    }

    public function testNotSetterLeadsToException()
    {
        $this->setExpectedException('Detail\Commanding\Exception\BadMethodCallException');

        $command = $this->getCommand();
        $command->setUnsupportedParam();
    }

    public function testUnsupportedParamLeadsToException()
    {
        $this->setExpectedException('Detail\Commanding\Exception\InvalidArgumentException');

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->setParams(array('unsupported_param' => 'value'));
    }

    public function testEmptyParamLeadsToException()
    {
        $this->setExpectedException('Detail\Commanding\Exception\InvalidArgumentException');

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->setParam('', 'value');
    }

    /**
     * @param array $properties
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCommand($properties = array())
    {
        $command = $this->getMockBuilder('Detail\Commanding\Command\GenericCommand')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        // Initialize properties
        foreach ($properties as $property) {
            $command->$property = null;
        }

        return $command;
    }
}

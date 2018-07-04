<?php

namespace DetailTest\Commanding\Command;

use PHPUnit\Framework\TestCase;

use Detail\Commanding\Command\GenericCommand;
use Detail\Commanding\Exception;

class GenericCommandTest extends TestCase
{
    public function provideParams()
    {
        return [
            [
                'param' => 'param1',
                'accepted_param' => 'param1',
                'setter' => 'setParam1',
                'getter' => 'getParam1',
                'property' => 'param1',
            ],
            [
                'param' => 'paramTwo',
                'accepted_param' => 'param_two',
                'setter' => 'setParamTwo',
                'getter' => 'getParamTwo',
                'property' => 'paramTwo',
            ],
            [
                'param' => 'param_three',
                'accepted_param' => 'param_three',
                'setter' => 'setParamThree',
                'getter' => 'getParamThree',
                'property' => 'paramThree',
            ],
            [
                'param' => 'param_Four',
                'accepted_param' => 'param_four',
                'setter' => 'setParamFour',
                'getter' => 'getParamFour',
                'property' => 'paramFour',
            ],
            [
                'param' => '_param_five',
                'accepted_param' => 'param_five',
                'setter' => 'setParamFive',
                'getter' => 'getParamFive',
                'property' => 'paramFive',
            ],
            [
                'param' => 'param_six_',
                'accepted_param' => 'param_six',
                'setter' => 'setParamSix',
                'getter' => 'getParamSix',
                'property' => 'paramSix',
            ],
            [
                'param' => 'param__seven',
                'accepted_param' => 'param_seven',
                'setter' => 'setParamSeven',
                'getter' => 'getParamSeven',
                'property' => 'paramSeven',
            ],
            [
                'param' => 'param__Eight',
                'accepted_param' => 'param_eight',
                'setter' => 'setParamEight',
                'getter' => 'getParamEight',
                'property' => 'paramEight',
            ],
            [
                'param' => 'param_NINe',
                'accepted_param' => 'param_n_i_ne',
                'setter' => 'setParamNINe',
                'getter' => 'getParamNINe',
                'property' => 'paramNINe',
            ],
        ];
    }

    public function provideInvalidProperties()
    {
        return [
            [
                'params' => [
                    '_beginningWithUnderscore',
                ],
            ],
            [
                'params' => [
                    'underscore_in_the_middle',
                ],
            ],
            [
                'params' => [
                    'endingWithUnderscore_',
                ],
            ],
            [
                'params' => [
                    'param',
                ],
            ],
            [
                'params' => [
                    'params',
                ],
            ],
            [
                'params' => [
                    'fromArray',
                ],
            ],
        ];
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
        $command = $this->getCommand([$property]);

        $this->assertTrue(is_array($command->getParams()));
        $this->assertCount(0, $command->getParams());

        $params = [$param => 1];
        $expectedParams = [$acceptedParam => 1];

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
        $this->expectException(Exception\RuntimeException::CLASS);

        $command = $this->getCommand($params);
        $command->setAnyParam();
    }

    public function testNonArrayLeadsToException()
    {
        $this->expectException(Exception\InvalidArgumentException::CLASS);

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->setParams('not_an_array');
    }

    public function testInvalidGetterLeadsToException()
    {
        $this->expectException(Exception\BadMethodCallException::CLASS);

        $command = $this->getCommand();
        $command->badMethod();
    }

    public function testNotGetterLeadsToException()
    {
        $this->expectException(Exception\BadMethodCallException::CLASS);

        $command = $this->getCommand();
        $command->getUnsupportedParam();
    }

    public function testNotSetterLeadsToException()
    {
        $this->expectException(Exception\BadMethodCallException::CLASS);

        $command = $this->getCommand();
        $command->setUnsupportedParam();
    }

    public function testUnsupportedParamLeadsToException()
    {
        $this->expectException(Exception\InvalidArgumentException::CLASS);

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->setParams(['unsupported_param' => 'value']);
    }

    public function testEmptyParamLeadsToException()
    {
        $this->expectException(Exception\InvalidArgumentException::CLASS);

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->setParam('', 'value');
    }

    /**
     * @param array $properties
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCommand($properties = [])
    {
        $command = $this->getMockBuilder(GenericCommand::CLASS)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        // Initialize properties
        foreach ($properties as $property) {
            $command->$property = null;
        }

        return $command;
    }
}

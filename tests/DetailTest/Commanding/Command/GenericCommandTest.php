<?php

namespace DetailTest\Commanding\Command;

use PHPUnit_Framework_TestCase as TestCase;

use Detail\Commanding\Command\GenericCommand;
use Detail\Commanding\Exception;
use ReflectionObject;

class GenericCommandTest extends TestCase
{
    public function testParamsCanBeSet()
    {
        $params = array(
            'param1' => 'value1',
            'param2' => 'value2',
        );

        /** @var GenericCommand $command */
        $command = $this->getCommand(array_keys($params));

        $this->assertTrue(is_array($command->getParams()));
        $this->assertCount(0, $command->getParams());

        $command->setParams($params);

        $this->assertEquals($params, $command->getParams());

        unset($params['param1']);

        $command->setFromArray($params);

        $this->assertEquals($params, $command->toArray());
    }

    public function testIndiviualParamsCanBeSet()
    {
        $params = array(
            'param_one' => 'value1',
        );

        /** @var GenericCommand $command */
        $command = $this->getCommand(array_keys($params));
        $command->setParamOne($params['param_one']);

        $this->assertEquals($params['param_one'], $command->getParamOne());
        $this->assertEquals($params, $command->getParams());
    }

    public function testNonArrayLeadsToException()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::CLASS);

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->setParams('not_an_array');
    }

    public function testNotGetterOrSetterLeadsToException()
    {
        $this->setExpectedException(Exception\BadMethodCallException::CLASS);

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->badMethod();
    }

    public function testUnsupportedParamLeadsToException()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::CLASS);

        /** @var GenericCommand $command */
        $command = $this->getCommand();
        $command->setParams(array('unsupported_param' => 'value'));
    }

    /**
     * @param array $acceptedParams
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCommand($acceptedParams = array())
    {
        $command = $this->getMockBuilder(GenericCommand::CLASS)
            ->disableOriginalConstructor()
//            ->setMethods(array('getAcceptedParams'))
            ->getMockForAbstractClass();

//        $class = new ReflectionObject($command);

        // Initialize as properties and make protected
        foreach ($acceptedParams as $acceptedParam) {
            $command->$acceptedParam = null;
//            $property = $class->getProperty($acceptedParam);
//            $property->setAccessible(false);
        }

        //

//        foreach ($acceptedParams as $acceptedParam) {
//            $command->$acceptedParam = null;
//        }

//        var_dump($command);

//        $command
//            ->expects($this->any())
//            ->method('getAcceptedParams')
//            ->will($this->returnValue($acceptedParams));

        return $command;
    }
}

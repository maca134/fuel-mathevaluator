<?php

/**
 * Test_MathEvaluator_Stack class tests
 *
 * @group MathEvaluator
 */
class Test_MathEvaluator_Stack extends Fuel\Core\TestCase {

    protected $stack = null;

    protected function setUp() {
        Package::load('mathevaluator');
        $this->stack = new \MathEvaluator\MathEvaluator_Stack;
    }

    protected function tearDown() {
        Package::unload('mathevaluator');
        $this->stack = null;
    }

    public function testInit() {
        $this->assertEquals(array(), $this->stack->stack);
        $this->assertEquals(0, $this->stack->count);
    }

    public function testPush() {
        $this->stack->push('2');
        $this->stack->push('3');
        $this->stack->push('+');

        $this->assertEquals(3, $this->stack->count);
        $this->assertEquals(array(
            0 => '2',
            1 => '3',
            2 => '+'
                ), $this->stack->stack);
    }

    public function testPop() {
        $this->stack->push('2');
        $this->stack->push('3');
        $this->stack->push('+');

        $this->assertEquals('+', $this->stack->pop());
        $this->assertEquals('3', $this->stack->pop());
        $this->assertEquals('2', $this->stack->pop());
        $this->assertEquals(null, $this->stack->pop());
    }

    public function testLast() {
        $this->stack->push('2');
        $this->stack->push('3');
        $this->stack->push('+');

        $this->assertEquals('+', $this->stack->last());
    }

    public function testToString() {
        $this->stack->push('2');
        $this->stack->push('3');
        $this->stack->push('+');

        $this->assertEquals('2 3 +', (string) $this->stack);
    }

}

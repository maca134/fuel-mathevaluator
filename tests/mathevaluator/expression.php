<?php

/**
 * Test_MathEvaluator_Stack class tests
 *
 * @group MathEvaluator
 */
class Test_MathEvaluator_Parse extends Fuel\Core\TestCase {

    protected static function getMethod($name) {
        $class = new ReflectionClass('\\MathEvaluator\\MathEvaluator_Expression');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected function setUp() {
        Package::load('mathevaluator');
    }

    protected function tearDown() {
        Package::unload('mathevaluator');
    }

    /**
     * @expectedException \MathEvaluator\MathEvaluator_Expression_Exception 
     */
    public function testBlankExpression() {
        new \MathEvaluator\MathEvaluator_Expression('');
    }

    /**
     * @dataProvider dataProviderInfixToPostfix
     */
    public function testInfixToPostfix($input, $expected) {
        $exp = new \MathEvaluator\MathEvaluator_Expression($input);
        $result = $exp->postfix();
        $this->assertEquals($expected, $result, "Expected '{$expected}' - Result: '{$result}'");
    }

    public function dataProviderInfixToPostfix() {
        return array(
            array('4 + 2', '4 2 +'),
            array('2 + (3 * 4)', '2 3 4 * +'),
            array('(x + 3) * (x + 7)', 'x 3 + x 7 + *'),
            array('2 * x ^ 2 + 3 * x + 7', '2 x 2 ^ * 3 x * + 7 +'),
            array('(5 * x + 3) + 4 * (2 * x + 3) + test', '5 x * 3 + 4 2 x * 3 + * + test +'),
            array('3 + 4 * 5 / 6', '3 4 5 * 6 / +'),
            array('(300 + 23) * (43 - 21) / (84 + 7)', '300 23 + 43 21 - * 84 7 + /'),
            array('(4 + 8) * (6 - 5) / ((3 - 2) * (2 + 2))', '4 8 + 6 5 - * 3 2 - 2 2 + * /')
        );
    }

    /**
     * @expectedException \MathEvaluator\MathEvaluator_Expression_Exception 
     */
    public function testInvalidParentheses() {
        new \MathEvaluator\MathEvaluator_Expression('2 + ((3 * 4)');
    }

    /**
     * @expectedException \MathEvaluator\MathEvaluator_Expression_Exception 
     */
    public function testInvalidOperator() {
        new \MathEvaluator\MathEvaluator_Expression('2 & (3 * 4)');
    }

    public function testTokenise() {
        $input = '15 * (3x^2 * 4x)';
        $token_func = static::getMethod('tokenise');
        $expression = new \MathEvaluator\MathEvaluator_Expression($input);
        $tokens = $token_func->invoke($expression);
        $this->assertEquals(array(
            '15', '*', '(', '3', '*', 'x', '^', '2', '*', '4', '*', 'x', ')',
                ), $tokens);
    }

    /**
     * @expectedException \MathEvaluator\MathEvaluator_Expression_Exception 
     */
    public function testTokeniseInvalidParentheses() {
        $input = '(15 * (3x^2 * 4x)';
        $token_func = static::getMethod('tokenise');
        $expression = new \MathEvaluator\MathEvaluator_Expression($input);
        $token_func->invoke($expression);
    }

    /**
     * @expectedException \MathEvaluator\MathEvaluator_Expression_Exception 
     */
    public function testTokeniseInvalidOperator() {
        $input = '15 ! (3x^2 * 4x)';
        $token_func = static::getMethod('tokenise');
        $expression = new \MathEvaluator\MathEvaluator_Expression($input);
        $token_func->invoke($expression);
    }

}

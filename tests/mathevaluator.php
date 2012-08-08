<?php

/**
 * Test_MathEvaluator class tests
 *
 * @group MathEvaluator
 */
class Test_MathEvaluator extends \Fuel\Core\TestCase {

    protected function setUp() {
        Package::load('mathevaluator');
    }

    protected function tearDown() {
        Package::unload('mathevaluator');
    }

    public function testAddVars() {
        $reflection_class = new ReflectionClass("\\MathEvaluator");
        $property = $reflection_class->getProperty('variables');
        $property->setAccessible(true);

        $m = new \MathEvaluator('a^2 + b^2');
        $m->add_var('a', 2);
        $m->add_var('b', 3);

        $this->assertEquals(array('a' => 2, 'b' => 3), $property->getValue($m));
    }

    /**
     * @dataProvider dataProviderAdditions
     */
    public function testAdditions($input, $expected) {
        $e = \MathEvaluator::e($input);
        $this->assertEquals($expected, $e);
    }

    public function dataProviderAdditions() {
        return array(
            array('4 + 2', '6'),
            array('1 + 2 + 3 + 4 + 5', '15'),
            array('100 + 200', '300'),
            array('2 + 3 + (5 + 5)', '15'),
            array('10 + (32 + (10 + 10))', '62'),
        );
    }

    /**
     * @dataProvider dataProviderSubtraction
     */
    public function testSubtraction($input, $expected) {
        $e = \MathEvaluator::e($input);
        $this->assertEquals($expected, $e, $input);
    }

    public function dataProviderSubtraction() {
        return array(
            array('4 - 2', '2'),
            array('1 - 2 - 3 - 4 - 5', '-13'),
            array('100 - 200', '-100'),
            array('2 - 3 - (5 - 5)', '-1'),
            array('10 - 32 - 10', '-32'),
        );
    }

    /**
     * @dataProvider dataProviderMultiply
     */
    public function testMultiply($input, $expected) {
        $e = \MathEvaluator::e($input);
        $this->assertEquals($expected, $e, $input);
    }

    public function dataProviderMultiply() {
        return array(
            array('2 * 3', '6'),
            array('2 * 2 * 2', '8'),
            array('1 * 2 * 3 * 4', '24'),
            array('1 * 2 * 4 * 0', '0'),
            array('(3 * 5) * (5 * 5)', '375'),
            array('20 * 0.5', '10'),
        );
    }

    /**
     * @dataProvider dataProviderDivide
     */
    public function testDivide($input, $expected) {
        $e = \MathEvaluator::e($input);
        $this->assertEquals($expected, round($e, 4), $input);
    }

    public function dataProviderDivide() {
        return array(
            array('2 / 3', '0.6667'),
            array('2 / 4', '0.5'),
            array('2 / (2 * 0.2)', '5'),
            array('1 / (2 / (3 / 4))', '0.375')
        );
    }

    /**
     * @dataProvider dataProviderModulus
     */
    public function testModulus($input, $expected) {
        $e = \MathEvaluator::e($input);
        $this->assertEquals($expected, round($e, 4), $input);
    }

    public function dataProviderModulus() {
        return array(
            array('5 % 3', '2'),
            array('5 % -3', '2'),
            array('-5 % 3', '-2'),
            array('-5 % -3', '-2'),
        );
    }

    /**
     * @dataProvider dataProviderPower
     */
    public function testPower($input, $expected) {
        $tests = array(
            '2 ^ 3' => '8',
            '2 ^ (2 ^ 4)' => '65536',
            '4 ^ 1' => '4',
            '4 ^ 0' => '1',
        );

        foreach ($tests as $input => $expected) {
            $e = \MathEvaluator::e($input);
            $this->assertEquals($expected, round($e, 4), $input);
        }
    }

    public function dataProviderPower() {
        return array(
            array('2 ^ 3', '8'),
            array('2 ^ (2 ^ 4)', '65536'),
            array('4 ^ 1', '4'),
            array('4 ^ 0', '1'),
        );
    }

    public function testVarsSphereVolume() {
        $vars = array(
            'r' => 10,
            'pi' => M_PI
        );

        $e = \MathEvaluator::e('(4 / 3) * pi * r ^ 3', $vars);
        $this->assertEquals('4188.7902', round($e, 4));
    }

}

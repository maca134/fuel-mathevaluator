<?php

/**
 * A package for safely evaluating mathamatical expressions
 *
 * @package    NotORM
 * @version    0.1
 * @author     Matthew McConnell
 * @license    MIT License
 * @copyright  2012 Matthew McConnell
 * @link       http://maca134.co.uk
 */
Autoloader::add_core_namespace('MathEvaluator');

Autoloader::add_classes(array(
    'MathEvaluator\\MathEvaluator' => __DIR__ . '/classes/mathevaluator.php',
    'MathEvaluator\\MathEvaluator_Stack' => __DIR__ . '/classes/mathevaluator/stack.php',
    'MathEvaluator\\MathEvaluator_Expression' => __DIR__ . '/classes/mathevaluator/expression.php',
    'MathEvaluator\\Test_MathEvaluatorSuite' => __DIR__ . '/tests/mathevaluatorsuite.php',
));
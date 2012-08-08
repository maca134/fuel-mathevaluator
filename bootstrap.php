<?php

/**
 * Math Evaluator: Evaluate maths expressions without using eval()
 *
 * @package    Math Evaluator
 * @version    v0.1
 * @author     Matthew McConnell
 * @license    MIT License
 * @link       https://github.com/maca134/fuel-mathevaluator
 */

Autoloader::add_core_namespace('MathEvaluator');

Autoloader::add_classes(array(
    'MathEvaluator\\MathEvaluator' => __DIR__ . '/classes/mathevaluator.php',
    'MathEvaluator\\MathEvaluator_Stack' => __DIR__ . '/classes/mathevaluator/stack.php',
    'MathEvaluator\\MathEvaluator_Expression' => __DIR__ . '/classes/mathevaluator/expression.php',
    'MathEvaluator\\Test_MathEvaluatorSuite' => __DIR__ . '/tests/mathevaluatorsuite.php',
));
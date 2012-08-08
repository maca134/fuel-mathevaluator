Math Evaluator
==============

This class is used to evaluate infix expressions safely.

Currently the class supports the following operations:
Addition (+)
Subtraction (-)
Multiplication (*)
Division (/)
Power (^)
Modulus (%)

Quick Example:

    $results = MathEvaluator::e('2a + (12 / 2) - b-2', array('a' => 2, 'b' => 4));

Installation
------------

1. Clone (`git clone git://github.com/maca134/fuel-mathevaluator`) / [download](https://github.com/maca134/fuel-mathevaluator/zipball/master)
2. Copy to fuel/packages/
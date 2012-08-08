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

namespace MathEvaluator;

class MathEvaluator_Exception extends \Fuel\Core\FuelException {
    
}

class MathEvaluator {

    /**
     * Array of variables used in evaluating expressions
     * 
     * @var     array
     * @access  private
     */
    private $variables = array();

    /**
     * The expression object 
     * 
     * @var     string
     * @access  private
     */
    private $expression = null;

    /**
     * Constructor 
     * 
     * @access  public
     * @param   string  $expression Infix expression to evaluate
     * @param   array   An array of variables to be used when evaluating the expression
     */
    public function __construct($expression, $variables = array()) {
        $this->expression = $expression;
        foreach ($variables as $name => $value) {
            $this->add_var($name, $value);
        }
    }

    /**
     * Evaluates a postfix expression and returns the result or false when fail 
     * 
     * @param   array|null  Set to manually override the internal postfix expression
     * @access  public
     * @return  boolean|float
     */
    public function evaluate() {
        try {
            $expression = new MathEvaluator_Expression($this->expression);
        } catch (MathEvaluator_Expression_Exception $e) {
            throw new MathEvaluator_Exception($e->getMessage());
        }
        $postfix = $expression->postfix(true);
        $stack = new MathEvaluator_Stack();

        foreach ($postfix as $token) {
            if ($expression->is_operator($token)) {
                if (is_null($op2 = $stack->pop()))
                    return false;
                if (is_null($op1 = $stack->pop()))
                    return false;

                switch ($token) {
                    case '+':
                        $stack->push($op1 + $op2);
                        break;
                    case '-':
                        $stack->push($op1 - $op2);
                        break;
                    case '*':
                        $stack->push($op1 * $op2);
                        break;
                    case '%':
                        $stack->push($op1 % $op2);
                        break;
                    case '/':
                        if ($op2 == 0) {
                            throw new MathEvaluator_Exception('Dividing by zero, now thats another story');
                            return false;
                        }
                        $stack->push($op1 / $op2);
                        break;
                    case '^':
                        $stack->push(pow($op1, $op2));
                        break;
                }
            } elseif ($expression->is_ident($token)) {
                if (is_numeric($token)) {
                    $stack->push($token);
                } elseif (isset($this->variables[$token])) {
                    $stack->push($this->variables[$token]);
                }
            } else {
                throw new MathEvaluator_Exception('No idea what a "' . $token . '" is...');
                return false;
            }
        }
        if ($stack->count != 1)
            return false;
        return $stack->pop();
    }

    /**
     * Add a variable
     * 
     * @param string $name
     * @param float $value
     * @return boolean 
     */
    public function add_var($name, $value) {
        if (is_numeric($value)) {
            $this->variables[$name] = $value;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Quick static function to evaluate an infix expression
     * 
     * @param   string  An infix expression
     * @param   array   An assocated array of variables to use in the expression [optional]
     * @return  array   An array containing the results and other information
     */
    public static function e($expression, $vars = array()) {
        $instance = new self($expression, $vars);
        return $instance->evaluate();
    }

}
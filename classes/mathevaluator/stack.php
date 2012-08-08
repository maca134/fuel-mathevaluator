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

class MathEvaluator_Stack {

    public $stack = array();
    public $count = 0;

    /**
     * Push a token onto the stack 
     * 
     * @param string $val 
     */
    public function push($val) {
        $this->stack[$this->count] = $val;
        $this->count++;
    }

    /**
     * Pop a token off the stack and return it
     * 
     * @return  mixed
     */
    public function pop() {
        if ($this->count > 0) {
            $this->count--;
            return $this->stack[$this->count];
        }
        return null;
    }

    /**
     * Return the last tokens from stack
     * 
     * @param   int
     * @return  mixed
     */
    public function last($n = 1) {
        return $this->stack[$this->count - $n];
    }

    /**
     * Convert stack to a string and return it
     * 
     * @return string 
     */
    public function __toString() {
        return trim(implode(' ', $this->stack));
    }

}
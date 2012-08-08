<?php

/**
 * This class is used to evaluate infix expressions safetly
 * 
 * @package		MathEvaluator
 * @author		Matthew McConnell <maca134@googlemail.com>
 * @version		0.1
 * @link		http://maca134.co.uk
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
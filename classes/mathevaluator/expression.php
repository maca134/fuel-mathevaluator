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

class MathEvaluator_Expression_Exception extends \Fuel\Core\FuelException {
    
}

class MathEvaluator_Expression {

    private $infix_expression = '';
    private $infix_tokens = array();
    private $postfix_expression = '';

    public function __construct($infix_expression) {
        if (empty($infix_expression)) {
            throw new MathEvaluator_Expression_Exception('The infix expression is blank');
        }
        $this->infix_expression = $infix_expression;
        $this->convert();
    }

    /**
     * Converts infix to postfix
     * 
     * @return string The postfix expression
     */
    private function convert() {
        $stack = new MathEvaluator_Stack;
        $operands = array();
        $operand_len = 0;

        $this->infix_tokens = $this->tokenise($this->infix_expression);
        $expecting_op = false;

        $pos = 0;

        do {
            $c = $this->infix_tokens[$pos];
            $pos++;
            if ($this->is_ident($c) and !$expecting_op) {
                $stack->push($c);
                $expecting_op = true;
            } elseif ($this->is_operator($c)) {
                while ($operand_len > 0) {
                    $sc = $operands[$operand_len - 1];
                    if ($this->is_operator($sc) &&
                            (($this->op_left_assoc($c) && ($this->op_preced($c) <= $this->op_preced($sc))) ||
                            (!$this->op_left_assoc($c) && ($this->op_preced($c) < $this->op_preced($sc))))) {
                        $stack->push($sc);
                        $operand_len--;
                    } else {
                        break;
                    }
                }
                $operands[$operand_len] = $c;
                $operand_len++;
                $expecting_op = false;
            } elseif ($c == '(') {
                $operands[$operand_len] = $c;
                $operand_len++;
                $expecting_op = false;
            } elseif ($c == ')') {
                $pe = false;

                while ($operand_len > 0) {
                    $sc = $operands[$operand_len - 1];
                    if ($sc == '(') {
                        $pe = true;
                        break;
                    } else {
                        $stack->push($sc);
                        $operand_len--;
                    }
                }
                if (!$pe) {
                    throw new MathEvaluator_Expression_Exception("Error: parentheses mismatched\n");
                }

                $operand_len--;

                if ($operand_len > 0) {
                    $sc = $operands[$operand_len - 1];
                }
                $expecting_op = true;
            } else {
                throw new MathEvaluator_Expression_Exception('Unknown operator or operand');
            }
        } while ($pos < count($this->infix_tokens));


        while ($operand_len > 0) {
            $sc = $operands[$operand_len - 1];
            if ($sc == '(' || $sc == ')') {
                throw new MathEvaluator_Expression_Exception("Error: parentheses mismatched\n");
            }
            $stack->push($sc);
            $operand_len--;
        }
        $this->postfix_expression = $stack->stack;
    }

    /**
     * Breaks an infix expression into an array
     * 
     * @return array An array containing all the operands and idents
     */
    private function tokenise() {
        $strpos = 0;
        $tokens = array();
        $invert_number = false;
        while ($strpos < strlen($this->infix_expression)) {
            $c = substr($this->infix_expression, $strpos, 1);
            if ($c != ' ') {
                $c_numeric = $this->is_ident($c);
                if ($c_numeric) {
                    $c_type = (is_numeric($c) || $c == '.') ? 'num' : 'var';
                    do {
                        $d = substr($this->infix_expression, $strpos + 1, 1);
                        $d_numeric = $this->is_ident($d);
                        $d_type = (is_numeric($d) || $d == '.') ? 'num' : 'var';
                        if ($d_numeric and $c_type == $d_type) {
                            $c .= $d;
                            $strpos++;
                        }
                    } while ($d_numeric and $c_type == $d_type);
                }
                if ($invert_number) {
                    $c = $c * -1;
                    $invert_number = false;
                }
                $tokens_end = end($tokens);
                if ($c == '-' && (
                        count($tokens) < 1 or
                        $this->is_operator($tokens_end) or $tokens_end == '('
                        )) {
                    $invert_number = true;
                    $strpos++;
                    continue;
                }
                if ($this->is_ident($tokens_end) && $c_numeric) {
                    $tokens[] = '*';
                }
                $tokens[] = $c;
            }
            $strpos++;
        }
        return $tokens;
    }

    /**
     * Returns the precedence of an operation
     *  
     * @param string $c
     * @return int 
     */
    private function op_preced($c = '') {
        switch ($c) {
            case '^':
                return 4;
                break;
            case '*': case '/': case '%':
                return 3;
                break;
            case '+': case '-':
                return 2;
                break;
        }
    }

    /**
     * Returns whether the operation is left or right precedence 
     * 
     * @param string $c
     * @return boolean 
     */
    private function op_left_assoc($c) {
        switch ($c) {
            case '*': case '/': case '%': case '+': case '-':
                return true;
        }
        return false;
    }

    /**
     * Is the token an operation
     * 
     * @param string $c
     * @return boolean 
     */
    public function is_operator($c) {
        if ($c == '+' or
                $c == '-' or
                $c == '/' or
                $c == '*' or
                $c == '^' or
                $c == '%') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Is the token an ident
     * 
     * @param string $c
     * @return boolean 
     */
    public function is_ident($c) {
        if (is_numeric($c) or preg_match('/[a-z\.]+/', $c)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the postfix expression
     * 
     * @return string Returns the postfix notation
     */
    public function postfix($as_array = false) {
        return ($as_array) ? $this->postfix_expression : implode(' ', $this->postfix_expression);
    }

    /**
     * Returns the infix expression
     * 
     * @return string Returns the postfix notation
     */
    public function infix() {
        return $this->infix_expression;
    }

    public function __toString() {
        return $this->postfix_expression;
    }

}
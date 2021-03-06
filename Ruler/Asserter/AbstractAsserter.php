<?php

namespace Rezzza\RulerBundle\Ruler\Asserter;

use Rezzza\RulerBundle\Ruler\Proposition;
use Ruler\Context;
use Rezzza\RulerBundle\Ruler\Exception\ContextValueNotFoundException;
use Rezzza\RulerBundle\Ruler\Exception\OperatorNotFoundException;

/**
 * AbstractAsserter
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class AbstractAsserter
{
    protected $operators = array();
    protected $ident;

    public function __sleep()
    {
        return array('ident');
    }

    /**
     * @param string $ident ident
     */
    public function setIdent($ident)
    {
        $this->ident = $ident;
    }

    /**
     * @return string
     */
    public function getIdent()
    {
        return $this->ident;
    }

    /**
     * @param array $operators operators
     */
    public function bindOperators(array $operators)
    {
        $defaultOperators = $this->getDefaultOperators();

        foreach ($operators as $operator) {
            if (array_key_exists($operator, $defaultOperators)) {
                $this->operators[$operator] = $defaultOperators[$operator];
            }
        }
    }

    /**
     * @return array
     */
    public function getDefaultOperators()
    {
        return array(
            '='  => function ($a, $b) { return $a == $b; },
            '!=' => function ($a, $b) { return $a != $b; },
            '>'  => function ($a, $b) { return $a > $b; },
            '>=' => function ($a, $b) { return $a >= $b; },
            '<'  => function ($a, $b) { return $a < $b; },
            '<=' => function ($a, $b) { return $a <= $b; },
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsProposition(Proposition $proposition)
    {
        return isset($this->operators[$proposition->getOperator()]);
    }

    /**
     * {@inheritdoc}
     */
    public function evaluate(Proposition $proposition, Context $context)
    {
        $key      = $proposition->getKey();

        if (!isset($context[$key])) {
            throw new ContextValueNotFoundException(sprintf('Key "%s" not found on context', $key));
        }

        $operator = $proposition->getOperator();

         if (!isset($this->operators[$operator])) {
             throw new OperatorNotFoundException('Operator "%s" does no exists', $operator);
         }

        $callable = $this->operators[$operator];
        $left     = $this->prepareValue($context[$key]);
        $right    = $this->prepareValue($proposition->getValue());

        if (!is_callable($callable)) {
            throw new \LogicException('Operator "%s" provides a non callable value', $operator);
        }

        if ($callable instanceof \Closure) {
            $value = $callable($left, $right);
        } else {
            $value = call_user_func_array($callable, array($left, $right));
        }

        return $value;
    }

    /**
     * @param string $value value
     *
     * @return string
     */
    public function prepareValue($value)
    {
        return $value;
    }
}

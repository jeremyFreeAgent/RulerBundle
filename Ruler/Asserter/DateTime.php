<?php

namespace Rezzza\RulerBundle\Ruler\Asserter;

use Rezzza\RulerBundle\Ruler\Proposition;
use Ruler\Context;

/**
 * DateTime
 *
 * @uses AbstractAsserter
 * @uses AsserterInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class DateTime extends AbstractAsserter implements AsserterInterface
{
    public function __construct()
    {
        $this->bindOperators(array(
            '=', '!=',
            '>', '>=', '<', '<='
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsProposition(Proposition $proposition)
    {
        if (!parent::supportsProposition($proposition)) {
            return false;
        }

        try {
            $value = $proposition->getValue();
            if ($value instanceof \DateTime) {
                return true;
            }

            $date = new \DateTime($value);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareValue($value)
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        return $value;
    }
}

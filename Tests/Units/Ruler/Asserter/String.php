<?php

namespace Rezzza\RulerBundle\Tests\Units\Ruler\Asserter;

require_once __DIR__ . '/../../../../vendor/autoload.php';

class String extends AbstractAsserter
{
    public function getAsserter()
    {
        return new \Rezzza\RulerBundle\Ruler\Asserter\String();
    }

    public function supportsPropositionDataProvider()
    {
        return array(
            array('=', '1', true),
            array('=', 'day', true),
            array('!=', '1.34', true),
            array('contains', 'toto', true),
            array('<', '1337', false),
        );
    }

    public function evaluateDataProvider()
    {
        return array(
            array('toto', '=', 'toto', true),
            array('toto', '!=', 'toto', false),
            array('toto', 'contains', 'toto', true),
            array('toto', 'contains', 'tot', true),
            array('tot', 'contains', 'toto', false),
            array('1006', 'contains', '[0-9]{4}', true),
        );
    }
}

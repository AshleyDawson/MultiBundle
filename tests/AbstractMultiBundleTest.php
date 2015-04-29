<?php

namespace AshleyDawson\MultiBundle\Tests;

use AshleyDawson\MultiBundle\AbstractMultiBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DummyDependantOneBundle extends Bundle
{

}

class DummyDependantTwoBundle extends Bundle
{

}

class DummyThreeBundle extends Bundle
{

}

class DummyParentBundle extends AbstractMultiBundle
{
    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    protected static function getBundles()
    {
        return array(
            new DummyDependantOneBundle(),
            new DummyDependantTwoBundle(),
            new DummyThreeBundle(),
        );
    }
}

/**
 * Class AbstractMultiBundleTest
 * @package Ashleydawson\MultiBundle\Tests
 */
class AbstractMultiBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterInto()
    {
        $bundles = array(
            new DummyThreeBundle(),
            new DummyParentBundle(),
        );

        DummyParentBundle::registerInto($bundles);

        print_r($bundles);
    }
}
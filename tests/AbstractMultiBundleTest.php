<?php

namespace AshleyDawson\MultiBundle\Tests;

use AshleyDawson\MultiBundle\AbstractMultiBundle;

/**
 * Class DummyParentNoDependantsBundle
 *
 * @package AshleyDawson\MultiBundle\Tests
 */
class DummyParentNoDependantsBundle extends AbstractMultiBundle
{

}

/**
 * Class DummyParentDependantsBundle
 *
 * @package AshleyDawson\MultiBundle\Tests
 */
class DummyParentDependantsBundle extends AbstractMultiBundle
{
    protected function __construct()
    {
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    protected static function getBundles()
    {
        return array(
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree(),
        );
    }
}

/**
 * Class DummyParentDependantsAltBundle
 *
 * @package AshleyDawson\MultiBundle\Tests
 */
class DummyParentDependantsAltBundle extends AbstractMultiBundle
{
    protected function __construct()
    {
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    protected static function getBundles()
    {
        return array(
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleFour(),
        );
    }
}

/**
 * Class DummyParentGroupedDependantsBundle
 *
 * @package AshleyDawson\MultiBundle\Tests
 */
class DummyParentGroupedDependantsBundle extends AbstractMultiBundle
{
    protected function __construct()
    {
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    protected static function getBundles()
    {
        return array(
            'prod' => array(
                new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne(),
                new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo(),
            ),
            'dev' => array(
                new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree(),
            ),
        );
    }
}

/**
 * Class DummyInvalidReturnTypeBundle
 *
 * @package AshleyDawson\MultiBundle\Tests
 */
class DummyInvalidReturnTypeBundle extends AbstractMultiBundle
{
    protected function __construct()
    {
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    protected static function getBundles()
    {
        return 1;
    }
}

/**
 * Class AbstractMultiBundleTest
 *
 * @package Ashleydawson\MultiBundle\Tests
 *
 * @backupStaticAttributes enabled
 */
class AbstractMultiBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterIntoNoDependantsNoPrevious()
    {
        $bundles = array();

        DummyParentNoDependantsBundle::registerInto($bundles);

        $this->assertCount(1, $bundles);

        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\DummyParentNoDependantsBundle', $bundles);
    }

    public function testRegisterIntoNoDependantsPrevious()
    {
        $bundles = array(
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo(),
        );

        DummyParentNoDependantsBundle::registerInto($bundles);

        $this->assertCount(3, $bundles);

        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\DummyParentNoDependantsBundle', $bundles);
    }

    public function testRegisterIntoDependantsNoPrevious()
    {
        $bundles = array();

        DummyParentDependantsBundle::registerInto($bundles);

        $this->assertCount(4, $bundles);

        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\DummyParentDependantsBundle', $bundles);
    }

    public function testRegisterIntoDependantsPrevious()
    {
        $bundles = array(
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo(),
        );

        DummyParentDependantsBundle::registerInto($bundles);

        $this->assertCount(4, $bundles);

        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\DummyParentDependantsBundle', $bundles);
    }

    public function testRegisterIntoGroupedDependantsNoPrevious()
    {
        $bundles = array();

        DummyParentGroupedDependantsBundle::registerInto($bundles, 'prod');

        $this->assertCount(3, $bundles);

        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\DummyParentGroupedDependantsBundle', $bundles);

        $bundles = array_filter($bundles, function($bundle){
            return ($bundle instanceof DummyParentGroupedDependantsBundle);
        });

        DummyParentGroupedDependantsBundle::registerInto($bundles, 'dev');

        $this->assertCount(2, $bundles);

        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree', $bundles);
    }

    public function testEnvironmentNotFound()
    {
        $this->setExpectedException('InvalidArgumentException');

        $bundles = array();

        DummyParentDependantsBundle::registerInto($bundles, 'prod');
    }

    public function testInvalidGetBundlesReturnType()
    {
        $this->setExpectedException('UnexpectedValueException');

        $bundles = array();

        DummyInvalidReturnTypeBundle::registerInto($bundles);
    }

    public function testMoreThanOneMultiBundleRegistrationNoPrevious()
    {
        $bundles = array();

        DummyParentDependantsBundle::registerInto($bundles);

        DummyParentDependantsAltBundle::registerInto($bundles);

        $this->assertCount(6, $bundles);

        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\DummyParentDependantsBundle', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleFour', $bundles);
        $this->_assertInstanceInArray('AshleyDawson\MultiBundle\Tests\DummyParentDependantsAltBundle', $bundles);
    }

    private function _assertInstanceInArray($expected, array $haystack)
    {
        foreach ($haystack as $item) {
            if ($expected == get_class($item)) {
                $this->assertTrue(true, sprintf('Instance "%s" found in array', $expected));
                return;
            }
        }

        $this->assertTrue(false, sprintf('Instance "%s" not found in array', $expected));
    }
}
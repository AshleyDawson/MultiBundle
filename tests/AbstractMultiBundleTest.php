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
 * Class DummyParentGroupedDependantsBundle
 *
 * @package AshleyDawson\MultiBundle\Tests
 */
class DummyParentGroupedDependantsBundle extends AbstractMultiBundle
{
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

        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\DummyParentNoDependantsBundle', $bundles[0]);
    }

    public function testRegisterIntoNoDependantsPrevious()
    {
        $bundles = array(
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo(),
        );

        DummyParentNoDependantsBundle::registerInto($bundles);

        $this->assertCount(3, $bundles);

        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles[0]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles[1]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\DummyParentNoDependantsBundle', $bundles[2]);
    }

    public function testRegisterIntoDependantsNoPrevious()
    {
        $bundles = array();

        DummyParentDependantsBundle::registerInto($bundles);

        $this->assertCount(4, $bundles);

        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles[0]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles[1]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree', $bundles[2]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\DummyParentDependantsBundle', $bundles[3]);
    }

    public function testRegisterIntoDependantsPrevious()
    {
        $bundles = array(
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne(),
            new \AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo(),
        );

        DummyParentDependantsBundle::registerInto($bundles);

        $this->assertCount(4, $bundles);

        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles[0]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles[1]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree', $bundles[2]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\DummyParentDependantsBundle', $bundles[3]);
    }

    public function testRegisterIntoGroupedDependantsNoPrevious()
    {
        $bundles = array();

        DummyParentGroupedDependantsBundle::registerInto($bundles, 'prod');

        $this->assertCount(3, $bundles);

        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleOne', $bundles[0]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleTwo', $bundles[1]);
        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\DummyParentGroupedDependantsBundle', $bundles[2]);

        $bundles = array();

        DummyParentGroupedDependantsBundle::registerInto($bundles, 'dev');

        $this->assertCount(1, $bundles);

        $this->assertInstanceOf('AshleyDawson\MultiBundle\Tests\Fixture\DummyBundleThree', $bundles[0]);
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
}
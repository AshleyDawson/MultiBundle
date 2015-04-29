<?php

namespace AshleyDawson\MultiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AbstractMultiBundle
 *
 * @package AshleyDawson\MultiBundle
 */
abstract class AbstractMultiBundle extends Bundle
{
    /**
     * Register this bundle collection into the Symfony kernel, e.g.
     *
     * <code>
     * // app/AppKernel.php
     *
     * // ...
     *
     * class AppKernel extends Kernel
     * {
     *     // ...
     *
     *     public function registerBundles()
     *     {
     *         $bundles = array(
     *             // ...,
     *             new FOS\UserBundle\FOSUserBundle(),
     *         );
     *
     *         // Without environment grouping
     *         Acme\FooBundle\AcmeFooBundle::registerInto($bundles);
     *
     *         // With environment grouping
     *         Acme\FooBundle\AcmeFooBundle::registerInto($bundles, 'prod');
     *
     *         // ...
     *     }
     * }
     * </code>
     *
     * @param \Symfony\Component\HttpKernel\Bundle\BundleInterface[] $bundles
     * @param string|null $env Arbitrary name of environment group to use (optional)
     * @return void
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public static function registerInto(array &$bundles, $env = null)
    {
        // Must be called using late static binding
        $calledClass = get_called_class();
        $dependencies = $calledClass::getBundles();

        // Is an environment grouping being used?
        if (null !== $env) {
            if ( ! isset($dependencies[$env])) {
                throw new \InvalidArgumentException(
                    sprintf('Environment group "%s" is not set in array returned from %s::getBundles()', $env, $calledClass));
            }
            $dependencies = $dependencies[$env];
        }

        // Sanity check of return type
        if ( ! is_array($dependencies)) {
            throw new \UnexpectedValueException(
                sprintf('Return type of %s::getBundles() must be an array, %s given', $calledClass, gettype($dependencies)));
        }

        // Register myself
        $dependencies[] = new $calledClass();

        // Remove duplicates
        foreach ($bundles as $bundle) {
            foreach ($dependencies as $k => $dependency) {
                if ($bundle instanceof $dependency) {
                    unset($dependencies[$k]);
                }
            }
        }

        $bundles = array_merge($bundles, $dependencies);
    }

    /**
     * Get array of bundles to register with kernel. This method
     * is overridden in your bundle. E.g.
     *
     * <code>
     * // Without environment grouping
     * protected static function getBundles()
     * {
     *     return array(
     *         new Acme\WelcomeBundle\AcmeWelcomeBundle(),
     *         new Acme\DemoBundle\AcmeDemoBundle(),
     *     );
     * }
     *
     * // With environment grouping
     * protected static function getBundles()
     * {
     *     return array(
     *         'prod' => array(
     *             new Acme\WelcomeBundle\AcmeWelcomeBundle(),
     *         ),
     *         'dev' => array(
     *             new Acme\DemoBundle\AcmeDemoBundle(),
     *         ),
     *     );
     * }
     * </code>
     *
     * The array of classes represents your bundle and all of the bundles
     * it depends on
     *
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface[]
     */
    protected static function getBundles()
    {
        return array();
    }
}

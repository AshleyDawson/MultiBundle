Symfony2 MultiBundle
====================

[![Build Status](https://travis-ci.org/AshleyDawson/MultiBundle.svg)](https://travis-ci.org/AshleyDawson/MultiBundle)

Register multiple, dependant bundles within the [Symfony2](http://symfony.com) kernel

Requirements
------------

```
 >= PHP 5.3
 >= Symfony Framework 2.3
```

Introduction
------------

When developing solutions that use a multi-bundle configuration in Symfony2 - this library provides an unobtrusive way of logically grouping dependant bundles together so that they can be registered with the Symfony kernel in one command.

The reason for building this helper is so that you can manage parallel dependant bundles. Alternatively you could use [bundle inheritance](http://symfony.com/doc/current/cookbook/bundles/inheritance.html) to solve this problem - but that may violate the single responsibility principal as the child bundle may have a different responsibility to its parent.

Installation
------------

You can install multi-bundle via Composer. To do that, simply require the package in your composer.json file like so:

```json
{
    "require": {
        "ashleydawson/multibundle": "~1.0"
    }
}
```

Run composer update to install the package.

Basic Usage
-----------

Step one is to extend the AbstractMultiBundle instead of the Bundle that ships with Symfony2. This abstract class allows
you to define your grouped bundles as well as expose a registerInto() method for use in the Symfony2 kernel.

```php
<?php

namespace Acme\MyBundle;

use AshleyDawson\MultiBundle\AbstractMultiBundle;

class AcmeMyBundle extends AbstractMultiBundle
{
    /**
     * Optional: define a protected constructor to stop instantiation outside of registerInto()
     */
    protected function __construct()
    {

    }

    /**
     * Define bundles that this bundle depends on
     */
    protected static function getBundles()
    {
        return array(
            new Acme\FooBundle\AcmeFooBundle(),
            new Acme\BarBundle\AcmeBarBundle(),
        );
    }
}
```

Step two is to then register your bundle in the Symfony2 kernel, like so:

```php
// app/AppKernel.php

// ...

class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = array(
            // ...,
            new FOS\UserBundle\FOSUserBundle(),
        );

        // Register my bundle and its dependencies
        \Acme\MyBundle\AcmeMyBundle::registerInto($bundles);

        // ...
    }
}
```

**Note:** You don't need to register the dependencies in the usual way now as the `registerInto()` method takes care of
that. Also, don't worry about your bundle trying to register duplicate bundles as de-duplication is built in.

**Note:** The `registerInto()` method will automatically register the parent bundle as well as it's dependencies, so you
don't need to specify the parent bundle in the `getBundles()` return array.

Environment Grouping
--------------------

Sometimes it's necessary to group bundles by environment. An example of this is that you may not require development bundles
in production. To do this, simply specify the environment groups within the `getBundles()` method:

```php
<?php

namespace Acme\MyBundle;

use AshleyDawson\MultiBundle\AbstractMultiBundle;

class AcmeMyBundle extends AbstractMultiBundle
{
    /**
     * Optional: define a protected constructor to stop instantiation outside of registerInto()
     */
    protected function __construct()
    {

    }

    /**
     * Define bundles that this bundle depends on
     */
    protected static function getBundles()
    {
        return array(
            'prod' => array(
                new Acme\FooBundle\AcmeFooBundle(),
                new Acme\BarBundle\AcmeBarBundle(),
            ),
            'dev' => array(
                new Acme\BazBundle\AcmeBazBundle(),
            ),
        );
    }
}
```

Then, in the kernel, filter the registration of bundles using the second argument of the `registerInto()` method:

```php
// app/AppKernel.php

// ...

class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = array(
            // ...,
            new FOS\UserBundle\FOSUserBundle(),
        );

        // Register my bundle and its dependencies for the 'prod' environment
        \Acme\MyBundle\AcmeMyBundle::registerInto($bundles, 'prod');

        if ('dev' == $this->getEnvironment()) {

            // Register my bundle and its dependencies for the 'dev' environment
            \Acme\MyBundle\AcmeMyBundle::registerInto($bundles, 'dev');
        }
    }
}
```

The Symfony2 standard edition already has similar logic in the `app/AppKernel.php`.

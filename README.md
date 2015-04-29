Symfony2 MultiBundle
====================

[![Build Status](https://travis-ci.org/AshleyDawson/MultiBundle.svg)](https://travis-ci.org/AshleyDawson/MultiBundle)

Register multiple bundles within the Symfony2 kernel

Requirements
------------

```
 >= PHP 5.3
 >= Symfony Framework 2.3
```

Introduction
------------

When developing solutions that use a multi-bundle configuration in Symfony2 - this library provides an unobtrusive way
of logically grouping dependant bundles together so that they can be registered with the Symfony kernel in one command.

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
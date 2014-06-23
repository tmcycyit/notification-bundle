NotificationBundle
======================

## Installation
-----------------------

### Step1: Download NotificationBundle using composer

Add NotificationBundle in your composer.json:

```js
{
    "require": {
        "friendsofsymfony/user-bundle": "~1.3"
    }
}
```

Now update composer.

Composer will install the bundle to your project's `vendor/yit` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Yit\NotificationBundle\YitNotificationBundle(),
    );
}
```

### Step 3: Configure the NotificationBundle

Add the following configuration to your `config.yml` file

``` yaml
# app/config/config.yml
yit_notification:
    note_user: Yit\UserBundle\Entity\User
```

###Step 4: Import NotificationBundle routing files

`` yaml
# app/config/routing.yml
yit_notification:
    resource: "@YitNotificationBundle/Controller/"
    type:     annotation
    prefix:   /

### Step 5: Update your database schema

Now that the bundle is configured, the last thing you need to do is update your
database schema.



AxeleroMailgunBundle
======================

1. [Installation](#1-installation)
2. [Configuration](#2-configuration)
3. [Usage](#3-usage)

### 1. Installation

Run from terminal:

```bash
$ composer require axelero/axelero-mailgun-bundle
```

Enable bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Axelero\MailgunBundle\AxeleroMailgunBundle()

    );
}
```

### 2. Configuration

Add following lines in your configuration:

``` yaml
# app/config/config.yml

axelero_mailgun:
  domain: 'your-domain'
  api_key: 'your-api-key'

```


``` yaml
# app/config/config_dev.yml

axelero_mailgun:
  domain: 'your-sandbox-domain'
  api_key: 'your-sandbox-api-key'
```


Otherwise the default value `post` will be used.

### 3. Usage

Example:

``` php
    ...
    //collecting the recipients
    $recipients = [
        [
            'email' => 'first@email.com',
            'first' => 'firstName',
            'last' => 'lastName'
        ],
        [
            'email' => 'second@email.com',
            'first' => 'anotherName',
            'last' => 'anotherSurname',
            'myCustomData' => 'customValue',
            'anotherCustom' => ['my', 'data'],
        ],
    ];

  ```
  For myCustomData see [https://github.com/mailgun/mailgun-php/blob/v1.8/src/Mailgun/Messages/README.md] 
  
  
  By default you can send mails with the account configured in your config.yml
  
``` php
    $this->gunman
    ->batchSend('from@email.com', 'mailTitle', '<h1>Your Content</h1>', $recipients);
```

Otherwise you can configure api parameters at runtime

``` php
    $this->gunman
    ->setKey($key)
    ->setDomain($domain)
    ->batchSend('from@email.com', 'mailTitle', '<h1>Your Content</h1>', $recipients);
```

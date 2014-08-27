# Listeners debug Command for Symfony2 console [![Build Status](https://travis-ci.org/egulias/TagDebugCommandBundle.png?branch=master)](https://travis-ci.org/egulias/TagDebugCommandBundle) [![Coverage Status](https://coveralls.io/repos/egulias/TagDebugCommandBundle/badge.png?branch=master)](https://coveralls.io/r/egulias/TagDebugCommandBundle?branch=master)[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/egulias/TagDebugCommandBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/egulias/TagDebugCommandBundle/?branch=master)

This bundle provides a simple command `container:debug:listeners` to allow to easily debug listeners by
providing useful information about those defined in the app. It will fetch information about all the listeners
tagged with .event_listener

# Usage

As for any command you should use: `app/console` from your project root.
The command is:
`app/console container:tag-debug`

## Available options

* --show-private :  if issued will show also private services
* --filter:         can be many of this. The form is `--filter name=param1,param2` for each filter,
                    where `param` are the parameters for the given filter

# Installation and configuration

## Get the bundle
Add to your composer.json

##Symfony >= 2.3

```
{
    "require": {
        "egulias/listeners-debug-command-bundle": "1.9.0"
    }
}
```

Use composer to download the new requirement
``` 
$ php composer.phar update egulias/listeners-debug-command-bundle
```

## Add TagDebugCommandBundle to your application kernel

``` php
<?php

  // app/AppKernel.php
  public function registerBundles()
  {
    return array(
      // ...
      new Egulias\TagDebugCommandBundle\EguliasTagDebugCommandBundle(),
      // ...
      );
  }
```

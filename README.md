# Tag debug Command for Symfony2 console [![Build Status](https://travis-ci.org/egulias/TagDebugCommandBundle.png?branch=master)] (https://travis-ci.org/egulias/TagDebugCommandBundle) [![Coverage Status](https://coveralls.io/repos/egulias/TagDebugCommandBundle/badge.png?branch=master)](https://coveralls.io/r/egulias/TagDebugCommandBundle?branch=master)[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/egulias/TagDebugCommandBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/egulias/TagDebugCommandBundle/?branch=master)

This bundle provides a simple command `container:tag-debug` to allow to easily debug tagged services by
providing useful information about those defined in the app.
It will fetch information about all the tagged services.

You can apply [filters](#filters) and define [your own filters](#custom-filters)

# Usage

As for any command you should use: `app/console` from your project root.
The command is:
`app/console container:tag-debug`

## Available options

* --show-private :  if issued will show also private services
* --filter:         can be many of this. The form is `--filter name=param1,param2` for each filter,
                    where `param` are the parameters for the given filter

### Available filters (#filters)
* Name (name): Filter by tag name, exact match. Requires one parameter, e.g : --filter name=tag_name
* Attribute Name (attribute_name): Filter by tag attribute name, exact match. Requires one parameter, e.g : --filter attribute_name=attr_name
* Attribute Value (attribute_value): Filter by tag attribute value, exact match. Requires two parameters, e.g: --filter attribute_value=attr_name,attr_value
* NameRegEx (name_regex): Filter by tag name, giving a regular expression. No need to provide a separator (`~` is used internally). Requiers one parameter, e.g : --filter name_regex=regex

For more information see [TagDebug lib](https://github.com/egulias/TagDebug)

###Sample usage and output
Sampel Filter
-----
`app/console sf container:tag-debug --filter name=kernel.event_listener` [Here](https://gist.github.com/egulias/143a3c458190f206f730)

Two filters, one with multiple values
-----
`app/console container:tag-debug --filter name=kernel.event_listener --filter attribute_value=event,kernel.controller` [Here](https://gist.github.com/egulias/4341f7dfc3206cca5ef8)

# Installation and configuration

## Get the bundle
Add to your composer.json

##Symfony >= 2.3

```
{
    "require": {
        "egulias/tag-debug-command-bundle": "~1.0"
    }
}
```

Use composer to download the new requirement
``` 
$ php composer.phar update egulias/tag-debug-command-bundle
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

## Configure your own filters (#custom-filters)
To create your custom filter follow this steps

1. Implement `Egulias\TagDebug\Tag\Filter` interface in your filter class.
    The filter will receive by constructor as many arguments as you define, from the console. See the examples above.
    Remember to add a constructor if you want to receive parameters.
2. On your config file add:
```yaml
egulias_tag_debug_command:
    filters:
        - {class: Fully\Qualified\Filter\Class\Name, name:"filter_console_name"}
```
3. Enjoy!




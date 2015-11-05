# Zend\\Config\\Reader

`Zend\Config\Reader` gives you the ability to read a config file. It works with concrete
implementations for different file format. The `Zend\Config\Reader` is only an interface, that
define the two methods `fromFile()` and `fromString()`. The concrete implementations of this
interface are:

- `Zend\Config\Reader\Ini`
- `Zend\Config\Reader\Xml`
- `Zend\Config\Reader\Json`
- `Zend\Config\Reader\Yaml`
- `Zend\Config\Reader\JavaProperties`

The `fromFile()` and `fromString()` return a PHP array contains the data of the configuration file.

> ## Note
#### Differences from ZF1
The `Zend\Config\Reader` component no longer supports the following features:
- Inheritance of sections.
- Reading of specific sections.

## Zend\\Config\\Reader\\Ini

`Zend\Config\Reader\Ini` enables developers to store configuration data in a familiar *INI* format
and read them in the application by using an array syntax.

`Zend\Config\Reader\Ini` utilizes the [parse\_ini\_file()](http://php.net/parse_ini_file) *PHP*
function. Please review this documentation to be aware of its specific behaviors, which propagate to
`Zend\Config\Reader\Ini`, such as how the special values of "`TRUE`", "`FALSE`", "yes", "no", and
"`NULL`" are handled.

> ## Note
#### Key Separator
By default, the key separator character is the period character ("**.**"). This can be changed,
however, using the `setNestSeparator()` method. For example:
``` sourceCode
$reader = new Zend\Config\Reader\Ini();
$reader-setNestSeparator('-');
```

The following example illustrates a basic use of `Zend\Config\Reader\Ini` for loading configuration
data from an *INI* file. In this example there are configuration data for both a production system
and for a staging system. Suppose we have the following INI configuration file:

``` sourceCode
webhost                  = 'www.example.com'
database.adapter         = 'pdo_mysql'
database.params.host     = 'db.example.com'
database.params.username = 'dbuser'
database.params.password = 'secret'
database.params.dbname   = 'dbproduction'
```

We can use the `Zend\Config\Reader\Ini` to read this INI file:

``` sourceCode
$reader = new Zend\Config\Reader\Ini();
$data   = $reader->fromFile('/path/to/config.ini');

echo $data['webhost'];  // prints "www.example.com"
echo $data['database']['params']['dbname'];  // prints "dbproduction"
```

The `Zend\Config\Reader\Ini` supports a feature to include the content of a INI file in a specific
section of another INI file. For instance, suppose we have an INI file with the database
configuration:

``` sourceCode
database.adapter         = 'pdo_mysql'
database.params.host     = 'db.example.com'
database.params.username = 'dbuser'
database.params.password = 'secret'
database.params.dbname   = 'dbproduction'
```

We can include this configuration in another INI file, for instance:

``` sourceCode
webhost  = 'www.example.com'
@include = 'database.ini'
```

If we read this file using the component `Zend\Config\Reader\Ini` we will obtain the same
configuration data structure of the previous example.

The `@include = 'file-to-include.ini'` can be used also in a subelement of a value. For instance we
can have an INI file like that:

``` sourceCode
adapter         = 'pdo_mysql'
params.host     = 'db.example.com'
params.username = 'dbuser'
params.password = 'secret'
params.dbname   = 'dbproduction'
```

And assign the `@include` as subelement of the database value:

``` sourceCode
webhost           = 'www.example.com'
database.@include = 'database.ini'
```

## Zend\\Config\\Reader\\Xml

`Zend\Config\Reader\Xml` enables developers to read configuration data in a familiar *XML* format
and read them in the application by using an array syntax. The root element of the *XML* file or
string is irrelevant and may be named arbitrarily.

The following example illustrates a basic use of `Zend\Config\Reader\Xml` for loading configuration
data from an *XML* file. Suppose we have the following *XML* configuration file:

``` sourceCode
<?xml version="1.0" encoding="utf-8"?>
<config>
    <webhost>www.example.com</webhost>
    <database>
        <adapter value="pdo_mysql"/>
        <params>
            <host value="db.example.com"/>
            <username value="dbuser"/>
            <password value="secret"/>
            <dbname value="dbproduction"/>
        </params>
    </database>
</config>
```

We can use the `Zend\Config\Reader\Xml` to read this XML file:

``` sourceCode
$reader = new Zend\Config\Reader\Xml();
$data   = $reader->fromFile('/path/to/config.xml');

echo $data['webhost'];  // prints "www.example.com"
echo $data['database']['params']['dbname']['value'];  // prints "dbproduction"
```

`Zend\Config\Reader\Xml` utilizes the [XMLReader](http://php.net/xmlreader) *PHP* class. Please
review this documentation to be aware of its specific behaviors, which propagate to
`Zend\Config\Reader\Xml`.

Using `Zend\Config\Reader\Xml` we can include the content of XML files in a specific XML element.
This is provided using the standard function [XInclude](http://www.w3.org/TR/xinclude/) of XML. To
use this function you have to add the namespace `xmlns:xi="http://www.w3.org/2001/XInclude"` to the
XML file. Suppose we have an XML files that contains only the database configuration:

``` sourceCode
<?xml version="1.0" encoding="utf-8"?>
<config>
    <database>
        <adapter>pdo_mysql</adapter>
        <params>
            <host>db.example.com</host>
            <username>dbuser</username>
            <password>secret</password>
            <dbname>dbproduction</dbname>
        </params>
    </database>
</config>
```

We can include this configuration in another XML file, for instance:

``` sourceCode
<?xml version="1.0" encoding="utf-8"?>
<config xmlns:xi="http://www.w3.org/2001/XInclude">
    <webhost>www.example.com</webhost>
    <xi:include href="database.xml"/>
</config>
```

The syntax to include an XML file in a specific element is `<xi:include
href="file-to-include.xml"/>`

## Zend\\Config\\Reader\\Json

`Zend\Config\Reader\Json` enables developers to read configuration data in a *JSON* format and read
them in the application by using an array syntax.

The following example illustrates a basic use of `Zend\Config\Reader\Json` for loading configuration
data from a *JSON* file. Suppose we have the following *JSON* configuration file:

``` sourceCode
{
  "webhost"  : "www.example.com",
  "database" : {
    "adapter" : "pdo_mysql",
    "params"  : {
      "host"     : "db.example.com",
      "username" : "dbuser",
      "password" : "secret",
      "dbname"   : "dbproduction"
    }
  }
}
```

We can use the `Zend\Config\Reader\Json` to read this JSON file:

``` sourceCode
$reader = new Zend\Config\Reader\Json();
$data   = $reader->fromFile('/path/to/config.json');

echo $data['webhost'];  // prints "www.example.com"
echo $data['database']['params']['dbname'];  // prints "dbproduction"
```

`Zend\Config\Reader\Json` utilizes the \[Zend\\Json\\Json\](zend.json.introduction) class.

Using `Zend\Config\Reader\Json` we can include the content of a JSON file in a specific JSON section
or element. This is provided using the special syntax `@include`. Suppose we have a JSON file that
contains only the database configuration:

``` sourceCode
{
  "database" : {
    "adapter" : "pdo_mysql",
    "params"  : {
      "host"     : "db.example.com",
      "username" : "dbuser",
      "password" : "secret",
      "dbname"   : "dbproduction"
    }
  }
}
```

We can include this configuration in another JSON file, for instance:

``` sourceCode
{
    "webhost"  : "www.example.com",
    "@include" : "database.json"
}
```

## Zend\\Config\\Reader\\Yaml

`Zend\Config\Reader\Yaml` enables developers to read configuration data in a *YAML* format and read
them in the application by using an array syntax. In order to use the YAML reader we need to pass a
callback to an external PHP library or use the [Yaml PECL
extension](http://www.php.net/manual/en/book.yaml.php).

The following example illustrates a basic use of `Zend\Config\Reader\Yaml` that use the Yaml PECL
extension. Suppose we have the following *YAML* configuration file:

``` sourceCode
webhost: www.example.com
database:
    adapter: pdo_mysql
    params:
      host:     db.example.com
      username: dbuser
      password: secret
      dbname:   dbproduction
```

We can use the `Zend\Config\Reader\Yaml` to read this YAML file:

``` sourceCode
$reader = new Zend\Config\Reader\Yaml();
$data   = $reader->fromFile('/path/to/config.yaml');

echo $data['webhost'];  // prints "www.example.com"
echo $data['database']['params']['dbname'];  // prints "dbproduction"
```

If you want to use an external YAML reader you have to pass the callback function in the constructor
of the class. For instance, if you want to use the [Spyc](http://code.google.com/p/spyc/) library:

``` sourceCode
// include the Spyc library
require_once ('path/to/spyc.php');

$reader = new Zend\Config\Reader\Yaml(array('Spyc','YAMLLoadString'));
$data   = $reader->fromFile('/path/to/config.yaml');

echo $data['webhost'];  // prints "www.example.com"
echo $data['database']['params']['dbname'];  // prints "dbproduction"
```

You can also instantiate the `Zend\Config\Reader\Yaml` without any parameter and specify the YAML
reader in a second moment using the `setYamlDecoder()` method.

Using `Zend\Config\ReaderYaml` we can include the content of a YAML file in a specific YAML section
or element. This is provided using the special syntax `@include`. Suppose we have a YAML file that
contains only the database configuration:

``` sourceCode
database:
    adapter: pdo_mysql
    params:
      host:     db.example.com
      username: dbuser
      password: secret
      dbname:   dbproduction
```

We can include this configuration in another YAML file, for instance:

``` sourceCode
webhost:  www.example.com
@include: database.yaml
```

Zend\\Config\\Reader\\JavaProperties -------------------------

`Zend\Config\Reader\JavaProperties` enables developers to read configuration data in a familiar
*JavaProperties* format and read them in the application by using an array syntax.

The following example illustrates a basic use of `Zend\Config\Reader\JavaProperties` for loading
configuration data from an *JavaProperties* file. Suppose we have the following *JavaProperties*
configuration file:

``` sourceCode
#comment
!comment
webhost:www.example.com
database.adapter:pdo_mysql
database.params.host:db.example.com
database.params.username:dbuser
database.params.password:secret
database.params.dbname:dbproduction
```

We can use the `Zend\Config\Reader\JavaProperties` to read this JavaProperties file:

``` sourceCode
$reader = new Zend\Config\Reader\JavaProperties();
$data   = $reader->fromFile('/path/to/config.properties');

echo $data['webhost'];  // prints "www.example.com"
echo $data['database.params.dbname'];  // prints "dbproduction"
```

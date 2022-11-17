# Aproxi

Aproxi is an API Proxy made by PHP.

It can be used for API request limit countermeasures and content caching. By default, it only supports WordPress REST API, but you can develop modules to support other APIs.


## Usage

First, download the library using Composer.

```
$ composer require green-spot/aproxi
```

Next, copy the file that will be the API endpoint to the document root.
```
$ cp -r vendor/green-spot/aproxi/api ./api
```

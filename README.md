# Aproxi

Aproxi is an API Proxy made by PHP.

It can be used for API request limit countermeasures and content caching. By default, it only supports WordPress REST API, but you can develop modules to support other APIs.


## Usage

First, download the library using Composer.

```
$ composer require green-spot/aproxi
```

Next, copy the API endpoint directory to the document root.
```
$ cp -r vendor/green-spot/aproxi/api ./api
```

Edit the config file. (`api/settings.php`)


## WordPress Module

For WordPress modules, `/wp-json/wp/v2/` is mapped to `/api/wp/` by default.

https://backend.example.com/wp/wp-json/wp/v2/posts<br>
↓<br>
https://frontend.example.com/api/wp/posts

You can also change the endpoint URL(`api/wp/`) by editing `settings.php`.

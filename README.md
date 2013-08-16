# Buster

Cache bust asset files by including a hash of the file within the file name.

![Buster](http://images2.wikia.nocookie.net/__cb20111027201442/arresteddevelopment/images/thumb/5/5d/Buster.jpg/360px-Buster.jpg)

## A note about support

While we have incentive to keep this project working because we use it
frequently, we are not always available to provide support for the Buster
plugin. We therefore offer it to you, free of charge, but with no guarantee of
support. Find something that's not working? Or could be improved? By all
means, fix it! Submit a pull request, and we'll pull it into the project so
everyone can benefit. But please, no hard feelings if we can't help you when
it's not working. Go forth and Open Source.

## Requirements

* EE 2.0
* PHP 5 >= 5.3

## Installation

1. Copy the "buster" folder to ExpressionEngine's third-party add-ons
directory. (e.g. `/system/expressionengine/third_party/`)

## Usage

First, include the following Apache Mod Rewrite code in your .htaccess file.
This will make it possible to reference asset files with cache-busting junk
shoved in the middle. For example, you can then reference `script.js` as
`script.8c9fcf8364b72ec65c233629375c241763bf245b.js`. This section is slightly
modified from [HTML5 Boilerplate's .htaccess](https://github.com/h5bp/html5-bo
ilerplate/blob/master/.htaccess#L511) file.

```
<IfModule mod_rewrite.c>
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^(.+)\.([0-9a-f]+)\.(js|css|png|jpg|gif)$ $1.$3 [L]
</IfModule>
```

Then, replace

```
<link rel="stylesheet" href="/assets/css/style.css">
```

with

```
<link rel="stylesheet" href="{exp:buster href='/assets/css/style.css'}">
```

## Parameters

Buster accepts either an `href` or a `src` parameter which do exactly the same
thing. Instead of choosing which of the two parameters is more correct – and
constantly forget which is the correct parameter — either is allowed.

*Implementation note*: if both an `href` and a `src` parameter are specified,
the `href` will be used.

## Caveats

* The referenced file *must* be relative to the document root. No relative
  paths allowed.
* Error checking is at a minimum.
* This is worthless unless the .htaccess is correct.

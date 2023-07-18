# Ashei

<p align="center"><img alt="Valravn Logo" src="assets/ashei-banner.png"></p>

[![codecov](https://codecov.io/gh/hans-thomas/ashei/branch/master/graph/badge.svg?token=X1D6I0JLSZ)](https://codecov.io/gh/hans-thomas/ashei)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/hans-thomas/ashei/php.yml)
![GitHub top language](https://img.shields.io/github/languages/top/hans-thomas/ashei)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/hans-thomas/ashei)

Ashei is a epub parser that allows you to get epub books content.

## Installation

Clone latest release via cURL.

```bash
composer require hans-thomas/ashei

```

then, publish config file.

```bash
php artisan vendor:publish --tag ashei-config

```

## Usage

### read

To get whole content at once.

```php
use Hans\Ashei\Facades\Ashei;

Ashei::read( '/path/to/ebook.epub' );
```

It will return an array like [this](https://github.com/hans-thomas/ashei/blob/master/tests/resources/chapter-one.php).

### iterator

To get a large epub file's content, you can use `iterator` method to get one limited part of the epub file in each
iteration.

```php
use Hans\Ashei\Facades\Ashei;

foreach ( Ashei::iterator( '/path/to/ebook.epub' ) as $number => $page ) {
    // ...
}
```

### setParagraphLength

Before getting content, you can set your idle paragraph length using `setParagraphLength` method. the default amount
is `2000`.

```php
use Hans\Ashei\Facades\Ashei;

Ashei::setParagraphLength( 50 )->read( '/path/to/ebook.epub' );
```

## Contributing

1. Fork it!
2. Create your feature branch: git checkout -b my-new-feature
3. Commit your changes: git commit -am 'Add some feature'
4. Push to the branch: git push origin my-new-feature
5. Submit a pull request ❤️

Support
-------

- [Report bugs](https://github.com/hans-thomas/ashei/issues)
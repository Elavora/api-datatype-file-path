# api-datatype-file-path

[![Packagist Version](https://img.shields.io/packagist/v/elavora/api-datatype-file-path.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-file-path)
[![PHP Version](https://img.shields.io/packagist/php-v/elavora/api-datatype-file-path.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-file-path)
[![Composer Quality](https://github.com/Elavora/api-datatype-file-path/actions/workflows/quality.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-file-path/actions/workflows/quality.yml)
[![CodeQL](https://github.com/Elavora/api-datatype-file-path/actions/workflows/codeql.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-file-path/actions/workflows/codeql.yml)
[![License](https://img.shields.io/packagist/l/elavora/api-datatype-file-path.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-file-path)

DataType imutavel para validar caminhos relativos de arquivo.

## Requisitos

- PHP 8.3 ou superior.
- Demais requisitos declarados em [`composer.json`](composer.json).

## Instalacao

```bash
composer require elavora/api-datatype-file-path
```

## Inicio rapido

```php
use Elavora\Api\DataTypes\Filesystem\FilePath;

$valor = FilePath::from('avatars/user.png');
$normalizado = $valor->value();
```

Cada pasta e o nome do arquivo precisam atender aos respectivos DataTypes de filesystem.

## Documentacao

Consulte o [guia de uso](docs/USO.md) para as regras de composicao e a validacao local.

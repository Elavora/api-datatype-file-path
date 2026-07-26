# Guia de uso

`FilePath` aceita caminhos relativos separados por `/`. O ultimo segmento deve ser um `FileName` valido; os anteriores devem ser `FolderName` validos.

```php
use Elavora\Api\DataTypes\Filesystem\FilePath;

$filePath = FilePath::from('avatars/user.png');

echo $filePath->value(); // avatars/user.png
```

Caminhos absolutos, segmentos vazios, `.` e `..` sao rejeitados. As restricoes de portabilidade dos nomes de arquivo e pasta tambem se aplicam a cada segmento.

## Validacao do pacote

Execute os comandos a partir da raiz do clone:

```bash
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer update --no-interaction --no-progress --prefer-dist
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer check
```

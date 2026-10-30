# Guia de uso

DataType de caminho de arquivo para aplicacoes Elavora API.

## Instalacao

```bash
composer require elavora/api-datatype-file-path
```

## Quando usar

- Validar e normalizar valores antes de chegar na regra de negocio.
- Evitar passar strings soltas entre services, DTOs e persistencia.
- Reutilizar a mesma validacao em controllers, comandos e testes.

## Exemplo rapido

```php
use Elavora\Api\DataTypes\Filesystem\FilePath;

$valor = FilePath::from('avatars/user.png');
$normalizado = $valor->value(); // 'avatars/user.png'
```

## API publica

A classe `Elavora\Api\DataTypes\Filesystem\FilePath` e `readonly` e estende
`Elavora\Api\DataTypes\AbstractDataType`, do `elavora/api-datatype-core`. O construtor nao e publico: crie o objeto
com `from()`.

| Metodo | Retorno | O que faz |
| --- | --- | --- |
| `FilePath::from(mixed $value)` | `FilePath` | valida e cria o objeto; lanca `InvalidArgumentException` se o valor for invalido |
| `FilePath::isValid(mixed $value)` | `bool` | diz se o valor e um caminho de arquivo valido; nunca lanca excecao |
| `value()` | `string` | o caminho, exatamente como foi informado |
| `jsonSerialize()` | `string` | o mesmo valor de `value()` |
| `(string) $caminho` | `string` | o mesmo valor de `value()` |

## Regras de validacao e normalizacao

O caminho usa `/` como separador e e **relativo**: o texto e dividido em partes por `/`. Todas as partes menos a
ultima sao nomes de pasta e a ultima e o nome do arquivo.

- Qualquer valor que nao seja `string` (inteiro, `null`, array, objeto) e invalido, e o texto vazio tambem.
- Cada parte antes da ultima precisa ser um nome de pasta valido (`FolderName`, do `elavora/api-datatype-folder-name`):
  de 1 a 255 caracteres, sem ponto (`.`) em nenhuma posicao e sem nenhum destes caracteres: `/ : * ? " < > |`.
- A ultima parte precisa ser um nome de arquivo valido (`FileName`, do `elavora/api-datatype-file-name`): de 1 a 255
  bytes, sem nenhum dos caracteres `/ : * ? " < > |`, diferente de `.` e `..`, e sem comecar nem terminar com ponto.
  O ponto no meio e permitido (`user.png`, `arquivo.tar.gz`).
- Como a primeira parte nunca pode ser vazia, um caminho que comeca com `/` (absoluto) e invalido. O mesmo vale para
  `//` no meio e para `/` no fim, que geram uma parte vazia.
- Nao ha normalizacao: nao remove espacos nem muda a caixa. `value()` devolve o texto como entrou.

| Entrada | `isValid` | `from()` |
| --- | --- | --- |
| `'avatars/user.png'` | `true` | `value()` e `'avatars/user.png'` |
| `'user.png'` (so o arquivo) | `true` | `value()` e `'user.png'` |
| `'a/b/c/arquivo.tar.gz'` | `true` | `value()` e `'a/b/c/arquivo.tar.gz'` |
| `'minha pasta/meu arquivo.txt'` | `true` | `value()` e `'minha pasta/meu arquivo.txt'` |
| `'../secret.txt'` (pasta `..`) | `false` | `InvalidArgumentException` |
| `'/secret.txt'` (absoluto) | `false` | `InvalidArgumentException` |
| `'avatars//user.png'` (pasta vazia) | `false` | `InvalidArgumentException` |
| `'avatars/'` (sem nome de arquivo) | `false` | `InvalidArgumentException` |
| `'v1.0/user.png'` (ponto no nome da pasta) | `false` | `InvalidArgumentException` |
| `'avatars/.env'` (arquivo comeca com ponto) | `false` | `InvalidArgumentException` |
| `'avatars/user.'` (arquivo termina com ponto) | `false` | `InvalidArgumentException` |
| `'avatars/us*r.png'` (caractere proibido) | `false` | `InvalidArgumentException` |
| `'avatars/' . str_repeat('a', 256)` (arquivo com mais de 255 bytes) | `false` | `InvalidArgumentException` |
| `''` | `false` | `InvalidArgumentException` |
| `123`, `null`, `[]` | `false` | `InvalidArgumentException` |

A mensagem da excecao e `Valor invalido para Elavora\Api\DataTypes\Filesystem\FilePath.`

## Principais pontos de entrada

- `Elavora\Api\DataTypes\Filesystem\FilePath`

## Dependencias de runtime

- PHP 8.3 ou superior
- `elavora/api-datatype-core` `^2.0` (que exige o `elavora/api-framework` `^2.0`)
- `elavora/api-datatype-file-name` `^1.0`
- `elavora/api-datatype-folder-name` `^1.0`

## Atualizacao da versao anterior

A versao `1.0.0` exige PHP 8.3 ou superior, o `elavora/api-datatype-core` `^2.0` e os pacotes
`elavora/api-datatype-file-name` e `elavora/api-datatype-folder-name` `^1.0`. Antes eram PHP 8.1 e core, file-name e
folder-name `^0.1`, que nao funcionam mais com o framework atual.

Quebras desta versao, que valem para os datatypes `1.0`:

- **O construtor nao e mais publico.** Crie o objeto so com `FilePath::from(...)`. Trocar `new FilePath('a/b.txt')` por
  `FilePath::from('a/b.txt')`. Chamar `new FilePath(...)` fora da classe lanca `Error` do PHP.
- **Valor invalido lanca `InvalidArgumentException`** com a mensagem
  `Valor invalido para Elavora\Api\DataTypes\Filesystem\FilePath.` Quem capturava a excecao ou comparava a mensagem
  anterior precisa se ajustar.

## Validacao no projeto consumidor

Depois de instalar o pacote, rode os testes da aplicacao consumidora.

Para validar o pacote isoladamente (testes, lint e PHPStan, e como reproduzir o CI), veja
[Desenvolvimento e testes](DESENVOLVIMENTO.md). A automacao, as versoes e os arquivos de qualidade do repositorio estao em
[Automacao do repositorio](AUTOMACAO.md).

## Observacoes

- Mantenha regras de produto fora deste pacote.
- Prefira configurar extensoes no bootstrap da aplicacao.
- Instale apenas os modulos que a aplicacao realmente usa.

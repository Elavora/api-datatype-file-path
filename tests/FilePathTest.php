<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\FilePath\Tests;

use Elavora\Api\DataTypes\Filesystem\FilePath;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Regras da tabela de docs/USO.md.
 *
 * @see docs/USO.md
 */
final class FilePathTest extends TestCase
{
    /** @return array<string, array{string}> */
    public static function validPaths(): array
    {
        return [
            'pasta e arquivo' => ['avatars/user.png'],
            'so o arquivo' => ['user.png'],
            'varias pastas e dupla extensao' => ['a/b/c/arquivo.tar.gz'],
            'espacos' => ['minha pasta/meu arquivo.txt'],
            'arquivo com 255 bytes' => ['avatars/' . str_repeat('a', 255)],
        ];
    }

    /** @return array<string, array{mixed}> */
    public static function invalidPaths(): array
    {
        return [
            'pasta ponto ponto' => ['../secret.txt'],
            'absoluto' => ['/secret.txt'],
            'pasta vazia' => ['avatars//user.png'],
            'sem nome de arquivo' => ['avatars/'],
            'ponto no nome da pasta' => ['v1.0/user.png'],
            'arquivo comeca com ponto' => ['avatars/.env'],
            'arquivo termina com ponto' => ['avatars/user.'],
            'caractere proibido no arquivo' => ['avatars/us*r.png'],
            'caractere proibido na pasta' => ['av:atars/user.png'],
            'arquivo com mais de 255 bytes' => ['avatars/' . str_repeat('a', 256)],
            'texto vazio' => [''],
            'inteiro' => [123],
            'null' => [null],
            'array' => [[]],
        ];
    }

    #[DataProvider('validPaths')]
    public function testValidPathsAreValid(string $path): void
    {
        self::assertTrue(FilePath::isValid($path));
    }

    #[DataProvider('validPaths')]
    public function testFromKeepsValueExactly(string $path): void
    {
        $filePath = FilePath::from($path);

        self::assertSame($path, $filePath->value());
        self::assertSame($path, $filePath->jsonSerialize());
        self::assertSame($path, (string) $filePath);
        self::assertSame(json_encode($path), json_encode($filePath));
    }

    #[DataProvider('invalidPaths')]
    public function testInvalidPathsAreInvalid(mixed $path): void
    {
        self::assertFalse(FilePath::isValid($path));
    }

    #[DataProvider('invalidPaths')]
    public function testFromThrowsForInvalidPaths(mixed $path): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Valor invalido para Elavora\Api\DataTypes\Filesystem\FilePath.');

        FilePath::from($path);
    }

    public function testNoNormalization(): void
    {
        self::assertSame(' Avatars/User.PNG ', FilePath::from(' Avatars/User.PNG ')->value());
    }

    public function testConstructorIsNotPublic(): void
    {
        $this->expectException(\Error::class);

        new FilePath('a/b.txt'); // @phpstan-ignore-line
    }
}

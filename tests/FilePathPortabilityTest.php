<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\FilePath\Tests;

use Elavora\Api\DataTypes\Filesystem\FilePath;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FilePathPortabilityTest extends TestCase
{
    public function testAcceptsPortableRelativePathWithoutChangingIt(): void
    {
        $path = 'Meus arquivos/relatorio 東京.pdf';

        self::assertSame($path, FilePath::from($path)->value());
    }

    #[DataProvider('invalidPaths')]
    public function testRejectsUnsafeFolderOrFileSegments(mixed $value): void
    {
        self::assertFalse(FilePath::isValid($value));
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function invalidPaths(): iterable
    {
        yield 'reserved folder' => ['CON/relatorio.pdf'];
        yield 'reserved file' => ['arquivos/CON.txt'];
        yield 'folder with trailing space' => ['arquivos /relatorio.pdf'];
        yield 'file with trailing space' => ['arquivos/relatorio.pdf '];
        yield 'control in folder' => ["arqui\nvos/relatorio.pdf"];
        yield 'control in file' => ["arquivos/relatorio\0.pdf"];
        yield 'absolute path' => ['/arquivos/relatorio.pdf'];
        yield 'parent segment' => ['../relatorio.pdf'];
        yield 'empty segment' => ['arquivos//relatorio.pdf'];
        yield 'non string' => [123];
    }
}

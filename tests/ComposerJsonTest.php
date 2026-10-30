<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\FilePath\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Campos do composer.json do pacote depois do upgrade para o api-datatype-core 2.0.
 *
 * @see docs/USO.md
 * @see docs/AUTOMACAO.md#composerjson
 */
final class ComposerJsonTest extends TestCase
{
    /** @var array<string, mixed> */
    private array $composer;

    protected function setUp(): void
    {
        $this->composer = json_decode(
            (string) file_get_contents(dirname(__DIR__) . '/composer.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
    }

    public function testRequiresPhp83(): void
    {
        self::assertSame('>=8.3', $this->composer['require']['php'] ?? null);
    }

    public function testRequiresDatatypeCore2(): void
    {
        self::assertSame('^2.0', $this->composer['require']['elavora/api-datatype-core'] ?? null);
    }

    public function testRequiresDatatypeFileName1(): void
    {
        self::assertSame('^1.0', $this->composer['require']['elavora/api-datatype-file-name'] ?? null);
    }

    public function testRequiresDatatypeFolderName1(): void
    {
        self::assertSame('^1.0', $this->composer['require']['elavora/api-datatype-folder-name'] ?? null);
    }

    public function testBranchAliasIs10Dev(): void
    {
        self::assertSame('1.0-dev', $this->composer['extra']['branch-alias']['dev-main'] ?? null);
    }

    public function testAutoloadIsUnchanged(): void
    {
        self::assertSame(
            ['Elavora\\Api\\DataTypes\\Filesystem\\' => 'src/'],
            $this->composer['autoload']['psr-4'] ?? null,
        );
    }

    public function testHasNoRepositories(): void
    {
        self::assertArrayNotHasKey('repositories', $this->composer);
    }
}

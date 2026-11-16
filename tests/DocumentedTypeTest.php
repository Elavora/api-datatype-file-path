<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\FilePath\Tests;

use Elavora\Api\DataTypes\Filesystem\FilePath;
use Elavora\Api\Framework\Contracts\DescribesType;
use PHPUnit\Framework\TestCase;

/**
 * @see docs/USO.md#documentação-do-tipo-options
 */
final class DocumentedTypeTest extends TestCase
{
    private const ALLOWED_KEYS = ['type', 'format', 'mask', 'pattern', 'enum', 'example', 'description'];

    public function testImplementsDescribesType(): void
    {
        self::assertInstanceOf(DescribesType::class, new FilePath('avatars/user.png'));
    }

    public function testDocumentedTypeUsesOnlyAllowedKeys(): void
    {
        $documented = FilePath::documentedType();

        self::assertArrayHasKey('type', $documented);
        self::assertSame([], array_diff(array_keys($documented), self::ALLOWED_KEYS));
    }

    public function testExampleIsValidAndMatchesPattern(): void
    {
        $documented = FilePath::documentedType();

        if (isset($documented['example'])) {
            self::assertTrue(FilePath::isValid($documented['example']));
        }

        if (isset($documented['pattern'], $documented['example'])) {
            self::assertSame(1, preg_match('~' . $documented['pattern'] . '~', (string) $documented['example']));
        }
    }
}

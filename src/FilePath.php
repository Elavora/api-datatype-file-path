<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Filesystem;

use Elavora\Api\DataTypes\AbstractDataType;
use Elavora\Api\Framework\Contracts\DescribesType;

readonly class FilePath extends AbstractDataType implements DescribesType
{
    /**
     * Descreve o tipo no OPTIONS publico da API.
     *
     * @return array<string, mixed>
     */
    public static function documentedType(): array
    {
        return [
            'type' => 'string',
            'example' => 'avatars/user.png',
            'description' => 'Caminho relativo de arquivo, com pastas separadas por "/". '
                . 'Nao pode comecar com "/" nem ter segmentos vazios, "." ou "..". '
                . 'Os nomes de pasta nao podem conter pontos e o nome do arquivo segue as regras de nome de arquivo '
                . '(sem / : * ? " < > |, sem comecar nem terminar com ponto).',
        ];
    }

    /**
     * Verifica se o valor e um caminho de arquivo valido.
     */
    public static function isValid(mixed $value): bool
    {
        if (!is_string($value) || $value === '') {
            return false;
        }

        $parts = explode('/', $value);
        foreach (array_slice($parts, 0, -1) as $folder) {
            if (!FolderName::isValid($folder)) {
                return false;
            }
        }

        return FileName::isValid((string) end($parts));
    }
}

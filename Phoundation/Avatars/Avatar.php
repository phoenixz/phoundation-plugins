<?php

/**
 * Avatar class
 *
 * This class manages and generate user and project avatars
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins/Phoundation/Avatars
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Avatars;

use Phoundation\Accounts\Users\Interfaces\UserInterface;
use Phoundation\Content\Images\Interfaces\ImageFileInterface;
use Phoundation\Exception\OutOfBoundsException;
use Plugins\Phoundation\Avatars\Robohash\Robohash;


class Avatar
{
    /**
     * Generate a new user avatar
     *
     * @param UserInterface $user
     * @param string|null $generator
     *
     * @return ImageFileInterface
     */
    public static function generate(UserInterface $user, ?string $generator = null): ImageFileInterface
    {
        return match (config()->get('users.avatars.generator', 'robohash', $generator)) {
            'gravatar' => Gravatar::generate($user),
            'robohash' => Robohash::generate($user),
            default    => throw new OutOfBoundsException(tr('Unknown avatar generator ":generator" specified', [
                ':generator' => $generator
            ])),
        };
    }
}

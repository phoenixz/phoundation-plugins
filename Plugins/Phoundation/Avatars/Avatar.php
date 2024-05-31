<?php

declare(strict_types=1);

namespace Plugins\Phoundation\Avatars;

use Phoundation\Accounts\Interfaces\UserInterface;
use Phoundation\Accounts\Users\Interfaces\UserInterface;
use Phoundation\Content\Images\Image;
use Phoundation\Content\Images\Interfaces\ImageInterface;
use Phoundation\Utils\Config;
use Phoundation\Exception\OutOfBoundsException;
use Plugins\Phoundation\Avatars\Robohash\Robohash;


/**
 * Avatar class
 *
 * This class manages and generate user and project avatars
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins/Avatars
 */
class Avatar
{
    /**
     * Generate a new user avatar
     *
     * @param UserInterface $user
     * @param string|null $generator
     * @return ImageInterface
     */
    public static function generate(UserInterface $user, ?string $generator = null): ImageInterface
    {
        return match (Config::get('users.avatars.generator', 'robohash', $generator)) {
            'gravatar' => Gravatar::generate($user),
            'robohash' => Robohash::generate($user),
            default    => throw new OutOfBoundsException(tr('Unknown avatar generator ":generator" specified', [
                ':generator' => $generator
            ])),
        };
    }
}

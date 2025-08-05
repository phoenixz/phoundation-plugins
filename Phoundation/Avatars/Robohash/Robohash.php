<?php

/**
 * Robohash class
 *
 * This class can connect to the robohash website and download and install robohash avatar images
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins/Phoundation/Avatars
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Avatars\Robohash;

use Phoundation\Accounts\Users\Interfaces\UserInterface;
use Phoundation\Content\Images\ImageFile;
use Phoundation\Content\Images\Interfaces\ImageFileInterface;
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Filesystem\PhoRestrictions;
use Phoundation\Web\Requests\FileResponse;


class Robohash
{
    /**
     * Generate a new avatar and assign it to
     *
     * @param UserInterface $user
     *
     * @return ImageFileInterface
     */
    public static function generate(UserInterface $user): ImageFileInterface
    {
        $restrictions = PhoRestrictions::new(DIRECTORY_DATA, true);
        $directory         = DIRECTORY_DATA . 'content/cdn/en/img/profiles/' . $user->getLogId();

        $picture      = FileResponse::new($restrictions)->download('https://robohash.org/' . $user->getDisplayName(), function ($file) use ($restrictions, $user, $directory) {
            $directory    = PhoDirectory::new($directory, $restrictions)->ensure();
            $picture = ImageFile::new($directory . 'profile.png');

            rename($file, $picture);
            $user->setProfilePictureFileObject($picture);

            return $picture;
        });

        return new ImageFile($picture);
    }
}

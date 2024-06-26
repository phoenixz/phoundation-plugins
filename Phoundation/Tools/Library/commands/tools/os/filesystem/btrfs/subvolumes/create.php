<?php

/**
 * Command tools/os/filesystem/btrfs/subvolumes/create
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */

declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\Exception\FileExistsException;
use Phoundation\Filesystem\FsFile;
use Phoundation\Filesystem\FsDirectory;
use Phoundation\Filesystem\FsRestrictions;
use Phoundation\Security\Crypt;

$directory    = '/';
$restrictions = FsRestrictions::new('/', true, tr('btrfs subvolumes create'));

CliDocumentation::setUsage('./pho tools os filesystem btrfs subvolumes create PATH');

CliDocumentation::setHelp('The BTRFS subvolume create script can create BTRFS subvolumes


ARGUMENTS


PATH                                    The path of the subvolume');

CliDocumentation::setAutoComplete([
    'positions' => [
        0 => [
            'word'   => function ($word) use ($directory, $restrictions) { return FsDirectory::new($directory, $restrictions)->scan($word . '*'); },
            'noword' => function ()      use ($directory, $restrictions) { return FsDirectory::new($directory, $restrictions)->scan('*'); },
        ],
    ]
]);


// Validate data
$argv = ArgvValidator::new()
    ->select('path')->isFile($directory, $restrictions, null)
    ->validate();


// Validate the target
try {
    Btrfs::new($argv['path'], $restrictions)
        ->subvolumes()
        ->create();

} catch (FileExistsException $e) {
    throw $e->makeWarning();
}


// Done!
Log::success(tr('Finished generating subvolume ":path"', [
    ':path' => $argv['path']
]));

<?php

declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Cli\CliCommand;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\FsDirectory;
use Phoundation\Filesystem\FsFile;
use Phoundation\Filesystem\FsRestrictions;


/**
 * Command tools/devices/storage/encrypt
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */
$restrictions = FsRestrictions::new('/');

CliDocumentation::setUsage('./pho tools devices storage location
./pho tools devices storage location /home/user/filename');

CliDocumentation::setHelp('This script will display the device where the specified file is stored


ARGUMENTS


FILE                                    The path to the file (or directory) which needs to be examined');

CliDocumentation::setAutoComplete([
    'positions' => [
        0 => [
            'word'   => function ($word) use ($restrictions) { return FsDirectory::new('/', $restrictions)->scan($word . '*'); },
            'noword' => function ()      use ($restrictions) { return FsDirectory::new('/', $restrictions)->scan('*'); },
        ],
    ]
]);


// Validate data
$argv = ArgvValidator::new()
    ->select('file')->hasMaxCharacters(2048)->isFile('/', $restrictions)->sanitizeCallback(function(mixed $value, array $source) { return '/' . $value; })
    ->validate();


// Echo the device path
CliCommand::echo(FsFile::new($argv['file'])->getMountDevice());
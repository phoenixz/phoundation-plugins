<?php

/**
 * Command tools/os/filesystem/mounts/get-mount
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
use Phoundation\Filesystem\FsDirectory;
use Phoundation\Filesystem\Mounts\FsMounts;
use Phoundation\Filesystem\FsRestrictions;

$restrictions = FsRestrictions::getWritable('/', 'command tools os filesystem mounts');

CliDocumentation::setAutoComplete([
    'positions' => [
        '0' => [
            'word'   => function ($word) use ($restrictions) {
                return FsDirectory::new('/', $restrictions)->scan($word . '*');
            },
            'noword' => function () use ($restrictions) {
                return FsDirectory::new('/', $restrictions)->scan('*');
            },
        ],
    ]
]);

CliDocumentation::setUsage('./pho tools os filesystem mounts get-mount PATH');

CliDocumentation::setHelp('This command will show where the specified PATH is mounted
directory


ARGUMENTS


PATH                                    The path to test');


$argv = ArgvValidator::new()
    ->select('path')->isDirectory('/', '/')
    ->validate();

show($argv);
showdie(FsMounts::getMountSources($argv['path'], FsRestrictions::new('/')));

Log::success(tr('Mounted source ":source" to target ":target"', [
    ':source' => $argv['source'],
    ':target' => $argv['target']
]));
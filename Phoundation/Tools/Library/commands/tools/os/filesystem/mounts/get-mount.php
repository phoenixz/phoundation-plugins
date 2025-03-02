<?php

/**
 * Command tools os filesystem mounts get-mount
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Tools
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Filesystem\Mounts\PhoMounts;
use Phoundation\Filesystem\PhoRestrictions;


$restrictions = PhoRestrictions::newWritableObject('/');

CliDocumentation::setAutoComplete([
    'positions' => [
        '0' => [
            'word'   => function ($word) use ($restrictions) { return PhoDirectory::newFilesystemRootObject()->scan($word, '/.*?$/'); },
            'noword' => function ($word) use ($restrictions) { return PhoDirectory::newFilesystemRootObject()->scan($word, '/.*?$/'); },
        ],
    ]
]);

CliDocumentation::setUsage('./pho tools os filesystem mounts get-mount PATH');

CliDocumentation::setHelp('This command will show where the specified PATH is mounted
directory


ARGUMENTS


PATH                                    The path to test');


$argv = ArgvValidator::new()
    ->select('path')->sanitizeDirectory(PhoDirectory::newFilesystemRootObject())
    ->validate();

show($argv);
showdie(PhoMounts::getMountSources($argv['path'], PhoRestrictions::new('/')));

Log::success(ts('Mounted source ":source" to target ":target"', [
    ':source' => $argv['source'],
    ':target' => $argv['target']
]));

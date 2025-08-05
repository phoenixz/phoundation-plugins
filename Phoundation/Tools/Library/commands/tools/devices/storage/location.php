<?php

/**
 * Command tools devices storage encrypt
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Tools
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Cli\CliCommand;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Filesystem\PhoFile;
use Phoundation\Filesystem\PhoRestrictions;


$restrictions = PhoRestrictions::new('/');

CliDocumentation::setUsage('./pho tools devices storage location
./pho tools devices storage location /home/user/filename');

CliDocumentation::setHelp('This command will display the device where the specified file is stored


ARGUMENTS


FILE                                    The path to the file (or directory) which needs to be examined');

CliDocumentation::setAutoComplete([
    'positions' => [
        0 => [
            'word'   => function ($word) use ($restrictions) { return PhoDirectory::newFilesystemRootObject()->scan($word, '/.*?$/'); },
            'noword' => function ($word) use ($restrictions) { return PhoDirectory::newFilesystemRootObject()->scan($word, '/.*?$/'); },
        ],
    ]
]);


// Validate data
$argv = ArgvValidator::new()
    ->select('file')->hasMaxCharacters(2048)-->isFile(PhoDirectory::newFilesystemRootObject())->sanitizeCallback(function(mixed $value, array $source) { return '/' . $value; })
                                                                                              ->validate();


// Echo the device path
CliCommand::echo(PhoFile::new($argv['file'])->getMountDevice());

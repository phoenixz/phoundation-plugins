<?php

/**
 * Command tools files initalize
 *
 * Will initalize the specified file by overwriting it (multiple times) with data
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Tools
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Filesystem\PhoRestrictions;


$restrictions = PhoRestrictions::newWritable('/');

CliDocumentation::setAutoComplete([
    'arguments' => [
        '-r,--random' => false,
        '-d,--data'   => ['random', 'zeroes', 'ones'],
    ],
    'positions' => [
        '0' => [
            'word'   => function ($word) use ($restrictions) { return PhoDirectory::newFilesystemRoot()->scan($word, '/.*?$/'); },
            'noword' => function ($word) use ($restrictions) { return PhoDirectory::newFilesystemRoot()->scan($word, '/.*?$/'); },
        ],
    ]
]);

CliDocumentation::setUsage('./pho tools files initalize FILE
./pho tools files initalize -rd random FILE');

CliDocumentation::setHelp('This command will initialize the specified file by overwriting it (multiple times) with 
data


ARGUMENTS


PATH                                    The path of which the size needs to be calculated 

[-d,--data DATA]                        The data with which the file will be initialized. Either one of "random" (will 
                                        fill the file with random data), "zeroes" (will fill the file with chr(0) 
                                        bytes), or "ones" (Will will the file with chr(255) bytes) or any other data 
                                        string that will be repeated over and over until the file is full

[-r,--random]                           If specified, will overwrite the file blocks randomly, instead of linearly');


// Get the arguments
$argv = ArgvValidator::new()
    ->select('path')->sanitizePath(PhoDirectory::newFilesystemRoot(true))
    ->select('-r,--random')->isOptional(false)->isBoolean()
    ->select('-d,--data', true)->isOptional(false)->isInteger()->isBetween(1, 100)
    ->validate();


// Shred the specified file
$argv['path']->initialize($argv['data'], $argv['random']);

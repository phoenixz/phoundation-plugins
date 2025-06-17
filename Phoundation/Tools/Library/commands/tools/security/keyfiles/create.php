<?php

/**
 * Command tools security keyfiles create
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
use Phoundation\Filesystem\Exception\FileExistsException;
use Phoundation\Filesystem\PhoDirectory;
use Phoundation\Security\Crypt;


$directory = PhoDirectory::newFilesystemRootObject();

CliDocumentation::setUsage('./pho tools security keyfiles create
./pho tools security keyfiles create -s 8192');

CliDocumentation::setHelp('The keyfiles create script can create a keyfile filled with random data that can be used for 
authentication 


ARGUMENTS


FILE                                    The file where to write the 

[-s,--size]                             The size of the key file');

CliDocumentation::setAutoComplete([
    'positions' => [
        0 => [
            'word'   => function ($word) use ($directory) { return $directory->scan($word . '*'); },
            'noword' => function ($word) use ($directory) { return $directory->scan('*'); },
        ],
    ],
    'arguments' => [
        '-s,--size' => true,
    ]
]);


// Validate data
$argv = ArgvValidator::new()
                     ->select('file')->sanitizeFile($directory, null)
                     ->select('-s,--size')->isOptional(4_096)->isNatural()->isLessThan(16_777_216, true)
                     ->validate();


// Validate the target
try {
    $argv['file']->checkNotExists()
                 ->getParentDirectory()
                 ->checkWritable();

} catch (FileExistsException $e) {
    throw $e->makeWarning();
}


// Generate the file
Crypt::createCryptFile($argv['file'], $argv['size']);


// Done!
Log::success(ts('Finished generating keyfile ":file"', [
    ':file' => $argv['file']
]), 10);

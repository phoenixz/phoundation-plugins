<?php

/**
 * Command tools security keyfiles delete
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


$directory = PhoDirectory::newFilesystemRootObject();

CliDocumentation::setUsage('./pho tools security keyfiles delete
./pho tools security keyfiles create -s 8192');

CliDocumentation::setHelp('The keyfiles delete script can securely delete a keyfile 


ARGUMENTS


FILE                                    The file where to write the 

[-p,--passes PASSED]                    The number of passes to overwrite the key file before deleting it');

CliDocumentation::setAutoComplete([
    'positions' => [
        0 => [
            'word'   => function ($word) use ($directory) { return $directory->scan($word . '*'); },
            'noword' => function ($word) use ($directory) { return $directory->scan('*'); },
        ],
    ],
    'arguments' => [
        '-p,--passes' => true,
    ]
]);


// Validate data
$argv = ArgvValidator::new()
                     ->select('file')->sanitizeFile($directory)
                     ->select('-p,--passes')->isOptional(3)->isNatural()->isLessThan(20, true)
                     ->validate();


// Validate the target
try {
    $argv['file']->shred($argv['passes']);

} catch (FileExistsException $e) {
    throw $e->makeWarning();
}


// Done!
Log::success(ts('Finished shredding keyfile ":file"', [
    ':file' => $argv['file']
]), 10);

<?php

/**
 * Command system backup everything
 *
 * This command will backup *everything* related to this project to the default backup directory
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\PhoDirectory;
use Plugins\Phoundation\Backups\Backup;


CliDocumentation::setAutoComplete([
    'arguments' => [
        '-t,--target'  => [
            'word'   => function ($word) { return PhoDirectory::newDataObject(false, 'backups/')->scan('/^.*?' . preg_quote($word, '/') . '.*?$/', glob_flags: GLOB_MARK | GLOB_ONLYDIR); },
            'noword' => function ($word) { return PhoDirectory::newDataObject(false, 'backups/')->scan('/^.*?' . preg_quote($word, '/') . '.*?$/', glob_flags: GLOB_MARK | GLOB_ONLYDIR); },
        ],
    ]
]);

CliDocumentation::setUsage('./pho system backup everything');

CliDocumentation::setHelp('This command will backup *everything* related to this project to the default backup directory


ARGUMENTS


-t / --target PATH                      The target path where to backup to');


// Validate arguments
$argv = ArgvValidator::new()
    ->select('-t,--target', true)->sanitizeDirectory(PhoDirectory::newFilesystemRootObject(true))
    ->validate();


// Start backup
Backup::new()
    ->setTarget($argv['target'])
    ->backupSystem()
    ->backupPlugins()
    ->backupAllDatabases()
    ->backupDataFiles();


// Done!
Log::success(tr('Successfully backed up everything'));

<?php

/**
 * Command system backup create
 *
 * This command will create a backup of this project
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\PhoDirectory;
use Plugins\Phoundation\Backups\Backup;


CliDocumentation::setAutoComplete([
    'arguments' => [
        '-f,--files'    => false,
        '-d,--database' => false,
        '-t,--target'   => false,
    ]
]);

CliDocumentation::setUsage('./pho backup create project
./pho backup create project -A');

CliDocumentation::setHelp('This command will backup *everything* related to this project to the default backup directory


ARGUMENTS


-t / --target PATH                      The target path where to backup to');


// Validate arguments
$argv = ArgvValidator::new()
    ->select('-t,--target', true)->sanitizeDirectory(PhoDirectory::newFilesystemRoot(true))
    ->validate();


// Start backup
Backup::new()
    ->setTarget($argv['target'])
    ->backupSystem()
    ->backupPlugins()
    ->backupAllDatabases()
    ->backupDataFiles();


// Done!
Log::success(ts('Successfully backed up everything'), 10);

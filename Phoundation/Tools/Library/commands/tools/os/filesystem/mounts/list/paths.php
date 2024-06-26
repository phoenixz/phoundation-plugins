<?php

declare(strict_types=1);

use Phoundation\Cli\Cli;
use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\Mounts\FsMounts;
use Phoundation\Utils\Arrays;


/**
 * Command tools/os/filesystem/mounts/list/paths
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */
CliDocumentation::setUsage('./pho tools os filesystem mounts list paths');
CliDocumentation::setHelp('This command will list all available mounted paths');

$argv = ArgvValidator::new()
    ->validate();

Cli::displayTable(Arrays::listKeepKeys(FsMounts::listMountTargets(), 'filesystem'), id_column: 'source');

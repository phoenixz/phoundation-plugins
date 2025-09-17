<?php

/**
 * Command tools files count
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\Tools
 */


declare(strict_types=1);

use Phoundation\Cli\Cli;
use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Filesystem\Mounts\PhoMounts;
use Phoundation\Utils\Arrays;


CliDocumentation::setUsage('./pho tools os filesystem mounts list devices');

CliDocumentation::setHelp('This command will list all available mountable devices');

$argv = ArgvValidator::new()
    ->validate();

Cli::displayTable(Arrays::listKeepKeys(PhoMounts::listMountSources(), 'filesystem'), id_column: 'source');

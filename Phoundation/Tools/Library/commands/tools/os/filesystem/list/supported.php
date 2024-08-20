<?php

/**
 * Command tools os filesystem list supported
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\Cli;
use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Os\Devices\Storage\Proc;


CliDocumentation::setUsage('./pho tools os filesystem list supported');

CliDocumentation::setHelp('This command will list all supported filesystem types');

$argv = ArgvValidator::new()
    ->validate();

Cli::displayTable(Proc::getSupportedFiletypes(), ['Filesystem types'], id_column: null);

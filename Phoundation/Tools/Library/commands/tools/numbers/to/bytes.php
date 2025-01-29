<?php

/**
 * Command tools numbers to bytes
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
use Phoundation\Cli\CliCommand;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Numbers;
use Phoundation\Utils\Strings;


CliDocumentation::setUsage('./pho tools numbers to bytes');

CliDocumentation::setHelp('This command will convert the given number of bytes to a human-readable format');

$argv = ArgvValidator::new()
    ->select('text')->isPositive()
    ->validate();

Log::cli(Numbers::getHumanReadableBytes($argv['text']));

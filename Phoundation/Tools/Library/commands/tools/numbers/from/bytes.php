<?php

/**
 * Command tools numbers from bytes
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Cli\CliCommand;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;
use Phoundation\Utils\Numbers;
use Phoundation\Utils\Strings;


CliDocumentation::setUsage('./pho tools numbers from bytes');

CliDocumentation::setHelp('This command will convert the given human-readable bytes to a byte number');

$argv = ArgvValidator::new()
    ->select('text')->hasMaxCharacters(128)
    ->validate();

Log::cli(Numbers::fromBytes($argv['text']));

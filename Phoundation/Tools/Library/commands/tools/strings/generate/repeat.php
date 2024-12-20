<?php

/**
 * Command tools strings generate repeat
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Tools
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\ArgvValidator;


CliDocumentation::setUsage('./pho tools strings generate repeat');

CliDocumentation::setHelp('The command will output the specified character repeated the specified times


ARGUMENTS


CHARACTER                               (string or number) The character(s) string to repeat

REPEAT                                  (integer number) The number of times to repeat the characters 
');


// Get the arguments
$argv = ArgvValidator::new()
    ->select('character')->hasMinCharacters(1)->hasMaxCharacters(4096)
    ->select('repeat')->isInteger()->isBetween(1, 1048576)
    ->validate();


// Display the repeated string
Log::cli(str_repeat($argv['character'], $argv['repeat']));

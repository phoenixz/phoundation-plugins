<?php

/**
 * Script tools tasks wait
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;


CliDocumentation::setUsage('./pho tools tasks wait SECONDS
./pho tools tasks wait 10
./pho tools tasks wait 0.1
./pho tools tasks wait 0.01
./pho tools tasks wait 0.000001');

CliDocumentation::setHelp('The wait tool will wait for the specified amount of seconds, and exit with exitcode 0');


// Validate arguments
$argv = ArgvValidator::new()
                     ->select('seconds')->isNumeric()->isBetween(0.000001, PHP_INT_MAX)
                     ->validate();


// Wait
usleep((int) round($argv['seconds'] * 1_000_000));


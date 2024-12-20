<?php

/**
 * Script statistics/flush
 *
 * This command will flush the statistics queue
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins/Phoundation/Statistics
 */


declare(strict_types=1);

use Phoundation\Accounts\Users\User;
use Phoundation\Core\Log\Log;
use Phoundation\Data\DataEntry\Exception\DataEntryNotExistsException;
use Phoundation\Data\Validator\ArgvValidator;
use Plugins\Phoundation\Statistics\Statistics;

$usage = './pho statistics flush';

$help  = 'This script will flush the statistics queue to the statistics rendering server  


ARGUMENTS


-';


// This script takes no arguments
$argv = ArgvValidator::new()->validate();


Log::information(tr('Flushing statistics queue'));

Statistics::new()->flushQueue();

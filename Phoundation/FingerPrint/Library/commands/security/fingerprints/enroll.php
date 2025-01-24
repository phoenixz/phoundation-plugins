<?php

/**
 * Command security fingerprints enroll
 *
 * This command will enroll a new fingerprint in the database.
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */


declare(strict_types=1);

use Phoundation\Accounts\Users\User;
use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Plugins\Phoundation\Humans\FingerPrint\FingerPrint;

CliDocumentation::setUsage('./pho security fingerprints enroll -u EMAIL');

CliDocumentation::setHelp('This script will enroll a new fingerprint in the database. The user which will have his / her 
fingerprints enrolled in the database must already exist


ARGUMENTS


-u / --user EMAIL                       The user who\'s fingerprints will be enrolled in the database');

CliDocumentation::setAutoComplete(User::getAutoComplete([
    'arguments' => [
        '-u,--user' => [
            'word'   => 'SELECT COALESCE(`username`, `email`, `code`) AS `email` FROM `accounts_users` WHERE COALESCE(`username`, `email`, `code`) LIKE :word AND `status` IS NULL',
            'noword' => 'SELECT COALESCE(`username`, `email`, `code`) AS `email` FROM `accounts_users` WHERE `status` IS NULL'
        ]
    ],
]));


// Validate arguments
$argv = ArgvValidator::new()
    ->select('-u,--user', true)->isEmail()
    ->validate();


// Set fingerprint for this user
$user = User::new($argv['user'])->load();

FingerPrint::new()->enroll($user);

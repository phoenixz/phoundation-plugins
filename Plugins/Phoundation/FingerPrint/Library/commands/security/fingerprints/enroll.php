<?php

declare(strict_types=1);

use Phoundation\Accounts\Users\User;
use Phoundation\Cli\CliDocumentation;
use Phoundation\Data\Validator\ArgvValidator;
use Plugins\Phoundation\FingerPrint\FingerPrint;


/**
 * Script security/fingerprints/enroll
 *
 * This script will enroll a new fingerprint in the database.
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Scripts
 */
CliDocumentation::usage('./pho security fingerprints enroll -u EMAIL');

CliDocumentation::help('This script will enroll a new fingerprint in the database. The user which will have his / her 
fingerprints enrolled in the database must already exist


ARGUMENTS


-u / --user EMAIL                       The user who\'s fingerprints will be enrolled in the database');

CliDocumentation::autoComplete(User::getAutoComplete([
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
$user = User::get($argv['user'], 'email');

FingerPrint::new()->enroll($user);

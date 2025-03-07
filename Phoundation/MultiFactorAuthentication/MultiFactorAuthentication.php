<?php

/**
 * Class MultiFactorAuthentication
 *
 *
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\MultiFactorAuthentication
 */


declare(strict_types=1);

namespace Plugins\Phoundation\MultiFactorAuthentication;

use Phoundation\Exception\OutOfBoundsException;
use RobThree\Auth\Providers\Qr\BaconQrCodeProvider;
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\TwoFactorAuthException;


class MultiFactorAuthentication
{
    /**
     * The TwoFactorAuthentication library
     *
     * @var TwoFactorAuth $tfa
     */
    protected TwoFactorAuth $tfa;


    /**
     * MultiFactorAuthentication class constructor
     *
     * @throws TwoFactorAuthException
     */
    public function __construct()
    {
        switch (config()->getString('security.web.mfa.provider', 'bacon')) {
            case 'bacon':
                $this->tfa = new TwoFactorAuth(qrcodeprovider: new BaconQrCodeProvider());
                break;

            default:
                throw new OutOfBoundsException(tr('Unknown MFA provider ":provider" specified in configuration path ":path"', [
                    ':provider' => config()->getString('security.web.mfa.provider', 'bacon'),
                    ':path'     => 'security.web.mfa.provider',
                ]));
        }
    }

}
<?php

/**
 * Class MultiFactorAuthentication
 *
 * This is the standard Phoundation Multi Factor Authentication library class
 *
 * This class (will) support(s) multiple methods of extra factor authentications like OAUTH, and email
 *
 * @see       https://github.com/RobThree/TwoFactorAuth
 * @see       https://robthree.github.io/TwoFactorAuth/optional-configuration.html
 * @see       https://robthree.github.io/TwoFactorAuth/improved-code-verification.html
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Plugins\Phoundation\MultiFactorAuthentication
 */


declare(strict_types=1);

namespace Plugins\Phoundation\MultiFactorAuthentication;

use Phoundation\Accounts\Users\Interfaces\UserInterface;
use Phoundation\Core\Log\Log;
use Phoundation\Core\Sessions\Session;
use Phoundation\Data\Traits\TraitDataUserObject;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Components\ElementsBlockCore;
use Phoundation\Web\Html\Components\Forms\Form;
use Phoundation\Web\Html\Components\Script;
use Phoundation\Web\Html\Enums\EnumHttpRequestMethod;
use Phoundation\Web\Html\Enums\EnumJavascriptWrappers;
use Plugins\Phoundation\MultiFactorAuthentication\Exception\MultiFactorAuthenticationFailedException;
use Plugins\Phoundation\MultiFactorAuthentication\Interfaces\MultiFactorAuthenticationInterface;
use RobThree\Auth\Providers\Qr\BaconQrCodeProvider;
use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\TwoFactorAuthException;


class MultiFactorAuthentication extends ElementsBlockCore implements MultiFactorAuthenticationInterface
{
    use TraitDataUserObject;


    /**
     * The TwoFactorAuthentication library
     *
     * @var TwoFactorAuth $tfa
     */
    protected TwoFactorAuth $tfa;

    /**
     * The secret for this MFA object
     *
     * @var string|null $secret
     */
    protected ?string $secret = null;

    /**
     * @var int|null
     */
    protected ?int $timeslice = null;




    /**
     * MultiFactorAuthentication class constructor
     *
     * @param UserInterface $o_user
     */
    public function __construct(UserInterface $o_user)
    {
        $this->o_user = $o_user;
        parent::__construct();
    }


    /**
     * Returns a new static object
     *
     * @param UserInterface $o_user
     *
     * @return static
     */
    public static function new(UserInterface $o_user): static
    {
        return new static($o_user);
    }


    /**
     * Returns true if MFA is enabled globally
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return config()->getBoolean('security.web.mfa.enabled', false);
    }


    /**
     * Verifies if the specified MFA code is valid
     *
     * @param string   $code
     * @param bool     $test
     * @param int|null $discrepancy
     * @param int|null $time
     *
     * @return static
     */
    public function verify(string $code, bool $test, ?int $discrepancy = null, ?int $time = null): static
    {
        if (strlen($code) !== 6) {
            Log::warning(tr('Specified MFA code ":code" is not exactly 6 characters long', [
                ':code' => $code
            ]));

            throw MultiFactorAuthenticationFailedException::new(tr('Specified MFA is not exactly 6 characters long'));
        }

        $discrepancy = $discrepancy ?? config()->getPositiveInteger('security.web.mfa.discrepancy', 1);

        if (Session::get('mfa_test_secret')) {
            $secret = Session::get('mfa_test_secret');

        } elseif (Session::get('mfa_secret')) {
            $secret = Session::get('mfa_secret');
        }

        if ($this->getTfaObject()->verifyCode($secret, $code, $discrepancy, $time, $this->timeslice)) {
            return $this;
        }

        throw MultiFactorAuthenticationFailedException::new(tr('MFA verification failed'));
    }


    /**
     * Updates the current UserSession with the specified MFA code
     *
     * @param string $code
     *
     * @return static
     */
    public function update(string $code): static
    {
        Session::getUserObject()->updateMfaCode($code, $this->timeslice);
        return $this;
    }


    /**
     * Returns true if the MFA test for this user passes
     *
     * @return bool
     */
    public function pass(): bool
    {
        if (!MultiFactorAuthentication::isEnabled()) {
            // MFA is disabled globally, so it always passes
            return true;
        }

        switch (config()->getString('security.web.mfa.method', 'device', true)) {
            case 'session':
                // 2FA has to be entered for each sign-in
                return false;

            case 'ip':
                // 2FA has to be entered for each IP address
                return false;

            case 'device':
                return false;

            default:
                throw new OutOfBoundsException(tr('Unknown MFA method ":method" specified in configuration path ":path"', [
                    ':method' => config()->getString('security.web.mfa.method', 'device', true),
                    ':path'   => 'security.web.mfa.method'
                ]));
        }
    }


    /**
     * Returns the TFA object
     *
     * @return TwoFactorAuth
     * @throws TwoFactorAuthException
     */
    protected function getTfaObject(): TwoFactorAuth
    {
        if (empty($this->tfa)) {
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

        return $this->tfa;
    }


    /**
     * Initializes a new secret for a new MFA object
     *
     * @return static
     * @throws TwoFactorAuthException
     */
    protected function initSecret(): static
    {
        $this->secret = $this->getTfaObject()->createSecret();
        Session::set($this->secret, 'mfa_test_secret');
        return $this;
    }


    /**
     * Renders and returns the multi-factor verify section
     *
     * @return string|null
     */
    public function renderCreate(): ?string
    {
        $this->initSecret();

        return '  <div class="form-outline mb-4 text-center" data-mdb-input-init>
                    <label class="form-label" for="qr-code">' . tr('Please scan the QR code below and register your account in your favorite 2FA application') . '</label>
                    <img src="' . $this->getTfaObject()->getQRCodeImageAsDataUri('Demo', $this->secret) . '">
                  </div>

                  <div class="form-outline mb-4" data-mdb-input-init>
                    <label class="form-label" for="loginPassword">' . tr('Or register the following code manually:') . '</label>
                    ' . chunk_split($this->secret, 4, ' ') . '
                  </div>';
    }


    /**
     * Renders and returns the multi-factor verify section
     *
     * @return string|null
     */
    public function renderVerify(): ?string
    {
        return Form::new()
                   ->setRequestMethod(EnumHttpRequestMethod::post)
                   ->setContent('<div class="row wide">
                                     <div class="col-sm-3">
                                     </div>
                                     <div class="col col-sm-1">
                                         <div class="form-horizontal text-center" data-mdb-input-init>
                                           <input type="number" id="number1" name="number1" class="form-control no-controls single-character mfa-code" />
                                         </div>
                                     </div>
                                     <div class="col col-sm-1">
                                         <div class="form-horizontal text-center" data-mdb-input-init>
                                           <input type="number" id="number2" name="number2" class="form-control no-controls single-character mfa-code" />
                                         </div>
                                     </div>
                                     <div class="col col-sm-1">
                                         <div class="form-horizontal text-center" data-mdb-input-init>
                                           <input type="number" id="number3" name="number3" class="form-control no-controls single-character mfa-code" />
                                         </div>
                                     </div>
                                     <div class="col col-sm-1">
                                         <div class="form-horizontal text-center" data-mdb-input-init>
                                           <input type="number" id="number4" name="number4" class="form-control no-controls single-character mfa-code" />
                                         </div>
                                     </div>
                                     <div class="col col-sm-1">
                                         <div class="form-horizontal text-center" data-mdb-input-init>
                                           <input type="number" id="number5" name="number5" class="form-control no-controls single-character mfa-code" />
                                         </div>
                                     </div>
                                     <div class="col col-sm-1">
                                         <div class="form-horizontal text-center" data-mdb-input-init>
                                           <input type="number" id="number6" name="number6" class="form-control no-controls single-character mfa-code" />
                                         </div>
                                     </div>
                                     <div class="col-sm-3">
                                     </div>
                                 </div>') . Script::new()
                                    ->setJavascriptWrapper(EnumJavascriptWrappers::ready)
                                    ->setContent('
                            $(".mfa-code").on("paste", function(e) {
                                let field = e.target.name.from("number");
                                let paste = e.originalEvent.clipboardData.getData("text");
//console.log("PASTE");
//console.log(paste);
//console.log(e);
//// 012345

                                e.stopPropagation();

                                for (let i = 0; i < paste.length; i++) {
                                    let e   = $.Event("keypress");
                                    e.which = paste.charCodeAt(i);
//console.log(e.key);
                                    $("#number" + field).focus().trigger(e);

                                    if (++field > 6) {
                                        field = 6
                                    }
                                }

                                e.stopPropagation();
                                return false;

                            }).on("keydown", function(e) {
                                // If it is not a number, ignore it
                                let field     = e.target.name.from("number");
                                let character = String.fromCharCode(e.which);
//console.error("CAUGHT KEYDOWN!" + e.keyCode);
//console.error(character);

                                if (character.match(/^\d+$/)) {
                                    return true;
                                }

                                switch (e.keyCode) {
                                    case 8:
                                        // Backspace on empty field will go to the previous field
                                        // Focus on the previous field
                                        $("#number" + field).val("");

                                        if (--field < 1) {
                                            field = 1
                                        }

                                        $("#number" + field).focus();
                                        break;

                                    case 9:
                                        // Tab
                                        // no break

                                    case 13:
                                        // Enter
                                        // no break

                                    case 39:
                                        // Tab, enter, and left arrow on any field will focus on the next field
                                        if (e.shiftKey) {
                                            if (--field < 1) {
                                                field = 1
                                            }

                                        } else {
                                            if (++field > 6) {
                                                field = 6
                                            }
                                        }

                                        $("#number" + field).focus();
                                        break;

                                    case 27:
                                        // Escape will clear all fields
                                        $(".mfa-code").val("");
                                        break;

                                    case 37:
                                        // Right arrow on any field will focus on the next field
                                        if (--field < 1) {
                                            field = 1
                                        }

                                        $("#number" + field).focus();

                                    case 45:
                                        if (e.shiftKey) {
                                            // Shift + Insert, ignore because this is a keyboard paste
                                            return true;
                                        }

                                    case 46:
                                        // Delete key, just clear the current field
                                        $("#number" + field).val("");
                                        break;

                                    case 86:
                                        if (e.ctrlKey) {
                                            // CTRL + V, ignore because this is a keyboard paste
                                            return true;
                                        }
                                }

                                e.stopPropagation();
                                return false;

                            }).on("keypress", function(e) {
                                // If it is not a number, ignore it
                                let selected  = $(\'.mfa-code:field-value("!")\').length;
                                let field     = e.target.name.from("number");
                                let character = String.fromCharCode(e.which);
//console.error("CAUGHT KEYPRESS!" + e.which);
//console.error(character);

                                if (!character.match(/^\d+$/)) {
                                    e.stopPropagation();
                                    return false;
                                }
//console.error("DIGIT!");

                                $("#number" + field).val(character);

                                // Does the current field have a value? If so, overwrite it
                                if (++field > 6) {
                                    field = 6
                                }

                                let count = 0;

                                // Check if all digit fields have a value. If so, submit the form automatically.

                                let newselected = $(\'.mfa-code:field-value("!")\').length;
                                
                                // Submit if all are selected
                                if (newselected === 6) {
                                    // if all were already selected, do not submit
                                    if (selected < newselected) {
                                        // Submit the form
                                        $("#number" + field).blur();
                                        $(e.target).closest("form").submit();
                                        e.stopPropagation();
                                        return false;
                                    }
                                }

                                $("#number" + field).focus();
                                e.stopPropagation();
                                return false;
                            });
                          ');
    }
}

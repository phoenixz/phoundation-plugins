<?php

/**
 * Page mfa-create
 *
 * This page forces the user to setup 2FA during the sign-in process
 *
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license   http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package   Phoundation\Web
 */


declare(strict_types=1);

use Phoundation\Accounts\Users\Sessions\Session;
use Phoundation\Data\Validator\PostValidator;
use Phoundation\Web\Html\Pages\MfaCreatePage;
use Phoundation\Web\Html\Pages\MfaVerifyPage;
use Phoundation\Web\Requests\Request;
use Phoundation\Web\Requests\Response;
use Plugins\Phoundation\MultiFactorAuthentication\Exception\MultiFactorAuthenticationFailedException;

// Validate sign in data and sign in
if (Request::isPostRequestMethod()) {
    try {
        if (PostValidator::new()->get('number1') === null) {
            // We're testing the newly created MFA code
            $post = PostValidator::new()->validate();

        } else {
            // Try to submit the new MFA code
            $post = PostValidator::new()
                                 ->select('number1')->isInteger()->isBetween(0, 9)
                                 ->select('number2')->isInteger()->isBetween(0, 9)
                                 ->select('number3')->isInteger()->isBetween(0, 9)
                                 ->select('number4')->isInteger()->isBetween(0, 9)
                                 ->select('number5')->isInteger()->isBetween(0, 9)
                                 ->select('number6')->isInteger()->isBetween(0, 9)
                                 ->validate();

            $code = $post['number1'] . $post['number2'] . $post['number3'] . $post['number4'] . $post['number5'] . $post['number6'];
Session::getMultiFactorAuthenticationObject()->update($code);

            // Verify this MFA code. If verified, add a flash message and redirect to the original target
            Session::getMultiFactorAuthenticationObject()->verify($code, true)->update($code);
            Response::getFlashMessagesObject()->addSuccess(tr('Your MFA code has been updated'));
            Response::redirect('prev');
        }

    } catch (MultiFactorAuthenticationFailedException $e) {
        Response::getFlashMessagesObject()->addWarning(tr('The specified MFA code is invalid, please try again'));
    }

    // Render and return the page
    return MfaVerifyPage::new();
}


// Render and return the page
return MfaCreatePage::new();

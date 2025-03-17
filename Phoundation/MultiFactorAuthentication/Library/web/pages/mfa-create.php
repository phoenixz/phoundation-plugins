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

use Phoundation\Core\Sessions\Session;
use Phoundation\Data\Validator\Exception\ValidationFailedException;
use Phoundation\Data\Validator\PostValidator;
use Phoundation\Security\Passwords\Exception\NoPasswordSpecifiedException;
use Phoundation\Security\Passwords\Exception\PasswordNotChangedException;
use Phoundation\Security\Passwords\Exception\PasswordTooShortException;
use Phoundation\Web\Html\Pages\MfaCreatePage;
use Phoundation\Web\Http\Url;
use Phoundation\Web\Requests\Request;
use Phoundation\Web\Requests\Response;


// Only allow being here when it was forced by redirect
if (!Session::getUserObject()->getRedirect() or (Session::getUserObject()->getRedirect() !== (string)Url::new('/force-password-update.html')->makeWww())) {
//    Response::redirect('prev', 302, reason_warning: tr('Force password update is only available when it was accessed using forced user redirect'));
}


// Validate sign in data and sign in
if (Request::isPostRequestMethod()) {
    try {
        $post = PostValidator::new()
                             ->select('password')->isPassword()
                             ->select('passwordv')->isEqualTo('password')
                             ->validate();

        // Update the password for this session's user and remove the forced redirect to this page
        Session::getUserObject()
               ->createMfa()
               ->setRedirect()
               ->save();

        // Add a flash message and redirect to the original target
        Response::getFlashMessagesObject()->addSuccess(tr('Your password has been updated'));
        Response::redirect('prev');

    } catch (PasswordTooShortException|NoPasswordSpecifiedException) {
        Response::getFlashMessagesObject()->addWarning(tr('Please specify at least ":count" characters for the password', [
            ':count' => config()->getInteger('security.passwords.size.minimum', 10),
        ]));

    } catch (ValidationFailedException $e) {
        Response::getFlashMessagesObject()->addMessage($e);

    } catch (PasswordNotChangedException $e) {
        Response::getFlashMessagesObject()->addWarning(tr('You provided your current password. Please update your account to have a new and secure password'));
    }
}


// Render and return the page
return MfaCreatePage::new()->setGetdata([
    'email' => Session::getUserObject()->getEmail()
]);

<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Pages;

use Phoundation\Core\Core;
use Phoundation\Utils\Config;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;
use Phoundation\Web\Requests\Response;


/**
 * Class TemplateLostPasswordPage
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Web
 */
class TemplateForcePasswordUpdatePage extends TemplateRenderer
{
    public function render(): ?string
    {
        // This page will build its own body
        Response::setBuildBody(false);

        $this->render = '   <body class="hold-transition login-page" style="background: url(' .  UrlBuilder::getImg('img/backgrounds/' . Core::getProjectSeoName() . '/password.jpg') . '); background-position: center; background-repeat: no-repeat; background-size: cover;">
                                <div class="login-box">
                                    <!-- /.login-logo -->
                                    <div class="card card-outline card-info">
                                        <div class="card-header text-center">
                                          <a href="' .  Config::getString('project.customer-url', 'https://phoundation.org') . '" class="h1">' . Config::getString('project.owner.label', '<span>Phoun</span>dation') . '</a>
                                    </div>
                                    <div class="card-body">
                                        <p class="login-box-msg">' .  tr('Please update your account to have a new and secure password password before continuing...') . '</p>
                                        <p class="login-box-msg">' .  tr('Please ensure that your password has at least 10 characters, is secure, and is known only to you.') . '</p>

                                        <form action="' .  UrlBuilder::getWww() . '" method="post">
                                            <div class="input-group mb-3">
                                                <input type="password" name="password" id="password" class="form-control" placeholder="' .  tr('Password') . '">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-lock"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-group mb-3">
                                                <input type="password" name="passwordv" id="passwordv" class="form-control" placeholder="' .  tr('Verify password') . '">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-lock"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary btn-block">' .  tr('Update and continue') . '</button>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <a href="' .  UrlBuilder::getWww('/sign-out.html') . '" class="btn btn-outline-secondary btn-block">' .  tr('Sign out') . '</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </body>';

        return parent::render();
    }
}
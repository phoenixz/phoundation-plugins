<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Pages;

use Phoundation\Core\Sessions\Session;
use Phoundation\Utils\Config;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;
use Phoundation\Web\Requests\Response;


/**
 * Class TemplateSignUpPage
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Web
 */
class TemplateSignUpPage extends TemplateRenderer
{
    public function render(): ?string
    {
        // This page will build its own body
        Response::setBuildBody(false);

        $terms = tr('terms');

        $this->render = '   <body class="hold-transition register-page">
                                <div class="register-box">
                                    <div class="card card-outline card-primary">
                                        <div class="card-header text-center">
                                            <a href="' . Config::getString('project.customer-url', 'https://phoundation.org') . '" class="h1">' . Config::getString('project.owner.label', '<span>Phoun</span>dation') . '</a>
                                        </div>
                                        <div class="card-body">
                                            <p class="login-box-msg">' . tr('Register a new membership') . '</p>
                                            <form action="' . UrlBuilder::getWww() . '" method="post">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="Full name">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-user"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="email" class="form-control" placeholder="' . tr('Email') . '">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-envelope"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control" placeholder="' . tr('Password') . '">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-lock"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control" placeholder="' . tr('Retype password') . '">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-lock"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="icheck-primary">
                                                            <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                                            <label for="agreeTerms">
                                                                ' . tr('I agree to the :terms', [':terms' => '<a href="' . UrlBuilder::getWww('terms') . '">' . $terms . '</a>']) . '
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-4">
                                                        <button type="submit" class="btn btn-primary btn-block">' . tr('Register') . '</button>
                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                            </form>';

        $html = '';

        if (Session::supports('facebook')) {
            $html .= '                          <a href="#" class="btn btn-block btn-primary">
                                                    <i class="fab fa-facebook mr-2"></i>
                                                    ' . tr('Sign up using Facebook') . '
                                                </a>';
        }

        if (Session::supports('google')) {
            $html .= '                          <a href="#" class="btn btn-block btn-danger">
                                                    <i class="fab fa-google-plus mr-2"></i>
                                                    ' . tr('Sign up using Google+') . '
                                                </a>';
        }

        if ($html) {
            $this->render .= '              <div class="social-auth-links text-center">
                                            ' . $html . '
                                            </div>';
        }

        $this->render .= '                  <a href="' . UrlBuilder::getWww('sign-in') . '" class="text-center">' . tr('I already have an account') . '</a>
                                        </div>';

        if (Session::supports('copyright')) {
            $this->render .= '          <div class="login-footer text-center">
                                            ' . 'Copyright © ' . Config::getString('project.copyright', '2023') . ' <b><a href="' . Config::getString('project.owner.url', 'https://phoundation.org') . '" target="_blank">' . Config::getString('project.owner.name', 'Phoundation') . '</a></b><br>' . '
                                            All rights reserved
                                        </div>';
        }

        $this->render .= '          </div>
                                </div>
                            </body>';

        return parent::render();
    }
}
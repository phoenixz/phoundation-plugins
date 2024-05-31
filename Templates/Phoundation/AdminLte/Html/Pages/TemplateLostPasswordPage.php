<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Pages;

use Phoundation\Core\Core;
use Phoundation\Core\Sessions\Session;
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
class TemplateLostPasswordPage extends TemplateRenderer
{
    public function render(): ?string
    {
        // This page will build its own body
        Response::setBuildBody(false);

        $this->render = '   <body class="hold-transition login-page" style="background: url(' . UrlBuilder::getImg('img/backgrounds/' . Core::getProjectSeoName() . '/lost-password.jpg') . '); background-position: center; background-repeat: no-repeat; background-size: cover;">
                                <div class="login-box">
                                    <div class="card card-outline card-info">
                                        <div class="card-header text-center">
                                            <a href="' . Config::getString('project.customer-url', 'https://phoundation.org') . '" class="h1">' . Config::getString('project.owner.label', '<span>Phoun</span>dation') . '</a>
                                        </div>
                                        <div class="card-body">
                                            <p class="login-box-msg">' . tr('Please provide your email address and we will send you a link where you can re-establish your password') . '</p>

                                            <form action="' . UrlBuilder::getWww() . '" method="post">';

        if (Session::supports('email')) {
            $this->render .= '                      <div class="input-group mb-3">
                                                        <input type="email" name="email" id="email" class="form-control" placeholder="' . tr('Email address') . '"' . isset_get($get['email']) ? 'value="' . $get['email'] . '"' : '' . '>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <span class="fas fa-envelope"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <!-- /.col -->
                                                        <div class="col-12">
                                                            <button type="submit" class="btn btn-primary btn-block">' . tr('Request a new password') . '</button>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a class="btn btn-outline-secondary btn-block" href="' . UrlBuilder::getWww('sign-in')->addQueries(isset_get($get['email']) ? 'email=' . $get['email'] : '', isset_get($get['redirect']) ? 'redirect=' . $get['redirect'] : '') . '">' . tr('Back to sign in') . '</a>
                                                        </div>
                                                    </div>';
        }

        $this->render .= '                  </form>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                </body>';

        return parent::render();
    }
}
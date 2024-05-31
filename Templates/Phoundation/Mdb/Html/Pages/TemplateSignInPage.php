<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Pages;

use Phoundation\Core\Core;
use Phoundation\Core\Sessions\Session;
use Phoundation\Utils\Config;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;
use Phoundation\Web\Requests\Response;


/**
 * Class TemplateSignInPage
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Web
 */
class TemplateSignInPage extends TemplateRenderer
{
    public function render(): ?string
    {
        // This page will build its own body
        Response::setBuildBody(false);

        $sso      = '';
        $terms    = '<a href="' . UrlBuilder::getWww('terms') . '">' . tr('terms and conditions') . '</a>';
        $register = '<a href="' . UrlBuilder::getWww('sign-up') . '">' . tr('Register') . '</a>';

        // Render SSO entries?
        if (Session::supports('facebook')) {
            $sso .= '   <button type="button" class="btn btn-link btn-lg btn-floating mx-1" data-mdb-ripple-init data-ripple-color="primary">
                          <i class="fab fa-facebook-f"></i>
                        </button>';
        }

        if (Session::supports('google')) {
            $sso .= '   <button type="button" class="btn btn-link btn-lg btn-floating mx-1" data-mdb-ripple-init data-ripple-color="primary">
                          <i class="fab fa-google"></i>
                        </button>';
        }

        if (Session::supports('twitter')) {
            $sso .= '   <button type="button" class="btn btn-link btn-lg btn-floating mx-1" data-mdb-ripple-init data-ripple-color="primary">
                          <i class="fab fa-twitter"></i>
                        </button>';
        }

        if (Session::supports('github')) {
            $sso .= '   <button type="button" class="btn btn-link btn-lg btn-floating mx-1" data-mdb-ripple-init data-ripple-color="primary">
                          <i class="fab fa-github"></i>
                        </button>';
        }

        // Render the signin page section
        $signin   = '   <form method="post" action="' . UrlBuilder::getWww() . '">
                          <div class="text-center h1">
                              ' . Config::getString('project.owner.label', '<span>Phoun</span>dation') . '
                          </div>
                          <hr>';

        if ($sso) {
            $this->render .= '<div class="text-center mb-3">
                                    <p>' . tr('Sign up with:') . '</p>
                                    ' . $sso . '
                                  </div>
                                  <p class="text-center">' . tr('or:') . '</p>';
        }

        $signin .= '      <div class="form-outline mb-4" data-mdb-input-init>
                            <input type="email" id="loginName" name="email" class="form-control"' . ($this->component->getEmail() ? 'value="' . $this->component->getEmail() . '"' : '') . ' />
                            <label class="form-label" for="loginName">' . tr('Email or username') . '</label>
                          </div>

                          <!-- Password input -->
                          <div class="form-outline mb-4" data-mdb-input-init>
                            <input type="password" id="loginPassword" name="password" class="form-control" />
                            <label class="form-label" for="loginPassword">' . tr('Password') . '</label>
                          </div>

                          <!-- 2 column grid layout -->
                          <div class="row mb-4">
                            <div class="col-md-6 d-flex justify-content-center">
                              <!-- Checkbox -->
                              <div class="form-check mb-3 mb-md-0">
                                <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                                <label class="form-check-label" for="loginCheck">
                                  ' . tr('Remember me') . '
                                </label>
                              </div>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center">
                              <!-- Simple link -->
                              <a href="#!">Forgot password?</a>
                            </div>
                          </div>

                          <!-- Submit button -->
                          <button type="submit" class="btn btn-primary btn-block mb-4" data-mdb-ripple-init>
                            Sign in
                          </button>';

        if (Session::supports('signup')) {
            $signin .= '  <div class="text-center">
                            <p>' . tr('Not a member? :register', [':register' => $register]) . '</p>
                          </div>';
        }

        if (Session::supports('copyright')) {
            $signin .= '  <div class="text-center">
                            Copyright © 2023 ' . Config::getString('project.name', 'Phoundation') . '<br/><small>All rights reserved</small>
                          </div>';
        }

        $signin .= '    </form>';

        if (Session::supports('signup')) {
            // Render the signup page section
            $signup = '     <form method="post" action="' . UrlBuilder::getWww() . '">
                              <div class="form-outline mb-4" data-mdb-input-init>
                                <input type="text" id="registerName" class="form-control" />
                                <label class="form-label" for="registerName">' . tr('Name') . '</label>
                              </div>

                              <!-- Username input -->
                              <div class="form-outline mb-4" data-mdb-input-init>
                                <input type="text" id="registerUsername" class="form-control" />
                                <label class="form-label" for="registerUsername">' . tr('Username') . '</label>
                              </div>

                              <!-- Email input -->
                              <div class="form-outline mb-4" data-mdb-input-init>
                                <input type="email" id="registerEmail" class="form-control" />
                                <label class="form-label" for="registerEmail">' . tr('Email') . '</label>
                              </div>

                              <!-- Password input -->
                              <div class="form-outline mb-4" data-mdb-input-init>
                                <input type="password" id="registerPassword" class="form-control" />
                                <label class="form-label" for="registerPassword">' . tr('Password') . '</label>
                              </div>

                              <!-- Repeat Password input -->
                              <div class="form-outline mb-4" data-mdb-input-init>
                                <input type="password" id="registerRepeatPassword" class="form-control" />
                                <label class="form-label" for="registerRepeatPassword">' . tr('Repeat password') . '</label>
                              </div>

                              <!-- Checkbox -->
                              <div class="form-check d-flex justify-content-center mb-4">
                                <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck" checked
                                       aria-describedby="registerCheckHelpText" />
                                <label class="form-check-label" for="registerCheck">
                                  ' . tr('I have read and agree to the :terms', [':terms' => $terms]) . '
                                </label>
                              </div>

                              <!-- Submit button -->
                              <button type="submit" class="btn btn-primary btn-block mb-3" data-mdb-ripple-init>
                                Sign in
                              </button>';

            if (Session::supports('copyright')) {
                $signup .= '  <div class="text-center">
                                Copyright © 2023 ' . Config::getString('project.name', 'Phoundation') . '<br/><small>All rights reserved</small>
                              </div>';
            }

            $signup .= '    </form>';
        }

        // Render the entire page
        $this->render = '   <!--Main Navigation-->
                            <header>
                              <!-- Heading -->
                              <section class="text-center text-md-start">
                                <!-- Background gradient -->
                                <div class="p-5" style="height: 200px; background: url(' . UrlBuilder::getImg('img/banners/' . Core::getProjectSeoName() . '/large.jpg') . ');">
                                </div>
                                <!-- Background gradient -->
                              </section>
                              <!-- Heading -->

                            </header>
                            <!--Main Navigation-->

                            <!--Main layout-->
                            <main class="mb-5" style="margin-top: -100px;">
                              <!-- Container for demo purpose -->
                              <div class="container px-4">

                                <div class="row d-flex justify-content-center">
                                  <div class="col-xl-5 col-md-8">
                                    <div class="card shadow-4">
                                      <div class="card-body p-4">';

        if (Session::supports('signup')) {
            $this->render .= '          <!-- Pills navs -->
                                        <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                                          <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="tab-login" data-mdb-pill-init href="#pills-login" role="tab"
                                               aria-controls="pills-login" aria-selected="true">Login</a>
                                          </li>
                                          <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="tab-register" data-mdb-pill-init href="#pills-register" role="tab"
                                               aria-controls="pills-register" aria-selected="false">Register</a>
                                          </li>
                                        </ul>';

            $this->render .= '          <div class="tab-content">
                                          <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                                            ' . $signin . '
                                          </div>
                                          <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                                            ' . $signup . '
                                          </div>
                                        </div>';
        } else {
            $this->render .= $signin;
        }

        $this->render .= '            </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </main>';

        return parent::render(); // TODO: Change the autogenerated stub
    }
}
<?php

/**
 * Class Template
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */

declare(strict_types=1);

namespace Templates\Mdb\Html\Pages;

use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;

class Template extends TemplateRenderer
{
    public function render(): ?string
    {
        switch ($this->component->getPage()) {
            case 'system/http-error':
                $this->render =  '<body>
                                    <div class="container pt-5">

                                      <!-- Section: Design Block -->
                                      <section class="mb-8">
                                        <style>
                                          .rounded-t-2-5 {
                                            border-top-left-radius: 0.75rem;
                                            border-top-right-radius: 0.75rem;
                                          }
                                          @media (min-width: 992px) {
                                            .rounded-tr-lg-0 {
                                              border-top-right-radius: 0;
                                            }
                                            .rounded-bl-lg-2-5 {
                                              border-bottom-left-radius: 0.75rem;
                                            }
                                          }
                                        </style>
                                        <div class="card rounded-6 shadow-3-soft" style="background-color: #fff9f2">
                                          <div class="row g-0 d-flex align-items-center">
                                            <div class="col-lg-6 col-xl-5">
                                              <img src=":img" alt="' . tr('Background image') . '" class="w-100 rounded-t-2-5 rounded-tr-lg-0 rounded-bl-lg-2-5"/>
                                            </div>
                                            <div class="col-lg-6 col-xl-7">
                                              <div class="card-body py-4 py-md-5 py-lg-4 py-xl-5 px-md-5">
                                                <div class="border-top border-dark" style="width: 100px"></div>
                                                <h2 class="display-4 mt-5 mb-4" style="color: #344e41"><i class="fas fa-exclamation-triangle text-:type"></i> :h2 :h3</h2>
                                                <p>:p</p>
                                                <a class="btn btn-lg btn-primary" href="' . UrlBuilder::getWww('sign-out') . '">' . tr('Sign out') . '</a>
                                                <a class="btn btn-lg btn-primary" href="' . UrlBuilder::getCurrentDomainRootUrl() . '" role="button" data-mdb-ripple-init>' . tr('Goto index page') . '</a>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </section>
                                    </div>
                                  </body>';
                break;

            default:
                throw new OutOfBoundsException(tr('Specified template page ":template" does not exist', [
                    ':template' => $this->component->getPage()
                ]));
        }

        return $this->render;
    }
}
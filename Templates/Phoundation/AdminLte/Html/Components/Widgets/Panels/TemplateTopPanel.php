<?php

declare(strict_types=1);

namespace Templates\AdminLte\Html\Components\Widgets\Panels;

use Phoundation\Core\Sessions\Session;
use Phoundation\Utils\Strings;
use Phoundation\Web\Html\Components\Icons\FullScreen;
use Phoundation\Web\Html\Components\Input\Interfaces\RenderInterface;
use Phoundation\Web\Html\Enums\EnumDisplayMode;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;
use Templates\AdminLte\Exception\AdminLteException;


/**
 * Class TemplateTopPanel
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\AdminLte
 */
class TemplateTopPanel extends TemplateRenderer
{
    /**
     * Renders and returns the top panel
     *
     * @return string|null
     */
    public function render(): ?string
    {
        // If impersonated, change top panel color and add an impersonation message
        if (Session::isImpersonated()) {
            $this->component->setMode(EnumDisplayMode::danger);
            $message = tr('(Impersonated by ":user")', [':user' => Session::getRealUser()->getDisplayName()]);

        } else {
            $this->component->setMode(EnumDisplayMode::white);
        }

        // Top level message?
        if (isset($message)) {
            $message = '    <li class="nav-item d-none d-sm-inline-block">
                              <a href="#" class="nav-link">' . Html::safe($message) . '</a>
                            </li>';
        }

        // Build the left menu
        $left_menu = '    <ul class="navbar-nav">
                            <li class="nav-item">
                              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                            </li>';

        if ($this->component->keyExists('menu')) {
            foreach ($this->component->get('menu') as $label => $url) {
                $left_menu .= ' <li class="nav-item d-none d-sm-inline-block">
                                  <a href="' . Html::safe($url) . '" class="nav-link">' . Html::safe($label) . '</a>
                                </li>';
            }
        }

        // Add the optional extra message and finish the left menu
        $left_menu .=       isset_get($message) . '
                          </ul>';

        // Build the top panel with the left menu in it
        $this->render = ' <nav class="main-header navbar navbar-expand navbar-' . Html::safe($this->component->getMode()->value) . ' navbar-light">
                            <!-- Left navbar links -->
                            ' . $left_menu . '                    
                            <!-- Right navbar links -->
                            <ul class="navbar-nav ml-auto">';

        foreach ($this->component->getElementsObject() as $element) {
            $element_type = Strings::until($element, '-');

            switch ($element) {
                case 'search':
                    $this->render .= '<!-- Navbar Search -->
                                      <li class="nav-item">
                                        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                                          <i class="fas fa-search"></i>
                                        </a>
                                        <div class="navbar-search-block">
                                          <form class="form-inline">
                                            <div class="input-group input-group-sm">
                                              <input class="form-control form-control-navbar" type="search" placeholder="' . tr('Search everywhere') . '" aria-label="' . tr('Search everywhere') . '">
                                              <div class="input-group-append">
                                                <button class="btn btn-navbar" type="submit">
                                                  <i class="fas fa-search"></i>
                                                </button>
                                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                                  <i class="fas fa-times"></i>
                                                </button>
                                              </div>
                                            </div>
                                          </form>
                                        </div>
                                      </li>';
                    break;

                case 'messages':
                    $this->render .= '<!-- Messages Dropdown Menu -->
                                      <li class="nav-item dropdown messages">
                                        ' . $this->component->getMessagesDropDown()->render() . '
                                      </li>';
                    break;

                case 'notifications':
                    $this->render .= '<!-- Notifications Dropdown Menu -->
                                      <li class="nav-item dropdown notifications">
                                        ' . $this->component->getNotificationsDropDown()->render() . '
                                      </li>';
                    break;

                case 'languages':
                    $this->render .= '<li class="nav-item dropdown languages">                                  
                                          ' . $this->component->getLanguagesDropDown()->render() . '
                                      </li>';
                    break;

                case 'icon':
                    $this->render .= '  <li class="nav-item">
                                          ' . $this->component->getIcons()->get($element_type)->render() . '
                                        </li>';
                    break;

                case 'full-screen':
                    $this->render .= '<li class="nav-item">
                                        <span class="nav-link">' . FullScreen::new()->render() . '</span>
                                      </li>';
                    break;

                case 'sign-out':
                    $this->render .= '<li class="nav-item">
                                        <a class="nav-link" href="' . Html::safe(UrlBuilder::getWww('sign-out')) . '" role="button">
                                          <i class="fas fa-sign-out-alt"></i>
                                        </a>
                                      </li>';
                    break;

                case 'sidebar-button':
                    $this->render .= '<li class="nav-item">
                                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                                          <i class="fas fa-th-large"></i>
                                        </a>
                                      </li>';
                    break;

                default:
                    // This is a custom element. Must be either a render-able object, or a callback that returns HTML
                    if ($element instanceof RenderInterface) {
                        $this->render .= $element->render();

                    } elseif (is_callable($element)) {
                        $this->render .= $element();

                    } else {
                        throw new AdminLteException(tr('Unknown top panel element ":element" specified', [
                            ':element' => $element
                        ]));
                    }
            }
        }

        $this->render .= '  </ul>
                          </nav>';

        return parent::render();
    }
}

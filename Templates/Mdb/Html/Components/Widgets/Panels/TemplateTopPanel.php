<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Widgets\Panels;

use Phoundation\Core\Sessions\Session;
use Phoundation\Utils\Strings;
use Phoundation\Web\Html\Components\Icons\FullScreen;
use Phoundation\Web\Html\Components\Icons\Icon;
use Phoundation\Web\Html\Components\Icons\SignOut;
use Phoundation\Web\Html\Components\Input\Interfaces\RenderInterface;
use Phoundation\Web\Html\Components\Logo;
use Phoundation\Web\Html\Components\Widgets\Panels\TopPanel;
use Phoundation\Web\Html\Enums\EnumDisplayMode;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;
use Phoundation\Web\Http\UrlBuilder;
use Templates\Mdb\Exception\MdbException;


/**
 * Class TemplateTopPanel
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateTopPanel extends TemplateRenderer
{
    /**
     * TemplateTopPanel class constructor
     */
    public function __construct(TopPanel $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders and returns the top panel
     *
     * @return string|null
     */
    public function render(): ?string
    {
//        return '<!-- Navbar -->
//<header>
//<nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
//  <!-- Container wrapper -->
//  <div class="container-fluid">
//    <!-- Toggle button -->
//    <button
//      data-mdb-collapse-init
//      class="navbar-toggler"
//      type="button"
//      data-mdb-target="#navbarSupportedContent"
//      aria-controls="navbarSupportedContent"
//      aria-expanded="false"
//      aria-label="Toggle navigation"
//    >
//      <i class="fas fa-bars"></i>
//    </button>
//
//    <!-- Collapsible wrapper -->
//    <div class="collapse navbar-collapse" id="navbarSupportedContent">
//      <!-- Navbar brand -->
//      <a class="navbar-brand mt-2 mt-lg-0" href="#">
//        <img
//          src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp"
//          height="15"
//          alt="MDB Logo"
//          loading="lazy"
//        />
//      </a>
//      <!-- Left links -->
//      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
//        <li class="nav-item">
//          <a class="nav-link" href="#">Dashboard</a>
//        </li>
//        <li class="nav-item">
//          <a class="nav-link" href="#">Team</a>
//        </li>
//        <li class="nav-item">
//          <a class="nav-link" href="#">Projects</a>
//        </li>
//      </ul>
//      <!-- Left links -->
//    </div>
//    <!-- Collapsible wrapper -->
//
//    <!-- Right elements -->
//    <div class="d-flex align-items-center">
//      <!-- Icon -->
//      <a class="text-reset me-3" href="#">
//        <i class="fas fa-shopping-cart"></i>
//      </a>
//
//      <!-- Notifications -->
//      <div class="dropdown">
//        <a
//          data-mdb-dropdown-init
//          class="text-reset me-3 dropdown-toggle hidden-arrow"
//          href="#"
//          id="navbarDropdownMenuLink"
//          role="button"
//          aria-expanded="false"
//        >
//          <i class="fas fa-bell"></i>
//          <span class="badge rounded-pill badge-notification bg-danger">1</span>
//        </a>
//        <ul
//          class="dropdown-menu dropdown-menu-end"
//          aria-labelledby="navbarDropdownMenuLink"
//        >
//          <li>
//            <a class="dropdown-item" href="#">Some news</a>
//          </li>
//          <li>
//            <a class="dropdown-item" href="#">Another news</a>
//          </li>
//          <li>
//            <a class="dropdown-item" href="#">Something else here</a>
//          </li>
//        </ul>
//      </div>
//      <!-- Avatar -->
//      <div class="dropdown">
//        <a
//          data-mdb-dropdown-init
//          class="dropdown-toggle d-flex align-items-center hidden-arrow"
//          href="#"
//          id="navbarDropdownMenuAvatar"
//          role="button"
//          aria-expanded="false"
//        >
//          <img
//            src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp"
//            class="rounded-circle"
//            height="25"
//            alt="Black and White Portrait of a Man"
//            loading="lazy"
//          />
//        </a>
//        <ul
//          class="dropdown-menu dropdown-menu-end"
//          aria-labelledby="navbarDropdownMenuAvatar"
//        >
//          <li>
//            <a class="dropdown-item" href="#">My profile</a>
//          </li>
//          <li>
//            <a class="dropdown-item" href="#">Settings</a>
//          </li>
//          <li>
//            <a class="dropdown-item" href="#">Logout</a>
//          </li>
//        </ul>
//      </div>
//    </div>
//    <!-- Right elements -->
//  </div>
//  <!-- Container wrapper -->
//</nav>
//</header>
//<!-- Navbar -->';
//
//





//        // TODO Change this hard coded menu below for a flexible one
////        $left_menu = $this->element->getMenu()?->render();
//
//        // If impersonated, change top panel color and add an impersonation message
//        if (Session::isImpersonated()) {
//            $this->component->setMode(EnumDisplayMode::danger);
//            $message = tr('(Impersonated by ":user")', [':user' => Session::getRealUser()->getDisplayName()]);
//
//        } else {
//            $this->component->setMode(EnumDisplayMode::white);
//        }
//
//        // Top level message?
//        if (isset($message)) {
//            $message = '    <li class="nav-item d-none d-sm-inline-block">
//                              <a href="#" class="nav-link">' . Html::safe($message) . '</a>
//                            </li>';
//        }
//
//        // Build the left menu
//        $left_menu = '    <ul class="navbar-nav">
//                            <li class="nav-item">
//                              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
//                            </li>';
//
//        if ($this->component->keyExists('menu')) {
//            foreach ($this->component->get('menu') as $label => $url) {
//                $left_menu .= ' <li class="nav-item d-none d-sm-inline-block">
//                                  <a href="' . Html::safe($url) . '" class="nav-link">' . Html::safe($label) . '</a>
//                                </li>';
//            }
//        }
//
//        // Add the optional extra message and finish the left menu
//        $left_menu .=       isset_get($message) . '
//                          </ul>';


        // Render the top panel
        $this->render = '   <header> 
                              <nav class="navbar phoundation-navbar navbar-expand-lg navbar-' . Html::safe($this->component->getBootstrapBackgroundColor()->value) . ' bg-body-tertiary">
                                <div class="container-fluid">';

        $delete   = [];
        $contents = '';

        // Render collapsible parts
        foreach ($this->component->getElementsObject() as $element_id => $element) {
            $element_type = Strings::until($element, '-');

            switch ($element) {
                case 'logo':
                    $delete[]  = $element_id;
                    $logo      = Logo::new();
                    $contents .= '<a class="navbar-brand mt-2 mt-lg-0" href="' . $logo->getAnchor()->getHref() . '">
                                    <img src="' . $logo->getSrc() . '" height="15" alt="' . $logo->getAlt() . '" loading="lazy"/>
                                  </a>';
                    break;

                case 'menu':
                    $delete[] = $element_id;

                    if ($this->component->getMenusObject()->getCount()) {
                        foreach ($this->component->getMenusObject() as $menu) {
                            $contents .= $menu->render();
                        }
                    }

                    break;

                case 'text':
                    $delete[] = $element_id;

                    if ($this->component->getTexts()->getCount()) {
                        foreach ($this->component->getTexts() as $text) {
                            $contents .= '<small>' . $text . '</small>';
                        }
                    }

                    break;

                case 'sidebar-button':
                    $delete[] = $element_id;

                    $contents .= '<button data-mdb-ripple-init data-mdb-toggle="sidenav" data-mdb-target="#sidenav-9" class="btn btn-primary me-2 d-flex align-items-center" aria-controls="#sidenav-9" aria-haspopup="true">
                                    <i class="fas fa-bars"></i>
                                  </button>';
                    break;

                case 'search':
                    $delete[] = $element_id;

                    $contents .= '<form class="d-flex input-group w-auto">
                                    <input type="search" class="form-control rounded" placeholder="' . tr('Search') . '" aria-label="' . tr('Search') . '" aria-describedby="search-addon" />
                                      <span class="input-group-text border-0" id="search-addon">
                                        <i class="fas fa-search"></i>
                                      </span>
                                  </form>';
                    break;

            }
        }

        if ($contents) {
            $this->component->getElementsObject()->removeKeys($delete);

            $this->render .= '<div class="d-flex">
                                ' . $contents . ' 
                              </div>';

//            $this->render .= '<div class="collapse navbar-collapse" id="navbarSupportedContent">
//                                ' . $contents . '
//                              </div>';
        }

        // Build the rest
        $contents = '';

        foreach ($this->component->getElementsObject() as $element) {
            $element_type = Strings::until($element, '-');

            switch ($element) {
                case 'messages':
                    $content = $this->component->getMessagesDropDown()->render();
                    break;

                case 'notifications':
                    $content = '  <li class="nav-item me-3 me-lg-1 dropdown">
                                      <span>' . $this->component->getNotificationsDropDown()->render() . '</span>
                                  </li>';
                    break;

                case 'languages':
                    $content = '  <li class="nav-item me-3 me-lg-1 dropdown">
                                    <span>' . $this->component->getLanguagesDropDown()->render() . '</span>
                                  </li>';
                    break;

                case 'breadcrumbs':
                    $content = $this->component->getBreadcrumbs()->get($element_type)->render();
                    break;

                case 'button':
                    $content = '  <li class="nav-item me-3 me-lg-1">
                                    <span>' . $this->component->getButtons()->get($element_type)->render() . '</span>
                                  </li>';
                    break;

                case 'avatar':
                    $content = '  <li class="nav-item me-3 me-lg-1 dropdown">
                                    <a class="nav-link" href="#">
                                      <span>' . $this->component->getAvatars()->get($element_type)->render() . '</span>
                                    </a>
                                  </li>';
                    break;

                case 'icon':
                    $content = '  <li class="nav-item me-3 me-lg-1">
                                    ' . $this->component->getIcons()->get($element_type)->render() . '
                                  </li>';
                    break;

                case 'full-screen':
                    $content = '  <li class="nav-item me-3 me-lg-1">
                                    ' . FullScreen::new()->render() .  '
                                  </li>';
                    break;

                case 'sign-out':
                    $content = '  <li class="nav-item me-3 me-lg-1">
                                    ' . SignOut::new()->render() .  '
                                  </li>';
                    break;

                default:
                    // This is a custom element. Must be either a render-able object, or a callback that returns HTML
                    if ($element instanceof RenderInterface) {
                        $content = $element->render();

                    } elseif (is_callable($element)) {
                        $content = $element();

                    } else {
                        throw new MdbException(tr('Unknown top panel element ":element" specified', [
                            ':element' => $element
                        ]));
                    }
            }

            $contents .= $content;
        }

        if ($contents) {
            $this->render .= '    <ul class="navbar-nav flex-row">
                                    ' . $contents . '
                                  </ul>';
        }

        $this->render .= '      </div>
                              </nav>
                            </header>';

        return parent::render();
    }
}

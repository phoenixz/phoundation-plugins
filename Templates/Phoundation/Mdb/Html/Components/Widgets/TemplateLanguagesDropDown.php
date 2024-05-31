<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Widgets;

use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Web\Html\Components\Widgets\LanguagesDropDown;
use Phoundation\Web\Html\Html;
use Phoundation\Web\Html\Template\TemplateRenderer;


/**
 * Class TemplateLanguagesDropDown
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateLanguagesDropDown extends TemplateRenderer
{
    /**
     * LanguagesDropDown class constructor
     */
    public function __construct(LanguagesDropDown $component)
    {
        parent::__construct($component);
    }


    /**
     * Renders and returns the NavBar
     *
     * @return string|null
     */
    public function render(): ?string
    {
        if (!$this->component->getSettingsUrl()) {
            throw new OutOfBoundsException(tr('No settings page URL specified'));
        }

        $languages = $this->component->getLanguages();
        $count     = $languages?->getCount();

        $this->render = '   <span data-mdb-dropdown-init class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" aria-expanded="false">
                              <i class="flag-united-kingdom flag m-0"></i>
                            </span>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">';

        if ($count) {
            $current = 0;

            $this->render .= '<li>
                                <span class="dropdown-item">
                                  <i class="flag-united-kingdom flag"></i>English
                                  <i class="fa fa-check text-success ms-2"></i
                                ></span>
                              </li>
                              <li>
                                <hr class="dropdown-divider" />
                              </li>';

            foreach ($languages as $language) {
                if (++$current > 12) {
                    break;
                }

                $this->render .= '<li>
                                    <a class="dropdown-item" href="' . Html::safe(str_replace(':ID', $language->getId(), $this->component->getLanguagesUrl())) . '"><i class="flag-' . $language->getFlagName() . ' flag"></i>' . $language->getName() . '</a>
                                  </li>';
            }
            
        } else {
            $this->render .= '    <li>
                                    <span class="dropdown-item" href="#">' . tr('No alternative languages available') . '</span>
                                  </li>
                                  <li>
                                    <hr class="dropdown-divider" />
                                  </li>';
        }

        $this->render .= '        <li>
                                    <a href="' . Html::safe($this->component->getSettingsUrl()) . '" class="dropdown-item dropdown-footer">' . tr('Language settings') . '</a>
                                  </li>
                                </ul>';

        return parent::render();
    }
}
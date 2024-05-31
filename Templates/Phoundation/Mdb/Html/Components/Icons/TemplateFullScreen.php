<?php

declare(strict_types=1);

namespace Templates\Mdb\Html\Components\Icons;

use Phoundation\Web\Html\Components\Icons\FullScreen;
use Phoundation\Web\Html\Components\Script;


/**
 * Class TemplateFullScreen
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateFullScreen extends TemplateIcon
{
    /**
     * Icons class constructor
     */
    public function __construct(FullScreen $component)
    {
        parent::__construct($component);
    }


    /**
     * Render the icon HTML
     *
     * @note This render skips the parent Element class rendering for speed and simplicity
     * @return string|null
     */
    public function render(): ?string
    {
        $this->component->getAnchor()->setHref('#');

        return parent::render() . Script::new('
            function toggleFullScreen() {
              if (!document.fullscreenElement &&    // alternative standard method
                  !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
                if (document.documentElement.requestFullscreen) {
                  document.documentElement.requestFullscreen();
                } else if (document.documentElement.msRequestFullscreen) {
                  document.documentElement.msRequestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                  document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                  document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
              } else {
                if (document.exitFullscreen) {
                  document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                  document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                  document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                  document.webkitExitFullscreen();
                }
              }
            }

            $("*[data-widget=\"fullscreen\"]").on("click", function(e) {
                e.stopPropagation();
                toggleFullScreen();
                return false;
            });
        ')->render();
    }
}
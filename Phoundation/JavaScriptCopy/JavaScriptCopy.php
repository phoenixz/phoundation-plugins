<?php

declare(strict_types=1);

namespace Plugins\Phoundation\JavaScriptCopy;

use Phoundation\Data\Traits\TraitDataBrowserEvent;
use Phoundation\Data\Traits\TraitDataSelector;
use Phoundation\Data\Traits\TraitDataTarget;
use Phoundation\Web\Http\Html\Components\Script;
use Phoundation\Web\Http\Html\Enums\BrowserEvent;
use Phoundation\Web\Http\Html\Traits\Rendered;


/**
 * Class JavaScriptCopy
 *
 * This class can add (reasonably) reliable javascript copy method to the browser client
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2022 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Phoundation\Data
 */
class JavaScriptCopy extends Script
{
    use Rendered;
    use TraitDataSelector;
    use TraitDataTarget;
    use TraitDataBrowserEvent;


    /**
     * JavaScriptCopy class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->browser_event = BrowserEvent::click;
    }


    /**
     * @inheritDoc
     */
    public function render(): ?string
    {
        if (self::$rendered) {
            $this->content = '';
        } else {
            $this->content = 'function fallbackCopyTextToClipboard(text) {
              var textArea = document.createElement("textarea");
              textArea.value = text;
              
              // Avoid scrolling to bottom
              textArea.style.top = "0";
              textArea.style.left = "0";
              textArea.style.position = "fixed";
            
              document.body.appendChild(textArea);
              textArea.focus();
              textArea.select();
            
              try {
                var successful = document.execCommand("copy");
                console.log("Fallback: Copying text command was " + (successful ? "successful" : "unsuccessful"));
              } catch (e) {
                console.error("Fallback copy failed to copy to clipboard too", e);
              }
            
              document.body.removeChild(textArea);
            }
            
            function copyTextToClipboard(text) {
              if (!navigator.clipboard) {
                fallbackCopyTextToClipboard(text);
                return;
              }
              navigator.clipboard.writeText(text).then(function() {
                console.log("Async: Copying to clipboard was successful!");
              }, function(e) {
                console.error("Async: Could not copy text: ", e);
              });
            }';
        }

        self::$rendered = true;

        $this->content .= 'document.querySelector("' . $this->selector . '").addEventListener("' . $this->browser_event->name . '", function(event) {
             copyTextToClipboard(document.querySelector("' . $this->target . '").textContent);
            });';

        return parent::render();
    }
}

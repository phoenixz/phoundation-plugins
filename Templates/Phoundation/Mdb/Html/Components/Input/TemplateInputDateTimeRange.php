<?php

declare(strict_types=1);

namespace Templates\Phoundation\Mdb\Html\Components\Input;

use Templates\Mdb\Html\Components\Input\InputDateTimeRange;


/**
 * Class TemplateInputDateTimeRange
 *
 *
 *
 * @author Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright (c) 2024 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Templates\Mdb
 */
class TemplateInputDateTimeRange extends TemplateInput
{
    /**
     * InputText class constructor
     */
    public function __construct(InputDateTimeRange $component)
    {
        $component->addClasses('form-control');
        parent::__construct($component);
    }


    /**
     * Render and return the HTML for this object
     *
     * @return string|null
     */
    public function render(): ?string
    {
//        <div class="form-group">
//            <label>Date and time range:</label>
//            <div class="input-group">
//                <div class="input-group-prepend">
//                    <span class="input-group-text"><i class="far fa-clock"></i></span>
//                </div>
//                <input type="text" class="form-control float-right" id="reservationtime">
//            </div>
//        </div>

//        //Date range picker with time picker
//        $('#reservationtime').daterangepicker({
//          timePicker: true,
//          timePickerIncrement: 30,
//          locale: {
//            format: 'MM/DD/YYYY hh:mm A'
//          }
//        })

        return parent::render();
    }
}
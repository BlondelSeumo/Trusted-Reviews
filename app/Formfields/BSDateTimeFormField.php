<?php

namespace App\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class BSDateTimeFormField extends AbstractHandler
{
    protected $codename = 'bsdatetime';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('formfields.bsdatetimepicker', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}
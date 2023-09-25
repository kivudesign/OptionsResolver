<?php
/*
 * Copyright (c) 2022.
 * The OptionsResolver component helps you configure objects with option arrays. It supports default values, option constraints and lazy options.
 */

namespace Wepesi\Resolver\Traits;

trait ExceptionTraits
{
    protected function exception($ex):array
    {
        return ['InvalidArgumentException' => $ex];
    }
}
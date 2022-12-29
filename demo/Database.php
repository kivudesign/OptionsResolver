<?php
/*
 * Copyright (c) 2022.
 * The OptionsResolver component helps you configure objects with option arrays. It supports default values, option constraints and lazy options.
 */

namespace Wepesi\Demo;

use Wepesi\Resolver\Option;
use Wepesi\Resolver\OptionsResolver;

class Database
{
    public array $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver([
            (new Option('host'))->setDefaultValue('localhost'),
            new Option('username'),
            new Option('password'),
            new Option('dbname'),
        ]);

        $this->options = $resolver->resolve($options);
    }
}

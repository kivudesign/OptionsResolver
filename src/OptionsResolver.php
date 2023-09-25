<?php
/*
 * Copyright (c) 2022.
 * The OptionsResolver component helps you configure objects with option arrays. It supports default values, option constraints and lazy options.
 */

namespace Wepesi\Resolver;

use Wepesi\Resolver\Traits\ExceptionTraits;

final class OptionsResolver
{
    /**
     * @var \ArrayObject
     */
    private \ArrayObject $options;

    use ExceptionTraits;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = new \ArrayObject();
        foreach ($options as $option) {
            $this->add($option);
        }
    }

    /**
     * @param array $options
     * @return array
     */
    public function resolve(array $options): array
    {
        try {
            $checkDiff = $this->checkDiff($options);
            if(isset($checkDiff['InvalidArgumentException'])){
                return $checkDiff;
            }
            /**
             * @var Option $option
             */
            $optionsResolved = [];
            foreach ($this->options as $option) {
                $optionName = $option->getName();
                if (\array_key_exists($optionName, $options)) {
                    $value = $options[$optionName];
                    if ($option->isValid($value) === false) {
                        throw new \InvalidArgumentException(sprintf('The option "%s" with value %s is invalid.', $optionName, self::formatValue($value)));
                    }
                    $optionsResolved[$optionName] = $value;
                    continue;
                }

                if ($option->hasDefaultValue()) {
                    $optionsResolved[$optionName] = $option->getDefaultValue();
                    continue;
                }
                throw new \InvalidArgumentException(sprintf('The required option "%s" is missing.', $optionName));
            }
            return $optionsResolved;
        } catch (\InvalidArgumentException $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * @param Option $option
     * @return void
     */
    private function add(Option $option): void
    {
        $this->options->offsetSet($option->getName(), $option);
    }

    /**
     * @param array $options
     * @return array
     */
    private function checkDiff(array $options): array
    {
        try {
            $defined = $this->options->getArrayCopy();
            $diff = array_diff_key($options, $defined);
            if (count($diff) > 0) {
                $arr_diff = implode(', ', array_keys($diff));
                $arr_defined = implode('", "', array_keys($defined));
                $error_message = sprintf('The option(s) "%s" do(es) not exist. Defined options are: "%s".',$arr_diff ,$arr_defined);
                throw new \InvalidArgumentException($error_message);
            }
            return [];
        } catch (\InvalidArgumentException $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * @param $value
     * @return string
     */
    private static function formatValue($value): string
    {
        if (is_object($value)) {
            return \get_class($value);
        }

        if (is_string($value)) {
            return '"' . $value . '"';
        }

        if (false === $value) {
            return 'false';
        }

        if (true === $value) {
            return 'true';
        }
        return \gettype($value);
    }
}
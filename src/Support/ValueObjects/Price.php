<?php

namespace Support\ValueObjects;

use InvalidArgumentException;
use Stringable;
use Support\Traits\Makeable;

class Price implements Stringable
{
    use Makeable;

    private array $currencies = [
        'RUB' => '₽',
    ];

    public function __construct(
        private readonly int $value,
        private readonly string $currency = 'RUB',
        //Знаки после запятой (100 - два знака, т.к. умножаем)
        private readonly int $precision = 100,
    )
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Цена должна быть больше 0');
        }

        if (!isset($this->currencies[$currency])) {
            throw new InvalidArgumentException('Валюта не поддерживается');
        }

    }

    public function raw() : int
    {
        return $this->value;
    }

    public function value() : float|int
    {
        return $this->value / $this->precision;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function symbol() : string
    {
        return $this->currencies[$this->currency];
    }

    public function __toString(): string
    {
        return number_format($this->value(), 0, ',', ' ')
            . ' ' . $this->symbol();
    }
}

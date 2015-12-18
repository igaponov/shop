<?php

namespace Shop\Presentation\Twig;

use NumberFormatter;

class PriceExtension extends \Twig_Extension
{
    /**
     * @var NumberFormatter
     */
    private $formatter;
    /**
     * @var
     */
    private $locale;
    /**
     * @var
     */
    private $currency;

    /**
     * @inheritDoc
     */
    public function __construct($locale, $currency)
    {
        if (!class_exists('IntlDateFormatter')) {
            throw new \RuntimeException('The intl extension is needed to use intl-based filters.');
        }

        $this->locale = $locale;
        $this->currency = $currency;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('price', [$this, 'price']),
        ];
    }

    public function price($value)
    {
        $value = (int)$value;

        return $this->getNumberFormatter()->formatCurrency($value / 100, $this->currency);
    }

    public function getName()
    {
        return 'shop.price_extension';
    }

    protected function getNumberFormatter()
    {
        if (!$this->formatter) {
            $this->formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        }

        return $this->formatter;
    }
}

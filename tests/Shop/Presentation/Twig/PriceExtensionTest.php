<?php

namespace Tests\Shop\Presentation\Twig;

use Shop\Presentation\Twig\PriceExtension;

class PriceExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $ext = new PriceExtension('en', 'USD');
        /** @var \Twig_SimpleFilter $filter */
        $filter = $ext->getFilters()[0];
        $this->assertSame('price', $filter->getName());
    }

    /**
     * @dataProvider priceProvider
     * @param string $locale
     * @param string $currency
     * @param string $expected
     */
    public function testCallable($locale, $currency, $expected)
    {
        $ext = new PriceExtension($locale, $currency);
        /** @var \Twig_SimpleFilter $filter */
        $filter = $ext->getFilters()[0];
        $price = call_user_func($filter->getCallable(), 2010);
        $this->assertSame($expected, $price);
    }

    public function priceProvider()
    {
        return [
            ['en', 'USD', '$20.10'],
            ['ru', 'USD', '20,10 $'],
        ];
    }
}
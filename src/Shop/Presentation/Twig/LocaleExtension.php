<?php

namespace Shop\Presentation\Twig;

use Symfony\Component\Intl\Intl;

class LocaleExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $locales;

    /**
     * @inheritDoc
     */
    public function __construct(\string $locales)
    {
        $this->locales = $locales;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('country', [$this, 'countryFilter']),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('locales', [$this, 'getLocales']),
        ];
    }

    public function countryFilter($countryCode, $locale = null)
    {
        return Intl::getRegionBundle()->getCountryName($countryCode, $locale);
    }

    public function getLocales()
    {
        $localeCodes = explode('|', $this->locales);
        $locales = [];
        foreach ($localeCodes as $localeCode) {
            $locales[] = [
                'code' => $localeCode,
                'name' => Intl::getLocaleBundle()->getLocaleName($localeCode, $localeCode),
            ];
        }

        return $locales;
    }

    public function getName()
    {
        return 'locale_extension';
    }
}
<?php

namespace Shop\Domain\Cart;

use Doctrine\Common\Collections\Collection;

interface LineItemCollection extends Collection
{
    /**
     * @return int
     */
    public function getProductCount();
}
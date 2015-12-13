<?php

namespace Shop\Domain\Option;

use Shop\Domain\ValueObject\UuidIdentity;

interface OptionRepository
{
    /**
     * @param UuidIdentity $id
     * @return Option
     */
    public function findByIdentity(UuidIdentity $id) : Option;

    /**
     * @param Option $option
     * @return void
     */
    public function save(Option $option);

    /**
     * @param Option $option
     * @return void
     */
    public function remove(Option $option);

    /**
     * @param UuidIdentity $id
     * @return Option
     */
    public function getReference(UuidIdentity $id): Option;
}
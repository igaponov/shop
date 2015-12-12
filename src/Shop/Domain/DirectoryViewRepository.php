<?php

namespace Shop\Domain;

interface DirectoryViewRepository
{
    /**
     * @return array|DirectoryView[]
     */
    public function getAll() : array;

    /**
     * @return array
     */
    public function getAllIndexed() : array;

    /**
     * @param $id
     * @return DirectoryView
     */
    public function getById($id) : DirectoryView;
}
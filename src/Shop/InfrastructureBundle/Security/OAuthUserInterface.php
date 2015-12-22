<?php

namespace Shop\InfrastructureBundle\Security;

interface OAuthUserInterface
{
    /**
     * @return string
     */
    public function getGoogleID();

    /**
     * @param string $id
     */
    public function addGoogleID($id);

    /**
     * @return string
     */
    public function getFacebookID();

    /**
     * @param string $id
     */
    public function addFacebookID($id);
}
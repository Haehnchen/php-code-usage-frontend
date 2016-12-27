<?php

namespace espend\Inspector\ImportBundle\Model;

class Author implements \JsonSerializable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $mail;

    /**
     * @inheritdoc
     */
    function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
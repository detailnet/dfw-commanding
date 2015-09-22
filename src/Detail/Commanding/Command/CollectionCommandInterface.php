<?php

namespace Detail\Commanding\Command;

interface CollectionCommandInterface
{
    /**
     * Create a new instance of the class
     * @return self
     */
    public static function create();

    /**
     * Return the name of the object used for the collection
     * @return string
     */
    public function getObjectClassName();

    /**
     * Set the data for the collection
     * @param object[] $data
     */
    public function setCollectionData($data = array());
}

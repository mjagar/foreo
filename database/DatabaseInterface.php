<?php
namespace Database;

/**
 * Interface DatabaseInterface
 * @package Database
 */
interface DatabaseInterface
{
    /**
     * @return mixed
     */
    public function connect();

    /**
     * @return mixed
     */
    public function disconnect();

    /**
     * @param $q
     * @return mixed
     */
    public function query($q);
}


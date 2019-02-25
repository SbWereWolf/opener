<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 26.02.2019 1:10
 */

namespace DataStorage\Basis;


class DataSource
{
    private $dsn = '';
    private $username = '';
    private $passwd = '';
    private $options = array();

    public function __construct(string $dsn)
    {
        $this->setDsn($dsn);
    }

    /**
     * @return string
     */
    public function getDsn(): string
    {
        return $this->dsn;
    }

    /**
     * @param string $dsn
     * @return DataSource
     */
    public function setDsn(string $dsn): DataSource
    {
        $this->dsn = $dsn;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return DataSource
     */
    public function setUsername(string $username): DataSource
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswd(): string
    {
        return $this->passwd;
    }

    /**
     * @param string $passwd
     * @return DataSource
     */
    public function setPasswd(string $passwd): DataSource
    {
        $this->passwd = $passwd;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return DataSource
     */
    public function setOptions(array $options): DataSource
    {
        $this->options = $options;
        return $this;
    }
}

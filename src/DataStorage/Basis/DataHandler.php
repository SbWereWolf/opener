<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 02.01.2019 21:49
 */

namespace DataStorage\Basis;


class DataHandler
{
    protected $access = null;
    private $dataSource = null;

    public function __construct(DataSource $dataPath)
    {
        $this->setDataSource($dataPath);
    }

    /**
     * @param DataSource $dataSource
     * @return DataHandler
     */
    private function setDataSource(DataSource $dataSource): DataHandler
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return DataSource
     */
    private function getDataSource(): DataSource
    {
        return $this->dataSource;
    }

    protected function begin(): bool
    {
        $result = $this->getAccess()->beginTransaction();

        return $result;
    }

    protected function getAccess(): \PDO
    {
        $access = $this->access;
        $isExists = !empty($access);

        if (!$isExists) {
            $dataSource = $this->getDataSource();
            $access = new \PDO(
                $dataSource->getDsn(),
                $dataSource->getUsername(),
                $dataSource->getPasswd(),
                $dataSource->getOptions());
            $this->access = $access;
        }

        return $access;
    }

    protected function commit(): bool
    {
        $result = $this->getAccess()->commit();

        $this->dropAccess();

        return $result;
    }

    protected function dropAccess(): self
    {
        $this->access = null;

        return $this;
    }

    protected function rollBack(): bool
    {
        $result = $this->getAccess()->rollBack();

        $this->dropAccess();

        return $result;
    }
}

<?php

namespace DataStorage;


class DataAccess
{
    private $access = null;
    private $status = false;
    private $rowCount = 0;

    function __construct(\PDO $access)
    {
        $this->setAccess($access)
            ->setSuccessStatus();
    }

    /**
     * @param \PDO $access
     * @return DataAccess
     */
    private function setAccess(\PDO $access): self
    {
        $this->access = $access;
        return $this;
    }

    /**
     * @param int $rowCount
     * @return DataAccess
     */
    protected function setRowCount(int $rowCount): self
    {
        $this->rowCount = $rowCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * @return null
     */
    protected function getAccess(): \PDO
    {
        return $this->access;
    }

    /**
     * @param $requestText
     * @return DataAccess
     */
    protected function processUpdate(\PDOStatement $request): self
    {
        $isSuccess = $request->execute();

        if ($isSuccess) {
            $this->setSuccessStatus();
            $rowCount = $request->rowCount();
            $this->setRowCount($rowCount);
        }

        if (!$isSuccess) {
            $this->setFailStatus();
        }

        return $this;
    }

    protected function setSuccessStatus(): self
    {
        $this->status = true;

        return $this;
    }

    protected function setFailStatus(): self
    {
        $this->status = false;

        return $this;
    }

    protected function isSuccess(): bool
    {
        return $this->status == true;

    }
}

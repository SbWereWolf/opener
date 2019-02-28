<?php
/**
 * Copyright © 2019 Volkhin Nikolay
 * Project: opener
 * DateTime: 16.01.2019 5:24
 */

namespace DataStorage\Unlock;


use BusinessLogic\Basis\Content;
use BusinessLogic\Basis\DataSet;
use DataStorage\Basis\DataAccess;

class CommonUnlockAccess extends DataAccess
{
    protected function processRead(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request)->isSuccess();

        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);

            $rowCount = count($dataSet);
            $this->setRowCount($rowCount);
        }

        $data = $this->parseOutput();
        $this->setData($data);

        return $this;
    }

    /**
     * @return Content
     */
    protected function parseOutput(): Content
    {
        $result = new DataSet();

        return $result;
    }

}

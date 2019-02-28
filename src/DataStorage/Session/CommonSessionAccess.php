<?php

namespace DataStorage\Session;


use BusinessLogic\Basis\Content;
use BusinessLogic\Session\Session;
use BusinessLogic\Session\SessionSet;
use DataStorage\Basis\DataAccess;
use LanguageFeatures\ArrayParser;

class CommonSessionAccess extends DataAccess
{
    protected function processForOutput(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request)->isSuccess();

        $this->setData(new SessionSet());

        return $this;
    }

    protected function processRead(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request)->isSuccess();

        $dataSet = array();
        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);

            $rowCount = count($dataSet);
            $this->setRowCount($rowCount);
        }

        $shouldParseData = $isSuccess && $this->getRowCount() > 0;
        $data = new SessionSet();
        if ($shouldParseData) {
            $data = $this->parseOutput($dataSet);
        }

        $this->setData($data);

        return $this;
    }

    /**
     * @param array $dataSet
     * @return Content
     */
    protected function parseOutput(array $dataSet): Content
    {
        $result = new SessionSet();
        foreach ($dataSet as $dataRow) {
            $parser = new ArrayParser($dataRow);

            $finish = $parser->getIntegerField('finish');

            $item = (new Session())
                ->setFinish($finish);

            $result->push($item);
        }

        return $result;
    }
}

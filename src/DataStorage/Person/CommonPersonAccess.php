<?php

namespace DataStorage\Person;


use BusinessLogic\Basis\Content;
use BusinessLogic\Person\Person;
use BusinessLogic\Person\PersonSet;
use DataStorage\Basis\DataAccess;
use LanguageFeatures\ArrayParser;

class CommonPersonAccess extends DataAccess
{
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
        $data = new PersonSet();
        if ($shouldParseData) {
            $data = $this->parseOutput($dataSet);
        }

        $this->setData($data);

        return $this;
    }

    protected function parseOutput(array $dataSet): Content
    {
        $result = new PersonSet();
        foreach ($dataSet as $dataRow) {
            $parser = new ArrayParser($dataRow);

            $secret = $parser->getStringField('secret');

            $item = (new Person())
                ->setSecret($secret);

            $result->push($item);
        }

        return $result;
    }
}

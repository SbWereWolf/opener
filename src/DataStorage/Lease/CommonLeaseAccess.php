<?php

namespace DataStorage\Lease;


use BusinessLogic\Basis\Content;
use BusinessLogic\Lease\Lease;
use BusinessLogic\Lease\LeaseSet;
use DataStorage\Basis\DataAccess;
use LanguageFeatures\ArrayParser;

class CommonLeaseAccess extends DataAccess
{

    /**
     * @param \PDOStatement $request
     * @return CommonLeaseAccess
     */
    protected function processForOutput(\PDOStatement $request): self
    {
        $isSuccess = $this->execute($request)->isSuccess();

        $dataSet = array();
        if ($isSuccess) {
            $dataSet = $request->fetchAll(\PDO::FETCH_ASSOC);

            $rowCount = count($dataSet);
            $this->setRowCount($rowCount);
        }

        $shouldParseData = $isSuccess && $this->getRowCount() > 0;
        $data = new LeaseSet();
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
        $result = new LeaseSet();
        foreach ($dataSet as $dataRow) {
            $parser = new ArrayParser($dataRow);

            $id = $parser->getIntegerField('id');
            $shutterId = $parser->getIntegerField('shutter_id');
            $start = $parser->getIntegerField('start');
            $finish = $parser->getIntegerField('finish');
            $occupancyTypeId = $parser->getIntegerField('occupancy_type_id');

            $item = (new Lease())
                ->setFinish($finish)
                ->setId($id)
                ->setOccupancyTypeId($occupancyTypeId)
                ->setShutterId($shutterId)
                ->setStart($start);

            $result->push($item);
        }

        return $result;
    }

}

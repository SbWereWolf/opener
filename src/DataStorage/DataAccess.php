<?php

namespace DataStorage;


class DataAccess
{
    private $dsn = '';

    function __construct(string $dataPath)
    {
        $this->dsn = "sqlite:$dataPath";
    }

    public function insert(IUser $user): bool
    {
        $valuesPhrase = self::getValuesPhrase($user);
        $requestText = 'INSERT INTO '
            . $user->getTable()
            . "(" . $user::COLUMN_EMAIL
            . ',' . $user::COLUMN_SECRET . ")"
            . "VALUES ($valuesPhrase) RETURNING "
            . $user::COLUMN_ID
            . ',' . $user::COLUMN_EMAIL
            . ',' . $user::COLUMN_SECRET
            . ';';

        $dbConnection = $this->getDbConnection();
        $request = $dbConnection->prepare($requestText);

        $request->bindValue($user::PARAM_EMAIL, $user->getEmail());
        $request->bindValue($user::PARAM_SECRET, $user->getSecret());

        $isSuccess = $request->execute();

        return $isSuccess;
    }

    /**
     * @return string
     */
    private static function getValuesPhrase(IUser $user): string
    {
        return $user::PARAM_EMAIL . ', ' . $user::PARAM_SECRET;
    }

    /**
     * @return \PDO
     */
    private function getDbConnection(): \PDO
    {
        $dbConnection = new \PDO($this->dsn);
        return $dbConnection;
    }
}

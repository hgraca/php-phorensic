<?php

namespace Hgraca\Phorensic\Analyser\Query;

use Hgraca\Phorensic\SharedKernel\Port\Database\DatabaseClientInterface;

final class RefactorPriorityQuery
{
    /** @var DatabaseClientInterface */
    private $dbClient;

    /** @var string */
    const SQL = "
        SELECT `path`, `type`, `commits`, `wmc`, (`commits` * `wmc`) as `refactor_priority`
        FROM `files`
        WHERE `type` = 'php'
        ORDER BY `refactor_priority` DESC
    ";

    public function __construct(DatabaseClientInterface $dbClient)
    {
        $this->dbClient = $dbClient;
    }

    /**
     * @return array [[path, commits, wmc, refactor_priority], ...]
     */
    public function execute(int $limit = null): array
    {
        $sql = $limit === null ? self::SQL : self::SQL . " LIMIT $limit";

        return $this->dbClient->executeQuery($sql);
    }
}

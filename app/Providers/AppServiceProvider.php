<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::listen(function (QueryExecuted $queryExecuted): void {
            Log::debug(
                strtoupper($queryExecuted->connectionName),
                [
                    "sql_syntax" => $queryExecuted->sql,
                    "query_bindings" => $queryExecuted->bindings,
                    "database_name" => $queryExecuted->connection->getDatabaseName(),
                    "pdo_connection" => [
                        "last_insert_id" => $queryExecuted->connection->getPdo()->lastInsertId(),
                        "is_in_transaction" => $queryExecuted->connection->getPdo()->inTransaction(),
                        "error_code" => $queryExecuted->connection->getPdo()->errorCode(),
                        "error_info" => $queryExecuted->connection->getPdo()->errorInfo(),
                    ],
                    // "query_log" => $queryExecuted->connection->getQueryLog(),
                    // "raw_query_log" => $queryExecuted->connection->getRawQueryLog(),
                    // "table_prefix" => $queryExecuted->connection->getTablePrefix(),
                    // "query_grammar" => [
                    //     "date_format" => $queryExecuted->connection->getQueryGrammar()->getDateFormat(),
                    //     "get_operators" => $queryExecuted->connection->getQueryGrammar()->getOperators(),
                    //     "get_bitwise_operators" => $queryExecuted->connection->getQueryGrammar()->getBitwiseOperators(),
                    // ],
                    "query_duration" => $queryExecuted->time,
                ]
            );
        });
    }
}

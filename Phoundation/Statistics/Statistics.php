<?php

/**
 * Statistics class
 *
 * This class can queue and push statistical data to statistics servers
 *
 * @see \Phoundation\Core\Libraries\Updates
 * @author    Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @license http://opensource.org/licenses/GPL-2.0 GNU Public License, Version 2
 * @copyright Copyright © 2025 Sven Olaf Oostenbrink <so.oostenbrink@gmail.com>
 * @package Plugins\Phoundation\Statistics
 */


declare(strict_types=1);

namespace Plugins\Phoundation\Statistics;

use Phoundation\Accounts\Config\Exception\ConfigPathDoesNotExistsException;
use Phoundation\Core\Log\Log;
use Phoundation\Data\Validator\Validate;
use Phoundation\Date\Enums\EnumDateTimeSegment;
use Phoundation\Date\PhoDateTime;
use Phoundation\Exception\OutOfBoundsException;
use Phoundation\Notifications\Notification;
use Phoundation\Utils\Arrays;
use Plugins\Phoundation\Statistics\Exception\StatisticsException;
use Throwable;

class Statistics
{
    /**
     * Singleton
     *
     * @var Statistics $instance
     */
    protected Statistics $instance;

    /**
     * Sockets that will connect to Grafana server
     *
     * @var array $socket
     */
    protected static array $socket = [];

    /**
     * Configuration for this statistics engine
     *
     * @var array $config
     */
    protected static array $config;

    /**
     * The server used for this Statistics object
     *
     * @var string $server
     */
    protected string $server;


    /**
     * Statistics class constructor.
     */
    protected function __construct(string $server = 'default')
    {
        if (empty($server)) {
            throw new OutOfBoundsException(tr('No statistics server specified'));
        }

        if (empty(static::$config[$server])) {
            static::loadConfig($server);
        }

        $this->server = $server;
    }


    /**
     * Returns a new Statistics object
     *
     * @param string $server
     * @return static
     */
    public static function new(string $server = 'default'): static
    {
        return new static($server);
    }


    /**
     * Returns the server used for this statistics object
     *
     * @return string
     */
    protected function getServer(): string
    {
        return $this->server;
    }


    /**
     * Return correct timestamp for use in statistics by removing the seconds from the current minute
     *
     * @param int|null                 $timestamp
     * @param EnumDateTimeSegment|null $interval
     *
     * @return int
     */
    protected function getTimestamp(?int $timestamp, ?EnumDateTimeSegment $interval = null): int
    {
        if (!$interval) {
            $interval = static::$config[$this->server]['interval'];
        }

        switch ($timestamp) {
            case null:
                $timestamp = time();
                break;

            case -1:
                $timestamp = time();
        }

        return PhoDateTime::new('@' . $timestamp, 'user')->round($interval)->getTimestamp();
    }


    /**
     * Returns the path adjusted for project and environment so that these two are always separated
     *
     * @param string $path
     * @return string
     */
    protected function getPath(string $path): string
    {
        return PROJECT . '.' . ENVIRONMENT . '.' . $path;
    }


    /**
     * Adds the specified value/path/timestamp for the specified server
     *
     * @param float|int|null $value The amount to increment the current value of $path with
     * @param string $path The path that should have its value increased
     * @param int|null $timestamp The timestamp to which this increase should be applied
     * @return static
     */
    public function add(float|int|null $value, string $path, ?int $timestamp = null): static
    {
        return $this->queue($path, $value, $timestamp, 'value=value+' . $value);
    }


    /**
     * Log to queue, decrease the value of the specified $path by the specified $value
     *
     * @param float|int|null $value
     * @param string $path
     * @param int|null $timestamp
     * @return static
     */
    public function substract(float|int|null $value, string $path, ?int $timestamp = null): static
    {
        return $this->queue($path, -$value, $timestamp, 'value=value-' . $value);
    }


    /**
     * Increases the specified path for the timestamp by 1
     *
     * @param string $path The path that should have its value increased
     * @param int|null $timestamp The timestamp to which this increase should be applied
     * @return static
     */
    public function increase(string $path, ?int $timestamp = null): static
    {
        return $this->add($path, 1, $timestamp);
    }


    /**
     * Decreases the specified path for the timestamp by 1
     *
     * @param string $path The path that should have its value increased
     * @param int|null $timestamp The timestamp to which this increase should be applied
     * @return static
     */
    public function decrease(string $path, ?int $timestamp = null): static
    {
        return $this->substract($path, 1, $timestamp);
    }


    /**
     * Set the value for the specified path to the specified value
     *
     * @param float|int|null $value
     * @param string $path
     * @param int|null $timestamp
     * @param bool $flush
     * @return Statistics
     */
    public function set(float|int|null $value, string $path, ?int $timestamp = null, bool $flush = false): static
    {
        if ($flush) {
            return $this->push($value, $path, $timestamp);
        }

        return $this->queue($value, $path, $timestamp, 'value=' . $value);
    }


    /**
     * Send statistics directly to statistics server, bypassing the queue
     *
     * @param float|int|null $value
     * @param string $path
     * @param int|null $timestamp
     * @return Statistics
     */
    protected function push(float|int|null $value, string $path, ?int $timestamp = null): static
    {
        $this->connect();
        fwrite(static::$socket[$this->server], str_replace(' ', '-', $path) . " " . ($value ?? 0) . " " . $this->getTimestamp($timestamp) . "\n");

        return $this;
    }


    /**
     * Add data to the stats table
     *
     * @param float|int|null $value
     * @param string $path
     * @param int|null $timestamp
     * @param array|string|null $update
     * @return static
     */
    protected function queue(float|int|null $value, string $path, ?int $timestamp = null, array|string|null $update = null): static
    {
        sql()->insert('statistics_queue', [
            'server'    => $this->server,
            'timestamp' => $this->getTimestamp($timestamp),
            'path'      => $this->getPath($path),
            'value'     => $value ?? 0,
        ], $update);

        return $this;
    }


    /**
     * Ensures that this object is connected to the statistics server
     */
    protected function connect(): static
    {
        if (array_key_exists($this->server, static::$socket)) {
            return $this;
        }

        try {
            static::$socket[$this->server] = fsockopen(
                static::$config[$this->server]['host'],
                static::$config[$this->server]['port'],
                $err_no,
                $err_str,
                10
            );

        } catch (Throwable $e) {
            throw new StatisticsException(tr('Failed to connect to statistics server ":server" with ":exception"', [
                ':server'    => $this->server,
                ':exception' => '[' . $err_no . '] ' . $err_str,
            ]), $e);
        }

        if ($err_no) {
            throw new StatisticsException(tr('Failed to connect to statistics server ":server" with ":exception"', [
                ':server'    => $this->server,
                ':exception' => '[' . $err_no . '] ' . $err_str,
            ]));
        }

        return $this;
    }


    /**
     * Flush the statistics queue
     *
     * @param int $limit
     * @return Statistics
     */
    public function flushQueue(int $limit = 100_000): static
    {
        $this->loadAllConfig();
        $this->connect();

        $errors  = 0;
        $count   = sql()->getColumn('SELECT COUNT(*) AS `count` FROM `statistics_queue`');
        $entries = sql()->query('SELECT   * 
                                       FROM     `statistics_queue` 
                                       WHERE    `timestamp` < ' . PhoDateTime::new()->round(EnumDateTimeSegment::minute)->getTimestamp() . ' 
                                       ORDER BY `timestamp` LIMIT 0, ' . $limit);

        Log::notice(ts('Statistics queue contains ":count" entries', [
            ':count' => $count
        ]));

        Log::notice(ts('Flushing a maximum of ":limit" entries', [
            ':limit' => $limit
        ]));

        $count = 0;

        foreach ($entries as $entry) {
            try {
                // Push entry
                $this->push($entry['path'], $entry['value'], $entry['timestamp']);

                // Delete entry from local queue
                if (!$entry['clear_after'] or PhoDateTime::new() > PhoDateTime::new($entry['clear_after'])) {
//                    sql()->delete('statistics_queue', ['id' => $entry['id']]);
                }

                $count++;
                Log::dot(1, '');

            } catch (Throwable $e) {
                $e = StatisticsException::new(tr('Failed to push entry ":id" to the statistics server', [
                    ':id' => $entry['id']
                ]), $e);

                if (++$errors > 5) {
                    throw StatisticsException::new(tr('Failed to push multiple statistics entries to the statistics server', [
                        ':id' => $entry['id']
                    ]), $e);
                }

                Notification::new()
                    ->setException($e)
                    ->log()
                    ->send();
            }
        }

        if ($count) {
            echo tr('Done') . PHP_EOL;
        }

        Log::success(ts('Flushed ":count" statistics entries', [
            ':count' => $count
        ]));

        return $this;
    }


    /**
     * Load the configuration for the specified server
     *
     * @param string $server
     * @return void
     */
    protected static function loadConfig(string $server): void
    {
        try {
            if (empty(static::$config[$server])) {
                try {
                    static::$config[$server] = config()->getArray('statistics.servers.' . $server);
                } catch (ConfigPathDoesNotExistsException $e) {
                    // If default configuration ignore. Other configurations must exist
                    if ($server !== 'default') {
                        throw $e;
                    }
                }

                Arrays::ensure(static::$config[$server]);
                Arrays::default(static::$config[$server], 'host'    , 'localhost');
                Arrays::default(static::$config[$server], 'port'    , 2003);
                Arrays::default(static::$config[$server], 'engine'  , 'grafana');
                Arrays::default(static::$config[$server], 'interval', EnumDateTimeSegment::minute->value);
            }

            static::$config[$server]['interval'] = EnumDateTimeSegment::from(static::$config[$server]['interval']);
            Validate::new(static::$config[$server]['port'])->isPort();
            Validate::new(static::$config[$server]['engine'])->isInArray(['grafana']);

        } catch (ConfigPathDoesNotExistsException $e) {
            throw new StatisticsException(tr('The specified statistics server ":server" has no configuration', [
                ':server' => $server
            ]), $e);
        }
    }


    /**
     * Load the configuration for all servers
     *
     * @return void
     */
    protected function loadAllConfig(): void
    {
        $servers = config()->getArray('statistics.servers');

        foreach ($servers as $server => $config) {
            static::loadConfig($server);
        }
    }
}

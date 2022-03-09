<?php

namespace App\Services;

/**
 * Interface AlertInterface
 * @package App\Service
 */
interface AlertInterface
{
    /**
     * Primary alert.
     * @param string $message
     */
    public function primary(string $message): void;

    /**
     * Secondary alert.
     * @param string $message
     */
    public function secondary(string $message): void;

    /**
     * Success alert.
     * @param string $message
     */
    public function success(string $message): void;

    /**
     * Danger alert.
     * @param string $message
     */
    public function danger(string $message): void;

    /**
     * Warning alert.
     * @param string $message
     */
    public function warning(string $message): void;

    /**
     * Info alert.
     * @param string $message
     */
    public function info(string $message): void;

    /**
     * Light alert.
     * @param string $message
     */
    public function light(string $message): void;

    /**
     * Dark alert.
     * @param string $message
     */
    public function dark(string $message): void;
}

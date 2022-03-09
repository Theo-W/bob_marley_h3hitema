<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AlertBootstrap
 * @package App\Service
 */
class Alert implements AlertInterface
{
    private const ALERT_PRIMARY = "primary";
    private const ALERT_SECONDARY = "secondary";
    private const ALERT_SUCCESS = "success";
    private const ALERT_DANGER = "danger";
    private const ALERT_WARNING = "warning";
    private const ALERT_INFO = "info";
    private const ALERT_LIGHT = "light";
    private const ALERT_DARK = "dark";

    private Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @inheritDoc
     */
    public function primary(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_PRIMARY, $message);
    }

    /**
     * @inheritDoc
     */
    public function secondary(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_SECONDARY, $message);
    }

    /**
     * @inheritDoc
     */
    public function success(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_SUCCESS, $message);
    }

    /**
     * @inheritDoc
     */
    public function danger(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_DANGER, $message);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_WARNING, $message);
    }

    /**
     * @inheritDoc
     */
    public function info(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_INFO, $message);
    }

    /**
     * @inheritDoc
     */
    public function light(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_LIGHT, $message);
    }

    /**
     * @inheritDoc
     */
    public function dark(string $message): void
    {
        $this->session->getFlashBag()->add(self::ALERT_DARK, $message);
    }
}
<?php

declare(strict_types=1);

namespace App\Auth\Session;

use App\Contracts\SessionInterface;
use App\DTO\SessionConfig;
use App\Exception\SessionException;

class Session implements SessionInterface
{
    public function __construct(private readonly SessionConfig $sessionConfig)
    {

    }

    public function start(): void
    {
        if($this->isActive()) {
            throw new SessionException('Session has alerday been started');
        }

        if(headers_sent($fileName, $line)) {
            throw new SessionException('Headers Alerday Sent');
        }

        session_set_cookie_params(
            [
            'secure' => $this->sessionConfig->secure,
            'httponly' => $this->sessionConfig->httpOnly,
            'samesite' => $this->sessionConfig->sameSite->value
            ]
        );

        if(empty($this->sessionConfig->name) === false) {
            session_name($this->sessionConfig->name);
        }

        if(session_start() === false) {
            throw new SessionException('Unable to start the Session');
        }
    }

    public function save(): void
    {
        session_write_close();
    }

    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }



    public function get(string $key, mixed $default = null): mixed
    {
        return  $this->has($key) ? $_SESSION[$key] : $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function delete(string $key): void
    {
        unset($_SESSION[$key]);

    }

    public function regenerate(): bool
    {
        return session_regenerate_id();
    }

    public function flash(string $key, array $messages): void
    {
        $_SESSION[$this->sessionConfig->flashKey][$key] = $messages;
    }

    public function getFlash(string $key): array
    {
        $messages = $_SESSION[$this->sessionConfig->flashKey][$key] ?? [];

        unset($_SESSION[$this->sessionConfig->flashKey][$key]);

        return $messages;
    }
}

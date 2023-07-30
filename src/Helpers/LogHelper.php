<?php

use App\Models\User;
use App\Services\Utility\EventLogService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;

if (!function_exists('eventLog')) {
    /**
     * Create or get event log in the session.
     *
     * @param bool $force
     * @return EventLogService
     */
    function eventLog(bool $force = false): EventLogService
    {
        $sessionKey = EventLogService::CLASS_SESSION_KEY_NAME;

        if (Session::get($sessionKey) instanceof EventLogService and not($force)) {
            return Session::get($sessionKey);
        }

        $eventLogService = new EventLogService();
        Session::put($sessionKey, $eventLogService);

        return $eventLogService;
    }
}

if (!function_exists('resetEventLog')) {
    /**
     * Reset event log as new class instance.
     *
     * @return void
     */
    function resetEventLog(): void
    {
        eventLog(true);
    }
}

if (!function_exists('setEventLog')) {
    /**
     * Set new event log service instance to the session.
     *
     * @param EventLogService $eventLogService
     * @return void
     */
    function setEventLog(EventLogService $eventLogService): void
    {
        $sessionKey = EventLogService::CLASS_SESSION_KEY_NAME;
        Session::put($sessionKey, $eventLogService);
    }
}

if (!function_exists('setEventLogDoer')) {
    /**
     * Set doer of current log service.
     *
     * @param User|Authenticatable $doer
     * @return void
     */
    function setEventLogDoer(User|Authenticatable $doer): void
    {
        $eventLogService = eventLog();
        $eventLogService->setDoer($doer);

        setEventLog($eventLogService);
    }
}

if (!function_exists('setEventLogKey')) {
    /**
     * Set event log instance event key.
     *
     * @param string $key
     * @return void
     */
    function setEventLogKey(string $key): void
    {
        $eventLogService = eventLog();
        $eventLogService->setEventKey($key);

        setEventLog($eventLogService);
    }
}

if (!function_exists('setEventLogDescription')) {
    /**
     * Set event log instance description
     *
     * @param string $description
     * @return void
     */
    function setEventLogDescription(string $description): void
    {
        $eventLogService = eventLog();
        $eventLogService->setDescription($description);

        setEventLog($eventLogService);
    }
}

if (!function_exists('addEventLoggable')) {
    /**
     * Add event loggable to the current session class.
     *
     * @param mixed $instance
     * @param array $changes
     * @param string $description
     * @return void
     */
    function addEventLoggable(
        mixed $instance,
        array $changes = [],
        string $description = ''
    ): void {
        $eventLogService = eventLog();
        $eventLogService->addEventLoggable($instance, $changes, $description);

        setEventLog($eventLogService);
    }
}

if (!function_exists('recordEvent')) {
    /**
     * Record event.
     *
     * @return void
     */
    function recordEvent(): void
    {
        $eventLogService = eventLog();
        $eventLogService->record();

        resetEventLog();
    }
}

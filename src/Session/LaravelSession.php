<?php namespace Jenssegers\AB\Session;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LaravelSession implements SessionInterface {

    /**
     * The session key.
     *
     * @var string
     */
    protected $sessionName = 'ab';

    /**
     * A copy of the session data.
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->data = Session::get($this->sessionName, []) ?? [];
        Log::info('LaravelSession initialized.');
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        if (!isset($this->data[$name])) {
            Log::warning("Session key {$name} not found.");
        } else {
            Log::info("Getting session key {$name}.");
        }

        return $this->data[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function put($name, $value)
    {
        $this->data[$name] = $value;
        Session::put($this->sessionName, $this->data);
        Log::info("Set session key {$name} with value {$value}.");

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->data = [];
        Session::forget($this->sessionName);
        Log::info("Cleared all keys from session {$this->sessionName}.");

        return true;
    }

    /**
     * Sync data back to session
     */
    public function sync()
    {
        Session::put($this->sessionName, $this->data);
        Log::info("Synced session data.");
    }
}

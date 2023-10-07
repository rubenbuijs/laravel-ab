<?php namespace Jenssegers\AB\Session;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;

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
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        if (!isset($this->data[$name])) {
            Log::warning("Session key {$name} not found.");
        }
        return $this->data[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function put($name, $value)
    {
        $this->data[$name] = $value;
        echo $this->sessionName;
        dd($this->data);
        if (!Session::put($this->sessionName, $this->data)) {
            Log::error("Failed to set session data for {$this->sessionName}.");
            throw new Exception("Failed to set session data");
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->data = [];
        
        if (!Session::forget($this->sessionName)) {
            Log::error("Failed to clear session data for {$this->sessionName}.");
            throw new Exception("Failed to clear session data");
        }

        return true;
    }
}

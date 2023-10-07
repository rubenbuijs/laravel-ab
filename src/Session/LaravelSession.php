<?php namespace Jenssegers\AB\Session;

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
    protected $data = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->data = Session::get($this->sessionName, []);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        return $this->data[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function put($name, $value)
    {
        $this->data[$name] = $value;

        return Session::put($this->sessionName, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->data = [];

        return Session::forget($this->sessionName);
    }

}

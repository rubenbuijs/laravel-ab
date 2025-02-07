<?php namespace Jenssegers\AB\Session;

use Illuminate\Support\Facades\Cookie;

class CookieSession implements SessionInterface {

    /**
     * The name of the cookie.
     *
     * @var string
     */
    protected $cookieName = 'ab';

    /**
     * A copy of the session data.
     *
     * @var array
     */
    protected $data = null;

    /**
     * Cookie lifetime.
     *
     * @var integer
     */
    protected $minutes = 60;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->data = json_decode(Cookie::get($this->cookieName, '[]'), true);
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

        return Cookie::queue($this->cookieName, json_encode($this->data), $this->minutes);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->data = [];

        return Cookie::queue($this->cookieName, null, -2628000);
    }

}

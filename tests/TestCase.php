<?php namespace Tests;

class TestCase extends \Laravel\Lumen\Testing\TestCase
{

    protected static $oAuthCredentials;

    const STRING = '#.*#';
    const NUMBER = '#[0-9]+#';
    const FLOAT = '#[0-9]+\.[0-9]+#';

    public static $regex = [
        self::STRING,
        self::NUMBER,
        self::FLOAT
    ];

    protected static $dbRefresh = [];

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->baseUrl = env('BASE_URL_TESTING');

        parent::__construct($name, $data, $dataName);
    }

    public function setUp()
    {
        parent::setUp();

        if (!isset(self::$dbRefresh[get_class($this)])) {
            $this->debug("\n# Refresh database for class " . get_class($this));
            $this->artisan('migrate:refresh');
            $this->seed();
            self::$dbRefresh[get_class($this)] = true;
        }
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * @param array $pattern
     *
     * @return $this
     */
    public function assertMatchPattern(array $pattern)
    {
        $data = (array)json_decode($this->response->getContent());
        $this->assertArrayMatchPattern($pattern, $data);

        return $this;
    }

    /**
     * @param array $pattern
     * @param       $data
     *
     */
    public function assertArrayMatchPattern(array $pattern, $data)
    {
        foreach ($pattern as $key => $element) {
            $this->assertArrayHasKey($key, $data);
            if (is_array($element)) {
                $this->assertInternalType('array', $data[$key]);
                $this->assertArrayMatchPattern($data[$key], $element);
            } else {
                if (in_array($element, self::$regex, true)) {
                    $this->assertRegExp($element, $data[$key], $key . ' must match pattern ' . $element . ' found ' . $data[$key]);

                } else {
                    $this->assertEquals($element, $data[$key]);
                }
            }
        }
    }

    /**
     * @param $text
     *
     */
    public function debug($text)
    {
        fwrite(STDOUT, $text . " \n");
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $cookies
     * @param array  $files
     * @param array  $server
     * @param null   $content
     *
     * @return \Illuminate\Http\Response
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        /**
         * If no OAuth credentials we need to login
         */
        if (is_null(self::$oAuthCredentials)) {
            $this->login();
        }

        /**
         * add OAuth2 Authorization header
         * @see https://github.com/laravel/framework/issues/1655 for explanation about the server array and HHTP_ prefix
         **/
        $server = array_merge($server, [
            'HTTP_Authorization' => self::$oAuthCredentials->token_type . ' ' . self::$oAuthCredentials->access_token
        ]);

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * @author LAHAXE Arnaud
     *
     * @param string $login
     * @param string $password
     * @param int    $status
     *
     */
    public function login($login = 'user', $password = 'user', $status = 200)
    {
        parent::call('POST', '/api/v1/oauth/access_token', [
            'username'      => $login,
            'password'      => $password,
            'grant_type'    => 'password',
            'client_id'     => 'versusmind',
            'client_secret' => 'versusmind'
        ]);
        $this->seeJson([]);
        $this->seeStatusCode($status);

        if($this->response->getStatusCode() == 200) {
            self::$oAuthCredentials = json_decode($this->response->getContent());
        }
    }


    public function logout()
    {
        self::$oAuthCredentials = null;
    }
}

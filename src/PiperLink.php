<?php
namespace Idm\PiperLink;

use Idm\PiperLink\Exceptions\AccessDeniedException;
use Idm\PiperLink\Exceptions\AuthenticationException;
use Idm\PiperLink\Exceptions\NotFoundException;
use Idm\PiperLink\Exceptions\NotImplementedException;
use Idm\PiperLink\Http\Request;
use Idm\PiperLink\Http\Response;
use Idm\PiperLink\Tokens\TokenRepository;
use Pyther\Ioc\Ioc;

class PiperLink
{
    protected Configurations $configurations;
    protected Router $router;
    protected Request $request;
    protected Response $response;

    function __construct(string $configPath)
    {
        $this->configurations = new Configurations($configPath);

        // [Ioc]
        Ioc::bindSingleton(Configurations::class, $this->configurations);
        Ioc::bindSingleton(TokenRepository::class, [TokenRepository::class, "newInstance"]);

        $this->router = new Router((string)$this->configurations->get("router.path"));
        $this->response = new Response();
    }

    public function getRequest(): Request {
        return $this->request;
    }

    public function getResponse(): Response {
        return $this->response;
    }

    public function getRouter(): Router {
        return $this->router;
    }

    public function getConfigurations(): Configurations {
        return $this->configurations;
    }

    public function getConfiguration(string $key) {
        return $this->configurations->get($key);
    }

    /**
     * Handle PiperLink Api Routing
     * @param string $path The requested url path relative to project home url.
     * @return boolean Returns true, if the route was a PiperLink route, false otherwise.
     */
    public function route(?string $path = null) : bool {
        $path ??= strtok($_SERVER["REQUEST_URI"], '?');
        $path = ltrim($path, "/");

        if ($path == null || !str_starts_with($path, $this->router->root)) {
            return false;
        }
        try {
            $this->request = new Request();
            return $this->router->route($path, $this);
        } catch (AuthenticationException $ex) {
            $this->response->addHeader("WWW-Authenticate", $ex->buildAuthenticateHeader());
            $this->response->setData(null, 401);
        } catch (AccessDeniedException) {
            $this->response->setData(null, 403);
        } catch (NotImplementedException $ex) {
            $this->response->setData(!empty($ex->getMessage()) ? ["message" => $ex->getMessage()] : null, 501, "JSON");
        } catch (NotFoundException) {
            $this->response->setData(null, 404);
        } finally {
            $this->response->deliver();
        }
        return true;
    }

    public function bindMultiple(string $name, string|callable|null $implementation, array $args = []): PiperLink {
        Ioc::bindMultiple($name, $implementation, $args);
        return $this;
    }

    public function bindSingleton(string $name, string|callable|null|object $implementation, array $args = []): PiperLink {
        Ioc::bindSingleton($name, $implementation, $args);
        return $this;
    }

}
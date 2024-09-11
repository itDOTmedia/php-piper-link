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
        $configurations = new Configurations($configPath);

        // [Ioc]
        Ioc::bindSingleton(Configurations::class, $configurations);
        Ioc::bindSingleton(TokenRepository::class, [TokenRepository::class, "newInstance"]);

        $this->router = new Router((string)$configurations->get("router.path"));
        $this->response = new Response();
    }

    public function getRequest() {
        return $this->request;
    }

    public function getResponse() {
        return $this->response;
    }

    public function route(string $path) : bool {
        if (!str_starts_with($path, $this->router->root)) {
            return false;
        }
        try {
            $this->request = new Request();
            return $this->router->route($path, $this);
        } catch (AuthenticationException $ex) {
            $this->response->addHeader("WWW-Authenticate", $ex->buildAuthenticateHeader());
            $this->response->setData(null, 401);
        } catch (AuthenticationException $ex) {
            $this->response->addHeader("WWW-Authenticate", $ex->buildAuthenticateHeader());
            $this->response->setData(null, 401);
        } catch (AccessDeniedException) {
            $this->response->setData(null, 403);
        } catch (NotImplementedException $ex) {
            $this->response->setData(!empty($ex->getMessage()) ? ["message" => $ex->getMessage()] : null, 501, "JSON");
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
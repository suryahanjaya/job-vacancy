<?php
/**
 * Simple Router Class
 */

class Router
{
    private static $routes = [];

    public static function get($path, $handler, $middleware = [])
    {
        self::$routes[] = [
            'method' => 'GET',
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public static function post($path, $handler, $middleware = [])
    {
        self::$routes[] = [
            'method' => 'POST',
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public static function dispatch($uri, $method)
    {
        foreach (self::$routes as $route) {
            if ($route['method'] !== $method)
                continue;

            // Convert route pattern to regex
            $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                // Run middleware
                foreach ($route['middleware'] as $mw) {
                    if (is_array($mw)) {
                        $middlewareClass = $mw[0];
                        $role = $mw[1] ?? null;
                        if ($role) {
                            if (!call_user_func([$middlewareClass, 'role'], $role)) {
                                return true;
                            }
                        } else {
                            if (!call_user_func([$middlewareClass, 'handle'])) {
                                return true;
                            }
                        }
                    } else {
                        if (!$mw::handle()) {
                            return true;
                        }
                    }
                }

                // Extract params
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Load controller
                list($controller, $action) = explode('@', $route['handler']);
                $controllerFile = APP_ROOT . '/controllers/' . $controller . '.php';

                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controllerInstance = new $controller();

                    if (method_exists($controllerInstance, $action)) {
                        call_user_func_array([$controllerInstance, $action], $params);
                        return true;
                    }
                }
            }
        }
        return false;
    }
}

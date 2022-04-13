<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace app\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * Class StaticFile
 * @package app\middleware
 */
class Auth implements MiddlewareInterface
{
    public function process(Request $request, callable $next): Response
    {
        if($request->get('token') !== config('app.token'))
        {
            return json([
                'code' => 401,
                'msg' => '会话失效，请重新登录'
            ]);
        }
        
        $response = $next($request);

        return $response;
    }
}

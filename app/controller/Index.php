<?php

namespace app\controller;

use support\Request;

class Index
{
    /**
     * 首页
     */
    public function index(Request $request)
    {
        $list = scandir('/');

        return view('index/index', [
            'list' => $list,
            'token'=> $request->get('token')
        ]);
    }

    /**
     * 获取目录列表
     */
    public function list(Request $request)
    {
        $dir = $request->post('dir', '/');

        // var_dump(is_dir($dir));

        if(is_dir($dir))
        {
            // 目录
            $code = 1;
            $list = scandir($dir);
            $list = array_filter($list, function($v){
                return $v != '.' && $v != '..';
            });

            $data = [];

            foreach($list as $r) 
            {
                if(IS_WIN)
                {
                    $data[] = [
                        'filename' => $r
                    ];
                }
                else
                {
                    $s = $dir == '/' ? "{$dir}$r" : "{$dir}/$r";
                    $user = posix_getpwuid(fileowner($s));
                    $group = posix_getgrgid(filegroup($s));
    
                    $data[] = [
                        'filename' => $r,
                        'user' => $user['name'],
                        'group' => $group['name']
                    ];
                }
            }
        }
        else
        {
            // 文件
            $code = 2;
            $data = file_get_contents($dir);
        }

        return json([
            'code' => $code,
            'msg'  => 'ok',
            'data' => $data
        ]);
    }

    /**
     * 保存文件
     */
    public function save(Request $request)
    {
        $postData = $request->post();

        if(!file_exists($postData['dir']))
        {
            return json([
                'code' => 0,
                'msg'  => '对不起，文件不存在',
            ]);
        }

        if(file_put_contents($postData['dir'], $postData['content'], LOCK_EX))
        {
            return json([
                'code' => 1,
                'msg'  => '已保存'
            ]);
        }
        else
        {
            return json([
                'code' => 0,
                'msg'  => '保存失败'
            ]);
        }
    }

    /**
     * 执行命令
     */
    public function exec(Request $request)
    {
        $cli = $request->post('cli');

        return response(shell_exec($cli));
    }
}

<?php

namespace Nebula\Widgets;

use Nebula\Common;
use Nebula\Helpers\Cookie;
use Nebula\Helpers\Validate;

class User extends Base
{
    /**
     * 是否已登陆
     *
     * @var null|bool
     */
    private $hasLogin = null;

    /**
     * 获取登陆状态
     *
     * @return bool 是否已登陆
     */
    public function hasLogin()
    {
        if (null === $this->hasLogin) {
            $uid = Cookie::get('nebula_uid');
            $token = Cookie::get('nebula_token');
            // cookie 是否存在
            if (null !== $uid && null !== $token) {
                $userInfo = $this->db->get('users', ['uid', 'username', 'email', 'token'], ['uid' => $uid]);
                // 用户信息是否存在
                if ($userInfo) {
                    // token 有效性
                    $this->hasLogin = Common::hashValidate($userInfo['token'], $token);
                    if ($this->hasLogin) {
                        foreach ($userInfo as $name => $value) {
                            $this->$name = $value;
                        }
                    }
                } else {
                    $this->hasLogin = false;
                }
            } else {
                $this->hasLogin = false;
            }
        }

        return $this->hasLogin;
    }

    /**
     * 登陆验证
     *
     * @return void
     */
    public function login()
    {
        // 权限验证，避免重复登陆
        if ($this->hasLogin()) {
            $this->response->redirect('/admin');
        }

        $userInfo = $this->db->get('users', ['uid', 'password'], [
            'OR' => [
                'username' => $this->request->post('account'),
                "email" => $this->request->post('account'),
            ],
        ]);

        // 验证密码
        if ($userInfo && Common::hashValidate($this->request->post('password'), $userInfo['password'])) {
            // 生成 token
            $token = Common::randString(32);
            $tokenHash = Common::hash($token);

            // 更新 token
            $this->db->update('users', ['token' => $token], ['uid' => $userInfo['uid']]);

            Cookie::set('nebula_uid', $userInfo['uid']);
            Cookie::set('nebula_token', $tokenHash);

            $this->response->redirect('/admin');
        } else {
            Notice::alloc()->set('登录失败', 'warning');
            $this->response->redirect('/admin/login.php');
        }
    }

    /**
     * 注册验证
     *
     * @return void
     */
    public function register()
    {
        // 权限验证，避免登陆注册
        if ($this->hasLogin()) {
            $this->response->redirect('/admin');
        }

        $params = $this->request->post();

        $validate = new Validate($params, [
            'username' => [
                ['type' => 'required', 'message' => '用户名不能为空'],
            ],
            'email' => [
                ['type' => 'required', 'message' => '邮箱不能为空'],
                ['type' => 'email', 'message' => '邮箱格式不正确'],
            ],
            'code' => [
                ['type' => 'required', 'message' => '验证码不能为空'],
            ],
            'password' => [
                ['type' => 'required', 'message' => '密码不能为空'],
            ],
            'confirmPassword' => [
                ['type' => 'required', 'message' => '确认密码不能为空'],
                ['type' => 'confirm', 'key' => 'password', 'message' => '两次输入密码不一致'],
            ],
        ]);

        if ($validate->run()) {
            if ($this->db->has('users', ['username' => $params['username']])) {
                // 用户名已存在
                Notice::alloc()->set('用户名已存在', 'warning');
                $this->response->redirect('/admin/register.php');
            } else if ($this->db->has('users', ['email' => $params['email']])) {
                // 邮箱已存在
                Notice::alloc()->set('邮箱已存在', 'warning');
                $this->response->redirect('/admin/register.php');
            } else {
                // 插入数据
                $this->db->insert('users', [
                    'username' => $params['username'],
                    'email' => $params['email'],
                    'password' => Common::hash($params['password']),
                ]);

                Notice::alloc()->set('注册成功', 'success');
                $this->response->redirect('/admin/login.php');
            }
        } else {
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/register.php');
        }
    }

    /**
     * 退出登陆
     *
     * @return void
     */
    public function logout()
    {
        Cookie::delete('nebula_uid');
        Cookie::delete('nebula_token');
        $this->response->redirect('/admin/login.php');
    }

    /**
     * 行动方法
     *
     * @return $this
     */
    public function action()
    {
        $action = $this->params['action'];

        $this->on($action === 'login')->login();
        $this->on($action === 'register')->register();
        $this->on($action === 'logout')->logout();

        return $this;
    }
}

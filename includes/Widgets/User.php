<?php

namespace Nebula\Widgets;

use Nebula\Common;
use Nebula\Helpers\Cookie;
use Nebula\Helpers\Validate;
use Nebula\Widget;

class User extends Widget
{
    /**
     * 是否已登陆
     *
     * @var null|bool
     */
    private $hasLogin = null;

    /**
     * 登陆用户信息
     *
     * @var null|array
     */
    private $loginUserInfo = null;

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
                $loginUserInfo = $this->db->get('users', ['uid', 'username', 'email', 'nickname', 'token'], ['uid' => $uid]);
                // 用户信息是否存在
                if ($loginUserInfo) {
                    // token 有效性
                    $this->hasLogin = Common::hashValidate($loginUserInfo['token'], $token);
                    if ($this->hasLogin) {
                        $this->loginUserInfo = $loginUserInfo;
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
     * 获取用户信息，若参数为空，则查询登陆用户信息
     *
     * @param null｜string $key 字段名
     * @return mixed
     */
    public function get($key = null)
    {
        $uid = $this->params['uid'] ?? null;
        $userInfo = null;
        if (null === $uid) {
            $userInfo = $this->loginUserInfo;
        } else {
            $userInfo = $this->db->get('users', ['uid', 'username', 'email', 'nickname', 'token'], ['uid' => $uid]);
        }

        if (null === $key) {
            return $userInfo;
        } else {
            return $userInfo[$key] ?? null;
        }
    }

    /**
     * 获取用户名称
     */
    public function getName()
    {
        return empty($this->get('nickname')) ? $this->get('username') : $this->get('nickname');
    }

    /**
     * 登陆验证
     *
     * @return void
     */
    private function login()
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
    private function register()
    {
        // 权限验证，避免登陆注册
        if ($this->hasLogin()) {
            $this->response->redirect('/admin');
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
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
            // 用户名已存在
            if ($this->db->has('users', ['username' => $data['username']])) {
                Notice::alloc()->set('用户名已存在', 'warning');
                $this->response->redirect('/admin/register.php');
            }

            // 邮箱已存在
            if ($this->db->has('users', ['email' => $data['email']])) {

                Notice::alloc()->set('邮箱已存在', 'warning');
                $this->response->redirect('/admin/register.php');
            }

            // 插入数据
            $this->db->insert('users', [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Common::hash($data['password']),
            ]);

            Notice::alloc()->set('注册成功', 'success');
            $this->response->redirect('/admin/login.php');
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
    private function logout()
    {
        $this->loginUserInfo = null;
        // 清空 token
        $this->db->update('users', ['token' => ''], ['uid' => Cookie::get('nebula_uid', '')]);
        // 清除用户 cookie
        Cookie::delete('nebula_uid');
        Cookie::delete('nebula_token');

        $this->response->redirect('/admin/login.php');
    }

    /**
     * 更新用户信息
     *
     * @return void
     */
    private function update()
    {
        $uid = $this->params['uid'];

        // 未登陆
        if (!$this->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        // 修改用户不存在
        if (!$this->db->has('users', ['uid' => $uid])) {
            Notice::alloc()->set('未知用户', 'error');
            $this->response->redirect('/admin/profile.php?uid=' . $this->loginUserInfo['uid']);
        }

        // 如果不是修改当前登陆用户
        if ($this->loginUserInfo['uid'] !== $uid) {
            Notice::alloc()->set('非法请求', 'error');
            $this->response->redirect('/admin/profile.php?uid=' . $this->loginUserInfo['uid']);
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'username' => [
                ['type' => 'required', 'message' => '用户名不能为空'],
            ],
            'email' => [
                ['type' => 'required', 'message' => '邮箱不能为空'],
                ['type' => 'email', 'message' => '邮箱格式不正确'],
            ],
        ]);

        if ($validate->run()) {
            // 用户名已存在
            $userInfo = $this->db->get('users', ['uid'], ['username' => $data['username']]);
            if (null !== $userInfo && $userInfo['uid'] !== $uid) {
                Notice::alloc()->set('用户名已存在', 'warning');
                $this->response->redirect('/admin/profile.php?uid=' . $uid);
            }

            // 邮箱已存在
            $userInfo = $this->db->get('users', ['uid'], ['email' => $data['email']]);
            if (null !== $userInfo && $userInfo['uid'] !== $uid) {
                Notice::alloc()->set('邮箱已存在', 'warning');
                $this->response->redirect('/admin/profile.php?uid=' . $uid);
            }

            // 修改数据
            $this->db->update('users', [
                'username' => $data['username'],
                'nickname' => $data['nickname'],
                'email' => $data['email'],
            ], ['uid' => $uid]);

            Notice::alloc()->set('修改成功', 'success');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        } else {
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        }
    }

    /**
     * 更新用户密码
     *
     * @return void
     */
    private function updatePassword()
    {
        $uid = $this->params['uid'];

        // 未登陆
        if (!$this->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        // 修改用户不存在
        if (!$this->db->has('users', ['uid' => $uid])) {
            Notice::alloc()->set('未知用户', 'error');
            $this->response->redirect('/admin/profile.php?uid=' . $this->loginUserInfo['uid']);
        }

        // 不是修改当前登陆用户
        if ($this->loginUserInfo['uid'] !== $uid) {
            Notice::alloc()->set('非法请求', 'error');
            $this->response->redirect('/admin/profile.php?uid=' . $this->loginUserInfo['uid']);
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'password' => [
                ['type' => 'required', 'message' => '密码不能为空'],
            ],
            'confirmPassword' => [
                ['type' => 'required', 'message' => '确认密码不能为空'],
                ['type' => 'required', 'message' => '确认密码不能为空'],
                ['type' => 'confirm', 'key' => 'password', 'message' => '两次输入密码不一致'],
            ],
        ]);

        if ($validate->run()) {
            // 修改数据
            $this->db->update('users', [
                'password' => Common::hash($data['password']),
                'token' => '',
            ], ['uid' => $uid]);

            Notice::alloc()->set('修改成功', 'success');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        } else {
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        }
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
        $this->on($action === 'update')->update();
        $this->on($action === 'update-password')->updatePassword();

        return $this;
    }
}

<?php

namespace Nebula\Widgets\Users;

use Nebula\Common;
use Nebula\Helpers\Cookie;
use Nebula\Helpers\Validate;
use Nebula\Widgets\Cache;
use Nebula\Widgets\Notice;
use Nebula\Widgets\Mails\Method as MailsMethod;

class Handle extends Method
{
    /**
     * 登陆
     *
     * @return void
     */
    private function login()
    {
        // 权限验证，避免重复登陆
        if (Method::factory()->hasLogin()) {
            $this->response->redirect('/admin');
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'account' => [
                ['type' => 'required', 'message' => '用户名不能为空'],
            ],
            'password' => [
                ['type' => 'required', 'message' => '密码不能为空'],
            ],
        ]);

        // 表单验证
        if (!$validate->run()) {
            Cache::factory()->set('loginAccount', $this->request->post('account', ''));

            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/login.php');
        }

        $userInfo = $this->db
            ->get('users', ['uid', 'password'])
            ->where([
                'OR' => [
                    'username' => $this->request->post('account'),
                    'email' => $this->request->post('account'),
                ]
            ])
            ->execute();

        // 验证密码
        if ($userInfo && Common::hashValidate($this->request->post('password'), $userInfo['password'])) {
            // 生成 token
            $token = Common::randString(32);
            $tokenHash = Common::hash($token);

            // 更新 token
            $this->db
                ->update('users', ['token' => $token])
                ->where(['uid' => $userInfo['uid']])
                ->execute();

            Cookie::set('uid', $userInfo['uid']);
            Cookie::set('token', $tokenHash);

            $this->response->redirect('/admin');
        } else {
            Cache::factory()->set('loginAccount', $this->request->post('account', ''));

            Notice::factory()->set('登录失败', 'warning');
            $this->response->redirect('/admin/login.php');
        }
    }

    /**
     * 注册
     *
     * @return void
     */
    private function register()
    {
        // 权限验证，避免登陆注册
        if (Method::factory()->hasLogin()) {
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
        if (!$validate->run()) {
            Cache::factory()->set('registerUsername', $this->request->post('username', ''));
            Cache::factory()->set('registerEmail', $this->request->post('email', ''));
            Cache::factory()->set('registerCode', $this->request->post('code', ''));

            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/register.php');
        }

        // 验证码是否正确
        if (!Common::hashValidate($data['email'] . $data['code'], Cookie::get('code_hash', ''))) {
            Cache::factory()->set('registerUsername', $this->request->post('username', ''));
            Cache::factory()->set('registerEmail', $this->request->post('email', ''));

            Notice::factory()->set('验证码错误', 'warning');
            $this->response->redirect('/admin/register.php');
        }

        // 用户名是否存在
        if ($this->db->has('users', ['username' => $data['username']])) {
            Cache::factory()->set('registerEmail', $this->request->post('email', ''));
            Cache::factory()->set('registerCode', $this->request->post('code', ''));

            Notice::factory()->set('用户名已存在', 'warning');
            $this->response->redirect('/admin/register.php');
        }

        // 邮箱是否存在
        if ($this->db->has('users', ['email' => $data['email']])) {
            Cache::factory()->set('registerUsername', $this->request->post('username', ''));

            Notice::factory()->set('邮箱已存在', 'warning');
            $this->response->redirect('/admin/register.php');
        }

        // 插入数据
        Method::factory()->createUser($data['username'], $data['password'], $data['email']);

        Notice::factory()->set('注册成功', 'success');
        $this->response->redirect('/admin/login.php');
    }

    /**
     * 退出登陆
     *
     * @return void
     */
    private function logout()
    {
        // 清空登陆用户信息
        $this->loginUserInfo = null;
        // 清空 token
        $this->db
            ->update('users', ['token' => ''])
            ->where(['uid' => Cookie::get('uid', '')])
            ->execute();
        // 清除用户 cookie
        Cookie::delete('uid');
        Cookie::delete('token');

        $this->response->redirect('/admin/login.php');
    }

    /**
     * 更新用户信息
     *
     * @return void
     */
    private function update()
    {
        // 判断用户权限
        if (!Method::factory()->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        $uid = $this->params('uid');

        // 修改用户不存在
        if (!$this->db->has('users', ['uid' => $uid])) {
            Notice::factory()->set('未知用户', 'error');
            $this->response->redirect('/admin/profile.php?uid=' . $this->loginUserInfo['uid']);
        }

        // 不是修改当前登陆用户，那么必须是管理员权限
        if ($this->loginUserInfo['uid'] !== $uid && !Method::factory()->inRole(['0'])) {
            Notice::factory()->set('非法请求', 'error');
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
        if (!$validate->run()) {
            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        }

        $userInfo = $this->db
            ->get('users', ['uid'])
            ->where(['username' => $data['username']])
            ->execute();

        // 用户名是否存在
        if (null !== $userInfo && $userInfo['uid'] !== $uid) {
            Notice::factory()->set('用户名已存在', 'warning');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        }

        // 邮箱是否存在
        $userInfo = $this->db
            ->get('users', ['uid'])
            ->where(['email' => $data['email']])
            ->execute();
        if (null !== $userInfo && $userInfo['uid'] !== $uid) {
            Notice::factory()->set('邮箱已存在', 'warning');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        }

        // 修改数据
        $this->db
            ->update('users', [
                'username' => $data['username'],
                'nickname' => $data['nickname'],
                'email' => $data['email'],
            ])
            ->where(['uid' => $uid])
            ->execute();

        Notice::factory()->set('修改成功', 'success');
        $this->response->redirect('/admin/profile.php?uid=' . $uid);
    }

    /**
     * 更新用户密码
     *
     * @return void
     */
    private function updatePassword()
    {
        // 未登陆
        if (!Method::factory()->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        $uid = $this->params('uid');

        // 修改用户不存在
        if (!$this->db->has('users', ['uid' => $uid])) {
            Notice::factory()->set('未知用户', 'error');
            $this->response->redirect('/admin/profile.php?action=password&uid=' . $this->loginUserInfo['uid']);
        }

        // 不是修改当前登陆用户，那么必须是管理员权限
        if ($this->loginUserInfo['uid'] !== $uid && !Method::factory()->inRole(['0'])) {
            Notice::factory()->set('非法请求', 'error');
            $this->response->redirect('/admin/profile.php?action=password&uid=' . $this->loginUserInfo['uid']);
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
        if (!$validate->run()) {
            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/profile.php?action=password&uid=' . $uid);
        }

        // 修改数据
        $this->db
            ->update('users', [
                'password' => Common::hash($data['password']),
                'token' => '',
            ])
            ->where(['uid' => $uid])
            ->execute();

        Notice::factory()->set('修改成功', 'success');
        $this->response->redirect('/admin/profile.php?action=password&uid=' . $uid);
    }

    /**
     * 更新用户权限
     *
     * @return void
     */
    private function updatePermission()
    {
        // 是否是管理员
        if (!Method::factory()->inRole(['0'])) {
            Notice::factory()->set('非法请求', 'error');
            $this->response->redirect('/admin/profile.php?action=permission&uid=' . $this->loginUserInfo['uid']);
        }

        $uid = $this->params('uid');

        // 修改用户不存在
        if (!$this->db->has('users', ['uid' => $uid])) {
            Notice::factory()->set('未知用户', 'error');
            $this->response->redirect('/admin/profile.php?action=permission&uid=' . $this->loginUserInfo['uid']);
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'role' => [
                ['type' => 'required', 'message' => '用户角色不能为空'],
            ],
        ]);
        if (!$validate->run()) {
            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/profile.php?action=permission&uid=' . $uid);
        }

        // 修改数据
        $this->db
            ->update('users', [
                'role' => $data['role'],
            ])
            ->where(['uid' => $uid])
            ->execute();

        Notice::factory()->set('修改成功', 'success');
        $this->response->redirect('/admin/profile.php?action=permission&uid=' . $uid);
    }

    /**
     * 发送注册验证码
     *
     * @return void
     */
    private function sendRegisterCaptcha()
    {
        // 已登陆限制
        if (Method::factory()->hasLogin()) {
            $this->response->sendJSON(['errorCode' => 1, 'type' => 'error', 'message' => '非法请求']);
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'email' => [
                ['type' => 'required', 'message' => '邮箱不能为空'],
                ['type' => 'email', 'message' => '邮箱格式不正确'],
            ],
        ]);
        if (!$validate->run()) {
            $this->response->sendJSON(['errorCode' => 2, 'type' => 'warning', 'message' => $validate->result[0]['message']]);
        }

        // 邮箱是否存在
        if ($this->db->has('users', ['email' => $data['email']])) {
            $this->response->sendJSON(['errorCode' => 3, 'type' => 'warning', 'message' => '邮箱已存在']);
        }

        // 发送邮件
        MailsMethod::factory()->sendCaptcha($data['email']);

        $this->response->sendJSON(['errorCode' => 0, 'type' => 'success', 'message' => '发送成功']);
    }

    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 登录
        $this->on($action === 'login')->login();

        // 注册
        $this->on($action === 'register')->register();

        // 退出登陆
        $this->on($action === 'logout')->logout();

        // 更新用户信息
        $this->on($action === 'update')->update();

        // 更新用户密码
        $this->on($action === 'update-password')->updatePassword();

        // 更新用户权限
        $this->on($action === 'update-permission')->updatePermission();

        // 发送注册验证码
        $this->on($action === 'send-register-captcha')->sendRegisterCaptcha();
    }
}

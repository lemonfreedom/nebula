<?php

namespace Nebula\Widgets;

use Nebula\Common;
use Nebula\Helpers\Cookie;
use Nebula\Helpers\Mail;
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
     * 登陆用户信息
     *
     * @var null|array
     */
    private $loginUserInfo = null;

    /**
     * 角色列表
     *
     * @var array
     */
    public $roleList = [
        ['name' => '管理员', 'value' => '0'],
        ['name' => '普通用户', 'value' => '1'],
        ['name' => '访客', 'value' => '2'],
        ['name' => '封禁', 'value' => '255'],
    ];

    /**
     * 获取登陆状态
     *
     * @return bool 是否已登陆
     */
    public function hasLogin()
    {
        if (null === $this->hasLogin) {
            $uid = Cookie::get('uid');
            $token = Cookie::get('token');

            // cookie 是否存在
            if (null !== $uid && null !== $token) {
                $loginUserInfo = $this->db->get('users', ['uid', 'role', 'username', 'email', 'nickname', 'token'], ['uid' => $uid]);
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
     * 判断用户角色是否存在某角色列表中
     *
     * @param array $roles 角色列表
     * @return bool
     */
    public function inRole($roles)
    {
        if ($this->hasLogin()) {
            return in_array($this->get('role'), $roles);
        } else {
            return false;
        }
    }

    /**
     * 通过角色值返回角色名
     *
     * @param string $value 角色值
     * @return string 角色名
     */
    public function getRoleName($value)
    {
        foreach ($this->roleList as $role) {
            if ($role['value'] === $value) {
                return $role['name'];
            }
        }

        return '未知';
    }

    /**
     * 获取指定用户信息，若参数为空，则查询登陆用户信息
     *
     * @param null｜string $key 字段名
     * @param string $defaultValue 默认值
     * @return mixed
     */
    public function get($key = null, $defaultValue = '')
    {
        $uid = $this->params['uid'] ?? null;
        $userInfo = null;

        if (null === $uid) {
            $userInfo = $this->loginUserInfo;
        } else {
            $userInfo = $this->db->get('users', ['uid', 'role', 'username', 'email', 'nickname', 'token'], ['uid' => $uid]);
        }

        if (null === $key) {
            return $userInfo;
        } else {
            return $userInfo[$key] ?? $defaultValue;
        }
    }

    /**
     * 登陆
     *
     * @return void
     */
    private function login()
    {
        // 权限验证，避免重复登陆
        if ($this->hasLogin()) {
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
            Cookie::set('account', $this->request->post('account', ''), time() + 1);

            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/login.php');
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

            Cookie::set('uid', $userInfo['uid']);
            Cookie::set('token', $tokenHash);

            $this->response->redirect('/admin');
        } else {
            Cookie::set('account', $this->request->post('account'), time() + 1);

            Notice::alloc()->set('登录失败', 'warning');
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
        if (!$validate->run()) {
            Cookie::set('username', $this->request->post('username', ''), time() + 1);
            Cookie::set('email', $this->request->post('email', ''), time() + 1);
            Cookie::set('code', $this->request->post('code', ''), time() + 1);

            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/register.php');
        }

        // 验证码是否正确
        if (!Common::hashValidate($data['email'] . $data['code'], Cookie::get('code_hash', ''))) {
            Cookie::set('username', $this->request->post('username', ''), time() + 1);
            Cookie::set('email', $this->request->post('email', ''), time() + 1);

            Notice::alloc()->set('验证码错误', 'warning');
            $this->response->redirect('/admin/register.php');
        }

        // 用户名是否存在
        if ($this->db->has('users', ['username' => $data['username']])) {
            Cookie::set('email', $this->request->post('email', ''), time() + 1);
            Cookie::set('code', $this->request->post('code', ''), time() + 1);

            Notice::alloc()->set('用户名已存在', 'warning');
            $this->response->redirect('/admin/register.php');
        }

        // 邮箱是否存在
        if ($this->db->has('users', ['email' => $data['email']])) {
            Cookie::set('username', $this->request->post('username', ''), time() + 1);

            Notice::alloc()->set('邮箱已存在', 'warning');
            $this->response->redirect('/admin/register.php');
        }

        // 插入数据
        $this->createUser($data['username'], $data['password'], $data['email']);

        Notice::alloc()->set('注册成功', 'success');
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
        $this->db->update('users', ['token' => ''], ['uid' => Cookie::get('uid', '')]);
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
        if (!$this->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        $uid = $this->params['uid'];

        // 修改用户不存在
        if (!$this->db->has('users', ['uid' => $uid])) {
            Notice::alloc()->set('未知用户', 'error');
            $this->response->redirect('/admin/profile.php?uid=' . $this->loginUserInfo['uid']);
        }

        // 不是修改当前登陆用户，那么必须是管理员权限
        if ($this->loginUserInfo['uid'] !== $uid && !$this->inRole(['0'])) {
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
        if (!$validate->run()) {
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        }

        $userInfo = $this->db->get('users', ['uid'], ['username' => $data['username']]);

        // 用户名是否存在
        if (null !== $userInfo && $userInfo['uid'] !== $uid) {
            Notice::alloc()->set('用户名已存在', 'warning');
            $this->response->redirect('/admin/profile.php?uid=' . $uid);
        }

        // 邮箱是否存在
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
    }

    /**
     * 更新用户密码
     *
     * @return void
     */
    private function updatePassword()
    {
        // 未登陆
        if (!$this->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        $uid = $this->params['uid'];

        // 修改用户不存在
        if (!$this->db->has('users', ['uid' => $uid])) {
            Notice::alloc()->set('未知用户', 'error');
            $this->response->redirect('/admin/profile.php?action=password&uid=' . $this->loginUserInfo['uid']);
        }

        // 不是修改当前登陆用户，那么必须是管理员权限
        if ($this->loginUserInfo['uid'] !== $uid && !$this->inRole(['0'])) {
            Notice::alloc()->set('非法请求', 'error');
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
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/profile.php?action=password&uid=' . $uid);
        }

        // 修改数据
        $this->db->update('users', [
            'password' => Common::hash($data['password']),
            'token' => '',
        ], ['uid' => $uid]);

        Notice::alloc()->set('修改成功', 'success');
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
        if (!$this->inRole(['0'])) {
            Notice::alloc()->set('非法请求', 'error');
            $this->response->redirect('/admin/profile.php?action=permission&uid=' . $this->loginUserInfo['uid']);
        }

        $uid = $this->params['uid'];

        // 修改用户不存在
        if (!$this->db->has('users', ['uid' => $uid])) {
            Notice::alloc()->set('未知用户', 'error');
            $this->response->redirect('/admin/profile.php?action=permission&uid=' . $this->loginUserInfo['uid']);
        }



        $data = $this->request->post();

        $validate = new Validate($data, [
            'role' => [
                ['type' => 'required', 'message' => '用户角色不能为空'],
            ],
        ]);
        if (!$validate->run()) {
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/profile.php?action=permission&uid=' . $uid);
        }

        // 修改数据
        $this->db->update('users', [
            'role' => $data['role'],
        ], ['uid' => $uid]);

        Notice::alloc()->set('修改成功', 'success');
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
        if ($this->hasLogin()) {
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
        Mail::getInstance()->sendCaptcha($data['email']);

        $this->response->sendJSON(['errorCode' => 0, 'type' => 'success', 'message' => '发送成功']);
    }

    /**
     * 获取用户列表
     *
     * @return array 用户列表
     */
    public function getUserList()
    {
        $keyword = trim($this->params['keyword']);
        return $this->db->select('users', ['uid', 'role', 'username', 'email', 'nickname', 'token'], [
            'OR' => [
                'uid[~]' => $keyword,
                'username[~]' => $keyword,
                'email[~]' => $keyword,
                'nickname[~]' => $keyword,
            ],
        ]);
    }

    /**
     * 创建用户
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $email 邮箱
     * @param string $role 角色
     * @return void
     */
    public function createUser($username, $password, $email, $role = 1)
    {
        $this->db->insert('users', [
            'nickname' => $username,
            'username' => $username,
            'password' => Common::hash($password),
            'email' => $email,
            'role' => $role,
        ]);
    }

    /**
     * 行动方法
     *
     * @return $this
     */
    public function action()
    {
        $action = $this->params['action'];

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

        return $this;
    }
}

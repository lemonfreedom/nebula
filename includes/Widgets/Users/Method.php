<?php

namespace Nebula\Widgets\Users;

use Nebula\Common;
use Nebula\Widget;
use Nebula\Helpers\Cookie;

class Method extends Widget
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
     * 获取指定用户信息，若参数为空，则查询登陆用户信息
     *
     * @param null|string $name 字段名
     * @param string $defaultValue 默认值
     * @return null|string|array
     */
    public function get($name = null, $defaultValue = '')
    {
        $uid = $this->params('uid');

        $userInfo = null;
        if (null === $uid) {
            $userInfo = $this->loginUserInfo;
        } else {
            $userInfo = $this->db->get('users', ['uid', 'role', 'username', 'email', 'nickname', 'token'], ['uid' => $uid]);
        }

        if (null === $name) {
            return $userInfo;
        } else {
            return $userInfo[$name] ?? $defaultValue;
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
     * 获取用户列表
     *
     * @return array 用户列表
     */
    public function getUserList()
    {
        $keyword = trim($this->params('keyword'));

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
}

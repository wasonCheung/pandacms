<?php

declare(strict_types=1);

use App\Admin\Panel\Contracts\Resource;
use App\Admin\Panel\Exceptions\LoginInvalidException;
use App\Admin\Panel\Exceptions\LoginLimitException;
use App\Admin\Panel\Pages\LoginPage;
use App\Admin\Panel\Resources\Role\RoleForm;
use App\Admin\Panel\Resources\Role\RoleTable;
use App\Admin\Panel\Resources\RoleResource;
use App\Admin\Panel\Resources\User\UserForm;
use App\Admin\Panel\Resources\User\UserTable;
use App\Admin\Panel\Resources\UserResource;

return [
    Resource::class => '资源',
    LoginInvalidException::class => '用户名或密码错误',
    LoginLimitException::class => '登录次数过多，请 :seconds 秒后再试。',
    LoginPage::class => [
        'title' => '管理面板',
        'name' => '用户名',
        'password' => '密码',
        'remember' => '记住我',
        'do_login' => '进入',
    ],
    RoleResource::class => [
        RoleResource::PERMISSION_VIEW_ANY => '列表',
        RoleResource::PERMISSION_VIEW => '详情',
        RoleResource::PERMISSION_CREATE => '新建',
        RoleResource::PERMISSION_UPDATE => '更新',
        RoleResource::PERMISSION_DELETE => '删除',
        'model_label' => '角色',
    ],
    RoleForm::class => [
        'name' => '角色名',
        'name_regex' => '角色名只能包含字母、数字',
        'guard_name' => '守卫',
        'permissions' => '关联权限',
    ],
    RoleTable::class => [
        'name' => '角色名',
        'guard_name' => '所属守卫',
        'permissions_count' => '关联权限',
    ],
    UserResource::class => [
        UserResource::PERMISSION_VIEW_ANY => '列表',
        UserResource::PERMISSION_VIEW => '详情',
        UserResource::PERMISSION_CREATE => '新建',
        UserResource::PERMISSION_UPDATE => '更新',
        UserResource::PERMISSION_DELETE => '删除',
        'model_label' => '用户',
    ],
    UserTable::class => [
        'roles_filter' => '选择角色',
        'guard_filter' => '选择守卫',
    ],
    UserForm::class => [
        'name' => '用户名',
        'name_regex' => '用户名只能包含字母、数字',
        'email' => '邮箱',
        'password' => '密码',
        'password_confirmation' => '确认密码',
        'password_confirmation_same' => '两次输入的密码不一致',
        'roles' => '关联角色',
        'guard_name' => '守卫',
    ],
];

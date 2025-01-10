<?php

declare(strict_types=1);

use App\Admin\Panel\Contracts\Resource;
use App\Admin\Panel\Exceptions\LoginInvalidException;
use App\Admin\Panel\Exceptions\LoginLimitException;
use App\Admin\Panel\Pages\LoginPage;
use App\Admin\Panel\Resources\Role\RoleForm;
use App\Admin\Panel\Resources\Role\RoleTable;
use App\Admin\Panel\Resources\RoleResource;
use App\Admin\Panel\Resources\UserResource;

return [
    LoginInvalidException::class => '用户名或密码错误',
    LoginLimitException::class => '登录次数过多，请 :seconds 秒后再试。',
    RoleResource::class => [
        'model_label' => '角色',
        'permissions' => [
            RoleResource::PERMISSION_VIEW_ANY => '列表',
            RoleResource::PERMISSION_VIEW => '详情',
            RoleResource::PERMISSION_CREATE => '新建',
            RoleResource::PERMISSION_UPDATE => '更新',
            RoleResource::PERMISSION_DELETE => '删除',
        ],
    ],
    UserResource::class => [
        'model_label' => '用户',
        'permissions' => [
            UserResource::PERMISSION_VIEW_ANY => '列表',
            UserResource::PERMISSION_VIEW => '详情',
            UserResource::PERMISSION_CREATE => '新建',
            UserResource::PERMISSION_UPDATE => '更新',
            UserResource::PERMISSION_DELETE => '删除',
        ],
    ],
    RoleForm::class => [
        'name' => '角色名',
        'name_regex' => '角色名只能包含字母、数字',
        'guard_name' => '守卫',
        'permissions' => '关联权限',
    ],
    LoginPage::class => [
        'title' => '管理面板',
        'name' => '用户名',
        'password' => '密码',
        'remember' => '记住我',
        'do_login' => '进入',
    ],
    RoleTable::class => [
        'name' => '角色名',
        'guard_name' => '所属守卫',
        'permissions_count' => '关联权限',
    ],
    Resource::class => '资源',
];

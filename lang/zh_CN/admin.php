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
            'resource_role_view_any' => '查看所有',
            'resource_role_view' => '查看',
            'resource_role_create' => '创建',
            'resource_role_edit' => '编辑',
            'resource_role_delete' => '删除',
        ],
    ],
    UserResource::class => [
        'model_label' => '用户',
        'permissions' => [
            'resource_user_view_any' => '查看所有',
            'resource_user_view' => '查看',
            'resource_user_create' => '创建',
            'resource_user_edit' => '编辑',
            'resource_user_delete' => '删除',
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

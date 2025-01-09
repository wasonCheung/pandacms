<?php

declare(strict_types=1);

use App\Admin\Panel\Exceptions\LoginInvalidException;
use App\Admin\Panel\Exceptions\LoginLimitException;
use App\Admin\Panel\Pages\LoginPage;
use App\Admin\Panel\Resources\Role\PermissionForms\Admin\ResourcePermissionComponent;
use App\Admin\Panel\Resources\Role\RoleForm;
use App\Admin\Panel\Resources\Role\RoleTable;
use App\Admin\Panel\Resources\RoleResource;
use App\Admin\Panel\Resources\UserResource;

return [
    ResourcePermissionComponent::class => '资源',
    LoginInvalidException::class => '用户名或密码错误',
    LoginLimitException::class => '登录次数过多，请 :seconds 秒后再试。',
    RoleResource::class => [
        'model_label' => '角色',
        'permissions' => [
            'view_any' => '列表',
            'view' => '详情',
            'create' => '创建',
            'edit' => '编辑',
            'delete' => '删除',
        ],
    ],
    UserResource::class => [
        'model_label' => '用户',
        'permissions' => [
            'view_any' => '列表',
            'view' => '详情',
            'create' => '创建',
            'edit' => '编辑',
            'delete' => '删除',
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
];

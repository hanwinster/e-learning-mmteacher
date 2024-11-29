<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function getItems()
    {
        return $this->model->get()->pluck('name', 'id');
    }

    public static function getItemList()
    {
        $repository = new self(new Role());
        $posts = $repository->getItems();
        $posts->prepend('- Select Role -', '');

        return $posts;
    }

    public static function getRoleList()
    {
        $repository = new self(new Role());
        return $repository->getItems();
    }
}

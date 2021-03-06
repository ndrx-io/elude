<?php namespace App\Libraries\Acl;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file PermissionResolver.php
 * @author LAHAXE Arnaud
 * @last-edited 05/09/2015
 * @description PermissionResolver
 *
 ******************************************************************************/


use App\Libraries\Acl\Interfaces\GroupInterface;
use App\Role;
use Illuminate\Support\Collection;
use App\Libraries\Acl\Interfaces\PermissionInterface;
use App\Libraries\Acl\Interfaces\RoleInterface;

/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 24/08/2015
 * Time: 20:12
 */
class PermissionResolver
{

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $permissions;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $roles;

    /**
     * @var GroupInterface
     */
    protected $group;

    /**
     * @return Collection
     */
    public function resolve()
    {
        $result = new Collection();

        if(!is_null($this->group)) {
            /** @var RoleInterface $role */
            foreach ($this->group->roles as $role) {
                $this->addPermissions($result, $role->permissions, $role->getFilter());
            }

            $this->addPermissions($result, $this->group->permissions);
        }

        if(!is_null($this->roles)) {
            /** @var RoleInterface $role */
            foreach ($this->roles as $role) {
                $this->addPermissions($result, $role->permissions, $role->getFilter());
            }
        }

        if(!is_null($this->permissions)) {
            $this->addPermissions($result, $this->permissions);
        }

        return $result;
    }

    /**
     * @param Collection $permissions
     * @param Collection $permissionsList
     * @param string     $filter
     */
    public function addPermissions(Collection $permissions, Collection $permissionsList, $filter = Role::FILTER_ACCESS)
    {
        foreach ($permissionsList as $permission) {
            $this->addPermission($permissions, $permission, $filter);
        }
    }

    /**
     * @param Collection          $permissions
     * @param PermissionInterface $permission
     * @param string              $filter
     */
    public function addPermission(Collection $permissions, PermissionInterface $permission, $filter = Role::FILTER_ACCESS)
    {
        if ($filter === Role::FILTER_ACCESS) {
            $permissions->put($permission->getAction(), true);
        } elseif ($filter === Role::FILTER_REVOKE) {
            $permissions->put($permission->getAction(), false);
        }
    }

    /**
     * @param \Illuminate\Support\Collection $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     * @param \Illuminate\Support\Collection $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param GroupInterface $group
     */
    public function setGroup(GroupInterface $group)
    {
        $this->group = $group;
    }
}
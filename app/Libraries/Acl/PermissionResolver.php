<?php namespace App\Libraries\Acl;

use App\Role;
use Illuminate\Support\Collection;
use Libraries\Acl\Interfaces\PermissionInterface;
use Libraries\Acl\Interfaces\RoleInterface;
use Libraries\Acl\Interfaces\UserInterface;

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
     * @var \Libraries\Acl\Interfaces\GroupInterface
     */
    protected $group;


    /**
     * @return Collection
     */
    public function resolve()
    {
        $result = new Collection();

        /** @var RoleInterface $role */
        foreach ($this->group->roles() as $role) {
            $this->addPermission($result, $role->permissions, $role->getFilter());
        }

        /** @var RoleInterface $role */
        foreach ($this->roles as $role) {
            $this->addPermission($result, $role->permissions, $role->getFilter());
        }

        $this->addPermission($result, $role->permissions);

        return $result;
    }

    /**
     * @param Collection $permissions
     * @param Collection $permissionsList
     * @param string $filter
     */
    public function addPermissions(Collection $permissions, Collection $permissionsList, $filter = Role::FILTER_ACCESS)
    {
        foreach($permissionsList as $permission) {
            $this->addPermission($permissions, $permission, $filter);
        }
    }

    /**
     * @param Collection $permissions
     * @param PermissionInterface $permission
     * @param string $filter
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
     * @param UserInterface $user
     */
    public function loadFromUser(UserInterface $user)
    {
        if(is_null($user)) {
            return;
        }

        $this->group = $user->group;
        $this->permissions = $user->permissions;
        $this->roles = $user->roles;
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
     * @param \Libraries\Acl\Interfaces\GroupInterface $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }
}
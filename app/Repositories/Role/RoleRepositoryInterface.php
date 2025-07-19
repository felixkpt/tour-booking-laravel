<?php

namespace App\Repositories\Role;

use App\Repositories\CommonRepoActionsInterface;
use Illuminate\Http\Request;

interface RoleRepositoryInterface extends CommonRepoActionsInterface
{

    public function index();

    public function getUserRoles();

    public function store(Request $request, $data);

    function getUserRolesAndPermissions();

    /**
     * Store role permissions for a specific role.
     *
     * @param  Request $request
     * @param  $role_id
     * @return Response
     */
    function storeRolePermissions(Request $request, $role_id);

    public function show($id);

    function storeRoleMenuAndCleanPermissions(Request $request, $id);
    /**
     * Update the specified resource in storage.
     */
    public function getRoleMenu($id);

    function getRoleRoutePermissions($id);

    function addUser($id);
}

<?php

namespace App\Http\Controllers\Dashboard\Settings\RolePermissions\Roles\View;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\Settings\RolePermissions\Roles\RolesController;
use Illuminate\Http\Request;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Services\Validations\Role\RoleValidationInterface;

class RoleController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private RoleRepositoryInterface $roleRepositoryInterface,
        private RoleValidationInterface $roleValidationInterface
    ) {
        $this->repo = $roleRepositoryInterface;
        sanctum_auth();
    }

    /**
     * Store role permissions for a specific role.
     *
     * @param  Request $request
     * @param  int     $id
     * @return Response
     */
    function storeRolePermissions(Request $request, $id)
    {
        $this->roleValidationInterface->storeRolePermissions($request);

        return $this->roleRepositoryInterface->storeRolePermissions($request, $id);
    }

    function storeRoleMenuAndCleanPermissions(Request $request, $id)
    {

        return $this->roleRepositoryInterface->storeRoleMenuAndCleanPermissions($request, $id);
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return app(RolesController::class)->store($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function getRoleMenu(string $id)
    {
        return $this->roleRepositoryInterface->getRoleMenu($id);
    }

    function getRoleRoutePermissions($id)
    {
        return $this->roleRepositoryInterface->getRoleRoutePermissions($id);
    }

    function addUser($id)
    {
        $this->roleValidationInterface->addUser();

        return $this->roleRepositoryInterface->addUser($id);
    }
}

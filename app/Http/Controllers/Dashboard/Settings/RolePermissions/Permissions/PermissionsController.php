<?php

namespace App\Http\Controllers\Dashboard\Settings\RolePermissions\Permissions;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Services\Validations\Permission\PermissionValidationInterface;

class PermissionsController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private PermissionRepositoryInterface $permissionRepositoryInterface,
        private PermissionValidationInterface $permissionValidationInterface
    ) {
        $this->repo = $permissionRepositoryInterface;
    }

    public function index()
    {
        return $this->permissionRepositoryInterface->index();
    }

    public function store(Request $request)
    {

        $data = $this->permissionValidationInterface->store($request);

        return $this->permissionRepositoryInterface->store($request, $data);
    }

    function getRolePermissions($role_id)
    {
        return $this->permissionRepositoryInterface->getRolePermissions($role_id);
    }
}

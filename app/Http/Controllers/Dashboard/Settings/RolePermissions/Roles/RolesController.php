<?php

namespace App\Http\Controllers\Dashboard\Settings\RolePermissions\Roles;

use App\Http\Controllers\CommonControllerMethods;
use App\Http\Controllers\Controller;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Services\Validations\Role\RoleValidationInterface;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    use CommonControllerMethods;

    function __construct(
        private RoleRepositoryInterface $roleRepositoryInterface,
        private RoleValidationInterface $roleValidationInterface
    ) {
        $this->repo = $roleRepositoryInterface;
        sanctum_auth();
    }

    public function index()
    {
        return $this->roleRepositoryInterface->index();
    }

    public function getUserRoles()
    {
        return $this->roleRepositoryInterface->getUserRoles();
    }

    public function store(Request $request)
    {
        $data = $this->roleValidationInterface->store($request);

        return $this->roleRepositoryInterface->store($request, $data);
    }

    function getUserRolesAndPermissions()
    {
        return $this->roleRepositoryInterface->getUserRolesAndPermissions();
    }
}

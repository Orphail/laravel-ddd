<?php

namespace Src\Agenda\Company\Application\DTO;

use Illuminate\Http\Request;
use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Agenda\Company\Infrastructure\EloquentModels\DepartmentEloquentModel;

class DepartmentData
{
    public static function fromRequest(Request $request, ?int $department_id = null): Department
    {
        return new Department(
            id: $department_id,
            name: new Name($request->input('name')),
            address_id: $request->input('address_id'),
            is_active: $request->input('is_active'),
        );
    }

    public static function fromArray(array $department): Department
    {
        return new Department(
            id: $department['id'] ?? null,
            name: new Name($department['name']),
            address_id: $department['address_id'] ?? null,
            is_active: $department['is_active'],
        );
    }

    public static function fromEloquent(DepartmentEloquentModel $departmentEloquentModel): Department
    {
        return new Department(
            id: $departmentEloquentModel->id,
            name: new Name($departmentEloquentModel->name),
            address_id: $departmentEloquentModel->address_id,
            is_active: $departmentEloquentModel->is_active,
        );
    }

    public static function toEloquent(Department $department): DepartmentEloquentModel
    {
        $departmentEloquent = new DepartmentEloquentModel();
        if ($department->id) {
            $departmentEloquent = DepartmentEloquentModel::query()->find($department->id);
        }
        $departmentEloquent->address_id = $department->address_id;
        $departmentEloquent->name = $department->name;
        $departmentEloquent->is_active = $department->is_active;
        return $departmentEloquent;
    }
}
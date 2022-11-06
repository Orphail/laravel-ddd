<?php

namespace Src\Agenda\Company\Application\Mappers;

use Illuminate\Http\Request;
use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Agenda\Company\Domain\Model\ValueObjects\Name;
use Src\Agenda\Company\Infrastructure\EloquentModels\DepartmentEloquentModel;

class DepartmentMapper
{
    public static function fromRequest(Request $request, ?int $department_id = null): Department
    {
        return new Department(
            id: $department_id,
            name: new Name($request->string('name')),
            address_id: $request->input('address_id'),
            is_active: $request->boolean('is_active', true),
        );
    }

    public static function fromArray(array $department): Department
    {
        $departmentEloquentModel = new DepartmentEloquentModel($department);
        $departmentEloquentModel->id = $department['id'] ?? null;
        return self::fromEloquent($departmentEloquentModel);
    }

    public static function fromEloquent(DepartmentEloquentModel $departmentEloquent): Department
    {
        return new Department(
            id: $departmentEloquent->id,
            name: new Name($departmentEloquent->name),
            address_id: $departmentEloquent->address_id,
            is_active: $departmentEloquent->is_active,
        );
    }

    public static function toEloquent(Department $department): DepartmentEloquentModel
    {
        $departmentEloquent = new DepartmentEloquentModel();
        if ($department->id) {
            $departmentEloquent = DepartmentEloquentModel::query()->findOrFail($department->id);
        }
        $departmentEloquent->address_id = $department->address_id;
        $departmentEloquent->name = $department->name;
        $departmentEloquent->is_active = $department->is_active;
        return $departmentEloquent;
    }
}
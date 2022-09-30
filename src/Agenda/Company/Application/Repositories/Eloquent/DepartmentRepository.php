<?php

namespace Src\Agenda\Company\Application\Repositories\Eloquent;

use Src\Agenda\Company\Application\Mappers\DepartmentMapper;
use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Agenda\Company\Domain\Repositories\DepartmentRepositoryInterface;
use Src\Agenda\Company\Infrastructure\EloquentModels\DepartmentEloquentModel;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function upsertAll(Company $company): Department
    {
        foreach ($company->departments as $department) {
            $departmentEloquent = DepartmentMapper::toEloquent($department);
            $departmentEloquent->company_id = $company->id;
            $departmentEloquent->save();
        }
        return DepartmentMapper::fromEloquent($departmentEloquent);
    }
    public function remove(int $department_id): void
    {
        $departmentEloquent = DepartmentEloquentModel::query()->findOrFail($department_id);
        $departmentEloquent->delete();
    }
}
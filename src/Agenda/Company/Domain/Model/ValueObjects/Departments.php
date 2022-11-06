<?php

namespace Src\Agenda\Company\Domain\Model\ValueObjects;

use Src\Agenda\Company\Domain\Model\Entities\Department;
use Src\Common\Domain\Exceptions\EntityNotFoundException;
use Src\Common\Domain\ValueObjectArray;

final class Departments extends ValueObjectArray
{
    public readonly array $departments;

    public function __construct(array $departments = [])
    {
        parent::__construct($departments);
        foreach ($departments as $department) {
            if (!$department instanceof Department) {
                throw new \InvalidArgumentException('Invalid department');
            }
        }
        $this->departments = $departments;
    }

    public function add(Department $department): void
    {
        $this->append($department);
    }

    public function update(Department $newDepartment): void
    {
        $departmentIds = array_column($this->departments, 'id');
        if (!in_array($newDepartment->id, $departmentIds)) {
            throw new EntityNotFoundException('Department not found');
        }
        $this->offsetSet(array_search($newDepartment->id, $departmentIds), $newDepartment);
    }

    public function remove(int $department_id): void
    {
        $departmentIds = array_column($this->departments, 'id');
        if (!in_array($department_id, $departmentIds)) {
            throw new EntityNotFoundException('Department not found');
        }
        $this->offsetUnset(array_search($department_id, $departmentIds));
    }

    public function jsonSerialize(): array
    {
        return $this->departments;
    }
}
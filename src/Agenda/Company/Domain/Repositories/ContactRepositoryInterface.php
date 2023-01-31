<?php

namespace Src\Agenda\Company\Domain\Repositories;

use Src\Agenda\Company\Domain\Model\Company;
use Src\Agenda\Company\Domain\Model\Entities\Contact;

interface ContactRepositoryInterface
{
    public function upsertAll(Company $company): void;
    public function remove(int $contact_id): void;
}
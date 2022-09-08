<?php

namespace Src\Agenda\Company\Application\DTO;

use Src\Agenda\Company\Application\Mappers\AddressMapper;
use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Agenda\Company\Domain\Model\ValueObjects\FiscalName;
use Src\Agenda\Company\Domain\Model\ValueObjects\SocialName;
use Src\Agenda\Company\Domain\Model\ValueObjects\Vat;
use Src\Agenda\Company\Infrastructure\EloquentModels\CompanyEloquentModel;

class CompanyWithMainAddressData
{
    public function __construct(
        public readonly int $id,
        public readonly FiscalName $fiscal_name,
        public readonly SocialName $social_name,
        public readonly Vat $vat,
        public readonly ?Address $main_address,
        public readonly int $num_contacts,
        public readonly int $num_departments,
        public readonly bool $is_active,
    )
    {}

    public static function fromEloquent(CompanyEloquentModel $companyEloquent): self
    {
        return new self(
            id: $companyEloquent->id,
            fiscal_name: new FiscalName($companyEloquent->fiscal_name),
            social_name: new SocialName($companyEloquent->social_name),
            vat: new Vat($companyEloquent->vat),
            main_address: $companyEloquent->main_address ? AddressMapper::fromEloquent($companyEloquent->main_address) : null,
            num_contacts: $companyEloquent->contacts->count(),
            num_departments: $companyEloquent->departments->count(),
            is_active: $companyEloquent->is_active,
        );
    }
}
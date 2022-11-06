<?php

namespace Src\Agenda\Company\Application\DTO;

use Illuminate\Http\Request;
use Src\Agenda\Company\Application\Mappers\AddressMapper;
use Src\Agenda\Company\Domain\Model\Entities\Address;
use Src\Agenda\Company\Domain\Model\ValueObjects\FiscalName;
use Src\Agenda\Company\Domain\Model\ValueObjects\SocialName;
use Src\Agenda\Company\Domain\Model\ValueObjects\Vat;
use Src\Agenda\Company\Infrastructure\EloquentModels\CompanyEloquentModel;

class CompanyData
{
    public function __construct(
        public readonly int $id,
        public readonly FiscalName $fiscal_name,
        public readonly SocialName $social_name,
        public readonly Address $main_address,
        public readonly Vat $vat,
        public readonly bool $is_active,
    )
    {}

    public static function fromRequest(Request $request, ?int $company_id = null): CompanyData
    {
        return new self(
            id: $company_id,
            fiscal_name: new FiscalName($request->string('fiscal_name')),
            social_name: new SocialName($request->string('social_name')),
            main_address: AddressMapper::fromArray($request->input('main_address', [])),
            vat: new Vat($request->string('vat')),
            is_active: $request->boolean('is_active', true),
        );
    }

    public static function fromEloquent(CompanyEloquentModel $companyEloquent): self
    {
        return new self(
            id: $companyEloquent->id,
            fiscal_name: new FiscalName($companyEloquent->fiscal_name),
            social_name: new SocialName($companyEloquent->social_name),
            main_address: AddressMapper::fromEloquent($companyEloquent->main_address),
            vat: new Vat($companyEloquent->vat),
            is_active: $companyEloquent->is_active,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fiscal_name' => $this->fiscal_name,
            'social_name' => $this->social_name,
            'main_address' => $this->main_address,
            'vat' => $this->vat,
            'is_active' => $this->is_active,
        ];
    }
}
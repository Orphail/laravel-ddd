<?php

namespace Src\Agenda\Company\Application\DTO;

use Illuminate\Http\Request;
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
        public readonly Vat $vat,
        public readonly bool $is_active,
    )
    {}

    public static function fromRequest(Request $request, ?int $company_id = null): CompanyData
    {
        return new self(
            id: $company_id,
            fiscal_name: new FiscalName($request->get('fiscal_name')),
            social_name: new SocialName($request->get('social_name')),
            vat: new Vat($request->get('vat')),
            is_active: $request->get('is_active'),
        );
    }

    public static function fromEloquent(CompanyEloquentModel $companyEloquent): self
    {
        return new self(
            id: $companyEloquent->id,
            fiscal_name: new FiscalName($companyEloquent->fiscal_name),
            social_name: new SocialName($companyEloquent->social_name),
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
            'vat' => $this->vat,
            'is_active' => $this->is_active,
        ];
    }
}
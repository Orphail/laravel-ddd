<?php

namespace Src\Agenda\Company\Domain\Model\ValueObjects;

enum AddressType: string
{
    case Fiscal = 'fiscal';
    case Logistic = 'logistic';
    case Administrative = 'administrative';
}

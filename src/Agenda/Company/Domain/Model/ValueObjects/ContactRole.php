<?php

namespace Src\Agenda\Company\Domain\Model\ValueObjects;

enum ContactRole: string
{
    case Commercial = 'commercial';
    case Logistic = 'logistic';
    case Administrative = 'administrative';
}

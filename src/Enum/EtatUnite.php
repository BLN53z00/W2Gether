<?php

namespace App\Enum;

enum EtatUnite: string
{
    case OK = 'ok';
    case INCIDENT = 'incident';
    case MAINTENANCE = 'maintenance';
}

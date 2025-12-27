<?php

namespace App\Enums;

enum ContractStatus
{
    case created;
    case sent;
    case viewed;
    case in_progress;
    case signed;
    case completed;
    case expired;
    case canceled;
    case declined;
}

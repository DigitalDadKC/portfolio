<?php

namespace App\Enums;

use JsonSerializable;

enum ContractStatus implements JsonSerializable
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

    public function jsonSerialize(): mixed {
        return $this->name;
    }
}

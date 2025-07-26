<?php

namespace App\Enums;

enum ProposalTypeEnum: String
{
    case BASE = "Base";
    case ALT = "Alternate";
    case CO = 'Change Order';
}

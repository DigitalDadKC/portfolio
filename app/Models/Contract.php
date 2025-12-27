<?php

namespace App\Models;

use App\Enums\ContractStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contract extends Model
{
    use HasFactory;
    protected function casts(): array {
        return [
            'status' => ContractStatus::class
        ];
    }
    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }
    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class);
    }
    public function services(): BelongsToMany {
        return $this->belongsToMany(Service::class);
    }
}

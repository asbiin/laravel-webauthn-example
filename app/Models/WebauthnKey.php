<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaravelWebauthn\Models\WebauthnKey as BaseWebauthnKey;

class WebauthnKey extends BaseWebauthnKey
{
    /**
     * Get the user record associated with the key.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

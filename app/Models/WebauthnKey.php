<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaravelWebauthn\Models\WebauthnKey as BaseWebauthnKey;

class WebauthnKey extends BaseWebauthnKey
{
    public function __construct()
    {
        parent::__construct();
        $this->mergeFillable(['used_at']);
        $this->setVisible(array_merge($this->getVisible(), ['used_at']));
        $this->mergeCasts(['used_at' => 'datetime']);
    }

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

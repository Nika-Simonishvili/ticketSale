<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property ?Collection $tickets
 * @property Booking $booking
 * @property Event $event
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}

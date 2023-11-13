<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $assistant_remote_id
 * @property int $action_id
 */
class Assistant extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'assistant_remote_id'
    ];

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function latestThread(): HasOne
    {
        return $this->hasOne(Thread::class)->latestOfMany();
    }

    /**
     * @param array $params
     * @return void
     */
    public function create(array $params): void
    {
        $api = new OpenAI();
        $assistant = $api->assistant()->assistant()->create($params);
        $this->action_id = 0;
        $this->assistant_remote_id = $assistant['id'];
        $this->save();
    }
}

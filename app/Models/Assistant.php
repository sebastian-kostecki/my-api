<?php

namespace App\Models;

use App\Lib\Apis\OpenAI;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function connect()
    {
        dd($this->action);



        if ($this->assistant_remote_id) {
            return $api->assistant()->assistant()->retrieve($this->assistant_remote_id);
        }

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

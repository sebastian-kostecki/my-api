<?php

namespace App\Jobs;

use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Qdrant;
use App\Models\Note;
use App\Models\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $lines;
    protected string $title;
    protected OpenAI $openAI;

    /**
     * Create a new job instance.
     */
    public function __construct(string $title, array $lines)
    {
        $this->title = $title;
        $this->lines = $lines;
        $this->openAI = new OpenAI();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::channel('jobs')->info('Job ' . __CLASS__ . ' started');

            foreach ($this->lines as $line) if (trim($line, ".,:") || str_word_count($line) > 1) {
                $language = detectLanguage($line);
                if ($language !== 'pl') {
                    $line = $this->openAI->translateToPolish($line);
                }
                $tags = json_decode($this->openAI->generateTags($line));

                $resource = new Resource();
                $resource->title = $this->title;
                $resource->content = $line;
                $resource->category = 'knowledge';
                $resource->tags = $tags->tags;
                $resource->save();

                $text = $resource->content . " " . implode(',', $resource->tags);
                $embedding = $this->openAI->createEmbedding($text);

                $vectorDatabase = new Qdrant('test');
                $vectorDatabase->insertVector($resource->id, $embedding, [
                    'id' => $resource->id,
                    'tags' => implode(',', $resource->tags)
                ]);
            }
            Log::channel('jobs')->info('Job ' . __CLASS__ . ' finished');
        } catch (\Exception $e) {
            Log::channel('jobs')->error($e->getMessage());
        }
    }
}

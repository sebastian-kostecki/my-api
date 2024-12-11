<?php

namespace App\Lib\Connections\GitLab;

use Illuminate\Support\Str;
use League\CommonMark\CommonMarkConverter;

class Issue
{
    private int $id;

    private int $iid;

    private string $title;

    private string $description;

    private string $state;

    private string $created_at;

    private string $updated_at;

    private ?string $closed_at;

    private ?array $closed_by;

    private array $labels;

    private ?array $milestone;

    private array $assignees;

    private array $author;

    private string $type;

    private ?array $assignee;

    private string $web_url;

    public function __construct(array $issue)
    {
        $this->id = $issue['id'];
        $this->iid = $issue['iid'];
        $this->title = $issue['title'];
        $this->description = $issue['description'];
        $this->state = $issue['state'];
        $this->created_at = $issue['created_at'];
        $this->updated_at = $issue['updated_at'];
        $this->closed_at = $issue['closed_at'];
        $this->closed_by = $issue['closed_by'];
        $this->labels = $issue['labels'];
        $this->milestone = $issue['milestone'];
        $this->assignees = $issue['assignees'];
        $this->author = $issue['author'];
        $this->type = $issue['type'];
        $this->assignee = $issue['assignee'];
        $this->web_url = $issue['web_url'];
    }

    public function getId(): int
    {
        return $this->iid;
    }

    public function getName(): string
    {
        return "#{$this->iid} {$this->title}";
    }

    public function getMilestone(): ?string
    {
        if ($this->milestone === null) {
            return null;
        }

        $title = $this->milestone['title'];
        $parts = explode(' ', $title);

        $milestone = trim($parts[0], 'v');

        if (preg_match('/^[0-9.]+$/', $milestone)) {
            return $milestone;
        }

        return 'Later';
    }

    public function getPriority(): string
    {
        $priority = 'Medium';
        foreach ($this->labels as $label) {
            $label = Str::lower($label);
            if (Str::startsWith($label, 'priority: ')) {
                $value = Str::after($label, 'priority: ');
                if (in_array($value, ['low', 'medium', 'high', 'critical'])) {
                    $priority = trim(Str::ucfirst($value));
                    break;
                }
            }
        }

        return $priority;
    }

    public function getLabels(): array
    {
        $labels = [];
        foreach ($this->labels as $label) {
            if (Str::startsWith($label, 'Priority: ')) {
                continue;
            }
            if (Str::startsWith($label, 'Type: ')) {
                $labels[] = Str::after($label, 'Type: ');
            }
        }

        return $labels;
    }

    public function getUrl(): string
    {
        return $this->web_url;
    }

    public function getDescription(): string
    {
        // remove markdown
        $converter = new CommonMarkConverter;
        $html = $converter->convert($this->description);
        $clearText = strip_tags($html);

        // short to 2000 characters
        if (mb_strlen($clearText) <= 2000) {
            return $clearText;
        }

        $fragment = mb_substr($clearText, 0, 2000);
        $lastSpace = mb_strrpos($fragment, ' ');

        if ($lastSpace !== false) {
            $fragment = mb_substr($fragment, 0, $lastSpace);
        } else {
            return $fragment;
        }

        return rtrim($fragment, '.,;:!?');
    }

    public function getAssigneeUsername(): ?string
    {
        if ($this->assignee === null) {
            return null;
        }

        return $this->assignee['username'];
    }

    public function getStatus(): ?string
    {
        if ($this->state === 'closed') {
            return 'Done';
        }

        return null;
    }
}

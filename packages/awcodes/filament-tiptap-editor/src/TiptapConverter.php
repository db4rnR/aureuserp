<?php

declare(strict_types=1);

namespace FilamentTiptapEditor;

use FilamentTiptapEditor\Extensions\Extensions\ClassExtension;
use FilamentTiptapEditor\Extensions\Extensions\Color;
use FilamentTiptapEditor\Extensions\Extensions\IdExtension;
use FilamentTiptapEditor\Extensions\Extensions\StyleExtension;
use FilamentTiptapEditor\Extensions\Extensions\TextAlign;
use FilamentTiptapEditor\Extensions\Marks\Link;
use FilamentTiptapEditor\Extensions\Marks\Small;
use FilamentTiptapEditor\Extensions\Nodes\CheckedList;
use FilamentTiptapEditor\Extensions\Nodes\Details;
use FilamentTiptapEditor\Extensions\Nodes\DetailsContent;
use FilamentTiptapEditor\Extensions\Nodes\DetailsSummary;
use FilamentTiptapEditor\Extensions\Nodes\Grid;
use FilamentTiptapEditor\Extensions\Nodes\GridBuilder;
use FilamentTiptapEditor\Extensions\Nodes\GridBuilderColumn;
use FilamentTiptapEditor\Extensions\Nodes\GridColumn;
use FilamentTiptapEditor\Extensions\Nodes\Hurdle;
use FilamentTiptapEditor\Extensions\Nodes\Image;
use FilamentTiptapEditor\Extensions\Nodes\Lead;
use FilamentTiptapEditor\Extensions\Nodes\ListItem;
use FilamentTiptapEditor\Extensions\Nodes\Mention;
use FilamentTiptapEditor\Extensions\Nodes\MergeTag;
use FilamentTiptapEditor\Extensions\Nodes\TiptapBlock;
use FilamentTiptapEditor\Extensions\Nodes\Video;
use FilamentTiptapEditor\Extensions\Nodes\Vimeo;
use FilamentTiptapEditor\Extensions\Nodes\YouTube;
use Tiptap\Editor;
use Tiptap\Extensions\StarterKit;
use Tiptap\Marks\Highlight;
use Tiptap\Marks\Subscript;
use Tiptap\Marks\Superscript;
use Tiptap\Marks\TextStyle;
use Tiptap\Marks\Underline;
use Tiptap\Nodes\CodeBlockHighlight;
use Tiptap\Nodes\Table;
use Tiptap\Nodes\TableCell;
use Tiptap\Nodes\TableHeader;
use Tiptap\Nodes\TableRow;

final class TiptapConverter
{
    private Editor $editor;

    private ?array $blocks = null;

    private bool $tableOfContents = false;

    private array $mergeTagsMap = [];

    public function getEditor(): Editor
    {
        return $this->editor ??= new Editor([
            'extensions' => $this->getExtensions(),
        ]);
    }

    public function blocks(array $blocks): static
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getExtensions(): array
    {
        $customExtensions = collect(config('filament-tiptap-editor.extensions', []))
            ->filter(function ($ext) {
                return $ext['parser'] ?? false;
            })
            ->transform(function ($ext) {
                return new $ext['parser'];
            })->toArray();

        return [
            new StarterKit([
                'listItem' => false,
            ]),
            new TextStyle,
            new TextAlign([
                'types' => ['heading', 'paragraph'],
            ]),
            new ClassExtension,
            new IdExtension,
            new StyleExtension,
            new Color,
            new CodeBlockHighlight,
            new ListItem,
            new Lead,
            new Image,
            new CheckedList,
            new Details,
            new DetailsSummary,
            new DetailsContent,
            new Grid,
            new GridColumn,
            new GridBuilder,
            new GridBuilderColumn,
            new MergeTag,
            new Vimeo,
            new YouTube,
            new Video,
            new TiptapBlock(['blocks' => $this->blocks]),
            new Hurdle,
            new Mention,
            new Table,
            new TableHeader,
            new TableRow,
            new TableCell,
            new Highlight,
            new Underline,
            new Superscript,
            new Subscript,
            new Link([
                'protocols' => config('filament-tiptap-editor.link_protocols', []),
            ]),
            new Small,
            ...$customExtensions,
        ];
    }

    public function mergeTagsMap(array $mergeTagsMap): static
    {
        $this->mergeTagsMap = $mergeTagsMap;

        return $this;
    }

    public function asHTML(string|array|null $content, bool $toc = false, int $maxDepth = 3): string
    {
        if (! $content) {
            return '';
        }

        $editor = $this->getEditor()->setContent($content);

        if ($toc) {
            $this->parseHeadings($editor, $maxDepth);
        }

        if (filled($this->mergeTagsMap)) {
            $this->parseMergeTags($editor);
        }

        /*
         * Temporary fix for Tiptap Serializer bug duplicating code block tags
         */
        return str_replace('</code></pre></code></pre>', '</code></pre>', $editor->getHTML());
    }

    public function asJSON(string|array|null $content, bool $decoded = false, bool $toc = false, int $maxDepth = 3): string|array
    {
        if (! $content) {
            return '';
        }

        $editor = $this->getEditor()->setContent($content);

        if ($toc) {
            $this->parseHeadings($editor, $maxDepth);
        }

        if (filled($this->mergeTagsMap)) {
            $this->parseMergeTags($editor);
        }

        return $decoded ? json_decode($editor->getJSON(), true) : $editor->getJSON();
    }

    public function asText(string|array|null $content): string
    {
        if (! $content) {
            return '';
        }

        $editor = $this->getEditor()->setContent($content);

        if (filled($this->mergeTagsMap)) {
            $this->parseMergeTags($editor);
        }

        $this->parseMentionItems($editor);

        return $editor->getText();
    }

    public function asTOC(string|array|null $content, int $maxDepth = 3, bool $array = false): string|array
    {
        if (! $content) {
            return '';
        }

        if (is_string($content)) {
            $content = $this->asJSON($content, decoded: true);
        }

        $headings = $this->parseTocHeadings($content['content'], $maxDepth);

        if (empty($headings)) {
            return $array ? [] : '';
        }

        return $array ?
            $this->generateTOCArray($headings) :
            $this->generateNestedTOC($headings, $headings[0]['level']);
    }

    public function parseHeadings(Editor $editor, int $maxDepth = 3): Editor
    {
        $editor->descendants(function (&$node) use ($maxDepth): void {
            if ($node->type !== 'heading') {
                return;
            }

            if ($node->attrs->level > $maxDepth) {
                return;
            }

            if (! property_exists($node->attrs, 'id') || $node->attrs->id === null) {
                $node->attrs->id = str(collect($node->content)->map(function ($node) {
                    return $node?->text ?? null;
                })->implode(' '))->slug()->toString();
            }

            array_unshift($node->content, (object) [
                'type' => 'text',
                'text' => '#',
                'marks' => [
                    [
                        'type' => 'link',
                        'attrs' => [
                            'href' => '#'.$node->attrs->id,
                        ],
                    ],
                ],
            ]);
        });

        return $editor;
    }

    public function parseTocHeadings(array $content, int $maxDepth = 3): array
    {
        $headings = [];

        foreach ($content as $node) {
            if ($node['type'] === 'heading') {
                if ($node['attrs']['level'] <= $maxDepth) {
                    $text = collect($node['content'])->map(function ($node) {
                        return $node['text'] ?? null;
                    })->implode(' ');

                    if (! isset($node['attrs']['id'])) {
                        $node['attrs']['id'] = str($text)->slug()->toString();
                    }

                    $headings[] = [
                        'level' => $node['attrs']['level'],
                        'id' => $node['attrs']['id'],
                        'text' => $text,
                    ];
                }
            } elseif (array_key_exists('content', $content)) {
                $this->parseTocHeadings($content, $maxDepth);
            }
        }

        return $headings;
    }

    public function parseMergeTags(Editor $editor): Editor
    {
        $editor->descendants(function (&$node): void {
            if ($node->type !== 'mergeTag') {
                return;
            }

            if (filled($this->mergeTagsMap)) {
                $node->content = [
                    (object) [
                        'type' => 'text',
                        'text' => $this->mergeTagsMap[$node->attrs->id] ?? null,
                    ],
                ];
            }
        });

        return $editor;
    }

    public function parseMentionItems(Editor $editor): Editor
    {
        $editor->descendants(function (&$node): void {
            if ($node->type !== 'mention') {
                return;
            }

            $node->content = [
                (object) [
                    'type' => 'text',
                    'text' => $node->attrs->label ?? $node->attrs->id,
                ],
            ];
        });

        return $editor;
    }

    public function generateTOCArray(array &$headings, int $parentLevel = 0): array
    {

        $result = [];

        foreach ($headings as $key => &$value) {
            $currentLevel = $value['level'];
            $nextLevel = $headings[$key + 1]['level'] ?? 0;

            if ($parentLevel >= $currentLevel) {
                break;
            }

            unset($headings[$key]);

            $heading = [
                'id' => $value['id'],
                'text' => $value['text'],
                'depth' => $currentLevel,
            ];

            if ($nextLevel > $currentLevel) {
                $heading['subs'] = $this->generateTOCArray($headings, $currentLevel);
            }

            $result[] = $heading;

        }

        return $result;

    }

    public function generateNestedTOC(array $headings, int $parentLevel = 0): string
    {
        $result = '<ul>';
        $prev = $parentLevel;

        foreach ($headings as $item) {
            $prev <= $item['level'] ?: $result .= str_repeat('</ul>', $prev - $item['level']);
            $prev >= $item['level'] ?: $result .= '<ul>';

            $result .= '<li><a href="#'.$item['id'].'">'.$item['text'].'</a></li>';

            $prev = $item['level'];
        }

        $result .= '</ul>';

        return $result;
    }
}

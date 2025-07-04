<?php

declare(strict_types=1);

namespace Awcodes\Curator\Actions;

use Awcodes\Curator\Components\Forms\Uploader;
use Awcodes\Curator\Models\Media;
use Filament\Actions\Action;
use Illuminate\Support\Facades\App;

class MultiUploadAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->button()
            ->color('gray')
            ->label(trans('curator::forms.multi_upload.action_label'))
            ->modalHeading(trans('curator::forms.multi_upload.modal_heading'))
            ->schema([
                Uploader::make('files')
                    ->acceptedFileTypes(config('curator.accepted_file_types'))
                    ->directory(config('curator.directory'))
                    ->disk(config('curator.disk'))
                    ->label(trans('curator::forms.multi_upload.modal_file_label'))
                    ->minSize(config('curator.min_size'))
                    ->maxSize(config('curator.max_size'))
                    ->multiple()
                    ->pathGenerator(config('curator.path_generator'))
                    ->preserveFilenames(config('curator.should_preserve_filenames'))
                    ->required()
                    ->visibility(config('curator.visibility'))
                    ->storeFileNamesIn('originalFilename'),
            ])
            ->action(function ($data): void {
                foreach ($data['files'] as $item) {
                    $item['title'] = pathinfo($data['originalFilename'][$item['path']] ?? null, PATHINFO_FILENAME);

                    tap(
                        App::make(Media::class)->create($item),
                        fn (Media $media) => $media->getPrettyName(),
                    )->toArray();
                }
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'curator_multi_upload';
    }
}

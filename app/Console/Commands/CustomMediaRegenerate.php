<?php

namespace App\Console\Commands;

use Error;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Conversions\FileManipulator;
use Spatie\MediaLibrary\MediaCollections\MediaRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomMediaRegenerate extends Command
{
    use ConfirmableTrait;

    protected $signature = 'custom-media-regenerate {modelType?} {--ids=*}
    {--only=* : Regenerate specific conversions}
    {--starting-from-id= : Regenerate media with an id equal to or higher than the provided value}
    {--X|exclude-starting-id : Exclude the provided id when regenerating from a specific id}
    {--only-missing : Regenerate only missing conversions}
    {--with-responsive-images : Regenerate responsive images}
    {--force : Force the operation to run when in production}
    {--debug : Print the media id for debug}';

    protected $description = 'Regenerate the derived images of media';

    protected MediaRepository $mediaRepository;

    protected FileManipulator $fileManipulator;

    protected array $errorMessages = [];

    public function handle(MediaRepository $mediaRepository, FileManipulator $fileManipulator)
    {
        $this->mediaRepository = $mediaRepository;

        $this->fileManipulator = $fileManipulator;

        if (! $this->confirmToProceed()) {
            return;
        }

        // $mediaFiles = $this->getMediaToBeRegenerated();
        $query = Media::where('is_regenerated', FALSE);
        $modelType = $this->argument('modelType') ?? '';
        if ($modelType !== '') {
            $query->where('model_type', $modelType);
        }
        $ids = $this->option('ids');
        if (!empty($ids)) {
            if (! is_array($ids)) {
                $ids = explode(',', $ids);
            }
            if (count($ids) === 1 && Str::contains($ids[0], ',')) {
                $ids = explode(',', $ids[0]);
            }
            $query->whereIn('id', $ids);
        }

        $progressBar = $this->output->createProgressBar($query->count());

        $query->get()->each(function (Media $media) use ($progressBar) {
            if($this->option('debug')) {
                $this->warn("Media id {$media->id}");
            }

            try {
                $this->fileManipulator->createDerivedFiles(
                    $media,
                    Arr::wrap($this->option('only')),
                    $this->option('only-missing'),
                    $this->option('with-responsive-images')
                );
                $media->is_regenerated = TRUE;
                $media->saveQuietly();
            } catch (Exception $exception) {
                $this->warn("ERROR Media id {$media->id}");
                $this->warn($exception->getMessage());
                $this->errorMessages[$media->getKey()] = $exception->getMessage();
            }

            $progressBar->advance();
        });

        $progressBar->finish();

        if (count($this->errorMessages)) {
            $this->warn('All done, but with some error messages:');

            foreach ($this->errorMessages as $mediaId => $message) {
                $this->warn("Media id {$mediaId}: `{$message}`");
            }
        }

        $this->info('All done!');
    }

    public function getMediaToBeRegenerated(): Collection
    {
        // Get this arg first as it can also be passed to the greater-than-id branch
        $modelType = $this->argument('modelType') ?? '';

        $startingFromId = (int)$this->option('starting-from-id');
        if ($startingFromId !== 0) {
            $excludeStartingId = $this->option('exclude-starting-id') ?? false;

            return $this->mediaRepository->getByIdGreaterThan($startingFromId, $excludeStartingId, $modelType);
        }

        if ($modelType !== '') {
            return $this->mediaRepository->getByModelType($modelType);
        }

        $mediaIds = $this->getMediaIds();
        if (count($mediaIds) > 0) {
            return $this->mediaRepository->getByIds($mediaIds);
        }

        return $this->mediaRepository->all();
    }

    protected function getMediaIds(): array
    {
        $mediaIds = $this->option('ids');

        if (! is_array($mediaIds)) {
            $mediaIds = explode(',', $mediaIds);
        }

        if (count($mediaIds) === 1 && Str::contains($mediaIds[0], ',')) {
            $mediaIds = explode(',', $mediaIds[0]);
        }

        return $mediaIds;
    }
}

<?php

namespace Homeful\Prospects\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{Str};
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Prospect
 *
 * @property int $id
 * @property string $reference_code
 * @property string $name
 * @property string $address
 * @property string $birthdate
 * @property string $email
 * @property string $mobile
 * @property string $id_type
 * @property string $id_number
 * @property array $media
 * @property Media $idImage
 * @property Media $selfieImage
 * @property string $idMarkImage
 *
 * @method int getKey()
 */
class Prospect extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'reference_code',
        'first_name',
        'middle_name',
        'last_name',
        'name_extension',
        'address',
        'birthdate',
        'email',
        'mobile',
        'id_type',
        'id_number',
        'idImage',
        'selfieImage',
        'idMarkImage',
    ];

    protected array $dates = [
        'birthdate',
    ];

    protected static function newFactory()
    {
        $modelName = static::class;
        $path = 'Homeful\\Prospects\\Database\\Factories\\'.class_basename($modelName).'Factory';

        return app($path)->new();
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setIdImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('idImage')
                ->toMediaCollection('id-images');
        }

        return $this;
    }

    public function getIdImageAttribute(): ?Media
    {
        return $this->getFirstMedia('id-images');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setSelfieImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('selfieImage')
                ->toMediaCollection('selfie-images');
        }

        return $this;
    }

    public function getSelfieImageAttribute(): ?Media
    {
        return $this->getFirstMedia('selfie-images');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setIdMarkImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('idMarkImage')
                ->toMediaCollection('id_mark-images');
        }

        return $this;
    }

    public function getIdMarkImageAttribute(): ?Media
    {
        return $this->getFirstMedia('id_mark-images');
    }

    public function registerMediaCollections(): void
    {
        $collections = [
            'id-images' => ['image/jpeg', 'image/png', 'image/webp'],
            'selfie-images' => ['image/jpeg', 'image/png', 'image/webp'],
            'id_mark-images' => ['image/jpeg', 'image/png', 'image/webp'],
        ];

        foreach ($collections as $collection => $mimeTypes) {
            $this->addMediaCollection($collection)
                ->singleFile()
                ->acceptsFile(function (File $file) use ($mimeTypes) {
                    return in_array(
                        needle: $file->mimeType,
                        haystack: (array) $mimeTypes
                    );
                });
        }
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function getUploadsAttribute(): array
    {
        return collect($this->media)
            ->mapWithKeys(function ($item, $key) {
                $collection_name = $item['collection_name'];
                $name = Str::camel(Str::singular($collection_name));
                $url = $item['original_url'];

                return [
                    $key => [
                        'name' => $name,
                        'url' => $url,
                    ],
                ];
            })
            ->toArray();
    }
}

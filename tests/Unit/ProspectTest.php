<?php

use Homeful\Prospects\Data\ProspectData;
use Homeful\Prospects\Model\Prospect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->faker = $this->makeFaker('en_PH');
    $migration = include 'vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub';
    $migration->up();
});

dataset('prospect', function () {
    return [
        [
            fn () => Prospect::factory()->create([
                'idImage' => 'https://jn-img.enclaves.ph/Test/idImage.jpg',
                'selfieImage' => 'https://jn-img.enclaves.ph/Test/selfieImage.jpg',
                'idMarkImage' => 'https://jn-img.enclaves.ph/Test/payslipImage.jpg',
            ]),
        ],
    ];
});

test('prospect has schema attributes', function (Prospect $prospect) {
    expect($prospect->name)->toBeString();
    expect($prospect->address)->toBeString();
    expect($prospect->birthdate)->toBeString();
    expect($prospect->email)->toBeString();
    expect($prospect->mobile)->toBeString();
    expect($prospect->id_type)->toBeString();
    expect($prospect->id_number)->toBeString();

    expect($prospect->idImage)->toBeInstanceOf(Media::class);
    expect($prospect->selfieImage)->toBeInstanceOf(Media::class);
    expect($prospect->idMarkImage)->toBeInstanceOf(Media::class);
    //    expect($prospect->reference_code)->toBeString();
})->with('prospect');

test('prospect can attach media', function () {
    $idImageUrl = 'https://jn-img.enclaves.ph/Test/idImage.jpg';
    $selfieImageUrl = 'https://jn-img.enclaves.ph/Test/selfieImage.jpg';
    $idMarkImageUrl = 'https://jn-img.enclaves.ph/Test/payslipImage.jpg';
    $prospect = Prospect::factory()->create([
        'idImage' => null,
        'selfieImage' => null,
        'idMarkImage' => null,
    ]);
    $prospect->idImage = $idImageUrl;
    $prospect->selfieImage = $selfieImageUrl;
    $prospect->idMarkImage = $idMarkImageUrl;
    $prospect->save();
    expect($prospect->idImage)->toBeInstanceOf(Media::class);
    expect($prospect->selfieImage)->toBeInstanceOf(Media::class);
    expect($prospect->idMarkImage)->toBeInstanceOf(Media::class);
    expect($prospect->idImage->name)->toBe('idImage');
    expect($prospect->selfieImage->name)->toBe('selfieImage');
    expect($prospect->idMarkImage->name)->toBe('idMarkImage');
    expect($prospect->idImage->file_name)->toBe('idImage.jpg');
    expect($prospect->selfieImage->file_name)->toBe('selfieImage.jpg');
    expect($prospect->idMarkImage->file_name)->toBe('payslipImage.jpg');

    tap(config('app.url'), function ($host) use ($prospect) {
        expect($prospect->idImage->getUrl())->toBe(__(':host/storage/:path', ['host' => $host, 'path' => $prospect->idImage->getPathRelativeToRoot()]));
        expect($prospect->selfieImage->getUrl())->toBe(__(':host/storage/:path', ['host' => $host, 'path' => $prospect->selfieImage->getPathRelativeToRoot()]));
        expect($prospect->idMarkImage->getUrl())->toBe(__(':host/storage/:path', ['host' => $host, 'path' => $prospect->idMarkImage->getPathRelativeToRoot()]));
    });
    $prospect->idImage->delete();
    $prospect->selfieImage->delete();
    $prospect->idMarkImage->delete();
    $prospect->clearMediaCollection('id-images');
    $prospect->clearMediaCollection('selfie-images');
    $prospect->clearMediaCollection('id_mark-images');
});

test('prospect has data', function (Prospect $prospect) {
    $data = ProspectData::fromModel($prospect);

    expect($data->name)->toBe($prospect->name);
    expect($data->address)->toBe($prospect->address);
    expect($data->birthdate)->toBe($prospect->birthdate);
    expect($data->email)->toBe($prospect->email);
    expect($data->mobile)->toBe($prospect->mobile);
    expect($data->id_type)->toBe($prospect->id_type);
    expect($data->id_number)->toBe($prospect->id_number);

    foreach (array_filter($data->uploads->toArray()) as $upload) {
        $name = $upload['name'];
        $url = $upload['url'];
        expect($prospect->$name->getUrl())->toBe($url);
    }
    expect($data->uploads->toArray())->toBe($prospect->uploads);
})->with('prospect');

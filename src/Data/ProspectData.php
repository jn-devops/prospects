<?php

namespace Homeful\Prospects\Data;

use Homeful\Prospects\Model\Prospect;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;

class ProspectData extends Data
{
    public function __construct(
        public string $name,
        public string $address,
        public string $birthdate,
        public string $email,
        public ?string $mobile,
        public string $id_type,
        public string $id_number,
        /** @var UploadData[] */
        public DataCollection|Optional $uploads,
    ) {
    }

    public static function fromModel(Prospect $model): self
    {
        return new self(
            name: $model->name,
            address: $model->address,
            birthdate: $model->birthdate,
            email: $model->email,
            mobile: $model->mobile,
            id_type: $model->id_type,
            id_number: $model->id_number,
            uploads: new DataCollection(UploadData::class, $model->uploads),
        );
    }
}

class UploadData extends Data
{
    public function __construct(
        public string $name,
        public string $url
    ) {
    }
}

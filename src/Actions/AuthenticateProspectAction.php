<?php

namespace Homeful\Prospects\Actions;

use Homeful\Prospects\Events\ProspectAuthenticated;
use Homeful\Prospects\Model\Prospect;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class AuthenticateProspectAction
{
    use AsAction;

    protected string $reference_code;

    public function handle(array $attributes): ?Prospect
    {
        $validated = Validator::validate($attributes, $this->rules());
        $prospect = $this->createProspect($validated);
        ProspectAuthenticated::dispatch($prospect);

        return $prospect;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'array'],
            'body.campaign.agent' => ['required', 'array'],
            'body.campaign.agent.name' => ['required', 'string'],
            'body.campaign.agent.email' => ['required', 'email'],
            'body.campaign.agent.mobile' => ['nullable', 'string'],
            'body.inputs' => ['required', 'array'],
            'body.inputs.code' => ['required', 'string'],
            'body.inputs.email' => ['required', 'email'],
            'body.inputs.mobile' => ['required', 'string', 'min:11'],
            'body.data' => ['required', 'array'],
            'body.data.idType' => ['required', 'string'],
            'body.data.fieldsExtracted' => ['required', 'array'],
            'body.data.fieldsExtracted.fullName' => ['required', 'string'],
            'body.data.fieldsExtracted.dateOfBirth' => ['required', 'date'],
            'body.data.fieldsExtracted.address' => ['required', 'string'],
            'body.data.fieldsExtracted.idNumber' => ['required', 'string'],
            'body.data.idImageUrl' => ['required', 'string'],
            'body.data.selfieImageUrl' => ['required', 'string'],
        ];
    }

    public function createProspect(array $validated): Prospect
    {
        $fieldsExtracted = Arr::get($validated, 'body.data.fieldsExtracted');
        $code = Arr::get($validated, 'body.inputs.code');
        $email = Arr::get($validated, 'body.inputs.email');
        $mobile = Arr::get($validated, 'body.inputs.mobile');
        $idType = Arr::get($validated, 'body.data.idType');
        $idImageUrl = Arr::get($validated, 'body.data.idImageUrl');
        $selfieImageUrl = Arr::get($validated, 'body.data.selfieImageUrl');

        $prospect = app(Prospect::class)->create([
            'first_name' => Arr::get($fieldsExtracted, 'first_name'),
            'last_name' => Arr::get($fieldsExtracted, 'last_name'),
            'name_extension' => Arr::get($fieldsExtracted, 'name_extension'),
            'middle_name' => Arr::get($fieldsExtracted, 'middle_name'),
            'address' => Arr::get($fieldsExtracted, 'address'),
            'birthdate' => Arr::get($fieldsExtracted, 'dateOfBirth'),
            'email' => $email,
            'mobile' => $mobile,
            'reference_code' => $code,
            'id_type' => $idType,
            'id_number' => Arr::get($fieldsExtracted, 'idNumber'),
        ]);

        //        $prospect->idImage = $idImageUrl;
        //        $prospect->selfieImage = $selfieImageUrl;
        //        $prospect->save();

        return $prospect;
    }
}

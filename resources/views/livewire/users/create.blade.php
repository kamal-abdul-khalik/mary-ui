<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use App\Models\Country;
use App\Models\User;
use App\Models\Language;

new class extends Component {
    use Toast;
    use WithFileUploads;

    #[Rule('nullable|image|max:1024')]
    public $photo;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255|unique:users')]
    public string $email = '';

    #[Rule('required|exists:countries,id')]
    public ?int $country_id = null;
    public ?string $bio = null;

    #[Rule('required|array|exists:languages,id')]
    public array $my_languages = [];

    public function save(): void
    {
        $data = $this->validate();
        $data['avatar'] = $this->photo ? $this->photo->store('users', 'public') : null;
        $data['password'] = Hash::make('password');
        // $data['username'] = random_int(1000, 9999) . explode(' ', trim($data['name']))[0];
        User::create($data)
            ->languages()
            ->sync($this->my_languages);

        $this->success('User updated with success.', redirectTo: '/users');
    }

    public function with(): array
    {
        return [
            'countries' => Country::all(),
            'languages' => Language::all(),
        ];
    }
}; ?>

<div>
    <x-header title="Create User" separator />
    <div>
        <x-form wire:submit="save">
            <div class="lg:grid grid-cols-5">
                <div class="col-span-2">
                    <x-header title="Basic" subtitle="Basic info from user" size="text-2xl" />
                </div>
                <div class="col-span-3 grid gap-3">
                    <x-file label="Avatar" wire:model="photo" accept="image/png, image/jpeg" crop-after-change>
                        <img src="/empty-user.jpg" class="h-40 rounded-lg" />
                    </x-file>
                    <x-input label="Name" wire:model.lazy="name" />
                    <x-input label="Email" wire:model.lazy="email" />
                </div>
            </div>

            <hr class="my-5" />

            <div class="lg:grid grid-cols-5">
                <div class="col-span-2">
                    <x-header title="Details" subtitle="More about the user" size="text-2xl" />
                </div>
                <div class="col-span-3 grid gap-3">
                    <x-choices label="Single" wire:model.lazy="country_id" :options="$countries" placeholder="Choose one"
                        single />
                    <x-choices-offline label="My languages" wire:model.lazy="my_languages" :options="$languages"
                        searchable />
                    <x-textarea label="Bio" wire:model='bio'></x-textarea>
                </div>
            </div>
            <x-slot:actions>
                <x-button label="Cancel" link="/users" />
                <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </div>
</div>

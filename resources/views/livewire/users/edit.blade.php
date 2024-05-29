<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Language;
use Mary\Traits\Toast;
use App\Models\Country;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

new class extends Component {
    use Toast;
    use WithFileUploads;

    public User $user;

    #[Rule('nullable|image|max:1024')]
    public $photo;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|exists:countries,id')]
    public ?int $country_id = null;
    public ?string $bio = null;

    #[Rule('required')]
    public array $my_languages = [];

    public function mount(): void
    {
        $this->fill($this->user);
        $this->my_languages = $this->user->languages->pluck('id')->all();
    }

    public function save(): void
    {
        $data = $this->validate();
        $this->user->update($data);
        $this->user->languages()->sync($this->my_languages);
        if ($this->photo) {
            $url = $this->photo->store('users', 'public');
            $this->user->update(['avatar' => "/storage/$url"]);
        }
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
    <x-header title="Update {{ $user->name }}" separator />
    <div>
        <x-form wire:submit="save">
            <div class="lg:grid grid-cols-5">
                <div class="col-span-2">
                    <x-header title="Basic" subtitle="Basic info from user" size="text-2xl" />
                </div>
                <div class="col-span-3 grid gap-3">
                    <x-file label="Avatar" wire:model="photo" accept="image/png, image/jpeg" crop-after-change>
                        <img src="{{ $user->avatar ?? '/empty-user.jpg' }}" class="h-40 rounded-lg" />
                    </x-file>
                    <x-input label="Name" wire:model="name" />
                    <x-input label="Email" wire:model="email" />
                </div>
            </div>

            <hr class="my-5" />

            <div class="lg:grid grid-cols-5">
                <div class="col-span-2">
                    <x-header title="Details" subtitle="More about the user" size="text-2xl" />
                </div>
                <div class="col-span-3 grid gap-3">
                    <x-choices label="Single" wire:model="country_id" :options="$countries" placeholder="Choose one"
                        single />
                    <x-choices-offline label="My languages" wire:model="my_languages" :options="$languages" searchable />
                    <x-textarea label="Bio" wire:model='bio'></x-textarea>
                </div>
            </div>
            <x-slot:actions>
                <x-button label="Cancel" link="/users" />
                {{-- The important thing here is `type="submit"` --}}
                {{-- The spinner property is nice! --}}
                <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </div>


</div>

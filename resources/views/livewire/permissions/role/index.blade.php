<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

new class extends Component {
    use Toast;
    use WithPagination;

    public string $search = '';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function clear(): void
    {
        $this->reset();
        $this->resetPage();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    public function updated($property): void
    {
        if (!is_array($property) && $property != '') {
            $this->resetPage();
        }
    }

    // Table headers
    public function headers(): array
    {
        return [['key' => 'name', 'label' => 'Name', 'class' => 'w-64'], ['key' => 'guard_name', 'label' => 'Guard Name', 'class' => 'w-64'], ['key' => 'created_at', 'label' => 'Created At', 'class' => 'w-64']];
    }

    public function roles(): LengthAwarePaginator
    {
        return Role::query()
            ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(5);
    }

    public function with(): array
    {
        return [
            'roles' => $this->roles(),
            'headers' => $this->headers(),
        ];
    }
}; ?>

<div>
    <x-header title="Roles" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Create" link="{{ route('roles.create') }}" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="$headers" :rows="$roles" :sort-by="$sortBy" with-pagination link="/roles/{id}/edit">
        </x-table>
    </x-card>
</div>

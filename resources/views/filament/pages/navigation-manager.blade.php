<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">Save navigation mode</x-filament::button>
        </div>

        <x-filament::section class="mt-8">
            <x-slot name="heading">Category order (drag in Categories admin)</x-slot>
            <p class="text-sm text-gray-500 mb-4">
                Open <strong>Catalog → Categories</strong> and drag rows to reorder navbar items.
                Parent categories appear in the main nav; children appear as dropdown items (Option B) or as separate items (Option A if promoted).
            </p>
            <div class="space-y-4">
                @foreach($this->categories as $parent)
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <p class="font-semibold">{{ $parent->name }} <span class="text-xs text-gray-400">(order: {{ $parent->sort_order }})</span></p>
                        @if($parent->children->count())
                            <ul class="mt-2 ml-4 list-disc text-sm text-gray-600 dark:text-gray-400">
                                @foreach($parent->children as $child)
                                    <li>{{ $child->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                <x-filament::button tag="a" href="{{ \App\Filament\Resources\CategoryResource::getUrl('index') }}" color="gray">
                    Manage categories
                </x-filament::button>
            </div>
        </x-filament::section>
    </form>
</x-filament-panels::page>

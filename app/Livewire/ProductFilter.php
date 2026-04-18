<?php

namespace App\Livewire;

use App\Models\PriceBucket;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class ProductFilter extends Component
{
    use WithPagination;

    public ?int $categoryId = null;

    public bool $isNewArrivals = false;

    public $fabrics = [];

    public $priceBuckets = [];

    public $categories = [];

    public array $selectedCategories = [];

    public array $selectedFabrics = [];

    public ?int $selectedPriceBucket = null;

    public function mount($fabrics = [], $priceBuckets = [], $categories = [])
    {
        $this->fabrics = $fabrics instanceof Collection ? $fabrics->all() : $fabrics;
        $this->priceBuckets = $priceBuckets instanceof Collection ? $priceBuckets->all() : $priceBuckets;
        $this->categories = $categories instanceof Collection ? $categories->all() : $categories;
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatedSelectedFabrics()
    {
        $this->resetPage();
    }

    public function updatedSelectedPriceBucket()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::visible()
            ->withPrimaryCategory()
            ->with([
                'images' => fn ($q) => $q->orderBy('sort_order')->limit(1),
                'fabric',
            ]);

        if ($this->categoryId) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $this->categoryId)
            );
        }

        foreach ($this->selectedCategories as $catId) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $catId)
            );
        }

        if (! empty($this->selectedFabrics)) {
            $query->whereIn('fabric_id', $this->selectedFabrics);
        }

        if ($this->selectedPriceBucket) {
            $bucket = PriceBucket::find($this->selectedPriceBucket);
            if ($bucket) {
                $query->where('price', '>=', $bucket->min_price);
                if ($bucket->max_price !== null) {
                    $query->where('price', '<=', $bucket->max_price);
                }
            }
        }

        if ($this->isNewArrivals) {
            $count = (int) Setting::get('new_arrivals_count', 8);
            $products = $query->latest()->paginate($count);
        } else {
            $products = $query->latest()->paginate(20);
        }

        return view('livewire.product-filter', [
            'products' => $products,
        ]);
    }

    public function clearFilters(): void
    {
        $this->selectedCategories = [];
        $this->selectedFabrics = [];
        $this->selectedPriceBucket = null;
        $this->resetPage();
    }
}

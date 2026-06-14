<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Fabric;
use App\Models\PriceBucket;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class ProductFilter extends Component
{
    use WithPagination;

    public ?int $categoryId = null;

    public bool $isNewArrivals = false;

    public string $filterMode = 'multi';

    public string $pageTitle = '';

    public bool $dynamicTitle = false;

    public $fabrics = [];

    public $priceBuckets = [];

    public $collectionCategories = [];

    public $productTypeCategories = [];

    public array $selectedCollections = [];

    public array $selectedProductTypes = [];

    public array $selectedFabrics = [];

    public ?int $selectedPriceBucket = null;

    public bool $featuredOnly = false;

    public bool $showFeaturedFilter = true;

    public function mount(
        $fabrics = [],
        $priceBuckets = [],
        $categories = [],
        ?int $categoryId = null,
        bool $showFeaturedFilter = true,
        string $filterMode = 'multi',
        string $pageTitle = '',
        bool $dynamicTitle = false,
    ): void {
        $this->showFeaturedFilter = $showFeaturedFilter;
        $this->categoryId = $categoryId;
        $this->filterMode = $filterMode;
        $this->pageTitle = $pageTitle;
        $this->dynamicTitle = $dynamicTitle;
        $this->fabrics = $fabrics instanceof Collection ? $fabrics->all() : $fabrics;
        $this->priceBuckets = $priceBuckets instanceof Collection ? $priceBuckets->all() : $priceBuckets;

        $allCategories = $categories instanceof Collection ? $categories : collect($categories);

        $this->collectionCategories = $allCategories
            ->filter(fn ($c) => empty($c->parent_id))
            ->values()
            ->all();

        $this->productTypeCategories = $allCategories
            ->filter(fn ($c) => ! empty($c->parent_id))
            ->values()
            ->all();

        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                if ($category->parent_id) {
                    $this->selectedProductTypes = [$category->id];
                    $this->selectedCollections = [$category->parent_id];
                    $this->productTypeCategories = Category::query()
                        ->where('parent_id', $category->parent_id)
                        ->visible()
                        ->ordered()
                        ->get()
                        ->all();
                } else {
                    $this->selectedCollections = [$category->id];
                    $this->productTypeCategories = $category->children()
                        ->visible()
                        ->ordered()
                        ->get()
                        ->all();
                }
            }
        }
    }

    public function selectProductType(int $id): void
    {
        if ($this->filterMode === 'single') {
            $this->selectedProductTypes = [$id];
        } else {
            if (in_array($id, $this->selectedProductTypes, true)) {
                $this->selectedProductTypes = array_values(array_filter(
                    $this->selectedProductTypes,
                    fn ($v) => (int) $v !== $id
                ));
            } else {
                $this->selectedProductTypes[] = $id;
            }
        }
        $this->resetPage();
    }

    public function getDisplayTitleProperty(): string
    {
        if (! $this->dynamicTitle) {
            return $this->pageTitle;
        }

        if (count($this->selectedProductTypes) === 1) {
            $cat = Category::find($this->selectedProductTypes[0]);
            if ($cat) {
                return str_ends_with(strtolower($cat->name), 'abaya')
                    ? $cat->name
                    : $cat->name.' Abaya';
            }
        }

        return $this->pageTitle;
    }

    public function updatedSelectedCollections(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedProductTypes(): void
    {
        if ($this->filterMode === 'single' && count($this->selectedProductTypes) > 1) {
            $this->selectedProductTypes = [array_last($this->selectedProductTypes)];
        }
        $this->resetPage();
    }

    public function updatedSelectedFabrics(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedPriceBucket(): void
    {
        $this->resetPage();
    }

    public function updatedFeaturedOnly(): void
    {
        $this->resetPage();
    }

    public function removeCollection(int $id): void
    {
        $this->selectedCollections = array_values(array_filter(
            $this->selectedCollections,
            fn ($v) => (int) $v !== $id
        ));
        $this->resetPage();
    }

    public function removeProductType(int $id): void
    {
        $this->selectedProductTypes = array_values(array_filter(
            $this->selectedProductTypes,
            fn ($v) => (int) $v !== $id
        ));
        $this->resetPage();
    }

    public function removeFabric(int $id): void
    {
        $this->selectedFabrics = array_values(array_filter(
            $this->selectedFabrics,
            fn ($v) => (int) $v !== $id
        ));
        $this->resetPage();
    }

    public function clearPrice(): void
    {
        $this->selectedPriceBucket = null;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->selectedCollections = [];
        $this->selectedProductTypes = [];
        $this->selectedFabrics = [];
        $this->selectedPriceBucket = null;
        $this->featuredOnly = false;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::visible()
            ->withPrimaryCategory()
            ->with([
                'images' => fn ($q) => $q->orderBy('sort_order')->limit(2),
                'categories',
                'fabric',
            ]);

        if ($this->categoryId && empty($this->selectedCollections) && empty($this->selectedProductTypes)) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $this->categoryId));
        }

        foreach ($this->selectedCollections as $catId) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $catId));
        }

        foreach ($this->selectedProductTypes as $catId) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $catId));
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

        if ($this->featuredOnly) {
            $query->where('is_featured', true);
        }

        if ($this->isNewArrivals) {
            $count = (int) Setting::get('new_arrivals_count', 8);
            $items = $query->latest()->limit($count)->get();
            $total = $items->count();
            $products = new LengthAwarePaginator(
                $items,
                $total,
                max($total, 1),
                1,
                ['path' => request()->url(), 'pageName' => 'page']
            );
        } else {
            $products = $query->latest()->paginate(20);
        }

        return view('livewire.product-filter', [
            'products' => $products,
            'activeFilters' => $this->buildActiveFilters(),
            'displayTitle' => $this->displayTitle,
            'filterMode' => $this->filterMode,
            'dynamicTitle' => $this->dynamicTitle,
        ]);
    }

    protected function buildActiveFilters(): array
    {
        $filters = [];

        foreach ($this->selectedCollections as $id) {
            $cat = collect($this->collectionCategories)->firstWhere('id', (int) $id);
            if ($cat) {
                $filters[] = ['type' => 'collection', 'id' => (int) $id, 'label' => $cat->name];
            }
        }

        foreach ($this->selectedProductTypes as $id) {
            $cat = collect($this->productTypeCategories)->firstWhere('id', (int) $id);
            if ($cat) {
                $filters[] = ['type' => 'product_type', 'id' => (int) $id, 'label' => $cat->name];
            }
        }

        foreach ($this->selectedFabrics as $id) {
            $fabric = collect($this->fabrics)->firstWhere('id', (int) $id);
            if ($fabric) {
                $filters[] = ['type' => 'fabric', 'id' => (int) $id, 'label' => $fabric->name];
            }
        }

        if ($this->selectedPriceBucket) {
            $bucket = collect($this->priceBuckets)->firstWhere('id', $this->selectedPriceBucket);
            if ($bucket) {
                $filters[] = ['type' => 'price', 'id' => $bucket->id, 'label' => $bucket->label];
            }
        }

        if ($this->featuredOnly) {
            $filters[] = ['type' => 'featured', 'id' => 0, 'label' => 'Featured designs'];
        }

        return $filters;
    }
}

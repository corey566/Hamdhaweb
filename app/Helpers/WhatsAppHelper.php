<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Collection;

class WhatsAppHelper
{
    public static function generateOrderUrl(Product $product): string
    {
        return self::buildUrl(self::productMessage($product));
    }

    public static function generateWishlistUrl(array $slugs): string
    {
        $products = Product::visible()
            ->whereIn('slug', $slugs)
            ->with('fabric')
            ->get();

        if ($products->isEmpty()) {
            return self::buildUrl("Hello Hamdha,\n\nI'm interested in items from my wish list. Please share availability and pricing.");
        }

        $lines = ["Hello Hamdha,\n\nI'm interested in these items from my wish list:\n"];
        foreach ($products as $index => $product) {
            $lines[] = ($index + 1).'. '.$product->name.' ('.$product->model_number.') — Rs. '.number_format($product->discount_price ?? $product->price);
            $lines[] = '   '.route('products.show', $product->slug);
        }
        $lines[] = "\nPlease confirm availability.";

        return self::buildUrl(implode("\n", $lines));
    }

    protected static function productMessage(Product $product): string
    {
        $template = Setting::get('whatsapp_message_template',
            "Hello Hamdha,\n\nI'm interested in this product:\n• Model: {model}\n• Name: {name}\n• Price: Rs. {price}\n• Fabric: {fabric}\n• Link: {url}\n\nPlease confirm availability."
        );

        return str_replace(
            ['{model}', '{name}', '{price}', '{fabric}', '{url}'],
            [
                $product->model_number,
                $product->name,
                number_format($product->discount_price ?? $product->price),
                $product->fabricLabel(),
                route('products.show', $product->slug),
            ],
            $template
        );
    }

    protected static function buildUrl(string $message): string
    {
        $phone = Setting::get('whatsapp_number', '94777626013');

        return 'https://wa.me/'.$phone.'?text='.urlencode($message);
    }
}

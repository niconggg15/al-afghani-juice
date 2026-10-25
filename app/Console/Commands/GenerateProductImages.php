<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateProductImages extends Command
{
    protected $signature = 'images:generate-products';
    protected $description = 'Generate placeholder SVG images for products';

    public function handle()
    {
        $products = Product::with('category')->get();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $svg = $this->generateProductSVG($product);
            $filename = 'products/' . Str::slug($product->name) . '.svg';
            
            Storage::disk('public')->put($filename, $svg);
            $product->update(['image' => $filename]);
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nGenerated images for {$products->count()} products!");
        return Command::SUCCESS;
    }

    private function generateProductSVG(Product $product): string
    {
        $category = $product->category;
        $color = $category->color ?? '#FF6B6B';
        $name = $product->name;
        
        // Determine icon and colors based on category
        $icons = [
            'Jus Buah' => ['🍹', '#FF6B6B', '#FF9F43'],
            'Es Jeruk' => ['🍊', '#FF9F43', '#FF6B6B'],
            'Capcin' => ['🥤', '#74B9FF', '#0984E3'],
            'Pop Ice' => ['🍦', '#A29BFE', '#6C5CE7'],
        ];
        
        $iconData = $icons[$category->name] ?? ['🥤', $color, '#FF6B6B'];
        [$emoji, $primary, $secondary] = $iconData;
        
        // Extract flavor for color variation
        $flavorColors = [
            'Mangga' => ['#FFD700', '#FFA500'],
            'Alpukat' => ['#7CB342', '#4CAF50'],
            'Jeruk' => ['#FF9800', '#FF5722'],
            'Strawberry' => ['#E91E63', '#C2185B'],
            'Melon' => ['#8BC34A', '#4CAF50'],
            'Semangka' => ['#E91E63', '#FFCDD2'],
            'Buah Naga' => ['#E91E63', '#FF6B6B'],
            'Apel' => ['#4CAF50', '#8BC34A'],
            'Nanas' => ['#FFEB3B', '#FFC107'],
            'Jambu' => ['#FF6B6B', '#FF9F43'],
            'Coklat' => ['#6D4C41', '#4E342E'],
            'Taro' => ['#BA68C8', '#9C27B0'],
            'Permen Karet' => ['#FF69B4', '#FF1493'],
            'Vanilla Blue' => ['#64B5F6', '#2196F3'],
            'Durian' => ['#FFD700', '#FFA000'],
            'Anggur' => ['#7B1FA2', '#4A148C'],
            'Cappuccino' => ['#8D6E63', '#5D4037'],
        ];
        
        $flavorColor = ['#FF6B6B', '#FF9F43'];
        foreach ($flavorColors as $flavor => $colors) {
            if (Str::contains($name, $flavor)) {
                $flavorColor = $colors;
                break;
            }
        }
        
        $svg = <<<SVG
<svg width="400" height="400" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$flavorColor[0]};stop-opacity:0.15" />
      <stop offset="100%" style="stop-color:{$flavorColor[1]};stop-opacity:0.1" />
    </linearGradient>
    <linearGradient id="liquidGrad" x1="0%" y1="100%" x2="0%" y2="0%">
      <stop offset="0%" style="stop-color:{$flavorColor[0]}" />
      <stop offset="100%" style="stop-color:{$flavorColor[1]}" />
    </linearGradient>
    <linearGradient id="cupGrad" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#FFFFFF" />
      <stop offset="50%" style="stop-color:#F5F5F5" />
      <stop offset="100%" style="stop-color:#EEEEEE" />
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="8" stdDeviation="12" flood-color="#000000" flood-opacity="0.15"/>
    </filter>
    <filter id="innerShadow">
      <feOffset dx="0" dy="2"/>
      <feGaussianBlur stdDeviation="3" result="offset-blur"/>
      <feComposite operator="out" in="SourceGraphic" in2="offset-blur" result="inverse"/>
      <feFlood flood-color="black" flood-opacity="0.1" result="color"/>
      <feComposite operator="in" in="color" in2="inverse" result="shadow"/>
      <feComposite operator="over" in="shadow" in2="SourceGraphic"/>
    </filter>
  </defs>
  
  <!-- Background -->
  <rect width="400" height="400" fill="url(#bgGrad)"/>
  
  <!-- Decorative circles -->
  <circle cx="350" cy="50" r="80" fill="{$flavorColor[0]}" opacity="0.08"/>
  <circle cx="-50" cy="350" r="100" fill="{$flavorColor[1]}" opacity="0.08"/>
  
  <!-- Cup/Glass -->
  <g filter="url(#shadow)">
    <!-- Cup body -->
    <path d="M100 320 L100 140 Q100 110 130 110 L270 110 Q300 110 300 140 L300 320 Q300 340 270 340 L130 340 Q100 340 100 320 Z" fill="url(#cupGrad)" filter="url(#innerShadow)" stroke="#E0E0E0" stroke-width="1"/>
    
    <!-- Liquid -->
    <path d="M115 310 L115 160 Q115 145 135 145 L265 145 Q285 145 285 160 L285 310 Q285 325 265 325 L135 325 Q115 325 115 310 Z" fill="url(#liquidGrad)" opacity="0.95"/>
    
    <!-- Liquid highlight -->
    <path d="M125 300 L125 180 Q125 170 140 170 L260 170 Q275 170 275 180 L275 300 Q275 310 260 310 L140 310 Q125 310 125 300 Z" fill="white" opacity="0.15"/>
    
    <!-- Cup rim -->
    <ellipse cx="200" cy="110" rx="100" ry="8" fill="none" stroke="#DDD" stroke-width="2"/>
    <ellipse cx="200" cy="110" rx="95" ry="6" fill="#FFF" opacity="0.5"/>
    
    <!-- Cup base -->
    <path d="M120 340 L120 350 Q120 360 135 360 L265 360 Q280 360 280 350 L280 340" fill="url(#cupGrad)" stroke="#E0E0E0" stroke-width="1"/>
  </g>
  
  <!-- Decorative fruit/icon -->
  <g transform="translate(200, 180)">
    <!-- Main fruit circle -->
    <circle r="55" fill="url(#liquidGrad)" opacity="0.3"/>
    <circle r="45" fill="url(#liquidGrad)" opacity="0.5"/>
    
    <!-- Fruit specific details based on name -->
    <text x="0" y="15" text-anchor="middle" font-size="50" dominant-baseline="middle">{$emoji}</text>
    
    <!-- Bubbles -->
    <circle cx="-20" cy="-40" r="6" fill="white" opacity="0.6">
      <animate attributeName="cy" values="-40;-80" dur="3s" repeatCount="indefinite"/>
      <animate attributeName="opacity" values="0.6;0" dur="3s" repeatCount="indefinite"/>
    </circle>
    <circle cx="25" cy="-50" r="4" fill="white" opacity="0.5">
      <animate attributeName="cy" values="-50;-90" dur="4s" repeatCount="indefinite" begin="1s"/>
      <animate attributeName="opacity" values="0.5;0" dur="4s" repeatCount="indefinite" begin="1s"/>
    </circle>
    <circle cx="0" cy="-60" r="5" fill="white" opacity="0.4">
      <animate attributeName="cy" values="-60;-100" dur="3.5s" repeatCount="indefinite" begin="0.5s"/>
      <animate attributeName="opacity" values="0.4;0" dur="3.5s" repeatCount="indefinite" begin="0.5s"/>
    </circle>
  </g>
  
  <!-- Straw for cold drinks -->
  <g transform="translate(250, 110)">
    <rect x="0" y="-80" width="8" height="120" rx="4" fill="#FF6B6B" opacity="0.8"/>
    <rect x="1" y="-75" width="6" height="110" rx="3" fill="#FF9F43" opacity="0.5"/>
  </g>
  
  <!-- Category label -->
  <text x="200" y="370" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="600" fill="{$flavorColor[0]}" opacity="0.8">{$category->name}</text>
</svg>
SVG;

        return $svg;
    }
}
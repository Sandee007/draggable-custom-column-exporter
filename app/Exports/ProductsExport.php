<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    private $columns;

    public function __construct($columns)
    {
        $this->columns = $columns;
    }

    public function collection()
    {
        return Product::all($this->columns);
    }

    public function headings(): array
    {
        return collect($this->columns)->map(fn($i) => ucfirst($i))->toArray();
    }
}

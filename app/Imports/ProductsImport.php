<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            //
        ]);
    }

    // public function collection(Collection $rows)
    // {

    //     foreach ($rows as $row) 
    //     {
    //         Product::create([
    //             'product_name' => $row[0],
    //         ]);
    //     }
    
    // }
}

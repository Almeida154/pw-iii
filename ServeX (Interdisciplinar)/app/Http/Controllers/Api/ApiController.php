<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Technicality;

class ApiController extends Controller {
    
    public function getTechnicalities() {
        $technicalities = Technicality::all();
        $json = array();

        foreach($technicalities as $technicality) {
            $categories = array();

            foreach($technicality->categories as $category) {
                array_push($categories, $category->category);
            }

            array_push($json, [
                'id' => $technicality->id,
                'technicality' => $technicality->technicality,
                'description' => $technicality->description,
                'categories' => $categories
            ]);
        }
        return json_encode($json);
    }

    public function getTechnicality($id) {
        $technicality = Technicality::findOrFail($id);
        $categories = array();

        foreach($technicality->categories as $category) {
            array_push($categories, $category->category);
        }

        $json = [
            'id' => $technicality->id,
            'technicality' => $technicality->technicality,
            'description' => $technicality->description,
            'categories' => $categories
        ];

        return json_encode($json);
    }

    public function getTechnicalitiesByCateg($categ) {
        $category = Category::where('category', '=', $categ)->firstOrFail();
        
        $technicalities = $category->technicalities;
        $json = array();

        foreach($technicalities as $technicality) {
            array_push($json, [
                'id' => $technicality->id,
                'technicality' => $technicality->technicality,
                'description' => $technicality->description,
                'categories' => [$category->category]
            ]);
        }
        return json_encode($json);
    }

    public function getCategories() {
        return json_encode(Category::all());
    }

    public function add(Request $request) {
        try {
            $data = json_decode($request->getContent());
            $registered = Category::where('category', '=', 'from-mobile')->first();

            if($registered === null) {
                $category = new Category();
                $category->category = 'from-mobile';
                $category->save();
                $registered->category;
            }

            $technicality = new Technicality();
            $technicality->technicality = $data->technicality;
            $technicality->description = $data->description;
            $technicality->save();

            $technicality->categories()->sync($registered->id);

            return response()->json([
                'status' => 'sucess',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'error' => $th
            ]);
        }
    }

}
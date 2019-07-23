<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
   
        $categorias = DB::table('categorias')->paginate(3);
        return response()->json([
            "paginacion" => [
                "current_page" => $categorias->currentPage(),
                "per_page" => $categorias->perPage(),
                "last_page" => $categorias->lastPage(),
                "from" => $categorias->firstItem(),
                "to" => $categorias->lastItem(),
                "total" => $categorias->total(),
            
            ],
            "data" => $categorias
        ],201);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categoria = Categoria::create([
            "nombre" => $request->nombre,
            "descripcion" => $request->descripcion
        ]);

        return response()->json([
            "ok" => true,
            "data" => $categoria
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $categoria = Categoria::find($request->categoria_id);
        $categoria->update([
            "nombre" => $request->nombre,
            "descripcion" => $request->descripcion
        ]);

        return response()->json([
            "ok" => true,
            "data" => $categoria,
            "message" => "Categoria actualizada exitosamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $categoria = Categoria::find($id);
        if($categoria){
            $categoria->condicion = 0;
            $categoria->update();
            return response()->json([
                "ok" => true,
                "data" => $categoria,
                "message" => "Categoria actualizada exitosamente"
            ],201);
        }else{
            return response()->json([
                "ok" => false,
                "message" => "Categoria no existe"
            ],404);
        }
    }

    public function activar($id)
    {
        $categoria = Categoria::find($id);
        if($categoria){
            $categoria->condicion = 1;
            $categoria->update();
            return response()->json([
                "ok" => true,
                "data" => $categoria,
                "message" => "Categoria actualizada exitosamente"
            ],201);
        }else{
            return response()->json([
                "ok" => false,
                "message" => "Categoria no existe"
            ],404);
        }
    }
}

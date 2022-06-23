<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
// dependencia pra el validador
use Illuminate\support\Facades\validator;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo "aqui va la lista de productos ";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //seleccionar marcas:
        $marcas= Marca::all();
        //seleccionar categorias:
        $categorias= categoria::all();
  //las enviamos a la vista
       return view("productos.new") 
        ->with ("categorias", $categorias)
        ->with ("marcas", $marcas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // acceder a los datos del formulario utilizando el objeto request
      //  echo "<pre>";
       // var_dump($request->imagen);
       // echo "</pre>";

       //*validacion de formulario
       //establecer reglas de validacion para aplicar a cada input
       $reglas=[
        "nombre"=>'required|alpha|unique:productos,nombre',
        "desc"=>'required|min:10|max:20',
        'precio'=> 'required|numeric',
        'imagen'=>'required|image',
        'categoria'=>'required'
        ];
        $mensajes =[
            "required"=>"campo obligatorio",
            "alpha"=> "solo letras",
            "numeric"=> "solo numeros",
            "image"=> "suba una imagen",
            "min"=> "minimo:min valor",
            "max"=> "maximo:max valor"
        ];
        //crear el objeto validar validador
        $v = validator::make ($request->all() , $reglas, $mensajes);
        //3 validar
        // fails retorna
        //true si la validacion falla
        // falla
        if($v->fails()){
            //validacion correcta
            // mostrar la vista new 
            //llevando los errores
            return redirect('productos/create')
           -> withErrors($v);

           

        }else {

            $archivo=$request->imagen;
            //capturar metodo "original" del archivo
            //desde el cliente
            $nombre_archivo=$archivo->getClientOriginalName();
            var_dump($nombre_archivo);
           // mover el archivo a la carpeta "public/img"
           $ruta = public_path();
           var_dump($ruta);
           $archivo->move("$ruta/img", $nombre_archivo);
           //registrar producto
           $producto = new producto;
           $producto->nombre = $request->nombre;
           $producto->descripcion = $request->desc;
           $producto->precio = $request->precio;
           $producto->imagen =$nombre_archivo;
           $producto->marca_id = $request->marca;
           $producto->categoria_id = $request->categoria;
           $producto->save();
           //redireccionar al formulario
           //llevando un mensaje de exito
           return redirect('productos/create')
           ->with("mensajito","producto registrado");


        }

       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show( $producto)
    {
        echo "aqui va el detalle de producto con id: $producto";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit($producto)
    {
        echo"aqui va el form para editar el producto con id: $producto";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}

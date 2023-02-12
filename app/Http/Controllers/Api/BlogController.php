<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//usamos el modelo Blog
use App\Models\Blog;

class BlogController extends Controller
{    
    // Crear un blog
    public function createBlog(Request $request) {
        //validacion
        $request->validate([
            "title" => "required",
            "content" => "required",
        ]);
        // Tenemos que traer el id del usuario logueado
        $user_id = auth()->user()->id;
        $blog = new Blog();    
        $blog->user_id = $user_id; //aqui tenemos el user_id
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->save();
        //response
        return response([
            "status" => 1,
            "msg" => "¡Blog creado exitosamente!"
        ]);
    }

    // Muestra TODOS los blogs de UN USUARIO en particular
    public function listBlog(Request $request) {
        $user_id = auth()->user()->id; //capturamos el ID del usuario

        $blogs = Blog::where("user_id", $user_id)->get();

        return response()->json([
            "status" => 1,
            "msg" => "Blogs",
            "data" => $blogs
        ]);
    }
    
    public function showBlog($id) {
        $user_id = auth()->user()->id;
        if( Blog::where( ["id" => $id, "user_id" => $user_id ])->exists() ){            
            $info = Blog::where( ["id" => $id, "user_id" => $user_id ])->get();
            return response()->json([
                "status" => 1,
                "msg" => "No se encontró el Blog",
                "msg" => $info,
            ], 404);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Blog"
            ], 404);
        }
    }

    public function update(Request $request, $id){
        $user_id = auth()->user()->id; //capturamos el ID del usuario
        if ( Blog::where( ["user_id"=>$user_id, "id" => $id] )->exists() ) {                        
            $blog = Blog::find($id);
            //la forma mas completa, con operadores ternarios
            $blog->title = isset($request->title) ? $request->title : $blog->title;    
            $blog->content = isset($request->content) ? $request->content : $blog->content;                
            //forma simple
            //$blog->title = $request->title;
            //$blog->content = $request->content;
            $blog->save();
            //respuesta
            return response()->json([
                "status" => 1,
                "msg" => "Blog actualizado correctamente."
            ]);
        }else{
            //responde la API
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Blog"
            ], 404);
        }
    }

    public function deleteBlog($id){
        $user_id = auth()->user()->id; //capturamos el ID del usuario
        if( Blog::where( ["id" => $id, "user_id" => $user_id ])->exists() ){
            $blog = Blog::where( ["id" => $id, "user_id" => $user_id ])->first();
            $blog->delete();
            //responde la API
            return response()->json([
                "status" => 1,
                "msg" => "El blog fue eliminado correctamente."
            ]);
        }else{
             //responde la API
             return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Blog"
            ], 404);
        }
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\UserController;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group( ['middleware'=> ["auth:sanctum"] ], function() {
    //rutas para User
    Route::get("user-profile", [UserController::class, "userProfile"]);
    Route::get("logout", [UserController::class, "logout"]);

    //rutas para Blog
    Route::post("create-blog", [BlogController::class, "createBlog"]); // crear un blog
    Route::get("list-blog", [BlogController::class, "listBlog"]); //mostrar TODOS los blogs
    Route::get("show-blog/{id}", [BlogController::class, "showBlog"]); //mostrar UN solo blog

    Route::delete("delete-blog/{id}", [BlogController::class, "deleteBlog"]); // borrar un blog (metodo DELETE)
    Route::put("update-blog/{id}", [BlogController::class, "update"]); // actualizar un blog (metodo PUT)
});

//Esta ruta viene por defecto
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

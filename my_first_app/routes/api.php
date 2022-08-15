 <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


 Route::post('auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
 Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
 Route::get('/shopping', [\App\Http\Controllers\ProductController::class, 'show_market']);

//all this route are authenticated
Route::middleware(['auth:api'])->group(function (){

    Route::get('/logout',[\App\Http\Controllers\AuthController::class,'logout']);


    Route::prefix('products')->group(function (){
        Route::get('/', [\App\Http\Controllers\ProductController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\ProductController::class, 'store']);
        Route::get('/{product}', [\App\Http\Controllers\ProductController::class, 'show']);
        Route::Post('/{product}', [\App\Http\Controllers\ProductController::class, 'update']);// note
        Route::delete('/{product}', [\App\Http\Controllers\ProductController::class, 'destroy']);

        Route::prefix('{product}/comments')->group(function (){
            Route::get('/', [\App\Http\Controllers\CommentController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\CommentController::class, 'store']);
            Route::get('/{comment}', [\App\Http\Controllers\CommentController::class, 'show']);
            Route::put('/{comment}', [\App\Http\Controllers\CommentController::class, 'update']);
            Route::delete('/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy']);
        });

        Route::prefix('{product}/likes')->group(function (){
            Route::get('/', [\App\Http\Controllers\LikeController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\LikeController::class, 'store']);
            Route::get('/{like}', [\App\Http\Controllers\LikeController::class, 'show']);
            Route::put('/{like}', [\App\Http\Controllers\LikeController::class, 'update']);
            Route::delete('/{like}', [\App\Http\Controllers\LikeController::class, 'destroy']);
        });
    });


    Route::get('categories/', [CategoryController::class, 'index']);
    Route::post('categories/', [CategoryController::class, 'store']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
});








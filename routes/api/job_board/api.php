<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*
 * Grupo 1
 */

/* Rutas para los profesionales
Route::group(['prefix' => 'professionals'], function () {
    //Route::group(['middleware' => 'auth:api'], function () {

    //Ruta para obtener un profesional segun el id, relación con la tabla academicFormations para la vista principal de la pagian
    Route::get('/{id}', 'JobBoard\ProfessionalController@show');
    //});
});
*/
/**********************************************************************************************************************/

/* Rutas para obtener todos los postulantes
Route::group(['prefix' => 'postulants'], function () {

    // Ruta para gestionar los datos personales
    Route::get('', 'JobBoard\ProfessionalController@getProfessionals');

    //Método para aplicar a una empresa
    Route::post('/apply', 'JobBoard\CompanyController@applyPostulant');

    //Método para validar aplicación de un profesional a una empresa
    Route::get('/validateAppliedPostulant', 'JobBoard\ProfessionalController@validateAppliedPostulant');
});

// Total de Empresas, Profesionales y Ofertas
Route::get('/total', function () {
    $now = Carbon::now();
    $totalCompanies = \App\Models\JobBoard\Company::where('state_id', 1)->count();
    $totalProfessionals = \App\Models\JobBoard\Professional::where('state_id', 1)->count();
    $totalOffers = \App\Models\JobBoard\Offer::where('state_id', 1)
        ->where('end_date', '>=', $now->format('Y-m-d'))
        ->where('start_date', '<=', $now->format('Y-m-d'))
        ->count();
    return response()->json(['totalCompanies' => $totalCompanies, 'totalOffers' => $totalOffers, 'totalProfessionals' => $totalProfessionals], 200);
});
*/
/**********************************************************************************************************************/

/* Rutas para filtrar a los profesionales y ofertas

//Ruta para filtrar los postulantes utilizando un campo
Route::post('/postulants/filter', 'JobBoard\ProfessionalController@filterPostulants');

//??? Esta ruta es igual a Route::get('', 'JobBoard\ProfessionalController@getProfessionals')
Route::get('/postulants/filter', 'JobBoard\ProfessionalController@filterPostulantsFields');
*/
/**********************************************************************************************************************/

/* Ruta para obtener las categorías del filtro
Route::group(['prefix' => 'categories'], function () {
    Route::get('', 'JobBoard\CategoryController@index');
});
*/
/*
 * FinGrupo 1
 */

/*
 * Grupo 2

// Rutas ofertas
Route::group(['prefix' => 'offers'], function () {
    // Route::group(['middleware' => 'auth:api'], function () {
    Route::get('all', 'JobBoard\OfferController@getAllOffers'); // Trae todas las ofertas.
    Route::get('/opportunities', 'JobBoard\OfferController@getOffers'); // Trae todas las ofertas con filtros
    Route::post('/filter', 'JobBoard\OfferController@filterOffers'); // Filtra las ofertas segun el buscador.
    Route::get('/opportunities/validateAppliedOffer', 'JobBoard\OfferController@validateAppliedOffer');
    Route::post('/opportunities/apply', 'JobBoard\OfferController@applyOffer');
});
// Total de Empresas, Profesionales y Ofertas
Route::get('/total', function () {
    $now = Carbon::now();
    $totalCompanies = \App\Models\JobBoard\Company::where('state_id', 1)->count();
    $totalProfessionals = \App\Models\JobBoard\Professional::where('state_id', 1)->count();
    $totalOffers = \App\Models\JobBoard\Offer::where('state_id', 1)
        ->where('end_date', '>=', $now->format('Y-m-d'))
        ->where('start_date', '<=', $now->format('Y-m-d'))
        ->count();
    return response()->json(['totalCompanies' => $totalCompanies, 'totalOffers' => $totalOffers, 'totalProfessionals' => $totalProfessionals], 200);
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/index', function () {
        $categories = Category::with('children')->get();
        return response()->json([
            'data' => [
                'categories' => $categories
            ]
        ], 200);
    });
});
*/
/*
 * FinGrupo 2
 */

/*
 * Grupo 3

Route::group(['prefix' => 'offers'], function () {
    Route::get('/', 'JobBoard\OfferController@getAllOffers');
    Route::get('/applied_offers', 'JobBoard\ProfessionalController@getAppliedOffers');
    Route::get('/professionales-ofertas', 'JobBoard\ProfessionalController@getAllprofessionalsTesteo');
    Route::get('/companias-interesadas', 'JobBoard\ProfessionalController@getInterestedCompanies');
});

Route::group(['prefix' => 'opportunities'], function () {
    Route::get('/', 'JobBoard\OfferController@indexOffers');
    Route::get('/applied-offers', 'JobBoard\ProfessionalController@getAppliedOffers');
    Route::get('/interested-companies', 'JobBoard\ProfessionalController@getInterestedCompanies');
    Route::put('/unlink-offer', 'JobBoard\ProfessionalController@unlinkOffer');
    Route::get('/professionals-offers', 'JobBoard\ProfessionalController@getAllprofessionalsTesteo');
    Route::get('/professional-companies', 'JobBoard\ProfessionalController@getAllcompaniesTesteo');
});
*/
/*
 * FinGrupo 3
 */

/*
 * Grupo4


// Route::group(['middleware' => 'auth:api'], function () {
Route::apiResource('', 'JobBoard\CourseController');
// });

// Route::group(['middleware' => 'auth:api'], function () {
Route::apiResource('academic_formations', 'JobBoard\AcademicFormationController');
// });
// Route::group(['middleware' => 'auth:api'], function () {
Route::apiResource('professional_references', 'JobBoard\ProfessionalReferenceController');
// });
//Route::group(['middleware'=> 'auth:api'], function () {
Route::apiResource('abilities','JobBoard\AbilityController');
//});
//Route::group(['middleware'=> 'auth:api'], function () {
Route::apiResource('professional_experiences','JobBoard\ProfessionalExperienceController');
//});
// Route::group(['middleware' => 'auth:api'], function () {
//Route::apiResource('offers', 'JobBoard\OfferReferenceController');
// });
Route::group(['prefix' => 'professionals'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::put('', 'JobBoard\ProfessionalController@update');
    });
});

//Rutas para las empresas
Route::group(['prefix' => 'companies'], function () {
    // Route::group(['middleware'=> 'auth:api'],function(){
    Route::get('/professionals', 'JobBoard\CompanyController@getAppliedProfessionals');//busca los profesionales que la empresa esta interesada
    Route::delete('/detachPostulant', 'JobBoard\CompanyController@detachPostulant');// quitar los professionales que la empresa ya no esta interesada
    Route::get('/{id}', 'JobBoard\CompanyController@show');
    Route::put('', 'JobBoard\CompanyController@update');
    // });

});
/*
 * FinGrupo4
 */

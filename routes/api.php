<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/oauth', 'UserController@apiLogin');

//List users
Route::middleware('auth:api')->get('users', 'UserController@index');
//Change user status
Route::middleware('auth:api')->post('user/change-status', 'UserController@changeStatus');
//Reset user password
Route::middleware('auth:api')->post('user/reset-password', 'UserController@ajaxResetPassword');
//Check if username exists
Route::middleware('auth:api')->get('user/unique-username/{username}', 'UserController@checkUsernameExists');
//Check if email exists
Route::middleware('auth:api')->get('user/unique-email/{email}', 'UserController@checkEmailExists');
//Select user
Route::middleware('auth:api')->get('user/{id}', 'UserController@selectUser');
//Delete user
Route::middleware('auth:api')->delete('user/{user}', 'UserController@destroy');

//List engagements
Route::middleware('auth:api')->get('engagements', 'EngagementController@index');
//Selectionner un engagement
Route::middleware('auth:api')->get('engagements/{engagement}', 'EngagementController@ajaxSelectSingle');
//Import fichier engagements
Route::middleware('auth:api')->post('engagements/import', 'EngagementController@import');
//Select engagements par statut
Route::middleware('auth:api')->get('engagements/ajax/engagements-par-statut', 'EngagementController@engagementsParStatut');
//nbre engagements par annee
Route::middleware('auth:api')->get('engagements/ajax/engagements-par-annee', 'EngagementController@engagementsParAnnee');
//montant total par annee
Route::middleware('auth:api')->get('engagements/ajax/montants-par-annee', 'EngagementController@montantParAnnee');
//Selection des engagements par filtre multi criteres
Route::middleware('auth:api')->get('search', 'EngagementController@engagementsByMutlicriteria');


//Selection des echelons par le numero d'engagement
Route::middleware('auth:api')->get('echelons/select-by-engagement-number', 'EchelonController@selectByEngagementNumber');
//Ajouter un paiement
Route::middleware('auth:api')->post('echelon', 'EchelonController@store');
//Upload de fichiers pour les echelons
Route::middleware('auth:api')->post('echelon/upload-files', 'EchelonController@uploadFiles');

/****** Echelons ******/
//List echelons
Route::middleware('auth:api')->get('echelons', 'EchelonController@index');

/****** Providers ******/
//List providers
Route::middleware('auth:api')->get('providers', 'ProviderController@index');
//On charge les informations du fournisseur
Route::middleware('auth:api')->get('providers/details', 'ProviderController@loadData');
//On charge les engagements du fournisseur
Route::middleware('auth:api')->get('provider/engagements', 'ProviderController@loadEngagements');
//On charge les echelons du fournisseur
Route::middleware('auth:api')->get('provider/echelons', 'ProviderController@loadEchelons');

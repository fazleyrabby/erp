<?php

use App\Http\Controllers\Admin\CRM\PartyController;
use Illuminate\Support\Facades\Route;

// Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    // Parties Routes
    Route::get('parties/view/{type}', [PartyController::class, 'index'])->name('parties.view');
    Route::get('parties/getParty', [PartyController::class, 'getPartyData'])->name('getPartyList');
    Route::get('parties/get/{type}', [PartyController::class, 'getParties']);
    Route::post('parties/store', [PartyController::class, 'store']);
    Route::get('parties/edit', [PartyController::class, 'edit'])->name('editParty');
    Route::post('parties/update', [PartyController::class, 'update'])->name('updateParty');
    Route::post('parties/delete', [PartyController::class, 'delete'])->name('partyDelete');
    Route::post('parties/updatePartyOpeningDue', [PartyController::class, 'updatePartyOpeningDue'])->name('updatePartyOpeningDue');

    // ----- End party party Web page links -------//

});

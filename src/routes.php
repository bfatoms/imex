<?php

Route::group(['middlewar' => config('imex.midlleware')], function(){
    Route::post(config('imex.import') ?? 'api/{model}/import', config('imex.import-controller') ?? 'BfAtoms\Imex\ImexController@import');
    Route::post(config('imex.export') ?? 'api/{model}/export', config('imex.export-controller') ?? 'BfAtoms\Imex\ImexController@export');
});

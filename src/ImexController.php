<?php

namespace BfAtoms\Imex;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use BfAtoms\Imex\Import;
use BfAtoms\Imex\ImportRequest;

class ImexController extends Controller {

    public function import(ImportRequest $request)
    {
        $import = new Import();

        $import->request($request);

        $import->model(request('model'));

        return $import->now();
    }

    public function export(Request $request)
    {
        return response()->json(["export"]);
    }
}

<?php

return [
    "import" => "api/{model}/import",
    "export" => "api/{model}/export",
    "model_path" => "App\Models",
    "user_path" => "App\User",
    "import-controller" => "BfAtoms\Imex\ImexController@import",
    "export-controller" => "BfAtoms\Imex\ImexController@export",
    "import_request_name" => "file",
    "export_request_name" => "file"
];
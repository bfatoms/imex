<?php

namespace BfAtoms\Imex;

use Illuminate\Support\Str;

use BfAtoms\Imex\ImportRequest;
use BfAtoms\Typecon\CsvToJson;

class Import {

    protected $filepath = "";
    protected $model = "";
    private $result = [];

    public function path(string $filepath)
    {
        $this->filepath = $filepath;
        return $this;
    }

    public function request(ImportRequest $request)
    {
        $this->filepath = $request->file('file')->getRealPath();
        return $this;
    }
  
    public function model($model)
    {
        if(is_string($model)){
            $model = Str::studly(Str::singular(request('model')));
            $model = config('imex.model_path') ?? "App\\Models" .'\\'.$model ?? "App".'\\'.$model;
        }
        $this->model = $model;
    }

    public function now()
    {
        $csv = new CsvToJson();
        $csv->filepath($this->filepath);
        $csv->setConversionKey('options', JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $csv->setConversionKey('join', ".");
        $converts = array_filter(json_decode($csv->convert(), true));
        $where = explode(",", request('find'));
        $updates = [];
        $errors = [];

        foreach($converts as $data)
        {
            try{
                $find = array_only($data, $where);
            
                $model_data = $this->model::updateOrCreate(
                    $find,
                    $data
                );

                $updates = $model_data;
            }
            catch(\Exception $ex)
            {
                $errors[] = $ex->getMessage();
            }

        }

        return ["updated" => $updates, "errors" => $errors];
    }

    public function import()
    {
        $result = [];

        $this->result = $result;
        return $this->result;
    }

    public function getResult()
    {
        return $this->result;
    }

}
<?php

namespace BfAtoms\Imex;

use Illuminate\Support\Str;

use BfAtoms\Imex\ImportRequest;
use BfAtoms\Typecon\CsvToJson;

class Import {

    protected $filepath = "";
    protected $model = "";
    protected $model_name = "";
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
            $this->model_name = $model;
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

        $converts = $this->findRelatedData($converts);

        $updates = [];
        $errors = [];
        $find = [];

        if(request('find'))
        {
            $find = explode(',', request('find'));

        }

        foreach($converts as $data)
        {
            try
            {
                if(request('find'))
                {
                    $find_this = collect($data)->only($find)->toArray();

                }

                $model_data = $this->model::updateOrCreate(
                    $find_this,
                    $data
                );

                $updates[] = $model_data;

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


    public function findRelatedData($data)
    {
        if(request('column'))
        {
            foreach(request('column') as $key => $col){
                $model = 'App\Models\\'.$col['model'];                
                $uniques = array_unique(array_column($data, $key));
                foreach($uniques as $unique)
                {
                    try
                    {
                        $find = str_replace("file_data", $unique, $col['find']);
                        $model_data = $model::withoutGlobalScopes()->where($find)->select($col['return'])->first();
                        $data = array_map(function($item) use($unique, $key, $model_data, $col){
                            if($item[$key] == $unique){
                                $item[$col['field']] = $model_data->{$col['return']};
                            }
                            return $item;
                        }, $data);
                    }
                    catch(\Exception $ex)
                    {
                        continue;
                    }
                    

                }
            }
        }
        return $data;
    }

}
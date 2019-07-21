## DISCLAIMER: ALWAYS TEST IN A SEPARATE ENVIRONMENT FIRST
## DATA IS PRICELESS
===============

# Installation
```
composer require bfatoms/imex
```

# Publish Config
```
php artisan vendor:publish bfatoms/imex
```

# Import Documentation

### Update Or Create
currently the importer uses the updateOrCreate() model function of laravel, the syntax for finding a certain field is

```
http://example.com/api/related-products/import?find=product_id,related_product_type_id,related_product_id
```
the query string above will produce the query below:
```
Product::updateOrCreate([
    'related_product_id' => 'id in csv or parsed from the database',
    'related_product_type_id' => 'id in csv or parsed from the database',
    'product_id' => 'id in csv or parsed from the database'
],[
    array data parsed from csv or database
]);
```

## Simplest Import

By default after installation you may import a file immediately by visiting this route
```
api/{model}/import
```
`
product_prices.csv
`

| product_id  | description | price |
|:-----------:|:----------  | -----:|
| 10000001    | Lollipop    | $2000 |
| 10000002    | Ice Cream   |  $120 |
| 10000003    | Choco Robot |  $100 |


```
$guzzle->post("http://example.com/api/products/import", ["file" => "product_prices.csv"]);
```


## Complex import:

Sometimes a client hands you a list of products with related products for upselling or cross selling, in database you create something like so.


Database Table:
`related_products`

| product_id | related_product_id |
|:----------:|:------------------:|
|10000001|10000002|

and you receive a file that looks like below

`related_products.csv`

| product_code | related_product_code |
|:----------:|:------------------:|
|000-AAA-001|000-AAA-002|
|000-AAA-001|000-AAA-003|
|000-AAA-003|000-AAA-004|


Problem: you wanted to find all product_code from a table and convert it to its product_id

Solution: Query Params
```
Legend:
column[field_name_in_csv][model]=ModelInSystem
column[field_name_in_csv][find][field_in_model]=file_data
column[field_name_in_csv][find][organization_id]=b8f7b594-f50d-454f-9b6c-7067b34d3391
column[field_name_in_csv][return]=ReturnSomethingFromModelLikeID
column[field_name_in_csv][field]=service_id
```
ex. for above `related_products.csv`
```
column[product_code][model]=Product
column[product_code][find][product_code]=file_data
column[product_code][find][organization_id]=b8f7b594-f50d-454f-9b6c-7067b34d3391
column[product_code][return]=id
column[product_code][field]=product_id

column[related_product_code][model]=Product
column[related_product_code][find][product_code]=file_data
column[related_product_code][find][organization_id]=b8f7b594-f50d-454f-9b6c-7067b34d3391
column[related_product_code][return]=id
column[related_product_code][field]=related_product_id
```

### Explanation:
the product_code will be converted to product_id because of the query string `column[product_code][field]=product_id`

the related_product_code will be converted to related_product_id because of the query string `column[related_product_code][field]=related_product_id`

now you satisfy the field names of your table `related_products`

then in the database the other column data will create a query like so:

```
Product::where([
    'product_code' => '000-AAA-001',
    'organization_id'=> 'b8f7b594-f50d-454f-9b6c-7067b34d3391'
])->select('id')->first();
```
the word `file_data` is constant meaning, it will get the data from the csv


## How to use the Custom Import Service

The import service accepts array for parsing 

```
use BfAtoms\Imex\Import;

class Test{
    public function import()
    {
        $data = [
            'product_id' => 1,
            'product_code' => '000-AAA-0001'
        ];
        $import = new Import();
        $import->model('Product');
        $import->import($data);
    }
}
```
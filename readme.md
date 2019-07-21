# Installation
```
composer require bfatoms/imex
```

# Publish Config
```
php artisan vendor:publish bfatoms/imex
```

# Import Documentation

## Simplest Import

By default after installation you may import a file immediately by visiting this route
```
api/{model}/import
```

product_prices.csv

```
product_id,description,price\n
"10001", "Lollipop", 100.00\n
```

```
$guzzle->post("http://example.com/api/products/import", ["file" => "product_prices.csv"]);
```



Complex import:
sometimes you get data that looks like below

```
product_code,description,price
"23947-123","louie", 123.00
```

if you look at it you get product_code, now you wanted to find all product_id of the product_code and import them to a related_products table. In order to do so you only need to specify some parameter but they have a format ex.

parameter format:
```
column=file.field:model_field:[model].field.to.return
```
## Legend:

`
file.field = is the name of the field you defined in the csv. on the product_prices.csv above it is the ["product_id","description","price"]
`

`
model_field = is the field name or column name in the table, ex product_code in products table. If you notice we didn't define the model or table name here, because we will define it in the third colon
`

`
[Model].id = is the field you want to change the file.field into. ex. Product.id
`

#### for multiple columns to change 


## Multiple fields to change

say you receive a file named related_products.csv
```
product_code,related_product_code\n
"100001","123401"\n
```


in your table usually you define it as:
```
related_products
--------------------
id,
product_id
related_product_id
```


now you parameters should look like below
```
?column=product_code:product_code:Product.id,related_product_id:product_code:Product.id
```

# I. Requirement

## 1. Make 2 Entities: Category and Product with these fields:
## Category:
* id (int)
* title (string length min: 3, max 12)
* eId (int|null)

## Product:
* id (int)
* categories (ManyToMany)
* title (string length min: 3, max 12)
* price (float range min: 0, max 200)
* eId (int|null) – custom id from external vendor

## 2. Make CRUD controller and service for the Product entity
Split your logic by controller and service.

## 3. Make console command.
This command reads two json files and adds / updates rows in the database:
### categories.json:
```
[
  {"eId": 1, "title": "Category 1"},
  { "eId": 2,"title": "Category 2"},
  { "eId": 2,"title": "Category 33333333"}
]
```

### products.json
```
[
  {"eId": 1, "title": "Product 1", "price": 101.01, "categoriesEId": [1,2]},
  {"eId": 2, "title": "Product 2", "price": 199.01, "categoryEId": [2,3]},
  {"eId": 3, "title": "Product 33333333", "price": 999.01, "categoryEId": [3,1]}
]
```

## 4. Make an event listener for adding / changing a product entity.
The listener should send an email. The email address must be filled in the .env file.

# II. Run the features
* Import the database from file, put in ```go-store/databases```
* Import data for adding/updating categories: ```php bin/console app:update-category```
* Import data for adding/updating products: ```php bin/console app:update-product```

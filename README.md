# 🍎🥕 Fruits and Vegetables

## 🎯 Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
* Provide an API endpoint to query the collections. As a bonus, this endpoint can accept filters to be applied to the returning collection.
* Provide another API endpoint to add new items to the collections (i.e., your storage engine).
* As a bonus you might:
  * consider giving an option to decide which units are returned (kilograms/grams);
  * how to implement `search()` method collections;
  * use latest version of Symfony's to embed your logic 

### ✔️ How can I check if my code is working?
You have two ways of moving on:
* You call the Service from PHPUnit test like it's done in dummy test (just run `bin/phpunit` from the console)

or

* You create a Controller which will be calling the service with a json payload

## 💡 Hints before you start working on it
* Keep KISS, DRY, YAGNI, SOLID principles in mind
* We value a clean domain model, without unnecessary code duplication or complexity
* Think about how you will handle input validation
* Follow generally-accepted good practices, such as no logic in controllers, information hiding (see the first hint).
* Timebox your work - we expect that you would spend between 3 and 4 hours.
* Your code should be tested
* We don't care how you handle data persistence, no bonus points for having a complex method

## Implementation Complete

This implementation provides a complete fruits and vegetables management system built with Symfony 6.4.

### Features Implemented
- **Item Entity**: Validation, automatic unit conversion (kg ↔ g)
- **Collections**: Separate FruitCollection and VegetableCollection with add/remove/list methods  
- **Storage**: Interface-based in-memory storage with CRUD operations
- **API Endpoints**: RESTful endpoints for querying and adding items
- **Search & Filtering**: Name search and quantity range filtering
- **Unit Conversion**: Flexible display units (grams/kilograms)
- **Console Commands**: Load data from request.json
- **Complete Test Suite**: Unit and integration tests with PHPUnit

### Architecture
- **Entity Layer**: `Item` with validation and unit conversion
- **Collection Layer**: Abstract base class with concrete implementations
- **Storage Layer**: Interface-based repository pattern
- **Service Layer**: `CollectionManager` and enhanced `StorageService`
- **API Layer**: Controllers with JSON validation and error handling

### Running Tests
```bash
# Run all tests
.phpunit
```

### Loading Initial Data
```bash
# Via console command
php bin/console app:load-data

# Via API endpoint
curl http://localhost:8000/load-data
```

### API Usage Examples

#### Get Collections
```bash
# Get all fruits
curl http://localhost:8000/api/collections/fruits

# Get all vegetables  
curl http://localhost:8000/api/collections/vegetables

# Get fruits in kilograms
curl http://localhost:8000/api/collections/fruits?unit=kg
```

#### Search and Filter
```bash
# Search fruits by name
curl http://localhost:8000/api/collections/fruits/search?q=apple

# Filter by quantity range
curl http://localhost:8000/api/collections/vegetables?min_quantity=200&max_quantity=1000&unit=g

# Filter by name
curl http://localhost:8000/api/collections/fruits?name=banana
```

#### Add New Items
```bash
# Add a fruit
curl -X POST http://localhost:8000/api/items \
  -H "Content-Type: application/json" \
  -d '{"name":"Mango","type":"fruit","quantity":1.5,"unit":"kg"}'

# Add a vegetable
curl -X POST http://localhost:8000/api/items \
  -H "Content-Type: application/json" \
  -d '{"name":"Broccoli","type":"vegetable","quantity":300,"unit":"g"}'
```

#### Remove Items
```bash
# Remove item by ID
curl -X DELETE http://localhost:8000/api/items/1
```

### API Response Format
```json
{
  "type": "fruits",
  "count": 2,
  "items": [
    {
      "id": 1,
      "name": "Apple",
      "type": "fruit", 
      "quantity": 500,
      "unit": "g"
    }
  ]
}
```

## 🐳 Docker image
Optional. Just here if you want to run it isolated.

### 📥 Pulling image
```bash
docker pull tturkowski/fruits-and-vegetables
```

### 🧱 Building image
```bash
docker build -t tturkowski/fruits-and-vegetables -f docker/Dockerfile .
```

### 🏃‍♂️ Running container
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables sh 
```

### 🛂 Running tests
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables bin/phpunit
```

### ⌨️ Run development server
```bash
docker run -it -w/app -v$(pwd):/app -p8080:8080 tturkowski/fruits-and-vegetables php -S 0.0.0.0:8080 -t /app/public
# Open http://127.0.0.1:8080 in your browser
```

# Product Importer Project

## Project Overview
This project implements a modular system for importing and validating product data from CSV files. 
The architecture follows the principles of Hexagonal Architecture (Ports and Adapters), ensuring clear separation of concerns, flexibility, and testability.

The project provides a command-line utility for importing product data from a CSV file. 
The tool includes features such as data validation, batch processing, and detailed error reporting. 
It is designed to handle large CSV files efficiently, ensuring seamless integration into the database.

## Features
* CSV Import: Processes product data from CSV files.
* Validation: Ensures required fields are present and properly formatted.
* Batch Processing: Handles large data imports in configurable batch sizes.
* Error Reporting: Outputs detailed information for rows that fail validation.
* Dry-Run Mode: Allows you to test the import process without committing changes to the database.


## Commands
### Import Products
To run the product import process, use the following command:

```shell
php bin/console products:import --batch-size=20 ./tests/_sample/stock_schema.csv --dry-run
````
Arguments
* `path/to/products.csv`: Path to the CSV file to be imported. 
Options
* `--batch-size`: Defines the number of records processed per batch. Default is 25.
* `--dry-run`: Run the command in dry mode to simulate the import without modifying the database.

## File Requirements
* CSV file must be UTF-8 encoded.
* Supported headers:
  * Product Code
  * Product Name
  * Product Description
  * Discontinued

## Testing
Run test suites and static analysis:
```shell
composer run test
```

## Error Handling
After the import process, invalid rows will be displayed in a table format with error details.

Example:
```shell
+-----------+------------ Invalid Rows ------------------------+
| Element # | Error Message                                    |
+-----------+--------------------------------------------------+
| 16        | The "Product Code" field is already in use.      |
| 18        | The "Discontinued" field must be "yes" or empty. |
+-----------+--------------------------------------------------+

```

## Architecture Overview
The project follows a modular and layered architecture, inspired by principles of Hexagonal Architecture (Ports and Adapters). 
Below is an overview of the main layers and their responsibilities:

### Domain Layer
The Domain layer contains the core business logic and rules of the application. 
The layer is completely independent of frameworks and databases.
 * **Use Cases**: Encapsulate application-specific behaviors.
 * **Facade**: Provide an abstraction for orchestrating complex operations

### Application Layer
The Application layer acts as the entry point to the system, handling external interactions.
This includes Command Line Interface (CLI) operations, reading input files, and invoking use cases in the Domain layer.

### Infrastructure Layer
The Infrastructure layer is responsible for implementing technical details such as database interactions, persistence, and configuration.
 * **Entities**: Represent database structures, such as ProductData.
 * **Repositories**: Implement domain-defined repository interfaces (ProductRepository).

### Shared Layer
The Shared layer provides reusable components that can be used across multiple layers for communication and interoperability.
 * **DTOs** (Data Transfer Objects): Facilitate data transfer between layers (ProductDTO, ImportResultDTO).

## Directories
```shel
src/
├── Application/
│   ├── Command/
│   ├── Service/
│   │   └── Products/
├── Domain/
│   ├── Repository/
│   ├── Service/
│   ├── UseCase/
│   │   └── Command/
├── Infrastructure/
│   ├── Doctrine/
│   │   ├── Entity/
│   │   ├── Mapping/
│   │   └── Repository/
├── Shared/
│   ├── DTO/
│   └── Facade/
```

## Contributing
Pull requests are welcome. 
For major changes, please open an issue to discuss proposed updates.


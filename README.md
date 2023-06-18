# Surcharges Cargo Five Challenge Project

The Surcharges Cargo Five API allows users to manage surcharges by uploading Excel files and performing operations on
the data.

## Installation

After clone the repository, open a bash in the project folder and run:

```bash
php composer install
```

```bash
php composer update
```

Then config the .env file with the database connection, something like this:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cargofive
DB_USERNAME=root
DB_PASSWORD=
```

When the database is correctly connected run the migrations

```bash
php artisan migrate
```

Run the initial gruoping rutine

```bash
php artisan surcharges:group
```

Now the platform is ready! You can run the app using

```bash
php artisan serve
```

## Methods

Please note that you need to replace `http://localhost:8000` with the actual base URL of your API.

### getAll()

Gets all surcharges. This is a simple list, show the surcharges without a group.

* **Returns:**
    * A JSON response containing an array of surcharges.
* **Example:**
  `curl -X GET http://localhost:8000/api/surcharges/getAll`

### getAllFathers()

Gets all grouped surcharges. Every surcharge View Model in the answer contains their similar surcharges as sons and
their rates. Look the structure of the response.

* **Returns:**
    * A JSON response containing an array of surcharges.
* **Example:**
  `curl -X GET http://localhost:8000/api/surcharges/getAllFathers`

### updateExcel(Request $request)

Updates surcharges from an Excel file.

* **Parameters:**
    * `Request $request` - The request object.
* **Returns:**
    * A JSON response containing the following properties:
        * `answer` - Boolean value indicating whether the update was successful.
* **Example:**
  `curl -X POST -F excel=@test.xls http://localhost:8000/api/surcharges/updateExcel`

### joinGroups(Request $request)

If the group algorithm is not enough, the user can join groups by the Surcharges ID.

* **Parameters:**
    * `Request $request` - The request object.
        * `idGroupA` - The ID of the first surcharge.
        * `idGroupB` - The ID of the second surcharge.
* **Returns:**
    * A JSON response containing the following properties:
        * `answer` - Boolean value indicating whether the join was successful.
* **Example:**
  `curl -X POST -d 'idGroupA=1&idGroupB=2' http://localhost:8000/api/surcharges/joinGroups`

## Response surcharges JSON Structure

The API responses for certain endpoints return arrays of ViewModel objects. These ViewModels provide a structured
representation of the surcharges and their associated data. Below is an example of the JSON structure for the response:

```json
{
    "id": 1,
    "name": "Surcharge Name",
    "apply_to": "origin",
    "calculation_type_id": 1,
    "isGrouped": true,
    "idFather": 2,
    "calculation_type": {
        // CalculationTypeViewModel details
    },
    "rates": [
        {
            // RateViewModel details
        },
        {
            // RateViewModel details
        }
        // More RateViewModel objects if applicable
    ],
    "sons": [
        {
            // SurchargeViewModel details
        },
        {
            // SurchargeViewModel details
        }
        // More SurchargeViewModel objects if applicable
    ]
}
```

## Database updates

In this version, changes have been made to the structure of the `surcharges` table to improve grouping capabilities and
the management of associated rates.

Two new fields have been added to the `surcharges` table:

- `isGrouped`: This boolean field indicates whether the surcharge has been grouped. When a surcharge is grouped with
  other similar surcharges, it is marked as grouped.
- `idFather`: This field stores the ID of the parent surcharge in case the surcharge is part of a group. It establishes
  the parent-child relationship among grouped surcharges.

These additional fields provide a more efficient way to identify and handle surcharge groups.

Additionally, it is suggested to move the `apply_to` field to the `rates` table. This is because a single surcharge can
have multiple associated rates, and each rate can apply to different areas, such as `'origin'`, `'freight'`,
or `'destination'`. This modification will allow for greater flexibility and accuracy in defining the rate applications
for each surcharge.

These updates to the `surcharges` table structure enhance management capabilities and provide clearer relationships
between surcharges and their associated rates.

## Project Structure

The project follows a structured architecture that separates concerns and promotes maintainability. Here is an overview
of the different components and their roles:

### Repositories

Repositories are responsible for handling database queries and operations. They provide an abstraction layer that
decouples the business logic from the underlying database implementation. By using repositories, the project becomes
more adaptable to potential changes in the database structure. Each repository has an associated interface that defines
the contract for interacting with the data.

### Services

Services encapsulate the business logic of the application. They orchestrate the operations and coordinate the
interactions between repositories, external services, and other components. Services utilize the repositories to perform
database operations and abstract away the details of data retrieval and manipulation. By separating the logic into
services, the codebase becomes more modular and easier to test. Services also have corresponding interfaces that define
their functionality.

### ViewModels

ViewModels represent the data that is exposed by the API. They serve as a structured representation of the models but
without any business functionalities. ViewModels are used to shape the data returned by the API endpoints and provide a
clear and consistent structure for clients consuming the API.

### Helpers

Helpers contain utility classes and functions that assist in various tasks within the application. In this project, a
class called JJKData is used for grouping similar data using the Jaro Winkler algorithm. The JJKData class resides in
the helpers folder and provides the necessary functionality for grouping surcharges based on their names.

### Dependency Injection

The project leverages dependency injection to manage the dependencies between classes. All repositories, services, and
other classes that require external dependencies have their corresponding abstract interfaces. The concrete
implementations of these interfaces are registered in the app service provider, allowing for loose coupling and easier
swapping of implementations.

### Routes and Request Validation

All API routes are defined in the api.php file within the routes folder. This file contains the route definitions and
maps them to the respective controllers. Additionally, a request validator is implemented using the JoinRequest.php
file. The request validator validates and sanitizes incoming requests, ensuring that the provided data meets the defined
criteria before further processing.

By adhering to this project structure and architectural approach, the codebase becomes more organized, maintainable, and
scalable.

## Unit Testing

The test suite includes various test cases to validate the functionality of the application. These tests cover some
scenarios, such as retrieving surcharges, updating surcharges with Excel files, and joining surcharge groups. Although
testing may not be my strongest skill, I have made an effort to ensure that the essential features are thoroughly
tested.

To run the unit tests for this project, you can use the following command:

```shell
php artisan test
```

## Vue.js App

In addition to the Laravel API, I have developed a small Vue.js 3 application using Vite, showcasing my front-end
development skills. This app serves as a demonstration of my proficiency in Vue.js, Vue Router, and JavaScript, which
can be valuable if there is a future need to migrate the system to a Node.js environment.

The Vue.js app consists of a single view that interacts with the RESTful API developed in Laravel. By leveraging the
structure and features of Vue.js, the app provides a seamless user experience and demonstrates my ability to build
responsive and dynamic front-end applications.

As a full-stack developer, I have both the expertise in Laravel for backend development and the skills to create
engaging user interfaces with Vue.js for the frontend. This combination allows me to contribute to the entire software
development life cycle and ensures a cohesive integration between the backend and frontend components.

## Running the Vue.js App

To run the Vue.js app locally, follow these instructions:

1. Open a terminal or command prompt.
2. Navigate to the project directory.
3. Run the following command to install the dependencies:

```bash
npm install
```

After the dependencies are successfully installed, execute the following command to start the development server:

```bash
npm run dev
 ```

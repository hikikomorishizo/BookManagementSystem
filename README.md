# Book Management System

## Description

This project is a Book Management System developed using Symfony. It provides functionality for adding, editing, deleting, and viewing books. It also includes fixtures for testing purposes.

## Installation

### Requirements

- PHP 8.0 or higher
- Composer
- Symfony 7

### Install Dependencies

1. Clone the repository:

```bash
   git clone <https://github.com/hikikomorishizo/BookManagementSystem.git>
   cd book_management
```

2. Install the project dependencies:
    Install the project dependencies:
```bash
    composer install 
```

3. Configure environment variables:

    Copy the .env file and edit it to suit your configuration:
```bash
    cp .env .env.local
    Ensure that your database settings and other environment variables are correctly specified.
```

4. Create the database and run migrations:

```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
```

5. Load fixtures (optional):

```bash
    php bin/console doctrine:fixtures:load
```
Warning: This command will load test data into your database.

6. Start the Symfony development server:

    symfony server:start

    Or use the built-in PHP server:
    
```bash
    DATABASE_URL="mysql://root:@127.0.0.1:3306/book_management" php -S 127.0.0.1:8000 -t public
```

7. Usage

Book List: Navigate to http://127.0.0.1:8000/books to view the list of books.
Add a Book: Go to http://127.0.0.1:8000/books/new to add a new book.
Edit a Book: Visit http://127.0.0.1:8000/books/{id}/edit, replacing {id} with the book ID to edit it.
Delete a Book: Go to http://127.0.0.1:8000/books/{id}/delete, replacing {id} with the book ID to delete it.
View a Book: Navigate to http://127.0.0.1:8000/books/{id}, replacing {id} with the book ID to view its details.

8. Logging
Logging in the project is configured using Monolog. Logs are recorded in var/logs/dev.log for development mode and var/logs/prod.log for production mode.

9. Symfony Commands:

```bash
    php bin/console list
```
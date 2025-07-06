<?php
// repositories/BookRepository.php

require_once __DIR__ . '/../traits/Logger.php';
require_once __DIR__ . '/RepositoryInterface.php';
require_once __DIR__ . '/../models/Book.php';

class BookRepository implements RepositoryInterface {
    use Logger; // Use the Logger trait

    private $conn;
    private $table = 'books';

    /**
     * Constructor with Database connection
     * @param PDO $db
     */
    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /**
     * Get all books from the database.
     * @return array An array of Book objects.
     */
    public function getAll() {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new Book($row);
        }
        $this->log("Fetched all books.", "INFO");
        return $books;
    }

    /**
     * Get a single book by ID.
     * @param int $id The ID of the book.
     * @return Book|null The Book object if found, otherwise null.
     */
    public function getById($id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->log("Fetched book with ID: $id", "INFO");
            return new Book($row);
        }
        $this->log("Book with ID: $id not found.", "WARNING");
        return null;
    }

    /**
     * Create a new book.
     * @param array $data Associative array of book data.
     * @return bool True on success, false on failure.
     */
    public function create(array $data) {
        $query = 'INSERT INTO ' . $this->table . '
                  SET title = :title, author = :author, isbn = :isbn,
                      published_year = :published_year, genre = :genre';
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $data = $this->sanitizeData($data);

        // Bind data
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':author', $data['author']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':published_year', $data['published_year'], PDO::PARAM_INT);
        $stmt->bindParam(':genre', $data['genre']);

        if ($stmt->execute()) {
            $this->log("Created new book: " . $data['title'], "INFO");
            return true;
        }
        $this->log("Error creating book: " . $stmt->errorInfo()[2], "ERROR");
        return false;
    }

    /**
     * Update an existing book.
     * @param int $id The ID of the book to update.
     * @param array $data Associative array of book data.
     * @return bool True on success, false on failure.
     */
    public function update($id, array $data) {
        
        $query = 'UPDATE ' . $this->table . '
                  SET title = :title, author = :author, isbn = :isbn,
                      published_year = :published_year, genre = :genre
                  WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $data = $this->sanitizeData($data);
        $id = htmlspecialchars(strip_tags($id));

        // Bind data
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':author', $data['author']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':published_year', $data['published_year'], PDO::PARAM_INT);
        $stmt->bindParam(':genre', $data['genre']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->log("Updated book with ID: $id", "INFO");
            return true;
        }
        $this->log("Error updating book with ID $id: " . $stmt->errorInfo()[2], "ERROR");
        return false;
    }

    /**
     * Delete a book.
     * @param int $id The ID of the book to delete.
     * @return bool True on success, false on failure.
     */
    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->log("Deleted book with ID: $id", "INFO");
            return true;
        }
        $this->log("Error deleting book with ID $id: " . $stmt->errorInfo()[2], "ERROR");
        return false;
    }

    /**
     * Sanitize input data.
     * @param array $data The data to sanitize.
     * @return array The sanitized data.
     */
    private function sanitizeData(array $data) {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars(strip_tags($value));
        }
        return $data;
    }
}

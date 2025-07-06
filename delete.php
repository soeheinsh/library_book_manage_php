<?php
// delete.php - Page to delete a book

// Include necessary files
include_once 'config/Database.php';
include_once 'repositories/BookRepository.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate Book object
$book = new BookRepository($db);

// Check if ID is provided in URL
if (isset($_GET['id'])) {
    $book->id = $_GET['id'];

    // Attempt to delete book
    if ($book->delete($book->id)) {
        // Redirect to index.php after successful deletion
        header('Location: index.php?message=Book deleted successfully!');
        exit();
    } else {
        // Redirect with error message
        header('Location: index.php?error=Error deleting book.');
        exit();
    }
} else {
    // If no ID, redirect to index
    header('Location: index.php');
    exit();
}
?>

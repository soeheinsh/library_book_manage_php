<?php
// add.php - Page to add a new book

// Include necessary files
require_once __DIR__ . '/vendor/autoload.php'; // For Dotenv
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/repositories/BookRepository.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate BookRepository
$bookRepository = new BookRepository($db);

$message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect data from POST
    $bookData = [
        'title' => $_POST['title'] ?? '',
        'author' => $_POST['author'] ?? '',
        'isbn' => $_POST['isbn'] ?? '',
        'published_year' => $_POST['published_year'] ?? null,
        'genre' => $_POST['genre'] ?? ''
    ];

    // Attempt to create book
    if ($bookRepository->create($bookData)) {
        $message = '<p class="text-green-600 text-center mb-4">Book added successfully!</p>';
        // Clear form fields after successful submission
        $_POST = array(); // Reset POST to clear form
    } else {
        $message = '<p class="text-red-600 text-center mb-4">Error adding book. Please check ISBN for uniqueness or missing data.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            box-sizing: border-box;
        }
        .btn-submit {
            background-color: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
            width: 100%;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #059669;
        }
        .btn-back {
            background-color: #6b7280;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-top: 1rem;
            width: 100%;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add New Book</h1>

        <?php echo $message; ?>

        <form action="add.php" method="POST">
            <div class="mb-4">
                <label for="title" class="form-label">Title:</label>
                <input type="text" id="title" name="title" class="form-input" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            </div>
            <div class="mb-4">
                <label for="author" class="form-label">Author:</label>
                <input type="text" id="author" name="author" class="form-input" required value="<?php echo htmlspecialchars($_POST['author'] ?? ''); ?>">
            </div>
            <div class="mb-4">
                <label for="isbn" class="form-label">ISBN:</label>
                <input type="text" id="isbn" name="isbn" class="form-input" required value="<?php echo htmlspecialchars($_POST['isbn'] ?? ''); ?>">
            </div>
            <div class="mb-4">
                <label for="published_year" class="form-label">Published Year:</label>
                <input type="number" id="published_year" name="published_year" class="form-input" value="<?php echo htmlspecialchars($_POST['published_year'] ?? ''); ?>">
            </div>
            <div class="mb-4">
                <label for="genre" class="form-label">Genre:</label>
                <input type="text" id="genre" name="genre" class="form-input" value="<?php echo htmlspecialchars($_POST['genre'] ?? ''); ?>">
            </div>
            <button type="submit" class="btn-submit">Add Book</button>
        </form>
        <a href="index.php" class="btn-back">Back to List</a>
    </div>
</body>
</html>

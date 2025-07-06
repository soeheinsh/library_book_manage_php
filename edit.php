<?php
// edit.php - Page to edit an existing book

// Include necessary files
include_once 'config/Database.php';
include_once 'models/Book.php';
include_once 'repositories/BookRepository.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate BookRepository
$bookRepo = new BookRepository($db);

$message = '';

// Check if ID is provided in URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $book = $bookRepo->getById($book_id); // Populate book properties from DB

    // If book not found, redirect to index
    if (!$book->title) {
        header('Location: index.php');
        exit();
    }
} else {

    // If no ID, redirect to index
    header('Location: index.php');
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set book properties from POST data
    $book->title = $_POST['title'];
    $book->author = $_POST['author'];
    $book->isbn = $_POST['isbn'];
    $book->published_year = $_POST['published_year'];
    $book->genre = $_POST['genre'];

    // Attempt to update book
    if ($bookRepo->update($book_id, $book->toArray())) {
        $message = '<p class="text-green-600 text-center mb-4">Book updated successfully!</p>';
        // Re-read single to reflect updated data immediately
        $bookRepo->getById($book_id);
        header('Location: index.php');
        exit();
        
    } else {
        $message = '<p class="text-red-600 text-center mb-4">Error updating book. Please check ISBN for uniqueness.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
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
            background-color: #2563eb;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
            width: 100%;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #1d4ed8;
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
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Book</h1>

        <?php echo $message; ?>

        <form action="edit.php?id=<?php echo htmlspecialchars($book->id); ?>" method="POST">
            <div class="mb-4">
                <label for="title" class="form-label">Title:</label>
                <input type="text" id="title" name="title" class="form-input" required value="<?php echo htmlspecialchars($book->title); ?>">
            </div>
            <div class="mb-4">
                <label for="author" class="form-label">Author:</label>
                <input type="text" id="author" name="author" class="form-input" required value="<?php echo htmlspecialchars($book->author); ?>">
            </div>
            <div class="mb-4">
                <label for="isbn" class="form-label">ISBN:</label>
                <input type="text" id="isbn" name="isbn" class="form-input" required value="<?php echo htmlspecialchars($book->isbn); ?>">
            </div>
            <div class="mb-4">
                <label for="published_year" class="form-label">Published Year:</label>
                <input type="number" id="published_year" name="published_year" class="form-input" value="<?php echo htmlspecialchars($book->published_year); ?>">
            </div>
            <div class="mb-4">
                <label for="genre" class="form-label">Genre:</label>
                <input type="text" id="genre" name="genre" class="form-input" value="<?php echo htmlspecialchars($book->genre); ?>">
            </div>
            <button type="submit" class="btn-submit">Update Book</button>
        </form>
        <a href="index.php" class="btn-back">Back to List</a>
    </div>
</body>
</html>

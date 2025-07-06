<?php
// index.php - Main page to display books

// Include necessary files
require_once __DIR__ . '/vendor/autoload.php'; // For Dotenv
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/repositories/BookRepository.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate BookRepository
$bookRepository = new BookRepository($db);

// Get all books
$books = $bookRepository->getAll();
$num = count($books);

// Handle messages from redirects (e.g., after delete)
$message = '';
if (isset($_GET['message'])) {
    $message = '<p class="text-green-600 text-center mb-4">' . htmlspecialchars($_GET['message']) . '</p>';
}
if (isset($_GET['error'])) {
    $message = '<p class="text-red-600 text-center mb-4">' . htmlspecialchars($_GET['error']) . '</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Book Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
        .btn-edit {
            background-color: #2563eb;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-edit:hover {
            background-color: #1d4ed8;
        }
        .btn-delete {
            background-color: #dc2626;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-delete:hover {
            background-color: #b91c1c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
        tr:hover {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Library Book Management</h1>

        <?php echo $message; // Display success/error messages ?>

        <div class="flex justify-end mb-6">
            <a href="add.php" class="btn-primary">Add New Book</a>
        </div>

        <?php if ($num > 0): ?>
            <div class="overflow-x-auto rounded-lg shadow">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Published Year</th>
                            <th>Genre</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book->title); ?></td>
                                <td><?php echo htmlspecialchars($book->author); ?></td>
                                <td><?php echo htmlspecialchars($book->isbn); ?></td>
                                <td><?php echo htmlspecialchars($book->published_year); ?></td>
                                <td><?php echo htmlspecialchars($book->genre); ?></td>
                                <td class="whitespace-nowrap">
                                    <a href="edit.php?id=<?php echo $book->id; ?>" class="btn-edit mr-2">Edit</a>
                                    <a href="delete.php?id=<?php echo $book->id; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600">No books found in the library. Add some!</p>
        <?php endif; ?>
    </div>
</body>
</html>

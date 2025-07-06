<?php
// models/Book.php

class Book {
    public $id;
    public $title;
    public $author;
    public $isbn;
    public $published_year;
    public $genre;
    public $created_at;

    /**
     * Constructor to initialize book properties.
     * @param array $data Associative array of book data.
     */
    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Converts the Book object to an associative array.
     * @return array
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'published_year' => $this->published_year,
            'genre' => $this->genre,
            'created_at' => $this->created_at,
        ];
    }
}

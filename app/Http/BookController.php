<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->get();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'year' => 'required|integer',
            'category' => 'required',
            'description' => 'required',
            'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $cover = null;

if ($request->hasFile('cover')) {
    $cover = $request->file('cover')->store('covers', 'public');
}

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'category' => $request->category,
            'description' => $request->description,
            'cover' => $cover
        ]);

        return redirect('/books')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'year' => 'required|integer',
            'category' => 'required',
            'description' => 'required',
        ]);

        if ($request->hasFile('cover')) {

            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }

            $cover = $request->file('cover')->store('covers', 'public');

            $book->cover = $cover;
        }

        $book->title = $request->title;
        $book->author = $request->author;
        $book->publisher = $request->publisher;
        $book->year = $request->year;
        $book->category = $request->category;
        $book->description = $request->description;

        $book->save();

        return redirect('/books')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Book $book)
    {
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect('/books')->with('success', 'Data berhasil dihapus');
    }
}
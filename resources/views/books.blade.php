<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku Digital - Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="antialiased text-gray-800 bg-gradient-to-br from-pink-200 via-white to-pink-50 min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-pink-700">Manajemen Buku Digital</h1>
                <p class="text-gray-600 mt-1">Sistem Informasi Perpustakaan Digital</p>
            </div>
            <button onclick="openModal()" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-md transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Buku
            </button>
        </div>

        <!-- Alert Sukses -->
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                            <th class="p-4 font-semibold">ID</th>
                            <th class="p-4 font-semibold">Sampul & Info</th>
                            <th class="p-4 font-semibold">Penerbit & Tahun</th>
                            <th class="p-4 font-semibold">Kategori</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($books as $book)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 text-sm text-gray-500">{{ $book->id }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    @if($book->cover)
                                    <img src="{{ Str::startsWith($book->cover, 'http') ? $book->cover : asset($book->cover) }}" class="w-12 h-16 rounded object-cover flex-shrink-0 shadow-sm">
                                    @else
                                    <div class="w-12 h-16 bg-gray-200 rounded object-cover flex-shrink-0 shadow-sm flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $book->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $book->author }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="text-gray-800">{{ $book->publisher }}</div>
                                <div class="text-sm text-gray-500">{{ $book->year }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full">{{ $book->category }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button onclick="editBook({{ $book->id }}, '{{ addslashes($book->title) }}', '{{ addslashes($book->author) }}', '{{ addslashes($book->publisher) }}', {{ $book->year }}, '{{ addslashes($book->category) }}', '{{ addslashes($book->description) }}')" class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors flex items-center justify-center" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-colors flex items-center justify-center" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">Belum ada data buku.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (Mockup) -->
            <div class="p-4 border-t border-gray-100 flex items-center justify-between text-sm">
                <div class="text-gray-500">Menampilkan 1 hingga 2 dari 2 entri</div>
                <div class="flex gap-1">
                    <button class="px-3 py-1 border border-gray-200 rounded hover:bg-gray-50 disabled:opacity-50 text-gray-600">Sebelumnnya</button>
                    <button class="px-3 py-1 bg-indigo-600 text-white rounded">1</button>
                    <button class="px-3 py-1 border border-gray-200 rounded hover:bg-gray-50 text-gray-600">Selanjutnya</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah/Edit -->
    <div id="bookModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm hidden z-50 overflow-y-auto">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl transform transition-all">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Tambah Buku Baru</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form id="bookForm" action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($errors->any())
                            <div class="col-span-1 md:col-span-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <strong class="font-bold">Gagal menyimpan data!</strong>
                                <ul class="list-disc pl-5 mt-2 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Judul Buku -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" required minlength="3" maxlength="255"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="Masukkan judul buku">
                                <div class="invalid-feedback text-red-500 text-xs mt-1 hidden">Judul wajib diisi (minimal 3 karakter).</div>
                            </div>

                            <!-- Penulis -->
                            <div>
                                <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Penulis <span class="text-red-500">*</span></label>
                                <input type="text" id="author" name="author" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="Nama Penulis">
                                <div class="invalid-feedback text-red-500 text-xs mt-1 hidden">Nama penulis wajib diisi.</div>
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                                <select id="category" name="category" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="Novel">Novel</option>
                                    <option value="Sastra Sejarah">Sastra Sejarah</option>
                                    <option value="Sains">Sains</option>
                                    <option value="Teknologi">Teknologi</option>
                                    <option value="Pendidikan">Pendidikan</option>
                                </select>
                                <div class="invalid-feedback text-red-500 text-xs mt-1 hidden">Kategori wajib dipilih.</div>
                            </div>

                            <!-- Penerbit -->
                            <div>
                                <label for="publisher" class="block text-sm font-medium text-gray-700 mb-1">Penerbit <span class="text-red-500">*</span></label>
                                <input type="text" id="publisher" name="publisher" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="Nama Penerbit">
                                <div class="invalid-feedback text-red-500 text-xs mt-1 hidden">Penerbit wajib diisi.</div>
                            </div>

                            <!-- Tahun Terbit -->
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit <span class="text-red-500">*</span></label>
                                <input type="number" id="year" name="year" required min="1000" max="2099"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="Contoh: 2023">
                                <div class="invalid-feedback text-red-500 text-xs mt-1 hidden">Tahun wajib diisi dengan format 4 digit angka.</div>
                            </div>

                            <!-- Unggah Sampul -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="cover" class="block text-sm font-medium text-gray-700 mb-1">Unggah Sampul Buku (Opsional)</label>
                                <input type="file" id="cover" name="cover" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                <div class="invalid-feedback text-red-500 text-xs mt-1 hidden">Pilih file gambar yang valid.</div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all resize-none"
                                    placeholder="Tuliskan deskripsi singkat atau sinopsis buku..."></textarea>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="mt-8 pt-4 border-t border-gray-100 flex justify-end gap-3">
                            <button type="button" onclick="closeModal()" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors font-medium shadow-md">
                                Simpan Buku
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Validasi dan Modal -->
    <script>
        const modal = document.getElementById('bookModal');
        const form = document.getElementById('bookForm');

        function openModal() {
            document.getElementById('modalTitle').innerText = 'Tambah Buku Baru';
            document.getElementById('bookForm').action = '{{ route("books.store") }}';
            document.getElementById('_method').value = 'POST';
            
            modal.classList.remove('hidden');
            // Menambahkan sedikit delay untuk efek transisi
            setTimeout(() => {
                modal.firstElementChild.firstElementChild.classList.add('scale-100', 'opacity-100');
                modal.firstElementChild.firstElementChild.classList.remove('scale-95', 'opacity-0');
            }, 10);
            
            // Reset form dan validasi saat modal dibuka
            form.reset();
            resetValidation();
        }

        function editBook(id, title, author, publisher, year, category, description) {
            document.getElementById('modalTitle').innerText = 'Edit Buku';
            document.getElementById('bookForm').action = '/books/' + id;
            document.getElementById('_method').value = 'PUT';
            
            document.getElementById('title').value = title;
            document.getElementById('author').value = author;
            document.getElementById('publisher').value = publisher;
            document.getElementById('year').value = year;
            document.getElementById('category').value = category;
            document.getElementById('description').value = description;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.firstElementChild.classList.add('scale-100', 'opacity-100');
                modal.firstElementChild.firstElementChild.classList.remove('scale-95', 'opacity-0');
            }, 10);
            
            resetValidation();
        }

        function closeModal() {
            modal.firstElementChild.firstElementChild.classList.add('scale-95', 'opacity-0');
            modal.firstElementChild.firstElementChild.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Menutup modal jika klik di luar box modal
        modal.addEventListener('click', function(e) {
            if (e.target === this || e.target.closest('.min-h-screen') === this) {
                closeModal();
            }
        });

        function resetValidation() {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.classList.remove('border-red-500');
                input.classList.add('border-gray-300');
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.classList.add('hidden');
                }
            });
        }

        // Custom Form Validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            resetValidation();

            // Cek setiap input yang required
            const inputs = form.querySelectorAll('input[required], select[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim() || !input.checkValidity()) {
                    isValid = false;
                    // Styling error
                    input.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                    input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    
                    // Show message
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.classList.remove('hidden');
                    }
                }
            });

            // Validasi File (Opsional, browser sudah membatasi tipe file dengan accept="image/*")
            const fileInput = document.getElementById('cover');
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                if (!file.type.startsWith('image/')) {
                    isValid = false;
                    fileInput.classList.remove('border-gray-300');
                    fileInput.classList.add('border-red-500');
                    fileInput.nextElementSibling.classList.remove('hidden');
                }
            }

            if (!isValid) {
                e.preventDefault(); // Hanya cegah submit jika tidak valid
            }
        });

        // Buka modal otomatis jika ada error validasi dari server
        @if ($errors->any())
            openModal();
        @endif
    </script>
</body>
</html>

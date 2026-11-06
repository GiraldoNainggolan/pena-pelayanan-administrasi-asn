<!DOCTYPE html>
<!-- Mengubah font-size dasar ke 13px akan mengecilkan seluruh ukuran UI Tailwind (padding, text, w, h) sebesar ~81% menyerupai zoom-out 80% -->
<html lang="id" style="font-size: 13px;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name'))</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />
        <!-- SweetAlert2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }
            .swal2-styled.swal2-confirm { background-color: #0284c7 !important; }
            .swal2-styled.swal2-cancel { background-color: #f1f5f9 !important; color: #475569 !important; }
        </style>
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased text-sm">
        <x-toasts />

        @auth
            <!-- Full Width Modern Dashboard Layout -->
            <!-- Memaksa flex-row agar sidebar selalu di kiri dan tidak menumpuk ke atas pada layar laptop kecil -->
            <div class="flex flex-row h-screen overflow-hidden bg-slate-50">
                
                <!-- Sidebar Component -->
                <x-sidebar />

                <!-- Content Area -->
                <div class="flex-1 flex flex-col min-w-0 bg-slate-50 h-full overflow-y-auto">
                    
                    <!-- Header Navbar Component -->
                    <x-navbar />

                    <!-- Main Content (Scrollable) -->
                    <main class="flex-1 p-4 sm:p-6 md:p-8">
                        <div class="mx-auto max-w-7xl">
                            @yield('content')
                        </div>
                    </main>
                </div>
                
            </div>
        @else
            <!-- Guest Area -->
            <main class="min-h-screen flex items-center justify-center p-6 bg-slate-50">
                @yield('content')
            </main>
        @endauth

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Intercept forms with 'onsubmit' containing 'confirm'
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    const onsubmitAttr = form.getAttribute('onsubmit');
                    if (onsubmitAttr && onsubmitAttr.includes('return confirm')) {
                        // Extract the message from confirm('message')
                        const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                        const message = match ? match[1] : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                        
                        // Remove original inline onsubmit to prevent browser native dialog
                        form.removeAttribute('onsubmit');
                        
                        // Add sweetalert trigger
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Konfirmasi',
                                text: message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#e11d48',
                                cancelButtonColor: '#f1f5f9',
                                confirmButtonText: 'Ya, Lanjutkan!',
                                cancelButtonText: 'Batal',
                                customClass: {
                                    cancelButton: 'text-slate-600 font-bold',
                                    confirmButton: 'font-bold'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        });
                    }
                });
            });
        </script>
    </body>
</html>

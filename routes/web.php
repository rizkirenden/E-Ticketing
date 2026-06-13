<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\JenisaplikasiController;
use App\Http\Controllers\RoleaplikasiController;
use App\Http\Controllers\Detaillaporan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanPdfController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ActivityLogPdfController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ProdukController; // Pastikan ini diimport
use App\Http\Controllers\BaganitController; // Pastikan ini diimport

// ===================================================
// PUBLIC ROUTES - TIDAK MEMERLUKAN LOGIN
// ===================================================

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/bagan-it', [BaganitController::class, 'index'])->name('bagan.it');
// Laporan Create - Bisa diakses publik
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/create', [LaporanController::class, 'create'])->name('create');
    Route::post('/', [LaporanController::class, 'store'])->name('store');
    Route::get('/get-products/{jenisAplikasiId}', [LaporanController::class, 'getProductsByApplication'])->name('get.products');
});

// Detail laporan (Tracking) - Bisa diakses publik
Route::prefix('detail')->name('detail_laporan.')->group(function () {
    Route::get('/laporan', [Detaillaporan::class, 'index'])->name('index');
    Route::get('/laporan/{id}', [Detaillaporan::class, 'show'])->name('show');
    Route::post('/laporan/track', [Detaillaporan::class, 'track'])->name('track');
});

// ===================================================
// GUEST ROUTES - HANYA UNTUK USER BELUM LOGIN
// ===================================================
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

// ===================================================
// PROTECTED ROUTES - MEMERLUKAN LOGIN
// ===================================================
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard - Perlu permission dashboard
    Route::middleware(['permission:dashboard,view'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    });

    // ===================================================
    // LAPORAN (TICKETING) ROUTES
    // ===================================================
    Route::prefix('laporan')->name('laporan.')->middleware(['permission:laporan,view'])->group(function () {
        // View routes
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/{id}', [LaporanController::class, 'show'])->name('show');

        // Create routes - perlu permission create
        Route::middleware(['permission:laporan,create'])->group(function () {
            // Create sudah di public, tapi untuk akses dari dalam perlu permission
        });

        // Edit/Update routes - perlu permission edit
        Route::middleware(['permission:laporan,edit'])->group(function () {
            Route::get('/{id}/edit', [LaporanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [LaporanController::class, 'update'])->name('update');
            Route::put('/{id}/status', [LaporanController::class, 'updateStatus'])->name('updateStatus');
        });

        // Delete routes - perlu permission delete
        Route::middleware(['permission:laporan,delete'])->group(function () {
            Route::delete('/{id}', [LaporanController::class, 'destroy'])->name('destroy');
        });

        // WhatsApp route - bisa diakses jika view permission
        Route::get('/{id}/whatsapp', [LaporanController::class, 'generateWhatsappMessage'])->name('whatsapp');

    });

    // ===================================================
    // LAPORAN PDF ROUTES
    // ===================================================
    Route::prefix('laporan-pdf')->name('laporan.')->middleware(['permission:laporan,export'])->group(function () {
        Route::get('/pdf', [LaporanPdfController::class, 'generate'])->name('pdf');
        Route::get('/{id}/pdf', [LaporanPdfController::class, 'generateSingle'])->name('pdf.single');
    });

    // ===================================================
    // LAPORAN EXCEL ROUTE - PERMISSION TERPISAH
    // ===================================================
    Route::prefix('laporan')->name('laporan.')->middleware(['auth', 'permission:laporan,excel'])->group(function () {
        Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export-excel');
    });

    // ===================================================
    // ACTIVITY LOGS ROUTES
    // ===================================================
  // Activity Logs Routes
Route::prefix('activity-logs')->name('activity-logs.')->middleware(['permission:activity-logs,view'])->group(function () {
    Route::get('/statistics', [ActivityLogController::class, 'statistics'])->name('statistics');
    Route::get('/all-statistics', [ActivityLogController::class, 'getAllStatistics'])->name('all-statistics');
    Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
    Route::get('/export-pdf', [ActivityLogPdfController::class, 'generate'])->name('export-pdf');
    Route::get('/{id}', [ActivityLogController::class, 'show'])->name('show');
    Route::get('/{id}/export-pdf', [ActivityLogPdfController::class, 'generateSingle'])->name('export-pdf-single');
});

    // ===================================================
    // KANTOR ROUTES (CRUD dengan permission)
    // ===================================================
    Route::prefix('kantor')->name('kantor.')->middleware(['permission:kantor,view'])->group(function () {
        Route::get('/', [KantorController::class, 'index'])->name('index');
        Route::get('/{id}', [KantorController::class, 'show'])->name('show');

        // Create - perlu permission create
        Route::middleware(['permission:kantor,create'])->group(function () {
            Route::get('/create', [KantorController::class, 'create'])->name('create');
            Route::post('/', [KantorController::class, 'store'])->name('store');
        });

        // Edit/Update - perlu permission edit
        Route::middleware(['permission:kantor,edit'])->group(function () {
            Route::get('/{id}/edit', [KantorController::class, 'edit'])->name('edit');
            Route::put('/{id}', [KantorController::class, 'update'])->name('update');
        });

        // Delete - perlu permission delete
        Route::middleware(['permission:kantor,delete'])->group(function () {
            Route::delete('/{id}', [KantorController::class, 'destroy'])->name('destroy');
        });
    });

    // ===================================================
    // JENIS APLIKASI ROUTES (CRUD dengan permission)
    // ===================================================
    Route::prefix('jenis-aplikasi')->name('jenis-aplikasi.')->middleware(['permission:jenis-aplikasi,view'])->group(function () {
        Route::get('/', [JenisaplikasiController::class, 'index'])->name('index');
        Route::get('/{id}', [JenisaplikasiController::class, 'show'])->name('show');

        // Create - perlu permission create
        Route::middleware(['permission:jenis-aplikasi,create'])->group(function () {
            Route::get('/create', [JenisaplikasiController::class, 'create'])->name('create');
            Route::post('/', [JenisaplikasiController::class, 'store'])->name('store');
        });

        // Edit/Update - perlu permission edit
        Route::middleware(['permission:jenis-aplikasi,edit'])->group(function () {
            Route::get('/{id}/edit', [JenisaplikasiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [JenisaplikasiController::class, 'update'])->name('update');
        });

        // Delete - perlu permission delete
        Route::middleware(['permission:jenis-aplikasi,delete'])->group(function () {
            Route::delete('/{id}', [JenisaplikasiController::class, 'destroy'])->name('destroy');
        });
    });

    // ===================================================
    // PRODUK ROUTES (CRUD dengan permission)
    // ===================================================
    Route::prefix('produk')->name('produk.')->middleware(['permission:produk,view'])->group(function () {
        Route::get('/', [ProdukController::class, 'index'])->name('index');
        Route::get('/{id}', [ProdukController::class, 'show'])->name('show');

        // Create - perlu permission create
        Route::middleware(['permission:produk,create'])->group(function () {
            Route::get('/create', [ProdukController::class, 'create'])->name('create');
            Route::post('/', [ProdukController::class, 'store'])->name('store');
        });

        // Edit/Update - perlu permission edit
        Route::middleware(['permission:produk,edit'])->group(function () {
            Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProdukController::class, 'update'])->name('update');
        });

        // Delete - perlu permission delete
        Route::middleware(['permission:produk,delete'])->group(function () {
            Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('destroy');
        });
    });

    // ===================================================
    // USER MANAGEMENT ROUTES (CRUD dengan permission)
    // ===================================================
    Route::prefix('user')->name('user.')->middleware(['permission:user,view'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');

        // Create - perlu permission create
        Route::middleware(['permission:user,create'])->group(function () {
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
        });

        // Edit/Update - perlu permission edit
        Route::middleware(['permission:user,edit'])->group(function () {
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
        });

        // Delete - perlu permission delete
        Route::middleware(['permission:user,delete'])->group(function () {
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

    // ===================================================
    // ROLE MANAGEMENT ROUTES (CRUD dengan permission)
    // ===================================================
    Route::prefix('role')->name('role.')->middleware(['permission:role,view'])->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');

        // Create - perlu permission create
        Route::middleware(['permission:role,create'])->group(function () {
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
        });

        // Edit/Update - perlu permission edit
        Route::middleware(['permission:role,edit'])->group(function () {
            Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        });

        // Delete - perlu permission delete
        Route::middleware(['permission:role,delete'])->group(function () {
            Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        });
    });

    // ===================================================
    // ROLE APLIKASI (PERMISSION) ROUTES
    // ===================================================
    Route::prefix('role-aplikasi')->name('role-aplikasi.')->middleware(['permission:role-aplikasi,view'])->group(function () {
        Route::get('/', [RoleaplikasiController::class, 'index'])->name('index');
        Route::get('/{id}', [RoleaplikasiController::class, 'show'])->name('show');

        // Create - perlu permission create
        Route::middleware(['permission:role-aplikasi,create'])->group(function () {
            Route::get('/create', [RoleaplikasiController::class, 'create'])->name('create');
            Route::post('/', [RoleaplikasiController::class, 'store'])->name('store');
        });

        // Edit/Update - perlu permission edit
        Route::middleware(['permission:role-aplikasi,edit'])->group(function () {
            Route::get('/{id}/edit', [RoleaplikasiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [RoleaplikasiController::class, 'update'])->name('update');
        });

        // Delete - perlu permission delete
        Route::middleware(['permission:role-aplikasi,delete'])->group(function () {
            Route::delete('/{id}', [RoleaplikasiController::class, 'destroy'])->name('destroy');
        });
    });

    // ===================================================
    // ROLE PERMISSIONS ROUTES (MANAJEMEN PERMISSION DINAMIS)
    // ===================================================
    Route::prefix('role-permissions')->name('role-permissions.')->middleware(['permission:role-permissions,view'])->group(function () {

        // ========== STATIC ROUTES (TANPA PARAMETER) ==========
        // Index
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');

        // Create
        Route::middleware(['permission:role-permissions,create'])->group(function () {
            Route::get('/create', [RolePermissionController::class, 'create'])->name('create');
            Route::post('/', [RolePermissionController::class, 'store'])->name('store');
        });

        // Export
        Route::middleware(['permission:role-permissions,export'])->group(function () {
            Route::get('/export/pdf', [RolePermissionController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/export/excel', [RolePermissionController::class, 'exportExcel'])->name('export-excel');
        });

        // Copy
        Route::middleware(['permission:role-permissions,edit'])->group(function () {
            Route::post('/copy', [RolePermissionController::class, 'copyPermissions'])->name('copy');
        });

        // ========== DYNAMIC ROUTES (DENGAN PARAMETER) ==========
        // SEMUA ROUTE DENGAN PARAMETER HARUS DIBAWAH ROUTE STATIC

        // View dengan parameter
        Route::get('/{roleId}', [RolePermissionController::class, 'show'])->name('show');
        Route::get('/{roleId}/matrix', [RolePermissionController::class, 'matrix'])->name('matrix');
        Route::get('/{roleId}/edit', [RolePermissionController::class, 'edit'])->name('edit');

        // Update dengan parameter
        Route::middleware(['permission:role-permissions,edit'])->group(function () {
            Route::put('/{roleId}', [RolePermissionController::class, 'update'])->name('update');
            Route::post('/{roleId}', [RolePermissionController::class, 'updateBulk'])->name('update-bulk');
            Route::post('/{roleId}/reset', [RolePermissionController::class, 'resetToDefault'])->name('reset');
        });

        // Delete dengan parameter
        Route::middleware(['permission:role-permissions,delete'])->group(function () {
            Route::delete('/{permissionId}', [RolePermissionController::class, 'destroy'])->name('destroy');
        });
    });

    // ===================================================
    // FALLBACK UNTUK SUPERADMIN (SEMUA AKSES)
    // Catatan: SUPERADMIN sudah memiliki akses penuh melalui middleware permission
    // karena di middleware CheckPermission SUPERADMIN selalu return true
    // ===================================================
});

// ===================================================
// API ROUTES (untuk keperluan AJAX)
// ===================================================
Route::prefix('api')->name('api.')->middleware(['auth'])->group(function () {
    // Get user permissions
    Route::get('/user/permissions', function () {
        return response()->json([
            'permissions' => \App\Helpers\PermissionHelper::getAccessibleMenus(),
            'buttons' => [
                'laporan' => \App\Helpers\PermissionHelper::getButtonPermissions('laporan'),
                'kantor' => \App\Helpers\PermissionHelper::getButtonPermissions('kantor'),
                'jenis-aplikasi' => \App\Helpers\PermissionHelper::getButtonPermissions('jenis-aplikasi'),
                'produk' => \App\Helpers\PermissionHelper::getButtonPermissions('produk'), // Tambahkan ini
                'user' => \App\Helpers\PermissionHelper::getButtonPermissions('user'),
                'role' => \App\Helpers\PermissionHelper::getButtonPermissions('role'),
                'role-aplikasi' => \App\Helpers\PermissionHelper::getButtonPermissions('role-aplikasi'),
                'activity-logs' => \App\Helpers\PermissionHelper::getButtonPermissions('activity-logs'),
                'role-permissions' => \App\Helpers\PermissionHelper::getButtonPermissions('role-permissions'),
            ]
        ]);
    })->name('user.permissions');

    // Check specific permission
    Route::post('/check-permission', function (Illuminate\Http\Request $request) {
        $menu = $request->menu;
        $action = $request->action ?? 'view';

        return response()->json([
            'allowed' => \App\Helpers\PermissionHelper::can($menu, $action)
        ]);
    })->name('check-permission');
});

// ===================================================
// FALLBACK ROUTE UNTUK 404
// ===================================================
Route::fallback(function () {
    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Halaman tidak ditemukan'
        ], 404);
    }

    return response()->view('errors.404', [], 404);
});

// ===================================================
// TEST ROUTE (HANYA UNTUK DEVELOPMENT)
// ===================================================
if (app()->environment('local')) {
    Route::get('/test-permissions', function () {
        $user = Auth::user();

        if (!$user) {
            return 'Silakan login terlebih dahulu';
        }

        $menus = [
            'dashboard', 'laporan', 'kantor', 'jenis-aplikasi', 'produk', // Tambahkan produk
            'user', 'role', 'role-aplikasi', 'activity-logs', 'role-permissions'
        ];

        $actions = ['view', 'create', 'edit', 'delete', 'export', 'import'];

        $result = [];

        foreach ($menus as $menu) {
            $result[$menu] = [];
            foreach ($actions as $action) {
                $result[$menu][$action] = \App\Helpers\PermissionHelper::can($menu, $action);
            }
        }

        return response()->json([
            'user' => $user->nama,
            'role' => $user->role->nama_role ?? 'No Role',
            'permissions' => $result
        ]);
    })->name('test.permissions');
}

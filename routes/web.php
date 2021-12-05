<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PermissionGroupController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
require __DIR__ . '/auth.php';

// Auth::routes();


Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {

	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

	// user  start
	Route::get('/users', [UserController::class, 'users'])->name('users')->middleware('role:Superadmin');
	Route::post('/user/store', [UserController::class, 'userStore'])->name('user.store')->middleware('role:Superadmin');
	Route::post('/user/edit', [UserController::class, 'userEdit'])->name('user.edit')->middleware('role:Superadmin');
	Route::post('/user/update/', [UserController::class, 'userUpdate'])->name('user.update')->middleware('role:Superadmin');
	Route::post('/user/delete', [UserController::class, 'userDelete'])->name('user.delete')->middleware('role:Superadmin');
	Route::post('/change/admin/status', [UserController::class, 'changeAdminStatus'])->name('is.admin')->middleware('role:Superadmin');
	// user end

	// admin start
	Route::get('/admins', [UserController::class, 'admins'])->name('admins');
	Route::post('/admin/store', [UserController::class, 'adminStore'])->name('admin.store');
	Route::post('/admin/edit', [UserController::class, 'adminEdit'])->name('admin.edit');
	Route::post('/admin/update/', [UserController::class, 'adminUpdate'])->name('admin.update');
	Route::post('/admin/delete', [UserController::class, 'adminDelete'])->name('admin.delete');
	// admin  end

	Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
	Route::get('/edit-profile', [ProfileController::class, 'editProfile'])->name('edit.profile');
	Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('update.profile');

	// app settings start
	Route::get('/app/settings', [SettingsController::class, 'appSettings'])->name('app.settings');
	Route::post('/settings/edit', [SettingsController::class, 'settingsEdit'])->name('settings.edit');
	Route::post('/settings/update/', [SettingsController::class, 'settingsUpdate'])->name('settings.update');
	// app settings end
	//Tags Start
	Route::get('/blogs', [BlogController::class, 'alltags'])->name('blogs');
	Route::post('/blogs/store', [BlogController::class, 'tagStore'])->name('blogs.store');
	Route::post('/blogs/edit', [BlogController::class, 'tagEdit'])->name('blogs.edit');
	Route::post('/blogs/update', [BlogController::class, 'tagUpdated'])->name('blogs.update');
	Route::post('/blogs/destroy', [BlogController::class, 'tagDestrotoy'])->name('blogs.destroy');
	//Tags End

	// =========Role And Permission Start============
	// Permission Group start
	Route::get('/permission/groups', [PermissionGroupController::class, 'Index'])->name('permission.groups')->middleware('role:Superadmin');
	Route::post('/permission/groups/store', [PermissionGroupController::class, 'Store'])->name('permission.groups.store')->middleware('role:Superadmin');
	Route::post('/permission/groups/edit', [PermissionGroupController::class, 'Edit'])->name('permission.groups.edit')->middleware('role:Superadmin');
	Route::post('/permission/groups/update/', [PermissionGroupController::class, 'Update'])->name('permission.groups.update')->middleware('role:Superadmin');
	Route::post('/permission/groups/destroy', [PermissionGroupController::class, 'Destroy'])->name('permission.groups.destroy')->middleware('role:Superadmin');
	// Permission Group  end
	// Permission start
	Route::get('/permissions', [PermissionController::class, 'Index'])->name('permissions')->middleware('role:Superadmin');
	Route::post('/permissions/store', [PermissionController::class, 'Store'])->name('permissions.store')->middleware('role:Superadmin');
	Route::post('/permissions/edit', [PermissionController::class, 'Edit'])->name('permissions.edit')->middleware('role:Superadmin');
	Route::post('/permissions/update/', [PermissionController::class, 'Update'])->name('permissions.update')->middleware('role:Superadmin');
	Route::post('/permissions/destroy', [PermissionController::class, 'Destroy'])->name('permissions.destroy')->middleware('role:Superadmin');
	// Permission end
	// Role start
	Route::get('/roles/with/permission', [RoleController::class, 'Index'])->name('roles.with.permission')->middleware('role:Superadmin');
	Route::post('/roles/with/permission/store', [RoleController::class, 'Store'])->name('roles.with.permission.store')->middleware('role:Superadmin');
	Route::post('/roles/with/permission/edit', [RoleController::class, 'Edit'])->name('roles.with.permission.edit')->middleware('role:Superadmin');
	Route::post('/roles/with/permission/update/', [RoleController::class, 'Update'])->name('roles.with.permission.update')->middleware('role:Superadmin');
	Route::post('/roles/with/permission/destroy', [RoleController::class, 'Destroy'])->name('roles.with.permission.destroy')->middleware('role:Superadmin');
	// Role  end
	// =========Role And Permission End============
});


//* password reset start */
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forgot.password');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::get('resend/email/{token}', [ForgotPasswordController::class, 'resendEmail'])->name('resend.email');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::get('/register/{token}', [UserController::class, 'registerNewUser'])->name('accept');
Route::post('/user/sign/up', [UserController::class, 'userSignUp'])->name('user.sign.up');
Route::get('/super-admin', [UserController::class, 'superAdmin']);
Route::post('/make/super/admin', [UserController::class, 'makeSuperAdmin'])->name('is.superadmin');

Route::get('/clear-cache', function() {

    $configCache = Artisan::call('config:cache');
    $clearCache = Artisan::call('cache:clear');
    // return what you want
    return "Finished";
});
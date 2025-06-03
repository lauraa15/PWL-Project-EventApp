use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestConnectionController;

Route::get('/test-connection', [TestConnectionController::class, 'test']);

Route::get('/status', function() {
    return response()->json(['message' => 'Laravel API is running']);
});
use App\Models\User;
use App\Models\Product;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Student;
use App\Models\Book;
use App\Models\Announcement;
use App\Models\Message;

public function index()
{
    $announcements = Announcement::orderBy('created_at', 'desc')->get();
    return view('user.home', compact('announcements'));
}

public function password()
{
    return view('user.password');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::user();
    if (Hash::check($request->current_password, $user->password)) {
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('/user/password')->with('success', 'Password updated successfully');
    } else {
        return redirect('/user/password')->with('error', 'Current password is incorrect');
    }
}

public function viewData()
{
    $products = Product::all();
    $patients = Patient::all();
    $doctors = Doctor::all();
    $students = Student::all();
    $books = Book::all();
    return view('user.view_data', compact('products', 'patients', 'doctors', 'students', 'books'));
}

public function search(Request $request)
{
    $search = $request->input('search');
    $products = Product::where('name', 'like', '%'.$search.'%')->get();
    $patients = Patient::where('name', 'like', '%'.$search.'%')->get();
    $doctors = Doctor::where('name', 'like', '%'.$search.'%')->get();
    $students = Student::where('name', 'like', '%'.$search.'%')->get();
    $books = Book::where('title', 'like', '%'.$search.'%')->get();
    return view('user.view_data', compact('products', 'patients', 'doctors', 'students', 'books'));
}

public function followAnnouncement($id)
{
    $user = Auth::user();
    $announcement = Announcement::find($id);
    $user->announcements()->attach($announcement);
    return redirect('/user')->with('success', 'Announcement followed successfully');
}

public function unfollowAnnouncement($id)
{
    $user = Auth::user();
    $announcement = Announcement::find($id);
    $user->announcements()->detach($announcement);
    return redirect('/user')->with('success', 'Announcement unfollowed successfully');
}

public function sendMessage(Request $request)
{
    $request->validate([
        'message' => 'required',
    ]);

    $user = Auth::user();
    $message = new Message;
    $message->user_id = $user->id;
    $message->message =

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
    return view('admin.dashboard');
}

public function users()
{
    $users = User::all();
    return view('admin.users', compact('users'));
}

public function addUser()
{
    return view('admin.add_user');
}

public function saveUser(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = new User;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect('/admin/users')->with('success', 'User added successfully');
}

public function editUser($id)
{
    $user = User::find($id);
    return view('admin.edit_user', compact('user'));
}

public function updateUser(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|min:8|confirmed',
    ]);

    $user = User::find($id);
    $user->name = $request->name;
    $user->email = $request->email;
    if (!empty($request->password)) {
        $user->password = Hash::make($request->password);
    }
    $user->save();

    return redirect('/admin/users')->with('success', 'User updated successfully');
}

public function deleteUser($id)
{
    $user = User::find($id);
    $user->delete();
    return redirect('/admin/users')->with('success', 'User deleted successfully');
}

public function products()
{
    $products = Product::all();
    return view('admin.products', compact('products'));
}

public function addProduct()
{
    return view('admin.add_product');
}

public function saveProduct(Request $request)
{
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
    ]);

    $product = new Product;
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->save();

    return redirect('/admin/products')->with('success', 'Product added successfully');
}

public function editProduct($id)
{
    $product = Product::find($id);
    return view('admin.edit_product', compact('product'));
}

public function updateProduct(Request $request, $

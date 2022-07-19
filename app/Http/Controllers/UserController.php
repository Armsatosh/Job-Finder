<?php declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['edit', 'update']]);
    }

    /**
     * Show User Registration Form
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Register User
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $this->validate($request, [
            'email' => 'unique:users',
            'avatar' => 'nullable|image',
        ]);

        $avatar = 'images/gender_neutral_avatar.png';
        if ($request->hasFile('avatar')) {
            $folder = date('Y-m-d');
            $avatar = $request->file('avatar')->store("images/{$folder}", 'public');
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'avatar' => $avatar,
        ]);
        Mail::to($request->email)
            ->send(new UserRegistered($request))
        ;

        Auth::login($user);

        return redirect('/job')
            ->with('flash_notification.message', 'User registered successfully')
            ->with('flash_notification.messageMail', 'We have sent your password  and login to your mentioned email')
            ->with('flash_notification.level', 'success')
        ;
    }

    /**
     * Show User Profile
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.profile', compact('user'));
    }

    /**
     * Update User Profile
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'confirmed',
            'avatar' => 'nullable',
        ]);
        $user->name = $request['name'];
        $user->email = $request['email'];

        if ($request->hasFile('avatar')) {
            if (Storage::exists($user->avatar)) {
                Storage::delete($user->avatar);
            }
            $folder = date('Y-m-d');
            $avatar = $request->file('avatar')->store("images/{$folder}", 'public');
            $user->avatar = $avatar;
        }
        if ($request->get('password') !== '') {
            $user->password = bcrypt($request->get('password'));
        }
        $user->save();

        return redirect('/job')
            ->with('flash_notification.message', 'Profile updated successfully')
            ->with('flash_notification.level', 'success')
        ;
    }
}

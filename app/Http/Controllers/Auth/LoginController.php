<?php

namespace Diploma\Http\Controllers\Auth;

use Auth;
use Diploma\User;
use Illuminate\Http\Request;
use Diploma\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Adldap\Laravel\Facades\Adldap;

use DB;
use Config;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $STUDENTS_GROUP_ID = '502';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username() {
        return "username";
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

    public function login(Request $request) {

        $this->validate($request, [
            'username' => 'required', 'password' => 'required',
        ]);

        // User credentials for querying the LDAP server
        $wheres = [
            'uid' => $request->username,
            'userpassword' => md5($request->password)
        ];

        // Authenticate the user by querying the LDAP server
        $search = Adldap::search()->where($wheres)->get();

        if (count($search) == 0) {
             // Superadmin credentials for querying the local DB
            $wheres = [
                'username' => $request->username,
                'password' => md5($request->password)
            ];

            // Static superadmin case
            $user = User::where($wheres)->first();

        } else {

            // Authenticate the user after checking the credentials in the LDAP server
            $user = User::where('username', '=', $search[0]->cn)
                ->first();

            if ($user == null) {

                $user_data = $search[0];
                // LDAP user is student boolean
                $is_student = $user_data->gidnumber[0] == $this->STUDENTS_GROUP_ID;
                $group_name = null;
                if ($is_student) {
                    $group_name = Adldap::search()
                        ->where('gidnumber', $user_data->gidnumber[0])
                        ->where('objectclass', 'posixgroup')
                        ->get()
                        [0]->cn[0];
                }

                $user = new User();
                $user->name = $user_data->givenname[0];
                $user->username = $user_data->cn[0];
                $user->password = $is_student ? md5('student') : md5('teacher');
                $user->role_id = $is_student ? Config::get('constants.BACHELOR_ROLE_ID') : Config::get('constants.TEACHER_ROLE_ID');
                $user->group = $group_name;
                $user->email = $user_data->mail[0];
                $user->save();
            }
        }

        if($user != null) {
            // Authenticate the user
            Auth::login($user);
            return redirect()->to('home');
        }

        // Credentials did not match any on the LDAP server users nor the static superadmin's
        return redirect('/login')
            ->withInput($request->only('username', 'password'))
            ->withErrors(['password' => 'Nume de utilizator sau parolă invalidă.']);
    }

}

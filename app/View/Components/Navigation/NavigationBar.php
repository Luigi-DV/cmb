<?php

namespace App\View\Components\Navigation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class NavigationBar extends Component
{
    public $department, $userID;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        if(isset($_COOKIE[$request->route()->parameter('department').'CaAuth']))
        {
            $this->department = $request->route()->parameter('department');
            $this->userID = $_COOKIE[$request->route()->parameter('department').'CaAuth'];
        }
        else{
            $this->department = null;
        }

    }

    public function getUserByID($id)
    {
        $user = head(DB::connection('mysql_' . $this->department)
            ->select("SELECT * FROM planning_user WHERE user_id='" . $id . "'"));
        if(empty($user))
        {
            return null;
        }
        else{
            return $user;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.navigation.navigation-bar');
    }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Members;
use App\Subscribers;
use App\ContactGroup;
use App\ContactInGroup;
use DB;
use Response;
use Session;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	public function attempt(Request $request)
	{
		$user = Members::where('email',$request->email)
					->where('pwd',md5($request->password))
					->first();
		if ($user) {
			Auth::login($user);
		}
		 return redirect('/home');
	}
	public function laravel(Request $request)
	{
		$credentials = array();
		$credentials = explode('|',$_GET['u']);
		$password = utf8_decode(base64_decode($credentials[1]));
		// exit;
		$user = Members::where('email',$credentials[0])
					->where('pwd',md5($password))
					->first();
		if ($user) {
			Auth::login($user);
		}
		if($credentials[2] == 'contacts'){
			return redirect('/home');
		}elseif($credentials[2] == 'contact-groups'){
			return redirect('/contact-group');
		}
		 
	}
    public function index()
    {
		$subscribers = DB::table('subscribers')->where('member_id',Auth::user()->member_id)->get();
		// dd(Auth::user()->member_id);
        return view('contacts')->with('subscribers',$subscribers);
    }
    // public function postlogin(Request $request) {
       
        // if (Auth::attempt ( array (
                // 'email' => $request->email,
                // 'password' => $request->password
        // ) )) {
             // dd('success');
        // } else {
            // Session::flash ( 'message', "Invalid Credentials , Please try again." );
           // dd('failed');
        // }
    // }
    public function logout() {
        Auth::logout ();
        return Redirect::to('http://pageiz.com/authentication/logout');
    }
	public function csv(){
		$table = DB::table('subscribers')->where('member_id',Auth::user()->member_id)->get();
		$filename = "subscribers.csv";
		$handle = fopen($filename, 'w+');
		fputcsv($handle, array('name', 'email', 'phone'));
		$subs = json_decode(json_encode($table), true);
		// var_dump($subs);exit;
		foreach($subs as $row) {
			fputcsv($handle, array($row['full_name'], $row['email'], $row['phone']));
		}

		fclose($handle);

		$headers = array(
			'Content-Type' => 'text/csv',
		);

		return Response::download($filename, 'subscribers.csv', $headers);

	}
	
	public function selected(Request $request){
		// $table = DB::table('subscribers')->where('member_id',Auth::user()->member_id)->get();
		// dd($request->email);
		$filename = "subscribers.csv";
		$handle = fopen($filename, 'w+');
		fputcsv($handle, array('email'));
		// $subs = json_decode(json_encode($table), true);
		// var_dump($subs);exit;
		foreach($request->email as $row) {
			fputcsv($handle, array($row));
		}

		fclose($handle);

		$headers = array(
			'Content-Type' => 'text/csv',
		);

		return Response::download($filename, 'subscribers.csv', $headers);

	}
	public function contact(){
		$cg = ContactGroup::where('member_id',Auth::user()->member_id)->get();
		return view('new_contact')->with('cg',$cg);
	}
	public function new_contact(Request $request){
		// dd($request->phone);
		$check = DB::table('subscribers')->where('member_id',Auth::user()->member_id)->where('email',$request->email)->first();
		if($check){
			Session::flash('error', "Email already exist");
			return Redirect::back();
		}else{
			// dd($request->cg_id);
			$sub = new Subscribers;
			$sub->full_name = $request->name;
			$sub->email = $request->email;
			$sub->phone = $request->phone;
			$sub->member_id = Auth::user()->member_id;
			$sub->save();
			
			foreach($request->cg_id as $cg_id){
				// echo $cg_id;
				$cig = new ContactInGroup;
				$cig->sub_id = $sub['sub_id'];
				$cig->cg_id = $cg_id;
				$cig->save();
			}
			Session::flash('message', "Contact has been created");
			return Redirect::back();
		}
		
		
	}
	/***** Start Contact Group ******/
	public function contact_group(){
		$groups = DB::table('contact_group')->where('member_id',Auth::user()->member_id)->get();
		return view('contact-group.contact_group')->with('group',$groups);
	}
	public function new_contact_group(){
		return view('contact-group.new-group');
	}
	public function post_contact_group(Request $request){
		// dd($request->name .'' .$request->description);
		$cg = new ContactGroup;
			$cg->name = $request->name;
			$cg->description = $request->description;
			$cg->member_id = Auth::user()->member_id;
			$cg->save();
			Session::flash('message', "Contact Group has been created");
			return Redirect::back();
	}
	public function view_contact_group($id){
		$cg = DB::table('contact_group')
				->where('cg_id',$id)->where('member_id',Auth::user()->member_id)
				->first();
				if($cg){
				$cig = DB::table('contact_in_group')
					->join('contact_group', 'contact_in_group.cg_id', '=', 'contact_group.cg_id')
					->join('subscribers', 'contact_in_group.sub_id', '=', 'subscribers.sub_id')
					->select('contact_in_group.*','contact_group.name', 'subscribers.*')
					->where('contact_group.cg_id',$id)->where('contact_group.member_id',Auth::user()->member_id)
					->get();
				return view('contact-group.view_contact_group')->with('cig_id',$cig)->with('cg',$cg);
			}else{
				return redirect('/home');
			}
	}
	public function remove_contact_group(Request $request){
		$id = $request->id;
		$remove = DB::table('contact_in_group')->where('cg_id',$request->cg_id)->where('sub_id',$id)->delete();
		if($remove){
			echo 'success';
		}else{
			echo 'error';
		}
	}
	/***** End Contact Group ******/
	/***** Start Email Marketing ******/
	public function selecttemplate(){
		return view('email-marketing.email-template');
	}
	public function emailmarketing(){
		return view('email-marketing.design');
	}
	public function sendemails(Request $request){
		$data = $request->emaildatas;
		echo $data;exit;
	}
	
	/***** End Email Marketing ******/
}

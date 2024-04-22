<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // user registration page
    public function userRegistrationPage(){
        return view('Authenitication.User-Registration');
    }
    // user registration operations
    public function userRegistrationOperation(Request $request){
        // apply form validation
        $validations = $request->validate([
            'username'           => 'required',
            'useremail'          => 'required|unique:users,email',
            'password'           => 'required|min:8|max:15|string|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'confirm_password'   => 'required|same:password|min:8|max:15|string|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
        ]);
        $formData = $request->all();
        if ( $validations ) {
            $checkUsers = User::where('email','=',$formData['useremail'])->get();
            if ( count ( $checkUsers ) > 0 ) {
                return redirect()->back()->with('error_message','This email is al-ready availible');
            } else {
                if ( $formData['password'] == $formData['confirm_password'] ) {
                $createUsers = DB::table('users')->insertOrIgnore([
                    'name'         => $formData['username'],
                    'email'        => $formData['useremail'],
                    'password'     => Hash::make($formData['password']),
                    'created_at'   => NOW(),
                    'updated_at'   => NOW(),
                ]);
                if ( $createUsers ) {
                    return redirect()->back()->with('success_message','User Creation Operation Is Successfully');
                }  else {
                    return redirect()->back()->with('error_message','User Creation Operation Is Un-successfully');
                 }
             } else {
                return redirect()->back()->with('error_message','Password And Confirm Password Is Not Match');
             }
            }
        } else {
            return redirect()->back()->with('error_message');
        }
    }
    // user login page
    public function userLoginPage(){
        return view('Authenitication.User-Login');
    }
    // user login operation
    public function userLoginOperation(Request $request) {
        $validations = $request->validate([
            'loginEmail'              => 'required',
            'loginPassword'           => 'required',
        ]);
        $formData = $request->all();
        if ( $validations ) {
            if ( Auth::guard('user')->attempt([
                'email'        => $formData['loginEmail'],
                'password'     => $formData['loginPassword'], 
            ]) ) {
                return redirect()->route('userDashboard');
            } else {
                return redirect()->back()->with('error_message','Invalid Users Details');
            }
        } else {
            return redirect()->back()->with('error_message');
        }
    }
    // logout operation
    public function logoutOperation(){
        Auth::guard('user')->logout();
        return redirect()->route('userLoginPage');
    }
    // user dashboard
    public function userDashboard(){
        $selectCategory = DB::table('categorys')->get();
        $selectFeedback = DB::table('feedbacks')
        ->join('categorys','feedbacks.feedback_category','=','categorys.id')
        ->select('feedbacks.*','categorys.category_name')
        ->latest()
        ->get();
        $selectFeedback->transform(function ($feedback) {
            $timestamp = Carbon::parse($feedback->created_at);
            $timeAgo = $timestamp->shortRelativeDiffForHumans(); // Using shortRelativeDiffForHumans to get "0 sec ago" format
            $feedback->relative_time = $timeAgo;
            return $feedback;
        });
        return view('dashboard.user-dashboard',[
            'selectCategory' => $selectCategory,
            'selectFeedback' => $selectFeedback,
            // 'selectComments' => $selectComments,
        ]);
    }
    // category list page
    public function categoryListPage(){
        $selectCategory = DB::table('categorys')->paginate(10);
        return view('category.category-list',[
            'selectCategory' => $selectCategory,
        ]);
    }
    // category Creation pages
    public function createCategoryPage(){
        return view('category.category-add-page');
    }
    // category creation operation
    public function createCategoryOperation(Request $request){
        $validations = $request->validate([
            'categoryName'              => 'required|unique:categorys,category_name',
        ]);
        $formData = $request->all();
        if ( $validations ) {
            $checkOldData = DB::table('categorys')
            ->where('category_name','=', $formData['categoryName'])
            ->get();
            if ( count ( $checkOldData ) > 0 ) {
                return redirect()->back()->with('error_message');
            } else {
                $createCategoryOperations = DB::table('categorys')
                                            ->insertOrIgnore([
                                                'category_name' => $formData['categoryName'],
                                                'created_at'    => NOW(),
                                                'updated_at'    => NOW(),
                                            ]);
                if ( $createCategoryOperations ) {
                    return redirect()->back()->with('success_message','Category Creation Operation Is Successfully');
                } else {
                    return redirect()->back()->with('error_message','Category Creation Operation Is Un-Successfully');
                }
            }
        } else {
            return redirect()->back()->with('error_message');
        }
    }
    // category edit pages
    public function editCategoryPages($id){
        $selectCategory = DB::table('categorys')->where('id','=',$id)->get();
        return view('category.category-edit-page',[
            'selectCategory'  => $selectCategory,
        ]);
    }
    // category edit operation
    public function editCategoryOperation(Request $request,$id){
        $validations = $request->validate([
            'categoryName'              => 'required',
        ]);
        $formData = $request->all();
        if ( $validations ) {
            $checkOldData = DB::table('categorys')
            ->where('id','=', $id)
            ->get();
            if ( count ( $checkOldData ) == 0 ) {
                return redirect()->back()->with('error_message');
            } else {
                $createCategoryOperations = DB::table('categorys')
                                            ->where('id','=', $id)
                                            ->update([
                                                'category_name' => $formData['categoryName'],
                                                'updated_at'    => NOW(),
                                            ]);
                if ( $createCategoryOperations ) {
                    return redirect()->back()->with('success_message','Category Update Operation Is Successfully');
                } else {
                    return redirect()->back()->with('error_message','Category Update Operation Is Un-Successfully');
                }
            }
        } else {
            return redirect()->back()->with('error_message');
        }
    }
    // remove edit operations
    public function removedCategoryOperation($id){
        $selectCategory = DB::table('categorys')->where('id','=',$id)->get();
        if ( count ( $selectCategory ) > 0 ) {
            $removeCategory = DB::table('categorys')->where('id','=',$id)->delete();
            if ( $removeCategory ) {
                return redirect()->back()->with('success_message','Removed Category Operation Is Successfully');
            } else {
                return redirect()->back()->with('error_message','Removed Category Operation Is Un-successfully');
            }
        } else {
            return redirect()->back()->with('error_message','Not Data Founded');
        }
    }
    // search Operation
    function searchCategories(Request $request){
            $query = $request->all();
            if ( $query !='' ) {
                $selectCategory  = DB::table('categorys')
                    ->where('category_name', 'like', '%'.$query['searchCategory'].'%')
                    ->orWhere('id', 'like', '%'.$query['searchCategory'].'%')
                    ->paginate(10);
            } else {
                $selectCategory  = DB::table('categorys')
                    ->paginate(10);
            }
                return view('category.category-list',[
                        'selectCategory' => $selectCategory,
                ]);
    }
    // feedback list
    public function feedbackListPage(){
        $selectFeedbackData = DB::table('feedbacks')
                              ->join('users','feedbacks.feedback_users','=','users.id')
                              ->join('categorys','feedbacks.feedback_category','=','categorys.id')
                              ->select('feedbacks.*','categorys.category_name','users.name')
                              ->paginate(10);
        return view('feedback.feedback-list',[
            'selectFeedbackData' => $selectFeedbackData,
        ]);
    }
    // create feedback formpages
    public function createFeedbackPage(){
        $selectCategory = DB::table('categorys')->get();
        return view('feedback.feedback-add-page',[
            'selectCategory' => $selectCategory,
        ]);
    }
    // create feedback operation
    public function createFeedbackOperation(Request $request){
        $validations = $request->validate([
            'feedbackTitle'              => 'required|min:4|max:20',
            'feedback_category'          => 'required',
            'feedback_details'           => 'required',
            
        ]);
        $formDatass = $request->all();
        if ( $validations ) {
            $createFeedbackOperation = DB::table('feedbacks')->insertOrIgnore([
                'feedback_users'      => Auth::guard('user')->user()->id,
                'feedback_title'      => $formDatass['feedbackTitle'],
                'feedback_category'   => $formDatass['feedback_category'],
                'feedback_details'    => $formDatass['feedback_details'],
                'created_at'          => NOW(),
                'updated_at'          => NOW(),
            ]);
            if ( $createFeedbackOperation ) {
                return redirect()->back()->with('success_message','Feedback Operation Is Successfully');
            } else {
                return redirect()->back()->with('error_message','Feedback Operation Is Un-Successfully');    
            }
        } else {
            return redirect()->back()->with('error_message');
        }
    }
    // search Operation
    public function searchFeedback(Request $request){
        $query = $request->all();
        if ($query !='') {
                $selectFeedbackData  = DB::table('feedbacks')
                    ->join('users','feedbacks.feedback_users','=','users.id')
                    ->join('categorys','feedbacks.feedback_category','=','categorys.id')
                    ->select('feedbacks.*','categorys.category_name','users.name')
                    ->where('feedbacks.feedback_title', 'like', '%'.$query['searchFeedback'].'%')
                    ->orWhere('categorys.category_name', 'like', '%'.$query['searchFeedback'].'%')
                    ->orWhere('users.name', 'like', '%'.$query['searchFeedback'].'%')
                    ->paginate(10);
        } else {
            $selectFeedbackData  = DB::table('feedbacks')
            ->join('users','feedbacks.feedback_users','=','users.id')
                              ->join('categorys','feedbacks.feedback_category','=','categorys.id')
                              ->select('feedbacks.*','categorys.category_name','users.name')
                    ->paginate(10);
        }
                    return view('feedback.feedback-list',[
                        'selectFeedbackData' => $selectFeedbackData,
                    ]);               
    }
    // edit page feedback
    public function editPagesFeedback($id){
        $selectCategory  = DB::table('categorys')->get();
        $selectEmployees = DB::table('feedbacks')
                          ->join('categorys','feedbacks.feedback_category','=','categorys.id')
                          ->select('feedbacks.*','categorys.category_name')
                          ->where('feedbacks.id','=',$id)
                          ->get(); 
            return view('feedback.feedback-edit-page',[
                'selectEmployees' => $selectEmployees,
                'selectCategory' => $selectCategory,
            ]);
    }
    // edit feedback operation
    public function editFeedbackOperation(Request $request,$id){
        $validations = $request->validate([
            'feedbackTitle'              => 'required|min:4|max:20',
            'feedback_category'          => 'required',
            'feedback_details'           => 'required',
            'feedbackEmail'              => 'required|email',
        ]);
        $formDatass = $request->all();
        if ( $validations ) {
            $selectFeedbacks          = DB::table('feedbacks')->where('id','=',$id)->get();
            if ( count ( $selectFeedbacks ) > 0 ) {
                $createFeedbackOperation  = DB::table('feedbacks')
                ->where('id','=',$id)
                ->update([
                    'feedback_email'      => $formDatass['feedbackEmail'],
                    'feedback_users'      => Auth::guard('user')->user()->id,
                    'feedback_title'      => $formDatass['feedbackTitle'],
                    'feedback_category'   => $formDatass['feedback_category'],
                    'feedback_details'    => $formDatass['feedback_details'],
                    'updated_at'          => NOW(),
                ]);
                if ( $createFeedbackOperation ) {
                    return redirect()->back()->with('success_message','Feedback Updated Operation Is Successfully');
                } else {
                    return redirect()->back()->with('error_message','Feedback Updated Operation Is Un-Successfully');    
                }
            } else {
                return redirect()->back()->with('error_message','Not Data Founded');
            }
        } else {
            return redirect()->back()->with('error_message');
        }
    }
    // details pages feedback
    public function detailsPagesOperation($id){
        $selectEmployees = DB::table('feedbacks')
                          ->join('categorys','feedbacks.feedback_category','=','categorys.id')
                          ->join('users','feedbacks.feedback_users','=','users.id')
                          ->select('feedbacks.*','categorys.category_name','users.name')
                          ->where('feedbacks.id','=',$id)
                          ->get(); 
            return view('feedback.feedback-details-page',[
                'selectEmployees' => $selectEmployees,
            ]);
    }
    // removed operation feedback
    public function removedOperations($id){
        $selectFeedbacks = DB::table('feedbacks')->where('id','=',$id)->get();
        if ( count ( $selectFeedbacks ) > 0 ) {
            $removeFeedbacks = DB::table('feedbacks')->where('id','=',$id)->delete();
            if ( $removeFeedbacks ) {
                return redirect()->back()->with('success_message','Removed Operation Is SuccessfullY');
            } else {
                return redirect()->back()->with('error_message','Removed Operation Is Un-successfullY');
            }
        } else {
            return redirect()->back()->with('error_message','Not Data Founded');
        }
    }
    // see my feedback
    public function feedbackMyListPage(){
        $selectFeedbackData = DB::table('feedbacks')
                              ->join('users','feedbacks.feedback_users','=','users.id')
                              ->join('categorys','feedbacks.feedback_category','=','categorys.id')
                              ->select('feedbacks.*','categorys.category_name','users.name')
                              ->where('feedbacks.feedback_users','=',Auth::guard('user')->user()->id)
                              ->get();
        return view('feedback.my-feedback-list',[
            'selectFeedbackData' => $selectFeedbackData,
        ]);
    }
    // comment page
    public function commentPage($id){
        // $selectCommentData = DB::table('comments')
        // ->join('users','comments.comment_users','=','users.id')
        //                       ->select('comments.*','users.name')
        // ->get();
        $selectFeedback = DB::table('feedbacks')->where('id','=',$id)->get();
        if ( count ( $selectFeedback ) > 0 ) {
            return view('comments.comment',[
                'selectFeedback' => $selectFeedback,
                // 'selectCommentData' => $selectCommentData,
            ]);
        } else {
            return redirect()->back()->with('error_message','Not Data Founded');
        }
    }
    // comment operation
    public function commentOperations(Request $request){
        $validations = $request->validate([
            'commentBox'  => 'required',
        ]);
        $oldData = $request->all();
        if ($validations) {
            $createFeedbackData = DB::table('comments')->insertOrIgnore(
                [
                    'comment_users' => Auth::guard('user')->user()->id,
                    'feedback_id'   => $oldData['feedback_id'],
                    'comments'      => $oldData['commentBox'],
                    'created_at'    => NOW(),
                    'updated_at'    => NOW(),
                ]
            );
            if ( $createFeedbackData ) {
                return redirect()->back()->with('success_message','Comments Operation Is Successfully');
            } else {
                return redirect()->back()->with('error_message','Comments Operation Is Un-Successfully');    
            }
        } else {
            return redirect()->back()->with('error_message');
        }
    }
    // comment list page
    public function commentListPage(){
        $commentsSelect = DB::table('comments')
        ->join('users','comments.comment_users','=','users.id')
                          ->select('comments.*','users.name')
        ->get();
        return view('comments.comment-list',[
            'commentsSelect' =>  $commentsSelect,
        ]);
    }
}

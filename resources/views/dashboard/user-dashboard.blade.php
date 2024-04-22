@extends('layout.layout');
@section('user-dashboard')
<div class="px-3 py-2">
    <div class="row">
        <div class="col-12 pe-lg-2">
            <div class="card mb-3 mb-lg-0">
               <div class="card-header bg-light d-flex justify-content-between">
                   <h5 class="mb-0">Posts Create</h5>
               </div>
               <div class="card-body scrollbar-overlay">
                  <div class="border-data py-2 px-4">
                     <form action="{{ route('createFeedbackOperation') }}" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="d-flex py-3 mb-2 align-items-center">
                        {{--  --}}
                        {{--  --}}
                        <div class="userNameAgo px-3 py-1">
                           <h5 class="mb-1">{{ Auth::guard('user')->user()->name }}</h5>
                        </div>
                     </div>
                     {{--  --}}
                     <input type="text" class="form-control" placeholder="Ex, Feedback Title" name="feedbackTitle">
                     {{--  --}}
                     <textarea name="feedback_details" class="form-control" placeholder="What's on your mind?" spellcheck="false" required rows="5"></textarea>
                     {{--  --}}
                      <div class="options d-flex justify-content-between align-items-center">
                        <p class="mt-2">Category Post</p>
                        <select name="feedback_category" id="feedback_category" class="form-control w-50">
                            <option value="">Select Category</option>
                            @if ($selectCategory->isEmpty())
                                
                            @else
                                @foreach ($selectCategory as $categorys)
                                <option value="{{ $categorys->id }}">{{ $categorys->category_name }}</option>  
                                @endforeach
                            @endif
                        </select>
                      </div>
                      <button type="submit">Post</button>
                     </form>
                  </div>
               </div>
            </div>
            <br>
            @if ($selectFeedback->isEmpty())
                  
            @else
            @foreach ($selectFeedback as $selectFeedbacks)
            <div class="card mb-3 mt-2 mb-lg-0">                
                <div class="card-body scrollbar-overlay">
                 <div class="row g-0">
                   <div class="mb-3 mb-lg-0">
                      <div>
                         {{--  --}}
                         <div class="border-data py-2 px-3">
                            {{-- user name and time ago --}}
                            <div class="d-flex py-3 mb-2">
                               {{--  --}}
                               <div class="userNameAgo px-2 py-1">
                                  <h5 class="mb-1">{{ Auth::guard('user')->user()->name }}</h5>
                                  <h6 class="mb-1">{{ $selectFeedbacks->relative_time }} <span class="mx-2"> ( {{ $selectFeedbacks->category_name }} ) </span> </h6>
                               </div>
                            </div>
                            {{-- post message --}}
                            <p class="mb-0 mt-2 text-1000">
                             {{ $selectFeedbacks->feedback_details }}
                            </p>
                         </div>
                         <hr>
                         {{-- you and others like and comment sections --}}
                         <div class="row px-3">
                            
                            <div class="col-md-12 text-start">
                               <p>
                                {{ \DB::table('comments')->where('feedback_id', '=', $selectFeedbacks->id)->count();}} <span class="mx-2">Comments</span>
                                </p>
                            </div>
                         </div>
                         <hr>
                         <div class="row px-3">
                            <div class="col-3 text-center btn-like">
                                <a href="{{ route('commentPage',$selectFeedbacks->id) }}" class="btn"><i class="fas fa-comment"></i>
                                <span class="px-2">Comment</span>
                                </a>
                            </div>
                         </div>
                         <hr> 
                         @if ( $comments = DB::table('comments')->join('users','comments.comment_users','=','users.id')
                         ->select('comments.*','users.name')->where('comments.feedback_id', '=', $selectFeedbacks->id)->get() )
                            <div class="d-none">
                                {{  $comments->transform(function ($comments) {
                                    $timestamp = Carbon\Carbon::parse($comments->created_at);
                                     $timeAgo = $timestamp->shortRelativeDiffForHumans(); // Using shortRelativeDiffForHumans to get "0 sec ago" format
                                    $comments->relative_time = $timeAgo;
                                    return $comments;
                                    }); }}
                            </div>
                         @if($comments->isEmpty())
                         @else
                         {{-- comment div --}}
                         <div class="row px-3">
                            <div class="col-12 text-end">
                               <p>
                                  All Comments
                               </p>
                               <div class="border-data">
                                
                                @foreach ($comments as $comment)
                                  <div class="d-flex p-3">
                                     <div class="userNameAgo px-3 pt-3 bg-light text-start mx-3" style="min-width:200px;max-width:700px;border-radius:15px">
                                        <h6 class="mb-1">{{ $comment->name }}</h6>
                                        <p class="mb-0 mt-1 text-1000">{{ $comment->comments }}</p>
                                        <div class="links d-flex pt-1">
                                           <p>{{ $comment->relative_time }}</p>
                                        </div>
                                     </div>
                                  </div>
                                  @endforeach
                               </div>
                            </div>
                         </div>
                         @endif
                         @endif
                      </div>
                   </div>
                 </div>
                </div>
            </div>
            <br>
            @endforeach
            @endif
</div>
@endsection
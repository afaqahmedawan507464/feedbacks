<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#ffffff">
    <script src="/themes/public/assets/js/config.js"></script>
    <script src="/themes/public/vendors/overlayscrollbars/OverlayScrollbars.min.js"></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="/themes/public/vendors/overlayscrollbars/OverlayScrollbars.min.css" rel="stylesheet">
    <link href="/themes/public/assets/css/theme-rtl.min.css" rel="stylesheet" id="style-rtl">
    <link href="/themes/public/assets/css/theme.min.css" rel="stylesheet" id="style-default">
    <link href="/themes/public/assets/css/user-rtl.min.css" rel="stylesheet" id="user-style-rtl">
    <link href="/themes/public/assets/css/user.min.css" rel="stylesheet" id="user-style-default">
    <title>Outgoing Inspection List</title>
</head>
<body>
    <div class="container mt-2">
        @if (Session::has('error_message'))

        <div class="alert alert-danger alert-dismissible fade show px-4 d-flex justify-content-center flex-column" role="alert">
         <strong>Error</strong> {{ Session::get('error_message'); }}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>

       @endif
       @if (Session::has('success_message'))

       <div class="alert alert-success alert-dismissible fade show px-4 d-flex justify-content-center flex-column" role="alert">
         <strong>Success:</strong> {{ Session::get('success_message'); }}
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
       </div>

       @endif
       @if ($errors->any())
       <div class="alert alert-danger alert-dismissible fade show px-4 d-flex justify-content-center flex-column" role="alert">
             @foreach ($errors->all() as $item)
               <li style="list-style: none">{{ $item }}</li>
             @endforeach
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('userDashboard') }}" class="btn btn-outline-primary"><i class="fas fa-angle-left me-2"></i>Back</a>
                        </div>
                        <div class="col-6">
                            <h4 class="text-end">Feedback List</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                    <input type="text" class="form-control" id="searchFeedback"  name="searchFeedback">
                <div class="col-12 py-2">
                    <div class="py-2 d-flex justify-content-end align-items-center">
                        <a href="{{ route('createFeedbackPage') }}" class="btn btn-outline-primary "><i class="mx-2 fas fa-plus"></i>Add More</a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 10%;text-align:center">
                                    Id
                                </th>
                                <th scope="col" style="width: 10%;text-align:center">
                                    Users
                                </th>
                                <th scope="col" style="width: 50%;text-align:center">
                                    Feedback Title
                                </th>
                                <th scope="col" style="width: 10%;text-align:center">
                                    Category
                                </th>
                                <th scope="col" style="width: 20%;text-align:center">
                                    Options
                                </th>
                            </tr>
                        </thead>
                        @if ( $selectFeedbackData->isEmpty() )
                            
                        @else
                        <tbody id="clientData">
                            <div id="total_records">
                            @foreach ($selectFeedbackData as $selectFeedbackDatas)
                            <tr>
                                <th scope="row" style="width: 10%;text-align:center">
                                    {{ $selectFeedbackDatas->id }}
                                </th>
                                <td scope="col" style="width: 10%;text-align:center">
                                    {{ $selectFeedbackDatas->name }}
                                </td>
                                <td  style="width: 50%;text-align:center">
                                    {{ $selectFeedbackDatas->feedback_title }}
                                </td>
                                <td  style="width: 10%;text-align:center">
                                    {{ $selectFeedbackDatas->category_name }}
                                </td>
                                <td  style="width: 20%;text-align:center">
                                    <div class="d-flex">
                                        <span>
                                            <a href="{{ route('detailsPagesOperation',$selectFeedbackDatas->id) }}" class="mx-1 text-decoration-none  btn btn-outline-primary "><i class="fas fa-user"></i></a>
                                        </span>
                                            <span>
                                                <a href="{{ route('removedOperations',$selectFeedbackDatas->id) }}" class="mx-1 text-decoration-none  btn btn-outline-danger "><i class="fas fa-trash"></i></a>
                                            </span>
                                            <span>
                                                <a href="{{ route('editPagesFeedback',$selectFeedbackDatas->id) }}" id="clientEdit" class="mx-1 text-decoration-none  btn btn-outline-warning "><i class="fas fa-edit"></i></a>
                                            </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </div>
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="/themes/public/vendors/popper/popper.min.js"></script>
    <script src="/themes/public/vendors/bootstrap/bootstrap.min.js"></script>
    <script src="/themes/public/vendors/anchorjs/anchor.min.js"></script>
    <script src="/themes/public/vendors/is/is.min.js"></script>
    <script src="/themes/public/vendors/echarts/echarts.min.js"></script>
    <script src="/themes/public/vendors/fontawesome/all.min.js"></script>
    <script src="/themes/public/vendors/lodash/lodash.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="/themes/public/vendors/list.js/list.min.js"></script>
    <script src="/themes/public/assets/js/theme.js"></script>
    <script src="/jquery-3.7.1.min.js"></script>
    <script>
        // search operations
    $(document).ready(function(){
 
 fetch_customer_data();

 function fetch_customer_data(query = '')
 {
     $.ajax({
         url:"{{ route('searchFeedback') }}",
         method:'GET',
         data:{query:query},
         dataType:'json',
         success:function(data)
         {
             $('tbody').html(data.table_data);
         }
     })
 }

 $(document).on('keyup', '#searchFeedback', function(){
     var query = $(this).val();
     fetch_customer_data(query);
 });
});
    </script>
</body>
</html>
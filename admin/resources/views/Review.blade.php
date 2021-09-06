@extends('Layout.app')
@section('title','Review')
@section('content')

<div id="mainDiv" class="container d-none">
<div class="row">
<div class="col-md-12 p-5">

<button id="addNewBtnId" class="btn my-3 btn-sm btn-danger">Add New</button>

<table id="reviewDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Image</th>
	  <th class="th-sm">Name</th>
	  <th class="th-sm">Description</th>
	  <th class="th-sm">Edit</th>
	  <th class="th-sm">Delete</th>
    </tr>
  </thead>
  <tbody id="review_table">

  </tbody>
</table>

</div>
</div>
</div>


<div id="loaderDiv" class="container">
<div class="row">
<div class="col-md-12 text-center p-5">
<img class="loading-icon m-5" src="{{asset('images/loader.svg')}}">

</div>
</div>
</div>

<div id="WrongDiv" class="container d-none">
<div class="row">
<div class="col-md-12 text-center p-5">
      <h3>Something went wrong !</h3>

</div>
</div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-3 text-center">
        <h5 class="m-4">Do You Want to Delete ?</h5>
        <h5 id="reviewDeleteId" class="m-4 d-none"></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">No</button>
        <button id="reviewDeleteConfirmBtn" type="button" class="btn btn-sm btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Update Review</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
      <div class="modal-body p-5 text-center">

      <h5 id="reviewEditId" class="m-4 d-none"></h5>
      <div id="reviewEditForm" class="d-none w-100">
      <input id="reviewNameID" type="text" id="" class="form-control mb-4" placeholder="Review Name">
      <input id="reviewDesID" type="text" id="" class="form-control mb-4" placeholder="Review Description">
      <input id="reviewImgID" type="text" id="" class="form-control mb-4" placeholder="Review Image Link">
      </div>

      <img id="reviewEditLoader" class="loading-icon m-5" src="{{asset('images/loader.svg')}}">
      <h5 id="reviewEditWrong" class="d-none" >Something went wrong !</h5>


      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button id="reviewEditConfirmBtn" type="button" class="btn btn-sm btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-5 text-center">

      <div id="reviewAddForm" class="w-100">
      <h6 class="mb-4">Add New Review</h6>
      <input id="reviewNameAddID" type="text" id="" class="form-control mb-4" placeholder="Review Name">
      <input id="reviewDesAddID" type="text" id="" class="form-control mb-4" placeholder="Review Description">
      <input id="reviewImgAddID" type="text" id="" class="form-control mb-4" placeholder="Review Image Link">
      </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button id="reviewAddConfirmBtn" type="button" class="btn btn-sm btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('script')

<script type="text/javascript">
	getReviewData();

//For Review Table

function getReviewData() {

    axios.get('/getReviewData')
        .then(function(response) {

            if (response.status == 200) {

                $('#mainDiv').removeClass('d-none');
                $('#loaderDiv').addClass('d-none');

                $('#reviewDataTable').DataTable().destroy();
                $('#review_table').empty();

                var jsonData = response.data;

                $.each(jsonData, function(i, item) {
                    $('<tr>').html(
                        "<td><img class='table-img' src=" + jsonData[i].img + "></td>" +
                        "<td>" + jsonData[i].name + "</td>" +
                        "<td>" + jsonData[i].des + "</td>" +
                        "<td><a class='reviewEditBtn' data-id=" + jsonData[i].id + "><i class='fas fa-edit'></i></a></td>" +
                        "<td><a class='reviewDeleteBtn' data-id=" + jsonData[i].id + "><i class='fas fa-trash-alt'></i></a></td>"
                    ).appendTo('#review_table');
                });


                //Review Table Delete Icon Click

                $('.reviewDeleteBtn').click(function() {
                    var id = $(this).data('id');

                    $('#reviewDeleteId').html(id);

                    $('#deleteModal').modal('show');

                })


                //Review Table Edit Icon Click

                $('.reviewEditBtn').click(function() {
                    var id = $(this).data('id');

                    $('#reviewEditId').html(id);
                    ReviewUpdateDetails(id);
                    $('#editModal').modal('show');

                })


                $('#reviewDataTable').DataTable({"order": false});
                $('.dataTables_length').addClass('bs-select');


            } else {

                $('#loaderDiv').addClass('d-none');
                $('#WrongDiv').removeClass('d-none');

            }


        })

        .catch(function(error) {

            $('#loaderDiv').addClass('d-none');
            $('#WrongDiv').removeClass('d-none');

        });

}


//Review Delete Modal Yes Btn

$('#reviewDeleteConfirmBtn').click(function() {

    var id = $('#reviewDeleteId').html();
    ReviewDelete(id);


})

//Review Delete Method

function ReviewDelete(deleteID) {

    $('#reviewDeleteConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation....

    axios.post('/ReviewDelete', {
            id: deleteID
        })
        .then(function(response) {
            $('#reviewDeleteConfirmBtn').html("Yes");

            if (response.status == 200) {

                if (response.data == 1) {

                    $('#deleteModal').modal('hide');
                    toastr.success('Delete Success');
                    getReviewData();

                } else {

                    $('#deleteModal').modal('hide');
                    toastr.error('Delete Fail');
                    getReviewData();

                }

            } else {
                $('#deleteModal').modal('hide');
                toastr.error('Something Went Wrong !');
            }

        })

        .catch(function(error) {
            $('#deleteModal').modal('hide');
            toastr.error('Something Went Wrong !');
        });
}

//Each Review Update Details

function ReviewUpdateDetails(detailsID) {
    axios.post('/ReviewDetails', {
            id: detailsID
        })
        .then(function(response) {

            if (response.status == 200) {

                $('#reviewEditForm').removeClass('d-none');
                $('#reviewEditLoader').addClass('d-none');

                var jsonData = response.data;
                $('#reviewNameID').val(jsonData[0].name);
                $('#reviewDesID').val(jsonData[0].des);
                $('#reviewImgID').val(jsonData[0].img);
            } else {
                $('#reviewEditLoader').addClass('d-none');
                $('#reviewEditWrong').removeClass('d-none');
            }

        })

        .catch(function(error) {
            $('#reviewEditLoader').addClass('d-none');
            $('#reviewEditWrong').removeClass('d-none');
        });

}


//Review Edit Modal Save Btn

$('#reviewEditConfirmBtn').click(function() {

    var id = $('#reviewEditId').html();
    var name = $('#reviewNameID').val();
    var des = $('#reviewDesID').val();
    var img = $('#reviewImgID').val();

    ReviewUpdate(id, name, des, img);


})

//Review Update Method

function ReviewUpdate(reviewID, reviewName, reviewDes, reviewImg) {

    if (reviewName.length == 0) {
        toastr.error('Review Name is Empty !');
    } else if (reviewDes.length == 0) {
        toastr.error('Review Description is Empty !');
    } else if (reviewImg.length == 0) {
        toastr.error('Review Image is Empty !');
    } else {
        $('#reviewEditConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation....
        axios.post('/ReviewUpdate', {
                id: reviewID,
                name: reviewName,
                des: reviewDes,
                img: reviewImg,
            })
            .then(function(response) {
                $('#reviewEditConfirmBtn').html("Save");

                if (response.status == 200) {

                    if (response.data == 1) {
                        $('#editModal').modal('hide');
                        toastr.success('Update Success');
                        getReviewData();

                    } else {
                        $('#editModal').modal('hide');
                        toastr.error('Update Fail');
                        getReviewData();

                    }

                } else {
                    $('#editModal').modal('hide');
                    toastr.error('Something Went Wrong !');
                }

            })

            .catch(function(error) {
                $('#editModal').modal('hide');
                toastr.error('Something Went Wrong !');
            });

    }
}

//Review Add New Btn Click

$('#addNewBtnId').click(function() {

    $('#addModal').modal('show');


});

//Review Add Modal Save Btn

$('#reviewAddConfirmBtn').click(function() {

    var name = $('#reviewNameAddID').val();
    var des = $('#reviewDesAddID').val();
    var img = $('#reviewImgAddID').val();

    ReviewAdd(name, des, img);


})

//Review Add Method

function ReviewAdd(reviewName, reviewDes, reviewImg) {

    if (reviewName.length == 0) {
        toastr.error('Review Name is Empty !');
    } else if (reviewDes.length == 0) {
        toastr.error('Review Description is Empty !');
    } else if (reviewImg.length == 0) {
        toastr.error('Review Image is Empty !');
    } else {
        $('#reviewAddConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation....
        axios.post('/ReviewAdd', {
                name: reviewName,
                des: reviewDes,
                img: reviewImg,
            })
            .then(function(response) {
                $('#reviewAddConfirmBtn').html("Save");

                if (response.status == 200) {

                    if (response.data == 1) {
                        $('#addModal').modal('hide');
                        toastr.success('Add Success');
                        getReviewData();

                    } else {
                        $('#addModal').modal('hide');
                        toastr.error('Add Fail');
                        getReviewData();

                    }

                } else {
                    $('#addModal').modal('hide');
                    toastr.error('Something Went Wrong !');
                }

            })

            .catch(function(error) {
                $('#addModal').modal('hide');
                toastr.error('Something Went Wrong !');
            });

    }
}

</script>

@endsection

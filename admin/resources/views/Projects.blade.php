@extends('Layout.app')
@section('title','Projects')
@section('content')

<div id="mainDivProject" class="container d-none">
<div class="row">
<div class="col-md-12 p-5">

<button id="addNewProjectBtnId" class="btn my-3 btn-sm btn-danger">Add New</button>

<table id="projectDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>

	  <th class="th-sm">Name</th>
	  <th class="th-sm">Description</th>
	  <th class="th-sm">Edit</th>
	  <th class="th-sm">Delete</th>

    </tr>
  </thead>

  <tbody id="project_table">

  </tbody>
</table>

</div>
</div>
</div>

<div id="loaderDivProject" class="container">
<div class="row">
<div class="col-md-12 text-center p-5">
<img class="loading-icon m-5" src="{{asset('images/loader.svg')}}">

</div>
</div>
</div>

<div id="WrongDivProject" class="container d-none">
<div class="row">
<div class="col-md-12 text-center p-5">
      <h3>Something went wrong !</h3>

</div>
</div>
</div>


<div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-5 text-center">

      <div id="projectAddForm" class="w-100">
      <h6 class="mb-4">Add New Project</h6>
      <input id="ProjectNameId" type="text" id="" class="form-control mb-4" placeholder="Project Name">
      <input id="ProjectDesId" type="text" id="" class="form-control mb-4" placeholder="Project Description">
      <input id="ProjectLinkId" type="text" id="" class="form-control mb-4" placeholder="Project Link">
      <input id="ProjectImgId" type="text" id="" class="form-control mb-3" placeholder="Project Image">
      </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button id="ProjectAddConfirmBtn" type="button" class="btn btn-sm btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="updateProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Update Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-5 text-center">

       <h5 id="projectEditId" class="m-4 d-none"></h5>

       <div id="projectEditForm" class="d-none w-100">

          <input id="ProjectNameUpdateId" type="text" id="" class="form-control mb-3" placeholder="Project Name">
          <input id="ProjectDesUpdateId" type="text" id="" class="form-control mb-3" placeholder="Project Description">
          <input id="ProjectLinkUpdateId" type="text" id="" class="form-control mb-3" placeholder="Project Link">
          <input id="ProjectImgUpdateId" type="text" id="" class="form-control mb-3" placeholder="Project Image">

       </div>

      <img id="projectEditLoader" class="loading-icon m-5" src="{{asset('images/loader.svg')}}">
      <h5 id="projectEditWrong" class="d-none" >Something went wrong !</h5>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Cancel</button>
        <button  id="ProjectUpdateConfirmBtn" type="button" class="btn  btn-sm  btn-danger">Save</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body p-3 text-center">
        <h5 class="m-4">Do You Want to Delete ?</h5>
        <h5 id="ProjectDeleteId" class="m-4 d-none"></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">No</button>
        <button id="ProjectDeleteConfirmBtn" type="button" class="btn btn-sm btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>


@endsection


@section('script')

<script type="text/javascript">

getProjectsData();

//For Projects Table

function getProjectsData() {

    axios.get('/getProjectsData')
        .then(function(response) {

            if (response.status == 200) {

                $('#mainDivProject').removeClass('d-none');
                $('#loaderDivProject').addClass('d-none');


                $('#projectDataTable').DataTable().destroy();
                $('#project_table').empty();

                var jsonData = response.data;

                $.each(jsonData, function(i, item) {
                    $('<tr>').html(
                        "<td>" + jsonData[i].project_name + "></td>" +
                        "<td>" + jsonData[i].project_desc + "</td>" +

                        "<td><a class='projectEditBtn' data-id=" + jsonData[i].id + "><i class='fas fa-edit'></i></a></td>" +

                        "<td><a class='projectDeleteBtn' data-id=" + jsonData[i].id + "><i class='fas fa-trash-alt'></i></a></td>"

                    ).appendTo('#project_table');
                });

                //Projects Table Delete Icon Click

                $('.projectDeleteBtn').click(function() {
                    var id = $(this).data('id');

                    $('#ProjectDeleteId').html(id);

                    $('#deleteProjectModal').modal('show');

                })

                //Projects Table Edit Icon Click

                $('.projectEditBtn').click(function() {
                     var id = $(this).data('id');

                    ProjectUpdateDetails(id);

                    $('#projectEditId').html(id);

                    $('#updateProjectModal').modal('show');

                })


                $('#projectDataTable').DataTable({"order": false});
                $('.dataTables_length').addClass('bs-select');


            } else {

                $('#loaderDivProject').addClass('d-none');
                $('#WrongDivProject').removeClass('d-none');

            }


        })

        .catch(function(error) {

            $('#loaderDivProject').addClass('d-none');
            $('#WrongDivProject').removeClass('d-none');

        });

}

//Projects Add New Btn Click

$('#addNewProjectBtnId').click(function(){

   $('#addProjectModal').modal('show');

});

//Projects Add Modal Save Btn

$('#ProjectAddConfirmBtn').click(function(){

   var ProjectName= $('#ProjectNameId').val();
   var ProjectDes= $('#ProjectDesId').val();
   var ProjectLink= $('#ProjectLinkId').val();
   var ProjectImg= $('#ProjectImgId').val();

   ProjectAdd(ProjectName, ProjectDes, ProjectLink, ProjectImg);

})

//Projects Add Method

function ProjectAdd(ProjectName, ProjectDes, ProjectLink, ProjectImg) {

    if (ProjectName.length == 0) {
        toastr.error('Project Name is Empty !');
    } else if (ProjectDes.length == 0) {
        toastr.error('Project Des is Empty !');
    } else if (ProjectLink.length == 0) {
        toastr.error('Project Link is Empty !');
    } else if (ProjectImg.length == 0) {
        toastr.error('Project Img is Empty !');
    }

    else {
        $('#ProjectAddConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation....
        axios.post('/ProjectsAdd', {
                project_name: ProjectName,
                project_desc: ProjectDes,
                project_link: ProjectLink,
                project_img: ProjectImg,
            })
            .then(function(response) {
                $('#ProjectAddConfirmBtn').html("Save");

                if (response.status == 200) {

                    if (response.data == 1) {
                        $('#addProjectModal').modal('hide');
                        toastr.success('Add Success');
                        getProjectsData();

                    } else {
                        $('#addProjectModal').modal('hide');
                        toastr.error('Add Fail');
                        getProjectsData();

                    }

                } else {
                    $('#addProjectModal').modal('hide');
                    toastr.error('Something Went Wrong !');
                }

            })

            .catch(function(error) {
                 $('#addProjectModal').modal('hide');
                 toastr.error('Something Went Wrong !');
            });

    }
}


//Projects Delete Modal Yes Btn

$('#ProjectDeleteConfirmBtn').click(function() {

    var id = $('#ProjectDeleteId').html();
    ProjectDelete(id);


})



//Projects Delete Method

function ProjectDelete(deleteID) {

    $('#ProjectDeleteConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation....

    axios.post('/ProjectsDelete', {
            id: deleteID
        })
        .then(function(response) {
            $('#ProjectDeleteConfirmBtn').html("Yes");

            if (response.status == 200) {

                if (response.data == 1) {

                    $('#deleteProjectModal').modal('hide');
                    toastr.success('Delete Success');
                    getProjectsData();

                } else {

                    $('#deleteProjectModal').modal('hide');
                    toastr.error('Delete Fail');
                    getProjectsData();

                }

            } else {
                $('#deleteProjectModal').modal('hide');
                toastr.error('Something Went Wrong !');
            }

        })

        .catch(function(error) {
            $('#deleteProjectModal').modal('hide');
            toastr.error('Something Went Wrong !');
        });
}


//Each Project Update Details

function ProjectUpdateDetails(detailsID) {
    axios.post('/ProjectsDetails', {
            id: detailsID
        })
        .then(function(response) {

            if (response.status == 200) {

                $('#projectEditForm').removeClass('d-none');
                $('#projectEditLoader').addClass('d-none');

                var jsonData = response.data;
                $('#ProjectNameUpdateId').val(jsonData[0].project_name);
                $('#ProjectDesUpdateId').val(jsonData[0].project_desc);
                $('#ProjectLinkUpdateId').val(jsonData[0].project_link);
                $('#ProjectImgUpdateId').val(jsonData[0].project_img);
            } else {
                $('#projectEditLoader').addClass('d-none');
                $('#projectEditWrong').removeClass('d-none');
            }

        })

        .catch(function(error) {
            $('#projectEditLoader').addClass('d-none');
            $('#projectEditWrong').removeClass('d-none');
        });

}


//Project Update Modal Save Btn

$('#ProjectUpdateConfirmBtn').click(function() {

    var projectID = $('#projectEditId').html();
    var projectName = $('#ProjectNameUpdateId').val();
    var projectDes = $('#ProjectDesUpdateId').val();
    var projectLink = $('#ProjectLinkUpdateId').val();
    var projectImg = $('#ProjectImgUpdateId').val();

   ProjectUpdate(projectID, projectName, projectDes, projectLink, projectImg);


})


//Project Update Method

function ProjectUpdate(projectID, projectName, projectDes, projectLink, projectImg) {

    if (projectName.length == 0) {
        toastr.error('Project Name is Empty !');
    } else if (projectDes.length == 0) {
        toastr.error('Project Description is Empty !');
    } else if (projectLink.length == 0) {
        toastr.error('Project Link is Empty !');
    } else if (projectImg.length == 0) {
        toastr.error('Project Img is Empty !');
    }

      else {
        $('#ProjectUpdateConfirmBtn').html("<div class='spinner-border spinner-border-sm' role='status'></div>") //Animation....
        axios.post('/ProjectsUpdate', {
                id: projectID,
                project_name: projectName,
                project_desc: projectDes,
                project_link: projectLink,
                project_img: projectImg,

            })
            .then(function(response) {
                $('#ProjectUpdateConfirmBtn').html("Save");

                if (response.status == 200) {

                    if (response.data == 1) {
                        $('#updateProjectModal').modal('hide');
                        toastr.success('Update Success');
                        getProjectsData();

                    } else {
                        $('#updateProjectModal').modal('hide');
                        toastr.error('Update Fail');
                        getProjectsData();

                    }

                } else {
                    $('#updateProjectModal').modal('hide');
                    toastr.error('Something Went Wrong !');
                }

            })

            .catch(function(error) {
                $('#updateProjectModal').modal('hide');
                toastr.error('Something Went Wrong !');
            });

    }
}



</script>

@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container mt-5">
    <h2 class="mb-4">Admin Page</h2>
    <p>Only users with <code>role = admin</code> can reach this page. Anyone else gets a 403.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
</div>






<div class="container mt-5">

    <h2 class="mb-4" id="form-title">User Registration</h2>

    <form id="userform">

        <input type="hidden" name="id" id="user_id" value="">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" >
                <span class="text-danger" id="first_name_error"></span>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" >
                <span class="text-danger" id="last_name_error"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" >
                <span class="text-danger" id="email_error"></span>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="03XXXXXXXXX">
                <span class="text-danger" id="phone_error"></span>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password">
            <small class="text-muted" id="password_hint" style="display:none;">
                Leave blank to keep the current password.
            </small>
            <span class="text-danger" id="password_error"></span>
        </div>

        <label class="form-label">Hobbies</label>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="hobbies[]" value="Programming">
                    <label class="form-check-label">Programming</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="hobbies[]" value="Design">
                    <label class="form-check-label">Design</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="hobbies[]" value="Reading">
                    <label class="form-check-label">Reading</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="hobbies[]" value="Gaming">
                    <label class="form-check-label">Gaming</label>
                </div>
            </div>
        </div>
        <span class="text-danger" id="hobbies_error"></span>
<div class="container mt-5">
 <h3>Image Upload with Preview</h3>
    <img id="preview-image"
         src="{{ asset('images/default.png') }}"
         width="200"
         height="200"
         class="border rounded mb-3">



    <input type="file"
           id="image-upload"
           class="form-control mb-3"
           accept="image/*">

    <button type="button" id="uploadBtn" class="btn btn-primary">
        Upload
    </button>

</div>
        <button id="submitBtn" type="submit" class="btn btn-primary">Save User</button>
        <button id="cancelBtn" type="button" class="btn btn-secondary" style="display:none;">Cancel Edit</button>
    </form>

    <hr class="my-5">

    <h3 class="mb-3">All Users</h3>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Hobbies</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody id="usersTableBody">
        </tbody>
    </table>
</div>

<script>

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


    $('#image-upload').change(function () {
    let file = this.files[0];

    if(file){
        let reader = new FileReader();

        reader.onload = function(e){
            $('#preview-image').attr('src', e.target.result);
        }

        reader.readAsDataURL(file);
    }
});

let uploadedImage = '';
        // Upload image
    $('#uploadBtn').click(function () {

        let file = $('#image-upload')[0].files[0];

        console.log("Selected File:", file);

        if (!file) {

            alert("Please select an image.");

            return;
        }

        console.log("File Name :", file.name);
        console.log("File Type :", file.type);
        console.log("File Size :", file.size);

        let formData = new FormData();
  formData.append('image', file);
   $.ajax({

            url: "/upload-image",

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                console.log(response);
     uploadedImage = response.image;
                alert("Image Uploaded Successfully.");

            },

            error: function (xhr) {

                console.log(xhr.responseText);

                alert("Image Upload Failed.");

            }

        });

    });



$(document).ready(function () {
    loadUsers();

    $('#userform').submit(function (e) {
        e.preventDefault();

        let id = $('#user_id').val();

        let hobbies = [];
        $("input[name='hobbies[]']:checked").each(function () {
            hobbies.push($(this).val());
        });

        let all = {
            first_name: $("input[name='first_name']").val(),
            last_name:  $("input[name='last_name']").val(),
            email:      $("input[name='email']").val(),
            phone:      $("input[name='phone']").val(),
            password:   $("input[name='password']").val(),
            hobbies:    hobbies,
            //image: uploadedImage,
            image:      'uploads/default.png'
        };

        $(".text-danger").html("");

        $.ajax({
            url: id ? '/users/' + id : '/users',
            method: id ? 'PUT' : 'POST',
            data: all,

            success: function (response) {
                resetForm();
                loadUsers();
            },

            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (field, messages) {
                        $("#" + field + "_error").html(messages.join("<br>"));
                    });
                } else {
                   console.log(xhr.responseText);
                }
            }
        });
    });

    $('#usersTableBody').on('click', '.edit-btn', function () {
        let id = $(this).data('id');

        $.ajax({
            url: '/users/' + id,
            method: 'GET',
            success: function (response) {
                let user = response.data;
                $('#user_id').val(user.id);
                $("input[name='first_name']").val(user.first_name);
                $("input[name='last_name']").val(user.last_name);
                $("input[name='email']").val(user.email);
                $("input[name='phone']").val(user.phone);
                $("input[name='password']").val('');

                $("input[name='hobbies[]']").prop('checked', false);
                (user.hobbies_list || []).forEach(function (hobby) {
                    $("input[name='hobbies[]'][value='" + hobby + "']").prop('checked', true);
                });
              
                    if(user.image)
{
    user.image = 'uploads/default.png';
    $('#preview-image').attr('src','/' + user.image);

     //                $('#preview-image').attr('src','/'+user.image);
  //               uploadedImage = user.image;
}


              


                $('#form-title').text('Update User');
                $('#submitBtn').text('Update User');
                $('#cancelBtn').show();
                $('#password_hint').show();

                $('html, body').animate({ scrollTop: 0 }, 300);
            },
            error: function () {
                alert('Could not load that user.');
            }
        });
    });

    // DELETE
    $('#usersTableBody').on('click', '.delete-btn', function () {
        let id = $(this).data('id');

        if (!confirm('Are you sure you want to delete this user?')) {
            return;
        }

        $.ajax({
            url: '/users/' + id,
            method: 'DELETE',
            success: function () {
                loadUsers();
            },
            error: function () {
                alert('Could not delete that user.');
            }
        });
    });

    // CANCEL EDIT
    $('#cancelBtn').click(function () {
        resetForm();
    });
});

function loadUsers() {
    $.ajax({
        url: '/users',
        method: 'GET',
        success: function (response) {
            let rows = '';
            response.data.forEach(function (user, index) {
                let hobbies = (user.hobbies_list || []).join(', ');
                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${user.first_name} ${user.last_name}</td>
                        <td>${user.email}</td>
                        <td>${user.phone}</td>
                        <td>${hobbies}</td>
                        <td>
                        ${user.image 
? `<img src="/${user.image}" width="80" height="80">`
: `<img src="/uploads/default.png" width="80" height="80">`
}
 
</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="${user.id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${user.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });

            $('#usersTableBody').html(rows);
        }
    });
}

function resetForm() {
    $('#userform')[0].reset();
    $('#user_id').val('');
    $('.text-danger').html('');
    $('#form-title').text('User Registration');
    $('#submitBtn').text('Save User');
    $('#cancelBtn').hide();
 //   $('#password_hint').hide();
     $('#preview-image').attr('src','/uploads/default.png');
}
</script>

</body>
</html>






<!DOCTYPE html>
<html>
<head>
    <title>HOTEL (LARAVEL - CRUD)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
</head>
<body>

<div class="container">

    <div class="header">

        <img src="images/head.png" style="width: 100%;
                                          height: auto;
                                          background-size: cover;">
        <div id="navbar">
            <a href="javascript:void(0)">Журнал регистрации гостей</a>
            <a href="{{route('ajaxrooms.index')}}">Номера</a>
            <a class="active" href="javascript:void(0)">Клиенты</a>
        </div>

    </div>

    <a class="btn btn-success" style="float: right; margin-bottom: 15px;" href="javascript:void(0)" id="createNewClient"> Create New Client</a>

    <table class="table table-bordered data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Surname</th>
            <th>Name</th>
            <th>Lastname</th>
            <th>Mail</th>
            <th>Comment</th>
            <th width="280px">Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="clientForm" name="clientForm" class="form-horizontal">
                    <input type="hidden" name="client_id" id="client_id">
                    <div class="form-group">
                        <label for="surname" class="col-sm-2 control-label">Surname</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Surname" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lastname" class="col-sm-2 control-label">Lastname</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Lastname" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mail" class="col-sm-2 control-label">Mail</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="mail" name="mail" placeholder="Enter Mail" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-12">
                            <textarea id="comment" name="comment" required="" placeholder="Enter Comment" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

<script type="text/javascript">
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ajaxclients.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'surname'},
                {data: 'name'},
                {data: 'lastname'},
                {data: 'mail'},
                {data: 'comment'},
                {data: 'action', orderable: false, searchable: false},
            ]
        });

        $('#createNewClient').click(function () {
            $('#saveBtn').val("create-client");
            $('#client_id').val('');
            $('#clientForm').trigger("reset");
            $('#modelHeading').html("Create New Client");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editClient', function () {
            var client_id = $(this).data('id');
            $.get("{{ route('ajaxclients.index') }}" +'/' + client_id +'/edit', function (data) {
                $('#modelHeading').html("Edit Client");
                $('#saveBtn').val("edit-client");
                $('#ajaxModel').modal('show');
                $('#client_id').val(data.id);
                $('#name').val(data.name);
                $('#surname').val(data.surname);
                $('#lastname').val(data.lastname);
                $('#mail').val(data.mail);
                $('#comment').val(data.comment);
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
                data: $('#clientForm').serialize(),
                url: "{{ route('ajaxclients.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {

                    $('#clientForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.deleteClient', function () {

            var client_id = $(this).data("id");
            var result = confirm("Are You sure want to delete !");

            if (result) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('ajaxclients.store') }}" + '/' + client_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

    });
</script>
</html>

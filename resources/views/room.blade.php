<!DOCTYPE html>
<html>
<head>
    <title>HOTEL (LARAVEL - CRUD)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
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
            <a href="{{route('ajaxjournals.index')}}">Журнал регистрации гостей</a>
            <a class="active" href="javascript:void(0)">Номера</a>
            <a href="{{route('ajaxclients.index')}}">Клиенты</a>
        </div>

    </div>

    <a class="btn btn-success" style="float: right; margin-bottom: 15px;" href="javascript:void(0)" id="createNewRoom">
        Create New Room</a>

    <table class="table table-bordered data-table">
        <thead>
        <tr>
            <th>№</th>
            <th>Capacity</th>
            <th>Comfortable</th>
            <th>Price</th>
            <th width="180px">Action</th>
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
                <form id="roomForm" name="roomForm" class="form-horizontal">

                    <input type="hidden" name="room_id" id="room_id">

                    <div class="form-group">
                        <label for="number" class="col-sm-4 control-label">№ Room</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="number" name="number" placeholder="Enter № Room"
                                   value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="capacity" class="col-sm-4 control-label">Capacity</label>
                        <div class="col-sm-12">
                            <input type="number" value="1" min="1" max="16" class="form-control" id="capacity"
                                   name="capacity" placeholder="Enter capacity" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comfortable" class="col-sm-4 control-label">Comfortable</label>
                        <div class="col-sm-12">
                            <select class="browser-default custom-select" id="comfortable" name="comfortable" value=""
                                    maxlength="50" required="">
                                <option selected>Enter comfortable</option>
                                <option value="Обычный">Обычный</option>
                                <option value="Полулюкс">Полулюкс</option>
                                <option value="Люкс">Люкс</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price" class="col-sm-4 control-label">Price</label>
                        <div class="col-sm-12">
                            <input type="number" value="10" min="10" max="16000" class="form-control" id="price"
                                   name="price" placeholder="Enter price" value="" maxlength="50" required="">
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
            ajax: "{{ route('ajaxrooms.index') }}",
            columns: [
                {data: 'number', name: 'number'},
                {data: 'capacity'},
                {data: 'comfortable'},
                {data: 'price'},
                {
                    data: 'action', orderable: false, searchable: false, className: 'text-right',
                    "render": function (data, type, row) {

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' + row.id + '" data-original-title="Edit" class="edit btn btn-primary btn-sm editRoom">Edit</a>';

                        $btn = $btn + ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' + row.id + '" data-original-title="Delete" class="btn btn-danger btn-sm deleteRoom">Delete</a>';

                        return $btn;
                    }
                }
            ]
        });

        $('#createNewRoom').click(function () {
            $('#saveBtn').val("create-room");
            $('#room_id').val('');
            $('#roomForm').trigger("reset");
            $('#modelHeading').html("Create New Room");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editRoom', function () {
            var room_id = $(this).data('id');
            $.get("{{ route('ajaxrooms.index') }}" + '/' + room_id + '/edit', function (data) {
                $('#modelHeading').html("Edit Room");
                $('#saveBtn').val("edit-room");
                $('#ajaxModel').modal('show');
                $('#room_id').val(data.id);
                $('#number').val(data.number);
                $('#capacity').val(data.capacity);
                $('#comfortable').val(data.comfortable);
                $('#price').val(data.price);
            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
                data: $('#roomForm').serialize(),
                url: "{{ route('ajaxrooms.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {

                    $('#roomForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.deleteRoom', function () {

            var room_id = $(this).data("id");
            var result = confirm("Are You sure want to delete !");

            if (result) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('ajaxrooms.store') }}" + '/' + room_id,
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

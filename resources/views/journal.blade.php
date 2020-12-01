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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
</head>
<body>

<div class="container-fluid">

    <div class="row-header">

        <img src="images/head.png" style="width: 100%;
                                          height: auto;
                                          background-size: cover;">
        <div id="navbar">
            <a class="active" href="javascript:void(0)">Журнал регистрации гостей</a>
            <a href="{{route('ajaxrooms.index')}}">Номера</a>
            <a href="{{route('ajaxclients.index')}}">Клиенты</a>
        </div>

    </div>

    <a class="btn btn-success" style="float: right; margin-bottom: 15px;" href="javascript:void(0)"
       id="createNewRecord"> Create New Record</a>

    <table class="table table-responsive-sm table-bordered data-table" width="100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Date income</th>
            <th>Client</th>
            <th>Room</th>
            <th>Date export</th>
            <th>Action</th>
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
                <form id="recordForm" name="recordForm" class="form-horizontal">

                    <input type="hidden" name="record_id" id="record_id">

                    <div class="form-group">
                        <label for="date_income" class="col-sm-4 control-label">Date income</label>
                        <div class="col-sm-12">
                            <input type="date" class="form-control" id="date_income" name="date_income" value=""
                                   required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="client_id" class="col-sm-4 control-label">Client</label>
                        <div class="col-sm-12">
                            <select name="client_id" id="client_id" class="form-control" required>
                                <option selected>Enter mail client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->mail }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="room_id" class="col-sm-4 control-label">Room</label>
                        <div class="col-sm-12">
                            <select name="room_id" id="room_id" class="form-control" required>
                                <option selected>Enter number room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->number }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="date_export" class="col-sm-4 control-label">Date export</label>
                        <div class="col-sm-12">
                            <input type="date" class="form-control" id="date_export" name="date_export"
                                   placeholder="Enter date export" value="" maxlength="50">
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
            ajax: "{{ route('ajaxjournals.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'date_income'},
                {data: 'mail'},
                {data: 'number'},
                {data: 'date_export'},
                {data: 'action', orderable: false, searchable: false}
            ]
        });

        console.log(table.columns.id);

        $('#createNewRecord').click(function () {
            $('#saveBtn').val("create-record");
            $('#record_id').val('');
            $('#recordForm').trigger("reset");
            $('#modelHeading').html("Create New Record");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editRecord', function () {
            var record_id = $(this).data('id');
            console.log($(this));
            console.log(record_id);
            $.get("{{ route('ajaxjournals.index') }}" + '/' + record_id + '/edit', function (data) {
                $('#modelHeading').html("Edit Record");
                $('#saveBtn').val("edit-record");
                $('#ajaxModel').modal('show');
                $('#record_id').val(data.id);
                $('#date_income').val(data.date_income);
                $('#client_id').val(data.client_id);
                $('#room_id').val(data.room_id);
                $('#date_export').val(data.date_export);

            })
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $(this).html('Sending..');

            $.ajax({
                data: $('#recordForm').serialize(),
                url: "{{ route('ajaxjournals.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {

                    $('#recordForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();

                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
        });

        $('body').on('click', '.deleteRecord', function () {

            var record_id = $(this).data("id");
            var result = confirm("Are You sure want to delete !");

            if (result) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('ajaxjournals.store') }}" + '/' + record_id,
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

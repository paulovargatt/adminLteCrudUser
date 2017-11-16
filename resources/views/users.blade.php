@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Usuários</h1>
@stop

@section('content')

    <button class="btn btn-primary" onclick="addForm()">Novo</button><br><br>
        <table class="table table-hover" style="background: #fff">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Atualização</th>
                <th>Criação</th>
                <th>Ações</th>
            </tr>
            @foreach($user as $users)
            <tr>
                <td>{{$users->id}}</td>
                <td>{{$users->name}}</td>
                <td>{{$users->email}}</td>
                <td>{{$users->updated_at->diffForHumans()}}</td>
                <td>{{$users->created_at->diffForHumans()}}</td>
                <td><span class="btn btn-xs btn-warning" id="edit" onclick="editForm({{$users->id}})">Edit</span>
                    <span class="btn btn-xs btn-danger" data-id="{{$users->id}}" id="delete">Delete</span>
                </td>
            </tr>
            @endforeach
        </table>
        {{$user->links()}}


        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Novo User</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="form-horizontal" data-toggle="validator">
                            {{csrf_field()}} {{method_field('POST')}}
                            <input type="hidden" id="id" name="id">
                            <div class="text-center" style="width: 80%;margin: 0 auto">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" >Nome</label>
                                    <input type="text" class="form-control " id="name" name="name" placeholder="Nome">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"  placeholder="E-mail"><br>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" id="password" name="password" class="form-control"  placeholder="Password"><br>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
@stop

@section('js')
    <script>
        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-title').text('Adicionar');
        }

        $( document ).on('submit', function (e) {
           e.preventDefault();
            var id = $('#id').val();
            if (save_method == 'edit') {
                url = "/edituser/" + id;
                tp = "PUT";
            }
            else{
                url = "/newuser";
                tp = "POST";
            }
            $.ajax({
                url: url,
                type: tp,
                data: $('#modal-form form').serialize(),
                success: function ($data) {
                    $('#modal-form').modal('hide');
                    $('.table').load(' .table')
                },
                error : function(){
                    alert('Não foi possível salvar esse registro');
                }
            });
        });


        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $.ajax({
                url: "getuser" + '/' + id,
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Editar');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                },
                error: function(){
                    alert("Erro ao atualizar");
                }
            });
        }

        $(document).on('click','#delete', function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/delete-user/' + id,
                type: "POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                },
                success: function (content) {
                    $('.table').load(' .table')
                }
            });
        });
    </script>
@stop
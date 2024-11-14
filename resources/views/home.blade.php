@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <form method="post"  id="tasksForm">
                        @csrf

                        <div class="row">
                            <div class="col-lg-12">

                            </div>
                            <input class="form-control mt-2" name="Tittle" id="Tittle" placeholder="Tittle" required></input>
                            <input class="form-control mt-2" name="Description" id="Description" placeholder="Description" required></input>
                            <select class="form-control mt-2" name="Status" id="Status">
                                <option selected disabled>Zgjedh Statusin</option>
                                <option value="1">Perfunduar</option>
                                <option value="0">Pa Perfunduar</option>
                            </select>
                            <select class="form-control mt-2" name="Priority" id="Priority">
                                <option selected disabled>Zgjedh Prioritetin</option>
                                <option value="1">Larte</option>
                                <option value="2">Mesatar</option>
                                <option value="3">Ulet</option>
                            </select>
                        </div>
                            

                            
                    </form>

                    <button  class="btn btn-success mt-2" onclick="ruaj()" id="ruajBtn">Ruaj</button>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-4">
            <select class="form-control mt-2" name="PriorityFilter" id="PriorityFilter">
                                <option selected disabled>Zgjedh Prioritetin</option>
                                <option value="1">Larte</option>
                                <option value="2">Mesatar</option>
                                <option value="3">Ulet</option>
                            </select>
            </div>
            <div class="col-lg-4 mt-2">
                    <button class="btn btn-warning" id="filterBtn">Filter</button>
            </div>
        </div>
    </div>

    <div class="row mt-4">
    <h1>My Tasks</h1>

@if($allTasks->isEmpty())
    <p>No tasks found.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>Tittle</th>
                <th>Description</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Created At</th>
                <th>Edited At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tbl-body">
            @foreach($allTasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->Description }}</td>
                    <td>{{ ($task->status==1)?'Perfunduar':'Pa Perfunduar' }}</td>
                    <td>{{ ($task->priority == 1) ? 'Larte' : (($task->priority == 2) ? 'Mesatar' : 'Ulet') }}                    </td>
                    <td>{{ $task->created_at }}</td>
                    <td>{{ $task->edited_at }}</td>
                    <td>
                        <button class="btn btn-primary" id="editBtn" onclick="edit({{$task->id}})">Edit</button>
                        
                            <button  class="btn btn-danger" onclick="deleteTask({{$task->id}})">Delete</button>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
    </div>
</div>

<script>
    $(document).ready(function(){
       
    $("#filterBtn").on('click',function(){
        let priority = $("#PriorityFilter").val()
        if(priority != ''){
            $.ajax({
                url:"{{route('filterTasks')}}",
                method:'get',
                data:{priority},
                dataType:'json',
                success:function(response){
                    let responseStr = JSON.stringify(response)
                  let responseParse = JSON.parse(responseStr)
                  $("#tbl-body").empty()
                  responseParse.forEach(element => {
                    $("#tbl-body").append(` <tr>
                    <td>${element.title}</td>
                    <td>${element.Description}</td>
                    <td>${(element.status==1)?'Perfunduar':'Pa Perfunduar'}</td>
                    <td>${(element.priority == 1) ? 'Larte' : (element.priority == 2 ? 'Mesatar' : 'Ulet')}                 </td>
                    <td>${element.created_at}</td>
                    <td>${element.edited_at}</td>
                    <td>
                        <button class="btn btn-primary" id="editBtn" onclick="edit(${element.id})">Edit</button>
                        
                            <button  class="btn btn-danger" onclick="deleteTask(${element.id})">Delete</button>
                        
                    </td>
                </tr>`)
                  });

                }
            })
        }
    })


 function ruaj(){
        let formData = new FormData($("form#tasksForm")[0])

        $.ajax({
            url:"{{route('save')}}",
            method:'post',
            data:formData,
            success:function(response){

            },
            contentType:false,
            processData:false,
            cache:false
        })
    }


    function edit(id){
        $.ajax({
                url:"{{route('getTasksData')}}",
                method:'get',
                data:{id},
                dataType:'json',
                success:function(response){
                    $("#Tittle").val(response.result.title)
                    $("#Description").val(response.result.Description)
                    $("#Status").val(response.result.status).trigger('change')
                    $("#Priority").val(response.result.priority).trigger('change')

                    $("#ruajBtn").attr('onclick',`update(${id})`)
                }
            }) 
    }


   function update(id){
    
    let formData = new FormData($("form#tasksForm")[0])
    formData.append('id',id)

    $.ajax({
        url:"{{route('update')}}",
        method:'post',
        data:formData,
        success:function(response){

        },
        cache:false,
        processData:false,
        contentType:false
    })
   }

    function deleteTask(id){
        alert("A jeni te sigurte qe deshironi te fshini taskun?")

        $.ajax({
            url:"{{route('deleteTask')}}",
            method:'delete',
            data:{id,_token: $('meta[name="csrf-token"]').attr('content')},
            success:function(response){
                location.reload()
            }
        })
    }

    })
</script>
@endsection

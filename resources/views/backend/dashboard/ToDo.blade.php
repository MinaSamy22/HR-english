@extends('backend.layouts.app')

@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
    <div class="content-header">
     <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="m-0 mt-3 mb-3">{{ __('h_todo.reminder') }}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" class="text">{{ __('h_todo.home') }}</a></li>
                        <li class="breadcrumb-item active text">{{ __('h_todo.reminder') }}</li>
                    </ol>
        </div>
      </div>
    </div>


    <!-- To Do List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ion ion-clipboard mr-1"></i>
                {{ __('h_todo.to_do_list') }}
            </h3>
            <button type="button" class="btn btn-danger float-right ml-2" onclick="deleteSelected()">
                <i class="fas fa-trash"></i>
            </button>
        </div>


        <div class="card-body">
            <div class="mb-2">
                <input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> {{ __('h_todo.select_all') }}
            </div>
            <ul class="todo-list" data-widget="todo-list">
                @foreach($tasks as $task)
                    <li>

                        <div class="icheck-primary d-inline ml-2">
                            <input type="checkbox" class="taskCheckbox" id="todoCheck{{ $task->id }}" value="{{ $task->id }}">
                            <label for="todoCheck{{ $task->id }}"></label>
                        </div>
                        <span class="text" id="taskText{{ $task->id }}">{{ $task->task }}</span>

                        <div class="tools" style="float: right;">
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="border: none; background: none;">
                                    <i class="fas fa-trash-o"></i>
                                </button>
                            </form>

                            <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display:inline;" id="editForm{{ $task->id }}">
                                @csrf
                                @method('PUT')
                                <input type="text" name="task" value="{{ $task->task }}" style="display:none;" id="editInput{{ $task->id }}">
                                <button type="button" class="btn btn-warning btn-sm" onclick="editTask({{ $task->id }})"><i class="fas fa-edit"></i></button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>


        <div class="card-footer clearfix">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" name="task" class="form-control" placeholder="{{ __('h_todo.add_new_task') }}">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('h_todo.add_item') }}</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pass translations to JavaScript -->
<script>
    window.translations = {
        deleteConfirm: "{{ __('h_todo.delete_selected_confirm') }}",
        noTasksSelected: "{{ __('h_todo.no_tasks_selected') }}"
    };
</script>

<!-- Link to the new JavaScript file -->
<script src="{{ url('dist/js/todo.js') }}"></script>

@endsection

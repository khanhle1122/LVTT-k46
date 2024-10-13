<section>
    <p class="text-center h2">Danh sách dự án</p>
    <div class="mt-5">  
        
        <div class="row">
            <div class="row col">
                <span class="col-1">Mã dự án</span>
                <span class="col">Tên dự án</span>
                <span class="col-2">người phụ trách</span>          

                <span class="col-2">Đối tác</span>          
                <span class="col-1">Mức độ dự án</span>          
    
                {{-- <span class="col-2">Ngày bắt đầu</span> --}}
                <span class="col-2">Ngày hoàn thành</span>
                
                <span class="col-1">Tiến độ</span>
    
            </div>
            <div class="col-1">
                <span></span>
            </div>
        </div>
        @foreach($project as $project)
        <div class="row">
            <div class="col-11">
                
                    <a href="{{ route('view.task',$project->id) }}" class="row mt-4 p-1  cursor-pointer" >
                        <span class="col-1">{{ $project->projectCode }}</span>
                        <span class="col">{{ $project->projectName }}</span>
                        <span class="col-2">{{ $project->users->name }}</span>

                        <span class="col-2">{{ $project->clientName }}</span>
                        <span class="col-1">{{ $project->level }}</span>

                        {{-- <span class="col-2">{{ $project->startDate }}</span> --}}
                        <span class="col-2">{{ $project->endDate }}</span>
                        <span class="col-1">{{ $project->progress }}%</span>

                        
                    </a>            
                    
               
            </div>
            <span class="col-1 mt-1 ">
                <div class="d-flex justify-content-around mt-3">
                    <div class="me-2">
                        <form action="{{ route('delete-project') }}" method="POST" class="">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger" value="{{ $project->id }}" name="id" title="Chỉnh sửa">
                                <i class="fa-solid fa-trash "></i>
                            </button>
                        </form>    
                    </div>                
                    
                    <div class="">
                        
                        <a href="#" data-bs-toggle="modal" data-bs-target="#editProject{{ $project->id }}" class="btn btn-warning" title="Chỉnh sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="editProject{{ $project->id }}" tabindex="-1" aria-labelledby="editProjectLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editProjectLabel">Chỉnh sửa <span class="h5">{{ $project->projectName }}</span></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('edit.project') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="projectID" value="{{ $project->id }}">
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <lablel for="projectName">Tên dự án </lablel>
                                                    <input type="text" id="projectName" class="form-control" value="{{ $project->projectName }}" name="projectName" required autocomplete="projectName">
                                                </div>
                                                <div class="col">
                                                    <lablel for="projectCode">Mã dự án</lablel>
                                                    <input type="text" id="projectCode" class="form-control" value="{{ $project->projectCode }}" name="projectCode" required autocomplete="projectCode" >
                                                </div>
                                                <div class="col-2">
                                                    <label for="startDate">Ngày bất đầu</label>
                                                    <input type="date" id="startDate" class="form-control" value="{{ $project->startDate }}" name="startDate" required autocomplete="startDate" >
                                                </div>
                                                <div class="col-2">
                                                    <label for="endDate">Ngày kết thúc</label>
                                                    <input type="date" id="endDate" class="form-control" value="{{ $project->endDate }}" name="endDate" required autocomplete="endDate" >
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col">
                                                    <lablel for="level">Quy mô</lablel>
                                                    <input type="text" id="level" class="form-control" value="{{ $project->level }}" name="level" required autocomplete="level" >
                                                </div>
                                                <div class="col">
                                                    <lablel for="budget">Ngân sách</lablel>
                                                    <input type="text" id="budget" class="form-control" value="{{ $project->budget }}" name="budget" required autocomplete="budget" >
                                                </div>
                                                <div class="col">
                                                    <lablel for="clientName">Đối tác</lablel>
                                                    <input type="text" id="clientName" class="form-control" value="{{ $project->clientName }}" name="clientName" required autocomplete="clientName" >
                                                </div>
                                                
                                                
                                                <div class="col"></div>
                                            </div>
                                            <div></div>
                                            
                                            <button type="submit" class="btn btn-primary mt-2">Chỉnh sửa</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </span>
        </div>
        @endforeach
    </div>



</section>
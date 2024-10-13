<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Doson;
use App\Models\FileSon;
use App\Models\File;
use App\Models\Task;
use App\Models\Document;



class TaskController extends Controller
{
    public function viewtask($id){
        $project = Project::find($id);
        $tasks = Task::where('projectID',$project->id)->get();
        return view('project.task',compact('project','tasks'));
    }

    public function addDoSon(Request $request){
        $request->validate([
            'do_son_name'=> 'required',
            'documentID' => 'required'
        ]);
        $document=Doson::create([
            'documentID'=> $request->documentID,
            'do_son_name' => $request->do_son_name
        ]);
        $documents = Document::find($request->documentID);


        if($request->hasFile('files')) {
            $request->validate([
                'files.*' => 'required|file|max:2048',
            ]);
            $files = $request->file('files');
            $document_dir = 'uploads/' . $documents->documentName .'/'. $document->do_son_name;
            
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
    
                // Tạo tên file unique
                $fileName = $this->generateUniqueFileName($document_dir, $originalName);
                
                // Lưu file và lấy đường dẫn đầy đủ
                $filePath = $file->storeAs('public/' . $document_dir, $fileName);
                FileSon::create([
                    'file_son_name' => $fileName,
                    'file_son_path' => $filePath, // Lưu đường dẫn đầy đủ vào DB
                    'document_sonID' => $document->id,
                ]);
            }
        }
        $notification = array(
            'message' => 'Thư mục đã được thêm',
            'alert-type' => 'success'
        );
        
        return redirect()->back()->with($notification);


    }
    public function addFileSon(Request $request){
        $request->validate([
            'file_son_name'=> 'required',
            'document_sonID' => 'required'
        ]);

        FileSon::create([
            'document_sonID'=> $request->document_sonID,
            'file_son_name' => $request->do_son_name
        ]);

    }
    public function generateUniqueFileName($directory, $originalName)
    {
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $counter = 1;

        // Tên file ban đầu
        $newFileName = $fileName . '.' . $extension;

        // Kiểm tra xem file đã tồn tại chưa
        while (Storage::exists('public/' . $directory . '/' . $newFileName)) {
            // Nếu đã tồn tại, thêm số vào sau tên file
            $newFileName = $fileName . ' (' . $counter . ').' . $extension;
            $counter++;
        }

        return $newFileName;
    }

    public function addFile(Request $request){
        
        $request->validate([
            'documentID' =>'required',
            'files.*' => 'required|file|max:2048',
        ]);
        $files = $request->file('files');
        $document=Document::find($request->documentID);
        $document_dir = 'uploads/' . $document->documentName;
        
        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();

            // Tạo tên file unique
            $fileName = $this->generateUniqueFileName($document_dir, $originalName);
            
            // Lưu file và lấy đường dẫn đầy đủ
            $filePath = $file->storeAs('public/' . $document_dir, $fileName);
            File::create([
                'fileName' => $fileName,
                'filePath' => $filePath, // Lưu đường dẫn đầy đủ vào DB
                'documentID' => $request->documentID,
            ]);
        }
        $notification = array(
            'message' => 'Tài liệu đã được thêm',
            'alert-type' => 'success'
        );
        
        return redirect()->back()->with($notification);
        
    }
    public function addTask(Request $request){
        $request->validate([
            'task_name' => 'required',
            'note' => 'required', 
            'end' => 'required|date',
            'start' => 'required|date',
            'projectID' => 'required',
            'userID' => 'required',
            'stt' => 'required'
        ]);
        
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        
        Task::create([
            'task_name' => $request->task_name,
            'start' => $request->start,
            'end' => $request->end,
            'status' => 0,
            'stt' =>$request->stt,
            'note' => $request->note,
            'parent_id' => $parent_id,
            'projectID' => $request->projectID,
            'userID' => $request->userID,
        ]);

        $notification = array(
            'message' => 'Công việc đã được thêm',
            'alert-type' => 'success'
        );
        
        return redirect()->back()->with($notification);
    }
    public function showGanttChart($id)
    {
        $project = Project::find($id);
        $tasks = Task::where('projectID',$project->id)->get();
        return view('project.task.gant-chart',compact('project','tasks'));
    }
}

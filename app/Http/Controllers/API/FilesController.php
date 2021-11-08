<?php

namespace App\Http\Controllers\API;

use App\Models\files;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $files = files::orderByRaw("type <> 'folder'")->where('user_id', Auth::guard('sanctum')->id())->where('parent_id', null)->paginate();
        return $files;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $num = $request->post('idFolder');
        // dd($num);
        $find = files::find($num);
        // dd($find);
        if ($find === null) {
            if ($request->post('folder')) {
                $files = new files();
                $files->name = $request->post('folder');
                $files->parent_id = null;
                $files->user_id = Auth::guard('sanctum')->id();
                $files->type = 'folder';
                $files->file_path = '/folders/' . Auth::guard('sanctum')->user()->name  . '/' . $files->name;
                $files->save();
                return Response::json(['message' => 'New Folder ' . $request->post('folder')], 201);
            }
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $name = $file->getClientOriginalName();
                    $path =  $file->store(Auth::guard('sanctum')->user()->name, [
                        'disk' => 'folders',
                    ]);
                    $newFile = new files();
                    $newFile->name = $name;
                    $newFile->parent_id = null;
                    $newFile->user_id = Auth::guard('sanctum')->id();
                    $newFile->type = 'file';
                    $newFile->file_path  = $path;
                    $newFile->file_size = $file->getSize();
                    $newFile->file_type = $file->getMimeType();
                    $newFile->save();
                }
                return Response::json(['message' => 'New file' .  $name], 201);
            }
        } else {
            if ($request->post('folder')) {
                $files = new files();
                $files->name = $request->post('folder');
                $files->parent_id = $num;
                $files->user_id = Auth::guard('sanctum')->id();
                $files->type = 'folder';
                $files->file_path = '/folders/' . Auth::guard('sanctum')->user()->name  . '/' . $files->name;
                $files->save();
                return Response::json(['message' => 'New Folder' . $request->post('folder'), 201]);
            }
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $name = $file->getClientOriginalName();
                    $path =  $file->store(Auth::guard('sanctum')->user()->name  . '/' . $num, [
                        'disk' => 'folders',
                    ]);
                    $newFile = new files();
                    $newFile->name = $name;
                    $newFile->parent_id = $num;
                    $newFile->user_id = Auth::guard('sanctum')->id();
                    $newFile->type = 'file';
                    $newFile->file_path  = $path;
                    $newFile->file_size = $file->getSize();
                    $newFile->file_type = $file->getMimeType();
                    $newFile->save();
                }
                return Response::json(['message' => 'New files' ], 201);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

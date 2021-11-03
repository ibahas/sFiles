<?php

namespace App\Http\Controllers;

use App\Models\files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $files = files::orderByRaw("type <> 'folder'")->where('user_id', Auth::id())->where('parent_id', null)->get();
        return view('home', compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        // dd($request->all());
        $var = explode('/', $request->server('HTTP_REFERER'));
        $num = (int)end($var);
        $find = files::find($num);
        if ($find === null) {
            if ($request->post('folder')) {
                $files = new files();
                $files->name = $request->post('folder');
                $files->parent_id = null;
                $files->user_id = Auth::id();
                $files->type = 'folder';
                $files->file_path = '/folders/' . Auth::user()->name . '/' . $files->name;
                $files->save();
            }
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $name = $file->getClientOriginalName();
                    $path =  $file->store(Auth::user()->name, [
                        'disk' => 'folders',
                    ]);
                    $newFile = new files();
                    $newFile->name = $name;
                    $newFile->parent_id = null;
                    $newFile->user_id = Auth::id();
                    $newFile->type = 'file';
                    $newFile->file_path  = $path;
                    $newFile->file_size = $file->getSize();
                    $newFile->file_type = $file->getMimeType();
                    $newFile->save();
                }
            }
            return redirect()->back();
        } else {
            if ($request->post('folder')) {
                $files = new files();
                $files->name = $request->post('folder');
                $files->parent_id = $num;
                $files->user_id = Auth::id();
                $files->type = 'folder';
                $files->file_path = '/folders/' . Auth::user()->name . '/' . $files->name;
                $files->save();
            }
            // dd("DDd", $request->hasFile('files'));

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $name = $file->getClientOriginalName();
                    $path =  $file->store(Auth::user()->name . '/' . $num, [
                        'disk' => 'folders',
                    ]);
                    $newFile = new files();
                    $newFile->name = $name;
                    $newFile->parent_id = $num;
                    $newFile->user_id = Auth::id();
                    $newFile->type = 'file';
                    $newFile->file_path  = $path;
                    $newFile->file_size = $file->getSize();
                    $newFile->file_type = $file->getMimeType();
                    $newFile->save();
                }
            }
            return redirect()->back();
        }


        return redirect()->route('file.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\files  $files
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $files = files::where('parent_id', $id)->get();
        $parent_id = files::where('id', $id)->first();
        // dd($parent_id);
        return view('show', compact('files', 'parent_id'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\files  $files
     * @return \Illuminate\Http\Response
     */
    public function edit(files $files)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\files  $files
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, files $files)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\files  $files
     * @return \Illuminate\Http\Response
     */
    public function destroy(files $files)
    {
        //
    }
}

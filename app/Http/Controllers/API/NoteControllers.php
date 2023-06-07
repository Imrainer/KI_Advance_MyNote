<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Helpers\Api;
use App\Models\Note;
use App\Models\User;
use App\Models\Categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NoteControllers extends ApiController
{
    // <!--MENAMPILKAN NOTE SESUAI USER YANG LOGIN--!>
    public function index()
    {   $userId = Auth::user()->id;
        $notes = Note::where('created_by', $userId)->get();

        $formattedNotes = $notes->map(function ($note) {
            $favorite = $note->favorite === 1 ? true : false;
            $note->favorite = $favorite;
            return $note;
        });

        return Api::createApi(200, 'success', $notes);
    }

    // <!--MENAMPILKAN NOTE BY ID--!>
    public function byId($uuid)
    {   $note = Note::where('id', $uuid)->first();

        if (!$note) {
            return Api::createApi(404, 'Note not found');
        }
    
        $favorite = $note->favorite === 1 ? true : false;
        $note->favorite = $favorite;
        return Api::createApi(200, 'success', $note);
    }

    // <!---MEMBUAT NOTE---!>
    public function create(Request $request)
    {   $userId = Auth::id();
        $notes=[
            'title'=>$request->title,
            'content'=>$request->content,
            'categories_id'=>$request->categories_id,
            'created_by'=> $userId,
        ];
        
        $formattedNotes = $notes->map(function ($note) {
            $favorite = $note->favorite === 1 ? true : false;
            $note->favorite = $favorite;
            return $note;
        });

        $notes['id'] = Str::uuid()->toString();
        
        Note::create($notes);
        return Api::createApi(200, 'successfully created note', $notes);
    }

    // <!---MENGEDIT NOTE--!>
    public function edit(Request $request, $uuid)
    {   
        $notes = Note::findOrFail($uuid);
        $validatedData = $request->validate([
            'photo' => 'nullable|image|max:3072'
            ]);
       
        if ($request->file('photo')) {
            $photo = $request->file('photo')->store('user-profile_picture');
        } else {
            $photo = $notes['photo'];
        }

        if ($request->input('title')) {
            $title = $request->input('title');
        } else {
            $title = $notes['title'];
        }

        if ($request->input('content')) {
            $content = $request->input('content');
        } else {
            $content = $notes['content'];
        }

        if ($request->input('categories_id')) {
            $categories_id = $request->input('categories_id');
        } else {
            $categories_id = $notes['categories_id'];
        }
        
        $notes->update([
           'title'=>$title,
           'content'=>$content,
           'categories_id'=>$categories_id,
           'photo'=>$photo
        ]);

        return Api::createApi(200, 'successfully updated note', $notes);

    }

    // <!---MENGHAPUS NOTE--!>
    public function delete(Request $request, $id)
    {   
        $note = Note::findOrFail($id);
    
        $note->delete();

        return Api::createApi(200, 'note successfully deleted');
    }

     // <!---MENGFAVORIT NOTE--!>
     public function favorite($uuid)
     {         
         $notes = Note::findOrFail($uuid);
 
         $notes->update([
            'favorite'=>1,
         ]);
 
         return Api::createApi(200, 'successfully favorited note', $notes);
     }

      // <!---MENGUNFAVORIT NOTE--!>
      public function unfavorite($uuid)
      {  
         
          $notes = Note::findOrFail($uuid);
  
          $notes->update([
             'favorite'=>null
          ]);
          
        $formattedNotes = $notes->map(function ($note) {
            $favorite = $note->favorite === 1 ? true : false;
            $note->favorite = $favorite;
            return $note;
        });

          return Api::createApi(200, 'successfully unfavorited note', $notes);
  
      }

}

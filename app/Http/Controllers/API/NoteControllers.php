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
        
            $note->created_at_formatted = date('Y-m-d H:i:s', strtotime($note->created_at));
            $note->updated_at_formatted = date('Y-m-d H:i:s', strtotime($note->updated_at));
        
            $photo = $note->photo;
            if ($photo === null) {
                $note->photo = null;
            } else {
                $note->photo = 'https://magang.crocodic.net/ki/Rainer/KI_Advance_MyNote/public/storage/' . $photo;
            }

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

        $note->created_at_formatted = date('Y-m-d H:i:s', strtotime($note->created_at));
        $note->updated_at_formatted = date('Y-m-d H:i:s', strtotime($note->updated_at));

        $photo = $note->photo;
        if ($photo === null) {
            $note->photo = null;
        } else {
            $note->photo = 'https://magang.crocodic.net/ki/Rainer/KI_Advance_MyNote/public/storage/' . $photo;
        }

        return Api::createApi(200, 'success', $note);
    }

    // <!---MEMBUAT NOTE---!>
    public function create(Request $request)
    {   $userId = Auth::id();
        $validatedData = $request->validate([
            'photo' => 'nullable|image|max:3072'
            ]);

        $notes=[
            'title'=>$request->title,
            'content'=>$request->content,
            'categories_id'=>$request->categories_id,
            'created_by'=> $userId,
            'photo' => null
        ];

        if ($request->file('photo')) {
            $photo = $request->file('photo')->store('user-note_picture');
            $notes['photo'] = $photo;
    }

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
            $photo = $request->file('photo')->store('user-note_picture');
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

        if($notes['photo']) {
            $notes->photo = 'https://magang.crocodic.net/ki/Rainer/KI_Advance_MyNote/public/storage/'.$notes['photo'];
        } else {
            $notes->photo = null;
        }

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

         $favorite = $notes->favorite === 1 ? true : false;
          $notes->favorite = $favorite;

         if($notes['photo']) {
            $notes->photo = 'https://magang.crocodic.net/ki/Rainer/KI_Advance_MyNote/public/storage/'.$notes['photo'];
        } else {
            $notes->photo = null;
        }
 
         return Api::createApi(200, 'successfully favorited note', $notes);
     }

      // <!---MENGUNFAVORIT NOTE--!>
      public function unfavorite($uuid)
      {
          $notes = Note::findOrFail($uuid);
  
          $notes->update([
             'favorite'=>null
          ]);
          
          $favorite = $notes->favorite === 1 ? true : false;
          $notes->favorite = $favorite;

          if($notes['photo']) {
            $notes->photo = 'https://magang.crocodic.net/ki/Rainer/KI_Advance_MyNote/public/storage/'.$notes['photo'];
            } else {
            $notes->photo = null;
            }
        
        return Api::createApi(200, 'successfully unfavorited note', $notes);

      }

}

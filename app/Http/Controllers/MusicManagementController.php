<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use Illuminate\Support\Facades\Auth;


class MusicManagementController extends Controller
{
    public function list()
    {
        // Lấy user_id của người dùng hiện tại
        $userId = Auth::id();

        // Lấy những bản ghi nhạc chỉ từ user_id hiện tại
        $musics = Music::where('user_id', $userId)->get();

        return view('music.list', ['musics' => $musics]);
    }

    public function edit($id)
    {
        $userId = auth()->user()->id;

        $music = Music::find($id);

        if ($music) {
            // Kiểm tra xem user_id của bản ghi có khớp với id của người dùng hiện tại hay không
            if ($music->user_id === $userId) {
                // Bản ghi tồn tại
                return view('music.edit', ['music' => $music]);
            } else {
                // Bản ghi không thuộc về người dùng hiện tại
                return redirect()->route('music.list')->with('error', 'Permission denied');
            }
        } else {
            // Bản ghi không tồn tại
            return redirect()->route('music.list')->with('error', 'Music not found');
        }
    }




    public function update(Request $request, $id)
    {
        $music = Music::find($id);
        $userId = auth()->user()->id;
    
        if ($music) {
            // Check if the music record belongs to the current user
            if ($music->user_id === $userId) {
                // Update the music details
                $music->update([
                    'title' => $request->input('title'),
                    'artist' => $request->input('artist'),
                    'genre' => $request->input('genre'),
                ]);
    
                // Check if a new file was uploaded
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
    
                    // Validate the uploaded file (e.g., file size, file type)
    
                    // Generate a unique file name
                    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
    
                    // Move the uploaded file to the storage directory
                    $file->move(storage_path('app/music'), $fileName);
    
                    // Update the file path in the music record
                    $music->file_path = $fileName;
                    $music->save();
                }
    
                return redirect()->route("music.edit", ['id' => $id])->with('success', 'Music updated successfully.');
            } else {
                // Music record does not belong to the current user
                return redirect()->route('music.list')->with('error', 'Permission denied');
            }
        } else {
            // Music record not found
            return redirect()->route('music.list')->with('error', 'Music not found');
        }
    }



    public function destroy($id)
    {
        // Tìm bản ghi âm nhạc cần xóa
        $music = Music::find($id);
        $userId = auth()->user()->id;

        if ($music) {


            // Kiểm tra xem user_id của bản ghi có khớp với id của người dùng hiện tại hay không
            if ($music->user_id === $userId) {

                // Trích xuất đường dẫn tệp từ bản ghi
                $music = Music::find($id);
                $filePath = $music->file_path;

                // Xóa tệp từ thư mục storage\app\music
                if (file_exists(storage_path('app/music/' . $filePath))) {
                    unlink(storage_path('app/music/' . $filePath));
                }

                // Sau đó, xóa bản ghi từ cơ sở dữ liệu
                $music->delete();
            } else {
                // Bản ghi không thuộc về người dùng hiện tại
                return redirect()->route('music.list')->with('error', 'Permission denied');
            }






            return redirect()->route('music.list')->with('success', 'Music deleted successfully.');
        } else {
            return redirect()->route('music.list')->with('error', 'Music not found.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\news;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function getAllNews()
    {
        try {
            $allNews = news::orderBy('releaseDate')->paginate(10);

            if (sizeof($allNews->items()) < 1) {
                return response()->json([
                    'success' => false,
                    'error' => 'Data Not Found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $allNews
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function detailNews(string $idnews)
    {
        try {
            $news = news::where('idNews', $idnews)
                ->join('user', 'news.user_id', '=', 'user.idUser')
                ->select('news.*', 'user.username', 'user.photoProfile')
                ->get();

            if (sizeof($news) < 1) {
                return response()->json(['success' => false, 'error' => "Data Not Found"]);
            }
            return response()->json(['success' => true, 'data' => $news]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createNews(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|min:3|max:120',
                'content' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:3000',
                'user_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, "error" => $validator->errors()], 422);
            }

            $path = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('content_image', $filename, 'public');
            }

            $news = news::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => url('storage/' . $path),
                'user_id' => $request->user_id
            ]);

            return response()->json(["success" => true, "data" => $news]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editNews(Request $request, string $idnews)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|min:3|max:120',
                'content' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png|max:3000',
                'user_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, "error" => $validator->errors()], 422);
            }

            $path = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('content_image', $filename, 'public');

                $newsImage = news::where('idNews', $idnews)->select("image")->get();
                $oldFile = basename($newsImage[0]["image"]);
                Storage::disk('public')->delete('content_image/' . $oldFile);
            }

            $news = news::where('idNews', $idnews)->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => url('storage/' . $path),
                'user_id' => $request->user_id
            ]);

            if ($news < 1) {
                return response()->json(["success" => false, "error" => "ID News Not Found"], 404);
            }

            return response()->json(["success" => true, "data" => $news]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteNews(string $idnews)
    {
        try {
            $newsImage = news::where('idNews', $idnews)->select("image")->get();
            $news = news::destroy($idnews);

            if ($news < 1) {
                return response()->json(["success" => false, "error" => "ID News Not Found"], 404);
            }

            $fileName = basename($newsImage[0]["image"]);
            Storage::disk('public')->delete('content_image/' . $fileName);


            return response()->json(['success' => true, 'data' => $news]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

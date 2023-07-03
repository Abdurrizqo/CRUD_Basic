<?php

namespace App\Http\Controllers;

use App\Models\draft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DraftController extends Controller
{
    public function getAllDraft()
    {
        try {
            $allDraft = draft::orderBy('savedDate')->paginate(10);

            if (sizeof($allDraft->items()) < 1) {
                return response()->json([
                    'success' => false,
                    'error' => 'Data Not Found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $allDraft
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function detailDraft(string $iddraft)
    {
        try {
            $draft = draft::where('idDraft', $iddraft)
                ->join('user', 'draftnews.user_id', '=', 'user.idUser')
                ->select('draftnews.*', 'user.username', 'user.photoProfile')
                ->get();

            if (sizeof($draft) < 1) {
                return response()->json(['success' => false, 'error' => "Data Not Found"]);
            }
            return response()->json(['success' => true, 'data' => $draft]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createDraft(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'string|min:3|max:120',
                'image' => 'image|mimes:jpg,jpeg,png|max:3000',
                'user_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, "error" => $validator->errors()], 422);
            }

            $path = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('draft_image', $filename, 'public');
                $path = url('storage/' . $path);
            }

            $draft = draft::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $path,
                'user_id' => $request->user_id
            ]);

            return response()->json(["success" => true, "data" => $draft]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editDraft(Request $request, string $iddraft)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'string|min:3|max:120',
                'image' => 'image|mimes:jpg,jpeg,png|max:3000',
                'user_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, "error" => $validator->errors()], 422);
            }

            $path = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('draft_image', $filename, 'public');
                $path = url('storage/' . $path);

                $draftImage = draft::where('idDraft', $iddraft)->select("image")->get();
                $oldFile = basename($draftImage[0]["image"]);
                Storage::disk('public')->delete('draft_image/' . $oldFile);
            }

            $draft = draft::where('idDraft', $iddraft)->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $path,
                'user_id' => $request->user_id
            ]);

            if ($draft < 1) {
                return response()->json(["success" => false, "error" => "ID Draft Not Found"], 404);
            }

            return response()->json(["success" => true, "data" => $draft]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteDraft(string $iddraft)
    {
        try {
            $draftImage = draft::where('idDraft', $iddraft)->select("image")->get();
            $draft = draft::destroy($iddraft);

            if ($draft < 1) {
                return response()->json(["success" => false, "error" => "ID Draft Not Found"], 404);
            }

            $fileName = basename($draftImage[0]["image"]);
            Storage::disk('public')->delete('draft_image/' . $fileName);

            return response()->json(['success' => true, 'data' => $draft]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

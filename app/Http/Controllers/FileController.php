<?php

namespace App\Http\Controllers;

use App\Models\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class FileController extends Controller
{
    public function index()
    {
        $files = auth()->user()->files()
            ->latest()
            ->get(['id', 'original_name', 'filename', 'disk', 'mime_type', 'size', 'path', 'created_at']);

        return response()->json([
            'success' => true,
            'data' => $files,
            'message' => 'Files retrieved successfully',
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'file' => 'required|file|max:10240',
                'disk' => 'nullable|in:private,public',
            ]);

            $disk = $validated['disk'] ?? 'private';
            $uploaded = $request->file('file');
            $path = $uploaded->store('uploads', $disk);

            $file = File::create([
                'user_id' => auth()->id(),
                'original_name' => $uploaded->getClientOriginalName(),
                'filename' => basename($path),
                'disk' => $disk,
                'mime_type' => $uploaded->getClientMimeType(),
                'size' => $uploaded->getSize(),
                'path' => $path,
            ]);

            return response()->json([
                'success' => true,
                'data' => $file,
                'message' => 'File uploaded successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(File $file)
    {
        return response()->json([
            'success' => true,
            'data' => $file,
            'message' => 'File details retrieved successfully',
        ], 200);
    }

    public function download(File $file)
    {
        return Storage::disk($file->disk)->download($file->path, $file->original_name);
    }

    public function destroy(File $file)
    {
        try {
            Storage::disk($file->disk)->delete($file->path);
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
                'data' => null,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

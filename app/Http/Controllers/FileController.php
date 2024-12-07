<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Str;

class FileController extends Controller
{
    // Listar todos los archivos y carpetas
    public function index(Request $request, $folderId = null)
    {
        $folder = $folderId ? Folder::findOrFail($folderId) : null;
        $folders = Folder::where('parent_id', $folderId)->get();
        $files = File::where('folder_id', $folderId)->withTrashed()->get(); // Incluye los eliminados

        return view('files.index', compact('folders', 'files', 'folder'));
    }

    // Subir un archivo
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        File::create([
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
            'folder_id' => $request->input('folder_id'),
        ]);

        return redirect()->back()->with('success', 'Archivo subido exitosamente.');
    }

    // Descargar un archivo
    public function download($id)
    {
        $file = File::findOrFail($id);

        $filePath = storage_path('app/public/' . $file->path);
        if (file_exists($filePath)) {
            return response()->download($filePath, $file->name);
        }

        return redirect()->back()->with('error', 'Archivo no encontrado.');
    }

    // Borrar (Soft Delete) un archivo
    public function delete($id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        return redirect()->back()->with('success', 'Archivo borrado exitosamente.');
    }

    // Restaurar un archivo
    public function restore($id)
    {
        $file = File::withTrashed()->findOrFail($id);
        $file->restore();

        return redirect()->back()->with('success', 'Archivo restaurado exitosamente.');
    }

    // Eliminar permanentemente un archivo
    public function forceDelete($id)
    {
        $file = File::withTrashed()->findOrFail($id);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->forceDelete();

        return redirect()->back()->with('success', 'Archivo eliminado permanentemente.');
    }

    // Vista previa del archivo
    public function preview($id)
    {
        $file = File::findOrFail($id);

        if (Storage::disk('public')->exists($file->path)) {
            $content = Storage::disk('public')->get($file->path);

            return view('files.preview', compact('file', 'content'));
        }

        return redirect()->back()->with('error', 'Archivo no encontrado.');
    }

    // Guardar cambios en el archivo
    public function updateFile(Request $request, $id)
    {
        $file = File::findOrFail($id);

        $request->validate([
            'content' => 'required',
        ]);

        $versionedPath = 'uploads/versions/' . now()->timestamp . '-' . $file->name;
        Storage::disk('public')->copy($file->path, $versionedPath);

        Storage::disk('public')->put($file->path, $request->content);

        return redirect()->back()->with('success', 'Archivo actualizado exitosamente.');
    }

    // Listar versiones anteriores
    public function versions($id)
    {
        $file = File::findOrFail($id);

        $versions = Storage::disk('public')->files('uploads/versions');
        $filteredVersions = array_filter($versions, function ($version) use ($file) {
            return Str::contains($version, $file->name);
        });

        return view('files.versions', compact('file', 'filteredVersions'));
    }

    // Búsqueda de archivos
    public function search(Request $request)
    {
        $query = $request->input('query');

        $files = File::withTrashed()
            ->where('name', 'LIKE', '%' . $query . '%')
            ->get();

        return view('files.index', compact('files'));
    }

    // Compartir un archivo
    public function share($id)
    {
        $file = File::findOrFail($id);

        // Generar token si no existe
        if (!$file->share_token) {
            $file->generateShareToken();
        }

        // Retornar el enlace
        return response()->json([
            'url' => route('files.shared', $file->share_token)
        ]);
    }

    // Descargar archivo compartido
    public function shared($token)
    {
        $file = File::where('share_token', $token)->firstOrFail();

        return Storage::download($file->path, $file->name);
    }

    // Edición de metadatos
    public function editMetadata($id)
    {
        $file = File::findOrFail($id);
        return view('files.edit-metadata', compact('file'));
    }

    public function updateMetadata(Request $request, $id)
    {
        $file = File::findOrFail($id);

        $request->validate([
            'description' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
        ]);

        $file->update([
            'description' => $request->input('description'),
            'tags' => $request->input('tags') ? json_decode($request->input('tags')) : null,
        ]);

        return redirect()->route('files.index')->with('success', 'Metadatos actualizados correctamente.');
    }

    // Crear una nueva carpeta
    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        Folder::create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
        ]);

        return back()->with('success', 'Carpeta creada exitosamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\NewInvoiceAutomation;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Throwable;
use zipArchive;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index()
    {
        Gate::authorize('index',"Projects");
        try {
            $projects = Project::all();
            $docs = [];
            foreach ($projects as $project) {
                if (Storage::disk('projects_doc')->exists("$project->id"))
                    $docs[] = $project->id;
            }
            return view("{$this->agent}.project_index", ["projects" => $projects, "docs" => $docs]);
        } catch (Throwable $ex) {
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function create()
    {
        Gate::authorize('create',"Projects");
        try {

            return view("{$this->agent}.create_new_project");
        } catch (Throwable $ex) {
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function store(ProjectRequest $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('create',"Projects");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["contract_amount"] = str_replace(",", '', $validated["contract_amount"]);
            $validated["user_id"] = auth()->id();
            $project = Project::query()->create($validated);
            if ($request->hasFile('agreement_sample')) {
                foreach ($request->file('agreement_sample') as $file)
                    Storage::disk('projects_doc')->put($project->id, $file);
            }
            DB::commit();
            return redirect()->back()->with(["result" => "saved"]);
        } catch (Throwable $ex) {
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        Gate::authorize("edit","Projects");
        try {
            $docs = null;
            $project = Project::query()->findOrFail($id);
            if (Storage::disk('projects_doc')->exists($id))
                $docs = Storage::disk('projects_doc')->allFiles($id);
            return view("{$this->agent}.edit_project", ["project" => $project, "docs" => $docs]);
        } catch (Throwable $ex) {
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function update(ProjectRequest $request,$id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("edit","Projects");
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $validated["contract_amount"] = str_replace(",", '', $validated["contract_amount"]);
            $validated["user_id"] = auth()->id();
            $project = Project::query()->findOrFail($id);
            $project->update($validated);
            if ($request->hasFile('agreement_sample')) {
                if (Storage::disk("projects_doc")->exists("zip/{$id}"))
                    Storage::disk("projects_doc")->deleteDirectory("zip/{$id}");
                foreach ($request->file('agreement_sample') as $file)
                    Storage::disk('projects_doc')->put($id, $file);
            }
            DB::commit();
            return redirect()->back()->with(["result" => "updated"]);
        } catch (Throwable $ex) {
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize("destroy","Projects");
        try {
            DB::beginTransaction();
            $project = Project::query()->findOrFail($id);
            $project->delete();
            if (Storage::disk("projects_doc")->exists($id))
                Storage::disk("projects_doc")->deleteDirectory($id);
            if (Storage::disk("projects_doc")->exists("zip/{$id}"))
                Storage::disk("projects_doc")->deleteDirectory("zip/{$id}");
            DB::commit();
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            DB::rollBack();
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function download_doc($id)
    {
        try {
            if (!Storage::disk("projects_doc")->exists("/zip/{$id}/project_{$id}_docs.zip")) {
                $zip = new ZipArchive();
                Storage::disk("projects_doc")->makeDirectory("/zip/{$id}");
                if ($zip->open(public_path("/storage/projects_doc/zip/{$id}/project_{$id}_docs.zip"), ZipArchive::CREATE) === TRUE) {
                    $files = File::files(public_path("/storage/projects_doc/{$id}"));
                    foreach ($files as $file)
                        $zip->addFile($file, basename($file));
                    $zip->close();
                }
            }
            $zip = public_path("/storage/projects_doc/zip/{$id}/project_{$id}_docs.zip");
            return Response::download($zip, "project_{$id}_docs.zip");
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }

    public function destroy_doc(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            Storage::disk("projects_doc")->delete($request->input("filename"));
            if (Storage::disk("projects_doc")->exists("zip/{$request->input("id")}"))
                Storage::disk("projects_doc")->deleteDirectory("zip/{$request->input("id")}");
            return redirect()->back()->with(["result" => "deleted"]);
        }
        catch (Throwable $ex){
            return redirect()->back()->with(["action_error" => $ex->getMessage()]);
        }
    }
}

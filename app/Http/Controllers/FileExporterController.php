<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\FileExporter;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class FileExporterController extends Controller
{
    private function validateRequest($request)
    {
        return $request->validate([
            'name' => 'required|max:255|unique:file_exporters,name,' . $request->id,
            'selectedColumns' => 'nullable',
            'id' => 'nullable'
        ]);
    }

    private function getCommonProps()
    {
        $propColumns = Schema::getColumnListing((new Product)->getTable());
        return compact('propColumns');
    }

    public function index()
    {
        $propExporters = FileExporter::all(['id', 'name']);
        return inertia('Dashboard', compact('propExporters'));
    }

    public function create()
    {
        list(
            'propColumns' => $propColumns
        ) = $this->getCommonProps();
        return inertia('FileExporter/CreateUpdate', compact('propColumns'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        try {
            DB::beginTransaction();
            FileExporter::create([
                'name' => $validated['name'],
                'selected_columns' => $validated['selectedColumns'],
            ]);
            DB::commit();
            return to_route('dashboard');
        } catch (Exception $e) {
            DB::rollBack();
            Log::emergency('store', compact('e'));
            return back()->withErrors([
                config('config-master.errors-messages.custom_exception') => config('config-master.errors-messages.common'),
            ]);
        }
    }


    public function edit($id)
    {
        $editExportFormat = FileExporter::findOrFail($id);
        list(
            'propColumns' => $propColumns
        ) = $this->getCommonProps();
        return inertia('FileExporter/CreateUpdate', compact('propColumns', 'editExportFormat'));
    }

    public function update(Request $request)
    {
        $validated = $this->validateRequest($request);

        try {
            DB::beginTransaction();
            FileExporter::where([
                ['id', $validated['id']],
            ])
                ->update([
                    'name' => $validated['name'],
                    'selected_columns' => $validated['selectedColumns'],
                ]);
            DB::commit();
            return to_route('dashboard');
        } catch (Exception $e) {
            DB::rollBack();
            Log::emergency('update', compact('e'));
            return back()->withErrors([
                config('config-master.errors-messages.custom_exception') => config('config-master.errors-messages.common'),
            ]);
        }
    }

    public function destroy($id)
    {
        FileExporter::findOrFail($id)->delete();
        return redirect(route('dashboard'));
    }

    public function export($id)
    {
        $exportFormat = FileExporter::findOrFail($id);

        try {
            $filename =  $exportFormat->name . '.csv';

            return Excel::download(
                new ProductsExport(
                    columns: $exportFormat->selected_columns
                ),
                $filename
            );
        } catch (Exception $e) {
            Log::emergency('export', compact('e'));
        }
    }
}

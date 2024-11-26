<?php

namespace App\Repositories;

use App\Services\Filerepo\Controllers\FilesController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait CommonRepoActions
{
    protected $applyFiltersOnly;

    function autoSave($data)
    {
        $id = $data['id'] ?? request()->id;
        $data['id'] = $id;

        if (!$id) {
            $data['user_id'] = auth()->user()->id ?? 0;

            if (!isset($data['status_id'])) {
                $data['status_id'] = activeStatusId();
            }
        }

        if (isset($data['priority']) && $data['priority'] == 0) {
            $highestPriority = $this->model::max('priority') ?? 0;
            $data['priority'] = $highestPriority + 1;
        }

        // remove base64
        $data = array_filter($data, function ($itm) {
            if (is_array($itm) || !isBase46($itm)) {
                return true;
            }
        });

        $record = $this->model::updateOrCreate(['id' => $id], $data);

        $this->saveModelFiles($record);

        return $record;
    }

    function saveModelFiles($record)
    {
        try {
            // Handle base64 encoded images
            foreach (request()->all() as $field => $value) {
                if (isBase46($value)) {
                    Log::info('Detected base64 image:', [$record->getTable(), $field]);

                    if (Schema::hasColumn($record->getTable(), $field)) {
                        $uploader = new FilesController();
                        $file_path = $uploader->saveBase64Image($value, $field);

                        if (isset($file_path['path'])) {
                            $record->$field = $file_path['path'];
                            $record->save();
                        } else {
                            Log::info('Error uploading:', [$file_path]);
                        }
                    }
                }
            }

            // Handle traditional file uploads
            foreach (request()->allFiles() as $field => $files) {
                Log::info('File upload:', [$record->getTable(), $field, Schema::hasColumn($record->getTable(), $field)]);

                if (Schema::hasColumn($record->getTable(), $field)) {
                    $uploader = new FilesController();
                    $file_data = $uploader->saveFiles($record, is_array($files) ? $files : [$files]);

                    // The uploader returns an array of file data
                    $path = $file_data[0]['path'] ?? null;

                    if ($path) {
                        $record->$field = $path;
                        $record->save();
                    }
                }
            }
        } catch (Exception $e) {
            Log::critical('saveModelFiles error: ' . $e->getMessage());
        }
    }

    function updateStatus($id)
    {
        request()->validate(['status_id' => 'required']);

        $status_id = request()->status_id;
        $this->model::find($id)->update(['status_id' => $status_id]);
        return response(['message' => "Status updated successfully."]);
    }

    function updateStatuses(Request $request)
    {

        $this->applyFiltersOnly = true;

        $filteredModel = method_exists($this, 'index') ? $this->index() : $this->model;

        request()->validate(['status_id' => 'required']);

        $msg = 'No record was updated.';
        $builder = $filteredModel->where('status_id', '!=', request()->status_id);

        $arr = ['status_id' => request()->status_id];
        $ids = $request->ids;

        if ($ids) {
            if ($ids == 'all') {
                $builder->update($arr);
                $msg = 'All records statuses updated.';
            } else {
                $ids = json_decode($ids);

                $builder->whereIn('id', $ids)->update($arr);
                $msg = count($ids) . ' records statuses updated.';
            }
        }

        return response(['message' => $msg]);
    }

    function destroy($id)
    {
        $this->model::findOrFail($id)->delete();
        return response(['message' => "Record deleted successfully."]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DownloadableForm;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DownloadableFormController extends Controller
{
    public function index()
    {
        $this->authorizeModify();

        return response()->json(DownloadableForm::orderBy('category')->orderBy('sort_order')->get());
    }

    public function store(Request $request)
    {
        $this->authorizeModify();
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:255', Rule::unique('downloadable_forms', 'title')],
                'url' => ['required', 'url', Rule::unique('downloadable_forms', 'url')],
                'category' => 'required|in:rndd_forms,moa_forms',
            ],
            [
                'title.unique' => 'A form with this form type already exists.',
                'title.required' => 'Form type is required.',
                'url.unique' => 'A form with this URL already exists.',
            ],
            [
                'title' => 'form type',
            ],
        );

        // Set default values for optional fields
        $validated['icon_type'] = 'document';
        $validated['is_active'] = true;
        $validated['sort_order'] = 0;

        $form = DownloadableForm::create($validated);

        // Log form creation
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_DOWNLOADABLE_FORM_CREATED, $form, null, [
            'form_title' => $form->title,
            'form_category' => $form->category,
        ]);

        return response()->json($form, 201);
    }

    public function update(Request $request, DownloadableForm $downloadableForm)
    {
        $this->authorizeModify();
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:255', Rule::unique('downloadable_forms', 'title')->ignore($downloadableForm->id)],
                'url' => ['required', 'url', Rule::unique('downloadable_forms', 'url')->ignore($downloadableForm->id)],
                'category' => 'required|in:rndd_forms,moa_forms',
            ],
            [
                'title.unique' => 'A form with this form type already exists.',
                'title.required' => 'Form type is required.',
                'url.unique' => 'A form with this URL already exists.',
            ],
            [
                'title' => 'form type',
            ],
        );

        // Track changes for logging
        $originalData = $downloadableForm->toArray();
        $changedColumns = [];

        $downloadableForm->update($validated);

        // Check what changed
        foreach (['title', 'url', 'category'] as $field) {
            if ($originalData[$field] !== $downloadableForm->fresh()->$field) {
                $changedColumns[] = $field;
            }
        }

        // Log form update if changes were made
        if (!empty($changedColumns)) {
            UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_DOWNLOADABLE_FORM_UPDATED, $downloadableForm, null, [
                'form_title' => $downloadableForm->title,
                'form_category' => $downloadableForm->category,
                'changed_columns' => $changedColumns,
            ]);
        }

        return response()->json($downloadableForm);
    }

    public function destroy(DownloadableForm $downloadableForm)
    {
        $this->authorizeModify();

        // Log form deletion before deleting
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_DOWNLOADABLE_FORM_DELETED, $downloadableForm, null, [
            'form_title' => $downloadableForm->title,
            'form_category' => $downloadableForm->category,
        ]);

        $downloadableForm->delete();

        return response()->json(['deleted' => true]);
    }

    public function getByCategory($category)
    {
        return response()->json(DownloadableForm::active()->byCategory($category)->orderBy('sort_order')->get());
    }

    private function authorizeModify(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->hasPermission('modify-downloadable-forms'), 403);
    }
}

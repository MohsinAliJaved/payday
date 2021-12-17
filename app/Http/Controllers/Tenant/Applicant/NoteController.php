<?php

namespace App\Http\Controllers\App\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\Applicant\NoteRequest;
use App\Models\App\Applicant\Note;
use App\Notifications\App\Applicant\NoteCreatedNotification;
use App\Services\App\Applicant\NoteService;

class NoteController extends Controller
{
    public function __construct(NoteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->with([
                'notedBy',
                'jobApplicant'
            ])
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    private function loggingData($lang, $find, $replace, $auth = null, $model)
    {
        //Store to timeline
        $description = trans('default.' . $lang);
        $description = str_replace($find, $replace, $description);
        log_to_database($description, [], 'timeline', $auth, $model);
    }

    public function store(NoteRequest $request)
    {
        $inputs = $request->only(['job_applicant_id', 'description']);
        $extra = ['noted_by' => auth()->id(),];
        $inputs = array_merge($inputs, $extra);

        $result = $this->service
            ->setAttributes($inputs)
            ->save();

        if (!$result) {
            return failed_responses(["status" => false, "message" => __t('failed_to_create_note')]);
        }

        $this->service
            ->setAudiences()
            ->notify('note_created', NoteCreatedNotification::class);

        //Store to timeline
        $auth = auth()->user();
        $note = $result;
        $this->loggingData('timeline_for_create_note',
            array('{candidate_name}', '{user_name}'),
            array($note->jobApplicant->appliedBy->full_name,
                $note->notedBy->full_name),
            $auth, $note->jobApplicant);

        return created_responses('note');
    }

    public function show(Note $note)
    {
        return $note;
    }

    public function update(NoteRequest $request, Note $note)
    {
        $this->service
            ->setModel($note)
            ->save(
                $request->only(['description'])
            );

        return updated_responses('note');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return deleted_responses('note');
    }

    public function getNotesByJobApplicantId(Note $note, $id)
    {
        return $note
            ->with([
                'notedBy.profilePicture',
                'jobApplicant'
            ])
            ->where('job_applicant_id', $id)
            ->latest()
            ->get();
    }
}

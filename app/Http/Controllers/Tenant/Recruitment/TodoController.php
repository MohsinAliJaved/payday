<?php

namespace App\Http\Controllers\Tenant\Recruitment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Recruitment\Todo;
use App\Services\Tenant\Recruitment\TodoService;

class TodoController extends Controller
{
    public function __construct(TodoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->with('status', 'user')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(Request $request)
    {
        $inputs = $request->only(['status_id', 'name']);
        $inputs['user_id'] = auth()->id();

        $this->service
            ->setAttributes($inputs)
            ->save();

        return created_responses('todo');
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return deleted_responses('todo');
    }

    public function clearAll()
    {
        $todos = Todo::query()->where('user_id', auth()->id())->get();

        foreach ($todos as $todo) {
            $todo->delete();
        }

        return deleted_responses('todo');
    }

    public function changeStatus(Todo $todo, Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required',
        ]);

        $todo = $todo->find($id);
        $todo->status_id = intval($request->status_id);
        $todo->save();

        return custom_failed_response('status_updated_response', [
            'name' => __t('todo'),
            'status' => strtolower(__t($todo->load('status'))),
        ]);
    }
}

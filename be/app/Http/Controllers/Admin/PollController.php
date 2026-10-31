<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTransactions;
use App\Http\Requests\Admin\StorePollRequest;
use App\Http\Requests\Admin\UpdatePollRequest;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    
    use HandlesTransactions;
/**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $polls = Poll::orderBy('created_at', 'desc')
            ->when($search, function ($query) use ($search) {
                $query->where('question', 'like', "%{$search}%");
            })
            ->paginate(10)->withQueryString();
        return view('pages.poll.index', compact('polls', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollRequest $request)
    {
        return $this->transactional(function () use ($request) {
            Poll::create($request->validated());
        }, 'Polling berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePollRequest $request, $uuid)
    {
        return $this->transactional(function () use ($request, $uuid) {
            $poll = Poll::where('uuid', $uuid)->firstOrFail();
            $poll->update($request->validated());
        }, 'Polling berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        return $this->transactional(function () use ($uuid) {
            Poll::where('uuid', $uuid)->firstOrFail()->delete();
        }, 'Polling berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Notice;
use App\Rules\SpamFree;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user)
    {
        $notices = Notice::latest()->get();
        return view('notices.index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user)
    {
        return view('notices.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($user)
    {
        request()->validate([
            'title'          => ['required', new SpamFree],
            'body'           => ['required', new SpamFree],
            'channel_id'     => 'required|exists:channels,id',
            'recipient_type' => 'required|boolean',
        ]);

        $notice = Notice::create([
            'user_id'        => auth()->id(),
            'channel_id'     => request('channel_id'),
            'recipient_type' => request('recipient_type'),
            'title'          => request('title'),
            'body'           => request('body'),
        ]);
        return redirect($notice->path())->with('flash', 'Your Notice Was Posted');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice)
    {
        //
    }
}

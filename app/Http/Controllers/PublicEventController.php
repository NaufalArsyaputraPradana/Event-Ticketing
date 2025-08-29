<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    /**
     * Tampilkan daftar event.
     */
    public function index()
    {
        $events = Event::where('status', 'published')
            ->where('event_date', '>', now())
            ->latest()
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    /**
     * Tampilkan detail event.
     */
    public function show(Event $event)
    {
        if ($event->status !== 'published') {
            abort(404);
        }

        return view('events.show', compact('event'));
    }

    /**
     * Cari event.
     */
    public function search(Request $request)
    {
        $query = (string) $request->get('q');

        $events = Event::where('status', 'published')
            ->where('event_date', '>', now())
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('venue', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(12);

        return view('events.index', compact('events', 'query'));
    }
}

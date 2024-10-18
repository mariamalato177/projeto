<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\ScreeningFormRequest;
use Illuminate\Support\Facades\Redirect;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScreeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today();
        $twoWeeksFromNow = Carbon::today()->addWeeks(2);

        $searchQuery = $request->input('search');
        $movieQuery = $request->input('movie');

        $screeningsQuery = Screening::query();
        if ($searchQuery) {
            $screeningsQuery = Screening::where('id', $searchQuery);
        }
        if($movieQuery) {
            $screeningsQuery = Screening::whereHas('movieRef', function ($query) use ($movieQuery) {
                $query->where('title', 'like', "%$movieQuery%");
            });
        }

        if (!Auth::check() || Auth::user()->type !== 'A') {
            $screeningsQuery->whereBetween('date', [$today, $twoWeeksFromNow]);
        }


        $screenings = $screeningsQuery
            ->with('movieRef', 'theaterRef')
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(70)
            ->withQueryString();

        return view('screenings.index', compact('screenings',));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $screening = new Screening();

        return view('screenings.create')
            ->with('screening', $screening);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dates' => 'required|array',
            'dates.*' => 'date',
            'times' => 'required|array',
            'times.*' => 'date_format:H:i',
            'movie_id' => 'required|exists:movies,id',
            'theater_id' => 'required|exists:theaters,id',
        ]);


        $movieId = $request->input('movie_id');
        $theaterId = $request->input('theater_id');
        $dates = $validated['dates'];
        $times = $validated['times'];

        foreach ($dates as $date) {
            foreach ($times as $time) {
                Screening::create([
                    'movie_id' => $movieId,
                    'theater_id' => $theaterId,
                    'start_time' => $time,
                    'date' => $date,
                ]);
            }
        }

        return redirect()->route('screenings.index')->with('success', 'Screenings created successfully.');
    }


    public function showTickets(Request $request,Screening $screening): View
    {
        $searchQuery = $request->input('search');

        if ($searchQuery) {
            $tickets = Ticket::where('id', $searchQuery)->paginate(70);
            return view('tickets.index', compact('tickets', 'searchQuery', 'screening'));
        }

        $tickets = $screening->tickets()->paginate(70);
        return view('tickets.index', compact( 'tickets','searchQuery','screening'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Screening $screening): View
    {
        $theater = $screening->theaterRef;
        $movie = $screening->movieRef;
        $seats = $theater->seats;


        return view('screenings.show')->with([
            'screening' => $screening,
            'theater' => $theater,
            'seats' => $seats,
            'movie' => $movie,
        ]);
    }

    public function isSoldOut(Screening $screening): bool
    {
        $theater = $screening->theaterRef;
        $seats = $theater->seats;

        foreach ($seats as $seat) {
            if ($seat->isAvailable($screening->id)) {
                return false;
            }
        }

        return true;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Screening $screening): View
    {

        return view('screenings.edit')
            ->with('screening', $screening);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScreeningFormRequest $request, Screening $screening): RedirectResponse
    {

        $screening->update($request->validated());
        $url = route('screenings.show', ['screening' => $screening]);
        $htmlMessage = "Movie <a href='$url'><u>{$screening->movieRef->title}</u></a> has been updated successfully!";
        return redirect()->route('screenings.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Screening $screening): RedirectResponse
    {
        try {
            $url = route('screenings.show', ['screening' => $screening]);
            $totalTickets = DB::scalar(
                'select count(*) from tickets where screening_id = ?',
                [$screening->id]
            );
            if ($totalTickets == 0) {
                $screening->delete();
                $alertType = 'success';
                $alertMsg = "Screening ({$screening->id}) has been deleted successfully!";
            } else {
                $alertType = 'warning';
                $screeningsStr = match (true) {
                    $totalTickets <= 0 => "",
                    $totalTickets == 1 => "it already includes 1 session",
                    $totalTickets > 1 => "it already includes $totalTickets Tickets",
                };
                $justification = $screeningsStr;
                $alertMsg = "Screening <a href='$url'><u>{$screening->id}</u></a> cannot be deleted because $justification.";
            }
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the screening
                            <a href='$url'><u>{$screening->id}</u></a>
                            because there was an error with the operation!";
        }

        return redirect()->back()
        ->with('alert-type', $alertType)
        ->with('alert-msg', $alertMsg);

    }
}
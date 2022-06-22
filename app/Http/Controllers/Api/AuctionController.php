<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auctions = Auction::with('lastBid')->whereDate('end_date', '>', now('Asia/Jakarta'))->get();
        return response()->json($auctions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function show(Auction $auction)
    {
        $auction = Auction::with('bids', 'lastBid')->find($auction->id);
        return response()->json($auction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auction $auction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auction $auction)
    {
        //
    }

    public function search(Request $request)
    {
        dd($request->term);
        $auctions = Auction::where('title', 'like', '%' . $request->term . '%')->get();
        return response()->json($auctions);
    }

    public function bid(Request $request)
    {
        $fields = $request->validate([
            'auction_id' => 'required|integer|exists:auctions,id',
            'amount' => 'required|numeric'
        ], [
            'auction_id.required' => 'Auction ID is required',
            'auction_id.integer' => 'Auction ID must be an integer',
            'auction_id.exists' => 'Auction ID must exist',
            'amount.required' => 'Amount is required',
            'amount.integer' => 'Amount must be an integer'
        ]);

        $auction = Auction::find($fields['auction_id']);
        $bids = $auction->bids;
        $lastBid = $bids->last();

        if ($lastBid) {
            if ($lastBid->user_id == $request->user()->id) {
                return response()->json([
                    'message' => 'You cannot bid on your own auction'
                ], 400);
            }

            if ($lastBid->amount >= $fields['amount']) {
                return response()->json([
                    'message' => 'Your bid must be higher than the last bid, the last bid was $' . $lastBid->amount
                ], 400);
            }
        }

        $bid = $auction->bids()->create([
            'user_id' => $request->user()->id,
            'amount' => $fields['amount']
        ]);

        return response()->json($bid);
    }

    public function userAuctions(Request $request)
    {
        $bids = $request->user()->bids;

        foreach ($bids as $key => $bid) {
            $auction = $bid->auction;
            $bids[$key]->auction = $auction;
        }

        return response()->json($bids);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->only(
            'name', 'description', 'image', 'starting_price', 'start_date', 'end_date'
        ), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'starting_price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $end_date = Carbon::parse($request->end_date);
        // set hours to 23:59:59
        $end_date = $end_date->setTime(23, 59, 59);

        $auction = Auction::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'starting_price' => $request->starting_price,
            'start_date' => $request->start_date,
            'end_date' => $end_date
        ]);

        return response()->json($auction);

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
        $validator = Validator::make($request->only('name', 'description', 'starting_price'), [
            'name' => 'required|string',
            'description' => 'required|string',
            'starting_price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $auction->name = $request->name;
        $auction->description = $request->description;
        $auction->starting_price = $request->starting_price;
        $auction->save();

        return response()->json($auction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auction $auction)
    {
        if ($auction) {
            $auction->delete();
            return response()->json(['message' => 'Auction deleted']);
        }else{
            return response()->json(['message' => 'Auction not found'], 404);
        }
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

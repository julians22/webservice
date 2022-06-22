<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'starting_price',
        'image',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'start_date' => 'datetime:Y-m-d H:i:s',
        'end_date' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['end_date_formatted', 'now_to_end_date', 'small_image_url', 'thumb_description'];

    /**
     * Get the thumb_description formatted.
     *
     * @return string
     */
    public function getThumbDescriptionAttribute()
    {
        return Str::limit($this->description, 100);
    }

    /**
     * Get the small_image_url attribute.
     *
     * @return string
     */
    public function getSmallImageUrlAttribute(){
        $path = $this->image;
        // check $path is exists on storage
        if (Storage::disk('public')->exists($path)) {
            // get extensiton
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            // add "-small" to filename
            $smallPath = str_replace('.' . $extension, '-small.' . $extension, $path);

            // check $smallPath is exists on storage
            if (Storage::disk('public')->exists($smallPath)) {
                // return url
                return Storage::disk('public')->url($smallPath);
            }

            // return url
            return Storage::disk('public')->url($path);
        }

        return $path;
    }

    /**
     * Get the formatted end date.
     *
     * @return string
     */
    public function getEndDateFormattedAttribute(){
        return $this->end_date->timestamp;
    }

    /**
     * Get the formatted end date.
     *
     * @return string
     */
    public function getNowToEndDateAttribute(){

        return $this->end_date->diffInSeconds(now('Asia/Jakarta')->format('Y-m-d H:i:s'));
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function lastBid()
    {
        return $this->hasOne(Bid::class)->latest();
    }
}
